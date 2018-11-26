@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1>Resumo</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3>4</h3>         
                    Avaliadores<br>&nbsp;
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer"><i class="fas fa-list-ul"></i> Detalhes</a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>0</h3>         
                    Redações para correção<br>&nbsp;
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <a href="#" class="small-box-footer"><i class="fas fa-list-ul"></i> Detalhes</a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>0</h3>         
                    Redações Corrigidas<br>(1 avaliador)
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <a href="#" class="small-box-footer"><i class="fas fa-list-ul"></i> Detalhes</a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>0</h3>         
                    Redações corrigidas<br>(2 avaliadores)
                </div>
                <div class="icon">
                    <i class="fas fa-check-double"></i>
                </div>
                <a href="#" class="small-box-footer"><i class="fas fa-list-ul"></i> Detalhes</a>
            </div>
        </div>
    </div>
@stop