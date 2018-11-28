<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Redaction;
use Freshbitsweb\Laratables\Laratables;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;


class RedactionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'can:level4'])->only(['import', 'process_import', 'for_correction', 'process_for_correction']);
        $this->middleware(['auth', 'can:level2'])->except(['import', 'process_import', 'for_correction', 'process_for_correction']);
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
        return view('redactions.allocate');
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
