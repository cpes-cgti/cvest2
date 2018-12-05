@extends('adminlte::page')

@section('css')
    
@stop

@section('content_header')
    <h1>Dados do Avaliador</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fas fa-home"></i></a></li>
        <li><a href="{{ route('corrector.index') }}">Avaliadores</a></li>
        <li><a href="{{ route('corrector.show', $corrector->id) }}">Exibir</a></li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <a href="{{ redirect()->back()->getTargetUrl() }}" class="btn btn-primary"><i class="fa fa-fw fa-arrow-left"></i> Voltar</a>
        </div>
        <div class="box-body">
            <fieldset>
                {{-- <legend>Redação:</legend> --}}
                <div class="form-group">
                    <label style="font-weight: normal;">ID: </label>
                    <strong style="font-size: 1.2em;">{{ isset($corrector) ? str_pad($corrector->id, 6, "0", STR_PAD_LEFT) : '' }}</strong>
                </div>
                <div class="form-group">
                    <label style="font-weight: normal;">CPF: </label>
                    <strong style="font-size: 1.2em;">{{ isset($corrector) ? $corrector->cpf : '' }}</strong>
                </div>
                <div class="form-group">
                    <label style="font-weight: normal;">SIAPE: </label>
                    <strong style="font-size: 1.2em;">{{ isset($corrector) ? $corrector->siape : '' }}</strong>
                </div>
                <div class="form-group">
                    <label style="font-weight: normal;">Nome: </label>
                    <strong style="font-size: 1.2em;">{{ isset($corrector) ? $corrector->user->name : '' }}</strong>
                </div>
                <div class="form-group">
                    <label style="font-weight: normal;">E-mail: </label>
                    <strong style="font-size: 1.2em;">{{ isset($corrector) ? $corrector->user->email : '' }}</strong>
                </div>
                <div class="form-group">
                    <label style="font-weight: normal;">Data de criação: </label>
                    <strong style="font-size: 1.2em;">{{ isset($corrector) ? Carbon\Carbon::parse($corrector->created_at)->format('d/m/Y H:i:s') : '' }}</strong>
                </div>
                <div class="form-group">
                    <label style="font-weight: normal;">Data de última modificação: </label>
                    <strong style="font-size: 1.2em;">{{ isset($corrector) ? Carbon\Carbon::parse($corrector->updated_at)->format('d/m/Y H:i:s') : '' }}</strong>
                </div>
            </fieldset>
        </div>
    </div>
@stop

@section('js')
    
@stop