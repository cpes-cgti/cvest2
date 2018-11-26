@extends('adminlte::page')

@section('css')
    
@stop

@section('content_header')
    @isset($corrector)
        <h1>Modificar Avaliador</h1>
    @else
        <h1>Adicionar Avaliador</h1>
    @endisset
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fas fa-home"></i></a></li>
        <li><a href="#">Redações</a></li>
        <li><a href="{{ route('corrector.index') }}">Avaliadores</a></li>
        @isset($corrector)
            <li><a href="{{ route('corrector.edit', $corrector->id) }}">Modificar</a></li>
        @else
            <li><a href="{{ route('corrector.create') }}">Adicionar</a></li>
        @endisset
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <a href="{{ redirect()->back()->getTargetUrl() }}" class="btn btn-primary"><i class="fa fa-fw fa-arrow-left"></i> Voltar</a>
        </div>
        <div class="box-body">
            @if (session('erro'))
                <div class="alert alert-error alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="fas fa-exclamation-circle"></i> Erro: </h4>
                    <p>{{ session('erro') }}</p>
                </div>
            @endif
            {{-- Formulário para adicionar ou modificar avaliadores --}}
            @isset($corrector)
                <form method="post" action="{{ route('corrector.update', $corrector->id) }}" enctype="multipart/form-data">
                    {{ method_field('PUT')}}
            @else
                <form method="post" action="{{ route('corrector.store') }}" enctype="multipart/form-data">
            @endisset
                {{ csrf_field() }}
                <div class="form-group {{ $errors->has('cpf') ? 'has-error' : '' }}">
                    <label for="cpf">CPF</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Número do cadastro de pessoa fisica" value="{{ isset($corrector) ? old('cpf', $corrector->cpf) : old('cpf') }}">
                    @if ($errors->has('cpf'))
                        <span class="help-block">
                            <strong>{{ $errors->first('cpf') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('siape') ? 'has-error' : '' }}">
                    <label for="siape">SIAPE</label>
                    <input type="text" class="form-control" id="siape" name="siape" placeholder="Matrícula SIAPE do servidor" value="{{ isset($corrector) ? old('siape', $corrector->siape) : old('siape') }}">
                    @if ($errors->has('siape'))
                        <span class="help-block">
                            <strong>{{ $errors->first('siape') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">NOME</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nome completo" value="{{ isset($corrector) ? old('name', $corrector->user->name) : old('name') }}">
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email">E-MAIL</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Endereço de correio eletrônico" value="{{ isset($corrector) ? old('email', $corrector->user->email) : old('email') }}">
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-2 col-md-offset-10">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-fw fa-save"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    
@stop