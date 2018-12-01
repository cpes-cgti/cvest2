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


class RedactionController extends Controller
{

    public function __construct()
    {
        $array = array(
            'import', 
            'process_import', 
            'for_correction', 
            'process_for_correction',
        );
        $this->middleware(['auth', 'can:level4'])->only($array);
        $this->middleware(['auth', 'can:level2'])->except($array);
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
        $img_data = $this->get_data($img, 710, 0);
        return view('redactions.image', compact('img_data'));
    }

    public function show_admin($id)
    {
        $redaction = Redaction::findOrFail($id);
        $img = $redaction->file;
        $img_data = $this->get_data($img, 0, 0);
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

    public function rate()
    {
        return 'Teste';
    }

    public function get_data($image, $ch, $cv)
    {
        $file = Storage::get($image);
        $img = Image::make($file);
        $corte_vertical = $cv;
        $corte_horizontal = $ch;
        $x = $corte_vertical;
        $y = $corte_horizontal;
        $largura = $img->width() - $corte_vertical;
        $altura =  $img->height() - $corte_horizontal;
        $img->crop($largura,  $altura, $x, $y);
        $img->resize(1240, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img_data = $img->encode('data-url');
        return $img_data;
    }


}
