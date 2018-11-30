@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css')}} "> 
    <link rel="stylesheet" href="{{ asset('vendor/select2-4.0.5/dist/css/select2.min.css')}}">
    <style>
        #loading i{
            -webkit-animation: spin 3s linear infinite; /* Safari */
            animation: spin 3s linear infinite;
        }

        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        #loading .fas{
            position: absolute;
            top: 50%;
            left: 50%;
            margin-left: -25px;
            margin-top: -25px;
            color: #000;
            font-size: 50px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #222D32;
            border-color: #000;
            padding: 1px 10px;
            color: #fff;
        }
        a {
            cursor: pointer;
        }
    </style>
@stop

@section('content_header')
    <h1>Distribuir redações para avaliadores:</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fas fa-home"></i></a></li>
        <li><a href="{{ route('redaction.index') }}">Redações</a></li>
        <li><a href="{{ route('redaction.allocate') }}">Distribuir para avaliadores</a></li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            
        </div>
        <div class="box-body" style="min-height: 70vh">
            @if (session('erro'))
                <div class="alert alert-error alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="fas fa-exclamation-circle"></i> Erro: </h4>
                    <p>{{ session('erro') }}</p>
                </div>
            @endif
            
            <p>Esta função distribui as redações selecionadas para correção entre os avaliadores disponíveis. 
                Lembrando que toda redação deve ser corrigida no mínimo por 2 avaliadores.</p>
            
            <br>
                
            <form id="form_allocate" method="post" action="{{ route('redaction.process_allocate') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group {{ $errors->has('correctors') ? 'has-error' : '' }}">
                    <label for="correctors">Avaliadores:</label>
                    (<a id="selecionar_c">Selecionar todos</a> / <a id="deselecionar_c">Limpar seleção</a>)
                    <select class="form-control" name="correctors[]" id="corrector_select" multiple="multiple">
                        @foreach($correctors as $c)
                            <option value="{{ $c->id }}">
                                {{ $c->user->name}} 
                            </option>
                        @endforeach
                    </select>
                    <p class="text-red">É necessário selecionar no mínimo 2 avaliadores.</p>
                    @if ($errors->has('correctors'))
                        <span class="help-block">
                            <strong>{{ $errors->first('correctors') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success btn-block"><i class="fas fa-project-diagram"></i> Distribuir redações</button>
                </div>
            </form>
            <br><br><br>
            <table id="tb_lotes" class="table table-striped">
                <thead>
                    <tr>
                        <th>LOTE</th>
                        <th>QUANTIDADE</th>
                        <th>CORRIGIDAS</th>
                        <th>PROGRESSO</th>
                        <th>AVALIADOR(A)</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lots as $l)
                    <tr>
                        <td>{{$l->lot}}</td>
                        <td>{{$l->lot_count}}</td>
                        <td>{{$l->ready}}</td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: {{ ceil($l->ready / $l->lot_count * 100) }}%">
                                    @if ($l->ready == 0)
                                    <span style="color: #000; font-weight: bold; margin-left: 1em;">0%</span>
                                    @else
                                    <span style="color: #FFF; font-weight: bold;">{{ ceil($l->ready / $l->lot_count * 100) }}%</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{$l->name}}</td>
                        <td>
                            <form action="{{ route('redaction.lot_destroy', $l->lot) }}" class="form-inline" method="POST" >
                                {{ csrf_field() }}
                                {{ method_field('DELETE')}}
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-default btn-sm form-delete"><i class="fas fa-trash-alt" style="color: darkred"></i></button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>
        </div>
        <div class="overlay" style="display:none;" id="loading">
            <i class="fas fa-sync-alt"></i>
            {{-- <i class="fas fa-spinner"></i> --}}
        </div>
        <!-- Modal -->
        <div class="modal fade" id="dialog_del">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Remover o lote?</h4>
                    </div>
                    <div class="modal-body">
                    <p>Tem certeza que deseja remover o lote?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="delete-btn">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@stop

@section('js')
    <script src="{{ asset('vendor/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('vendor/select2-4.0.5/dist/js/select2.min.js')}}"></script>
    <script>
        $(document).ready( function () {
            $('#corrector_select').select2();
            $('#selecionar_c').click(function(){
                /* $('#corrector_select option').attr('selected','selected');
                $('#corrector_select').select2(); */
                $('#corrector_select').val([{{ $correctors_ids->implode('id', ',') }}]).trigger('change');
            }); 
            $('#deselecionar_c').click(function(){
                /* $('#corrector_select option').removeAttr('selected');
                $('#corrector_select').select2(); */
                $('#corrector_select').val(null).trigger('change');
            });
            $('#tb_lotes').DataTable({
                "language": {
                    "decimal":        "",
                    "emptyTable":     "Não existem registros para exibir.",
                    "info":           "Exibindo _START_ até _END_ de _TOTAL_ registros",
                    "infoEmpty":      "Exibindo 0 até 0 de 0 registros",
                    "infoFiltered":   "(Fitrado a partir de _MAX_ registros)",
                    "infoPostFix":    "",
                    "thousands":      ",",
                    "lengthMenu":     "Exibir _MENU_ registros",
                    "loadingRecords": "Carregando...",
                    "processing":     "Processando...",
                    "search":         "Buscar:",
                    "zeroRecords":    "Nenhum regsitro encontrado",
                    "paginate": {
                        "first":      "Primeiro",
                        "last":       "Último",
                        "next":       "Próximo",
                        "previous":   "Anterior"
                    },
                    "aria": {
                        "sortAscending":  ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    }
                }
            });
        } );
        window.onload = function() {
            $("#form_allocate").submit(function(event) {
                $('#loading').css('display','block');
            });
        };
        $('#tb_lotes').on('click', '.form-delete', function(e){
            e.preventDefault();
            var $form = $(this).parents("form");
            $('#dialog_del').modal()
                .on('click', '#delete-btn', function(){
                    $form.submit();
                });
        });
    </script>
@stop