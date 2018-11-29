<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Redaction;
use App\Models\Corrector;
use App\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Verificar se o usuário logado é um avaliador
        $corrector = Corrector::where('user_id', \Auth::user()->id)->first();
        if ($corrector == null){
            $isCorrector = false;
        } else {
            $isCorrector = true;
        }
        
        //Agrupa as redações por status
        $redactions = DB::table('redactions')
                   ->select('status', DB::raw('COUNT(id) as qtde'))
                   ->groupBy('status')->get();
        
        //Define as cores de cada status, que serão utilizadas nos gráficos
        $colors = collect();
        $colors->put('Digitalizada', '#d2d6de');
        $colors->put('Para correção', '#f39c12');
        $colors->put('Corrigida (1x)', '#3c8dbc');
        $colors->put('Corrigida (concluído)', '#00a65a');
        $colors->put('Inconsistência', '#f56954');

        return view('home', compact('redactions', 'colors', 'isCorrector'));
    }
}
