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
            <dl>
                <dt>CPF:</dt>
                <dd>{{ $corrector->cpf }}</dd>
                <dt>SIAPE:</dt>
                <dd>{{ $corrector->siape }}</dd>
                <dt>Nome:</dt>
                <dd>{{ $corrector->user->name }}</dd>
                <dt>E-mail:</dt>
                <dd>{{ $corrector->user->email }}</dd>
                <dt>Data de criação:</dt>
                <dd>{{ $corrector->created_at }}</dd>
                <dt>Data de última modificação:</dt>
                <dd>{{ $corrector->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop

@section('js')
    
@stop