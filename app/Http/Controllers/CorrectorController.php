<?php

namespace App\Http\Controllers;

use App\Models\Corrector;
use App\User;
use App\Http\Requests\CorrectorRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CorrectorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $avaliadores = Corrector::with('user')->get();
        return view('correctors.index', compact('avaliadores'));
    }

    public function create()
    {
        return view('correctors.edit');
    }

    public function store(CorrectorRequest $request)
    {
        $r = $request;
        DB::beginTransaction();
        try {
            $usuario = User::where('email', $r->email)->first();
            if ($usuario == null){
                $usuario = User::create([
                    'name' => $r->name,
                    'email' => $r->email,
                    'password' => bcrypt(str_random(10)),
                ]);
            } else {
                $usuario->name = $r->name;
                $usuario->save();
            }
            $avaliador = Corrector::create([
                'cpf' => $r->cpf,
                'siape' => $r->siape,
                'user_id' => $usuario->id,
            ]);
            DB::commit();
            return redirect()->route('corrector.index');
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->back()->withInput()->with('erro', 'Erro ao adicionar o avaliador.');
    }

    public function show($id)
    {
        $corrector = Corrector::with('user')->findOrFail($id);
        return view('correctors.show', compact('corrector'));
    }

    public function edit($id)
    {
        $corrector = Corrector::with('user')->findOrFail($id);
        return view('correctors.edit', compact('corrector'));
    }

    public function update(CorrectorRequest $request, $id)
    {
        $r = $request;
        $corrector = Corrector::with('user')->findOrFail($id);
        DB::beginTransaction();
        try {
            $corrector->cpf = $r->cpf;
            $corrector->siape = $r->siape;
            $corrector->user->name = $r->name;
            $corrector->user->email = $r->email;
            $corrector->save();
            $corrector->user->save();
            DB::commit();
            return redirect()->route('corrector.index');
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->back()->withInput()->with('erro', 'Erro ao modificar o avaliador.');
    }

    public function destroy($id)
    {
        $corrector = Corrector::findOrFail($id);
        dd($corrector);
    }
}
