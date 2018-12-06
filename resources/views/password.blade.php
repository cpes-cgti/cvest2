@extends('adminlte::page')

@section('css')
    
@stop

@section('content_header')
    <h1>Alterar senha</h1>
    
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fas fa-home"></i></a></li>
        <li><a href="{{ route('password.change') }}">Alterar senha</a></li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <a href="{{ route('admin') }}" class="btn btn-primary"><i class="fa fa-fw fa-arrow-left"></i> Voltar</a>
        </div>
        <div class="box-body">
            @if (session('sucesso'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="fas fa-exclamation-circle"></i> Sucesso: </h4>
                    <p>{{ session('sucesso') }}</p>
                </div>
            @endif
            {{-- Formulário para adicionar ou modificar avaliadores --}}
            
            <form method="post" action="{{ route('password.change') }}" enctype="multipart/form-data">
                {{ method_field('PUT')}}
                {{ csrf_field() }}
                <div class="form-group {{ $errors->has('old_password') ? 'has-error' : '' }}">
                    <label for="old_password">Senha atual:</label>
                    <input type="password" class="form-control" id="old_password" name="old_password">
                    @if ($errors->has('old_password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('old_password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password">Senha:</label>
                    <input type="password" class="form-control" id="password" name="password">
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                    <label for="password_confirmation">Senha de confirmação:</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-2 col-md-offset-10">
                    <button type="submit" class="btn btn-success btn-block"><i class="fas fa-exchange-alt"></i> Alterar senha</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    
@stop