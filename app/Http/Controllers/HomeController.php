<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Redaction;
use App\Models\Corrector;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Rules\OldPassword;

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
        //Acompanhamento dos Avaliadores
        $correctors = DB::table('redactions')
            ->join('corrector_redaction', 'corrector_redaction.redaction_id', '=','redactions.id')
            ->join('correctors', 'correctors.id', '=', 'corrector_redaction.corrector_id')
            ->join('users', 'users.id', '=', 'correctors.user_id')
            ->select(
                'correctors.id',
                'users.name', 
                DB::raw('COUNT(corrector_redaction.id) as to_do'),
                DB::raw('SUM(IF(ISNULL(corrector_redaction.score), 0, 1)) as ready'),
                'users.name'
            )
            ->groupBy('users.name', 'correctors.id')
            ->get();

        //Verificar se o usuário logado é um avaliador
        $corrector = Corrector::where('user_id', \Auth::user()->id)->first();
        if ($corrector == null){
            $isCorrector = false;
            $corrector = null;
        } else {
            $isCorrector = true;
            $corrector = $correctors->where('name', \Auth::user()->name)->first();
        }
        
        /* dd($correctors, $corrector, \Auth::user()->id, \Auth::user()->name); */

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
        $colors->put('Necessita revisão', '#f56954');

        return view('home', compact('redactions', 'colors', 'isCorrector', 'correctors', 'corrector'));
    }

    public function change_password()
    {
        return view('password');
    }

    public function update_password(Request $request)
    {
        $validatedData = $request->validate([
            'old_password' => ['required', 'string', 'min:6', new OldPassword],
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = \Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('password.change')->with('sucesso', 'Sua senha foi alterada com sucesso.');
    }

}
