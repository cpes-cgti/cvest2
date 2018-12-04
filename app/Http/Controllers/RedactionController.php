<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Corrector;
use App\Models\Redaction;
use App\Models\Lot;
use Freshbitsweb\Laratables\Laratables;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class RedactionController extends Controller
{

    public function __construct()
    {
        $level4 = array(
            'import', 
            'process_import', 
            'for_correction', 
            'process_for_correction',
        );
        $corretor = array(
            'rate_lots', 
            'rate_lot', 
            'rate', 
            'rate_save', 
        );
        $this->middleware(['auth', 'can:level4'])->only($level4);
        $this->middleware(['auth', 'can:level2'])->except(array_merge($level4, $corretor));
        $this->middleware(['auth', 'can:corrector'])->only($corretor);
    }


    public function datatables()
    {
        return Laratables::recordsOf(Redaction::class);
    }

    public function index()
    {
        return view('redactions.index');
    }

    public function show($id)
    {
        $redaction = Redaction::findOrFail($id);
        $img = $redaction->file;
        $img_data = $this->get_data($img);
        return view('redactions.image', compact('img_data'));
    }

    public function show_admin($id)
    {
        $redaction = Redaction::findOrFail($id);
        $img = $redaction->file;
        $img_data = $this->get_data($img, false);
        return view('redactions.image', compact('img_data'));
    }

    public function import()
    {
        return view('redactions.import');
    }

    public function process_import()
    {
        $files = Storage::files();
        $extensions = array("JPG", "PNG", "GIF");
        foreach ($files as $file) {
            list($entry, $ext) = explode(".", $file);
            if (in_array(strtoupper($ext), $extensions)){
                $redaction = Redaction::where('entry', $entry)->first();
                if ($redaction == null){
                    Redaction::create([
                        'entry' => $entry,
                        'file' => $file,
                    ]);
                }
            }
        }
        return redirect()->route('redaction.index');
    }

    public function for_correction()
    {
        return view('redactions.for_correction');
    }

    public function process_for_correction(Request $request)
    {
        /* Valida dados da requisição */
        $validatedData = $request->validate([
            'arquivo' => 'required|file',
        ]);
        /* Importar do arquivo CSV */
        try
        {
            DB::beginTransaction();
            $fp = fopen($request->arquivo, "r");
            while(!feof($fp)) {
                $linha = fgets($fp);
                //Remove aspas e quebra de linha
                $linha = str_replace("\"", "", $linha);
                $linha = str_replace("\n", "", $linha); 
                $linha = str_replace("\r", "", $linha);
                //
                $redaction = Redaction::where('entry', $linha)->where('status', 'Digitalizada')->first();
                if ($redaction != null){
                    $redaction->status = 'Para correção';
                    $redaction->save();
                }
            }
            DB::commit();
        } catch ( \Exception $e ) {
            DB::rollback();
            return redirect()->route('redaction.for_correction')->with('erro', 'Falha ao processar arquivo.');
        } finally {
            fclose($fp);
        }
        return redirect()->route('redaction.index');
    }

    public function allocate()
    {
        $lots = DB::table('redactions')
            ->join('corrector_redaction', 'corrector_redaction.redaction_id', '=','redactions.id')
            ->join('correctors', 'correctors.id', '=', 'corrector_redaction.corrector_id')
            ->join('users', 'users.id', '=', 'correctors.user_id')
            ->select(
                'corrector_redaction.lot', 
                DB::raw('COUNT(corrector_redaction.id) as lot_count'),
                /* DB::raw('14 as ready'), */
                DB::raw('SUM(IF(ISNULL(corrector_redaction.score), 0, 1)) as ready'),
                'users.name'
            )
            ->groupBy('corrector_redaction.lot', 'users.name')
            ->get();
        $correctors = Corrector::with('user')->get();
        $correctors_ids = DB::table('correctors')->select('id')->get();
        return view('redactions.allocate', compact('lots', 'correctors', 'correctors_ids'));
    }
    
    public function process_allocate(Request $request)
    {
        $validatedData = $request->validate([
            'correctors' => 'required',
        ]);
        DB::beginTransaction();
        try{
            //Lista e conta os Avaliadores disponíveis
            $correctors = Corrector::whereIn('id', $request->correctors)->get();
            $qtde_correctors = $correctors->count();
            if ($qtde_correctors < 2){
                return redirect()->route('redaction.allocate')
                    ->with('erro', 'Não foi possível distribuir as redações. É necessário selecionar no mínimo 2 avaliadores.');
            }
            //Verificar se existem redações para distribuir
            $redactions = Redaction::has('correctors', 0)->where('status', 'Para correção')->get();
            $qtde_redactions = $redactions->count();
            $redactions = Redaction::has('correctors', 1)->where('status', 'Para correção')->get();
            $qtde_redactions += $redactions->count();
            if ($qtde_redactions < 1){
                return redirect()->route('redaction.allocate')
                    ->with('erro', 'Não foi possível distribuir as redações. Não existem redações para distribuir ou todas já estão atribuídas aos avaliadores.');
            }
            //Distribuir redações para os corretores (que não foram atribuídas para nenhum corretor)
            $redactions = Redaction::has('correctors', 0)->where('status', 'Para correção')->get();
            $qtde_redactions = $redactions->count();
            $redactions = $redactions->concat($redactions);
            $qtde_corrections = $redactions->count();
            $qtde_per_correctors = ceil($qtde_corrections/$qtde_correctors);
            $max_lot = 30;
            $cont = 1;
            $i = 0;
            $lot = DB::table('corrector_redaction')->max('lot') + 1;
            $cont_lot = 1;
            foreach ($redactions as $r) {
                if ($cont_lot > $max_lot){
                    $lot++;
                    $cont_lot = 1;
                }
                if ($cont > $qtde_per_correctors) {
                    $i++;
                    $cont = 1;
                    $lot++;
                    $cont_lot = 1;
                }
                $r->correctors()->attach($correctors[$i],['lot' => $lot]);
                $cont++;
                $cont_lot++;
            }
            //Distribuir redações para os avaliadores (que foram atribuídas apenas para um avaliador)
            $redactions = Redaction::has('correctors', 1)->where('status', 'Para correção')->get();
            $new_lot = DB::table('corrector_redaction')->max('lot') + 1;
            //primeiro avaliador
            $i = 0;
            foreach ($redactions as $r) {
                $allocated = false;
                while (!$allocated){
                    $available = DB::table('corrector_redaction')
                        ->where('corrector_id', $correctors[$i]->id)
                        ->where('redaction_id', $r->id)->doesntExist();
                    // Atribui a redação ao avaliador se o mesmo já não estiver avaliando a mesma em outro lote.
                    if ($available){
                        // Verifica último lote deste avaliador
                        $lot = DB::table('corrector_redaction')
                            ->where('corrector_id', $correctors[$i]->id)->max('lot');
                        if ($lot == null){
                            // Se o avaliador não possuir nenhum lote, cria um e atribui a redação
                            $r->correctors()->attach($correctors[$i],['lot' => $new_lot]);
                            $new_lot++;
                        } else {
                            $lot_size = DB::table('corrector_redaction')
                            ->where('lot', $lot)->count('id');
                            if ($lot_size < 30){
                                // Se o lote possuir menos de 30, atribui a redação ao avaliador neste lote
                                $r->correctors()->attach($correctors[$i],['lot' => $lot]);
                            } else {
                                // Se o lote estiver cheio, cria um lote e atribui a redação ao avaliador
                                $r->correctors()->attach($correctors[$i],['lot' => $new_lot]);
                                $new_lot++;
                            }
                        }
                        $allocated = true;
                    }
                    //ir para próximo avaliador
                    $i++;
                    if ($i == $qtde_correctors) {
                        //retorna ao primeiro avaliador
                        $i = 0;
                    }
                }
            }
            DB::commit();
            return redirect()->route('redaction.allocate');
        } catch ( \Exception $e ) {
            DB::rollback();
            return redirect()->route('redaction.allocate')->with('erro', 'Falha ao distribuir as redações.');
        }


    }

    public function lot_destroy($id)
    {
        $lot = DB::table('corrector_redaction')->where('lot', $id)->whereNull('score');
        if ($lot->count() > 0){
            $del = $lot->delete();
            if ($del > 0){
                return redirect()->route('redaction.allocate');
            }
        }
        return redirect()->route('redaction.allocate')->with('erro', 'O lote não pode ser excluído.');

    }

    public function rate_lots()
    {
        $lots = DB::table('corrector_redaction')
            ->join('correctors', 'correctors.id', '=', 'corrector_redaction.corrector_id')
            ->join('users', 'users.id', '=', 'correctors.user_id')
            ->where('users.id', \Auth::user()->id)
            ->select(
                'corrector_redaction.lot', 
                DB::raw('COUNT(corrector_redaction.id) as to_do'),
                DB::raw('SUM(IF(ISNULL(corrector_redaction.score), 0, 1)) as ready'),
                DB::raw('MIN(start) as start'),
                DB::raw('MAX(end) as end')
            )
            ->groupBy('corrector_redaction.lot')
            ->get();
        return view('redactions.rate_lots', compact('lots'));
    }
    
    public function rate_lot($lot)
    {
        $redactions = DB::table('corrector_redaction')
            ->where('corrector_redaction.lot', $lot)
            ->orderBy('corrector_redaction.id')
            ->get();
        // Valida se o lote informado é válido
        if ($redactions->count() < 1){
            abort(400);
        }
        // Valida se o lote informado foi atribuído para usuário logado
        $corrector = Corrector::findOrFail($redactions->first()->corrector_id);
        if ($corrector->user->id != \Auth::user()->id){
            abort(403);
        }

        $next_redactions = $redactions->where('start', null);

        if ($next_redactions->count() > 0){
            return redirect()->route('redaction.rate', [$lot, $next_redactions->first()->id]);
        }
        return redirect()->route('redaction.rate', [$lot, $redactions->first()->id]);
    }

    public function rate($lot, $id)
    {
        $redactions = DB::table('corrector_redaction')
            ->where('corrector_redaction.lot', $lot)
            ->orderBy('corrector_redaction.id')
            ->get();
        // Valida se o lote informado é válido
        if ($redactions->count() < 1){
            abort(400);
        }
        $redaction = DB::table('corrector_redaction')
        ->where('corrector_redaction.lot', $lot)
        ->where('corrector_redaction.id', $id)
        ->orderBy('corrector_redaction.id')
        ->get();
        // Valida se o lote e o id são válidos (simultâneamente)
        if ($redactions->count() < 1 || $redaction->count() < 1){
            abort(400);
        }
        // Valida se a redacão foi atribuída para usuário logado
        $corrector = Corrector::findOrFail($redaction->first()->corrector_id);
        if ($corrector->user->id != \Auth::user()->id){
            abort(403);
        }
        $r = $redaction->first();

        $redaction = Redaction::findOrFail($redaction->first()->redaction_id);
        $img_data = $this->get_data($redaction->file);
        
        $first = ($redactions->first()->id == $id);
        $last = ($redactions->last()->id == $id);
        $missing = $redactions->where('score', null)->count();
        $start = \Carbon\Carbon::now();
        $previous = null;
        $next = null;

        $index = $redactions->search($redactions->where('id',$id)->first());
        if (!$first){
            $previous = $redactions[$index - 1]->id;
        }
        if (!$last){
            $next = $redactions[$index + 1]->id;
        }
        
        return view('redactions.rate', compact('img_data', 'lot','id', 'first', 'last', 'missing', 'start', 'previous', 'next', 'r'));
    }

    public function rate_save($lot, $id, Request $request)
    {
        $validatedData = $request->validate([
            'action' => 'required|in:previous,next,finish',
            'start' => 'required|date',
            'competenceA' => 'required_without_all:zerar_1,zerar_2,zerar_3,zerar_4,zerar_5|in:0.0,0.5,1.0,1.5,2.0,2.5',
            'competenceB' => 'required_without_all:zerar_1,zerar_2,zerar_3,zerar_4,zerar_5|in:0.0,0.5,1.0,1.5,2.0,2.5',
            'competenceC' => 'required_without_all:zerar_1,zerar_2,zerar_3,zerar_4,zerar_5|in:0.0,0.5,1.0,1.5,2.0,2.5',
            'competenceD' => 'required_without_all:zerar_1,zerar_2,zerar_3,zerar_4,zerar_5|in:0.0,0.5,1.0,1.5,2.0,2.5',
        ]);

        $redactions = DB::table('corrector_redaction')
            ->where('corrector_redaction.lot', $lot)
            ->orderBy('corrector_redaction.id')
            ->get();
        // Valida se o lote informado é válido
        if ($redactions->count() < 1){
            abort(400);
        }
        //Validar Lote e ID
        $redaction = DB::table('corrector_redaction')
        ->where('corrector_redaction.lot', $lot)
        ->where('corrector_redaction.id', $id)
        ->orderBy('corrector_redaction.id')
        ->get();
        if ($redaction->count() < 1){
            abort(400);
        }
        // Valida se a redacão foi atribuída para usuário logado
        $corrector = Corrector::findOrFail($redaction->first()->corrector_id);
        if ($corrector->user->id != \Auth::user()->id){
            abort(403);
        }

        $start = Carbon::parse($request->start);
        $end = Carbon::now();
        $duration =$end->diffInSeconds($start);

        if ( $duration <  $redaction->first()->duration * 1){
            $start = Carbon::parse($redaction->first()->start);
            $end = Carbon::parse($redaction->first()->end);
            $duration =$end->diffInSeconds($start);
        }

        $data = [
            "start" => $start->format('Y-m-d H:i:s'),
            "end" => $end->format('Y-m-d H:i:s'),
            "duration" => $duration,
            "score" => null,
            "zero_empty" => false,
            "zero_identification" => false,
            "zero_theme" => false,
            "zero_lines" => false,
            "zero_offensive_content" => false,
            "competenceA" => null,
            "competenceB" => null,
            "competenceC" => null,
            "competenceD" => null,
            "note" => null,
        ];

        if (isset($request->zerar_1)){
            $data["zero_empty"] = true;
        }
        if (isset($request->zerar_2)){
            $data["zero_identification"] = true;
        }
        if (isset($request->zerar_3)){
            $data["zero_theme"] = true;
        }
        if (isset($request->zerar_4)){
            $data["zero_lines"] = true;
        }
        if (isset($request->zerar_5)){
            $data["zero_offensive_content"] = true;
        }
        if (isset($request->note)){
            $data["note"] = $request->note;
        }

        if ($data["zero_empty"] || $data["zero_identification"] || $data["zero_theme"] 
                || $data["zero_lines"] || $data["zero_offensive_content"]){
            $data["score"] = 0;
        } else {
            $data["competenceA"] = $request->competenceA;
            $data["competenceB"] = $request->competenceB;
            $data["competenceC"] = $request->competenceC;
            $data["competenceD"] = $request->competenceD;
            $data["score"] = 1 * $request->competenceA + $request->competenceB + $request->competenceC + $request->competenceD;
        }

        $corrector->redactions()->updateExistingPivot($redaction->first()->redaction_id,$data);

        /* Atualizar status e média da redação */
        
        $corrections = DB::table('corrector_redaction')
            ->where('corrector_redaction.redaction_id', $redaction->first()->redaction_id)
            ->whereNotNull('corrector_redaction.score')
            ->get();

        $qtde_corrections = $corrections->count();

        $avg_score = $corrections->avg('score');
        $redaction = Redaction::find($redaction->first()->redaction_id);
        if ($qtde_corrections == 1){
            $redaction->status = 'Corrigida (1x)';
        } elseif ($qtde_corrections == 2){
            if (abs($corrections->first()->score - $corrections->last()->score) >= 3){
                $redaction->status = 'Necessita revisão';
            } else {
                $redaction->status = 'Corrigida (concluído)';
            }
        }
        $redaction->final_score = $avg_score;
        $redaction->save();

        $index = $redactions->search($redactions->where('id',$id)->first());
        if ($request->action == 'next'){
            $next = $redactions[$index + 1]->id;
            return redirect()->route('redaction.rate', [$lot, $next]);
        } elseif ($request->action == 'previous'){
            $previous = $redactions[$index - 1]->id;
            return redirect()->route('redaction.rate', [$lot, $previous]);
        } else {
            return redirect()->route('redaction.rate_lots');
        }
    }

    public function get_data($image, $crop = true)
    {
        
        $file = Storage::get($image);
        $img = Image::make($file);
        if ($crop){
            $x = ceil(env('CROP_IMAGE_LEFT', 0) * $img->width());
            $y = ceil(env('CROP_IMAGE_TOP', 0) * $img->height());
            $largura = $img->width() - $x - ceil(env('CROP_IMAGE_RIGHT', 0) * $img->width());
            $altura =  $img->height() - $y - ceil(env('CROP_IMAGE_BOTTOM', 0) * $img->height());
            $img->crop($largura,  $altura, $x, $y);
        }
        $img->resize(1240, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img_data = $img->encode('data-url');
        return $img_data;
    }


}
