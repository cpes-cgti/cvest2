@extends('adminlte::master')

@section('adminlte_css')
    <style>

        html, body {
            background-color: #F39C12;
            color: #000;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        h1 {
            font-size: 20em;
        }
        
        h2 {
            font-size: 5em;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
            position: relative;
            height: 100vh;
        }

        .center {
            text-align: center;
        }

    </style>
@stop

@section('body')
    <div class="flex-center">
        <div class="center">
            <h1><i class="fas fa-exclamation-triangle"></i> 404</h1>
            <h2>Página não encontrada</h2>
        </div>
    </div>
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
@stop