@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css')}} ">  
@stop

@section('content_header')
    <h1>Redações para corrigir</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fas fa-home"></i></a></li>
        <li><a href="{{ route('redaction.index') }}">Redações</a></li>
        <li><a href="{{ route('redaction.rate_lots') }}">Lotes para correção</a></li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            {{-- <a href="{{ route('corrector.create') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> Adicionar Avaliador</a> --}}
        </div>
        <div class="box-body" style="min-height: 70vh">
            @if (session('erro'))
                <div class="alert alert-error alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="fas fa-exclamation-circle"></i> Erro: </h4>
                    <p>{{ session('erro') }}</p>
                </div>
            @endif
            <div class="row">
                <div class="col-md-4 col-md-offset-8">
                    <div class="box box-solid box-primary">
                        <div class="box-header with-border">
                            <i class="fa fa-fw fa-user"></i>
                            <h3 class="box-title">{{ \Auth::user()->name }}</h3>
                            <div class="progress-group">
                                <span class="progress-text">Redações corrigidas: {{ number_format($lots->sum->ready, 0, ",", ".")  }} / {{ number_format($lots->sum->to_do, 0, ",", ".")  }}</span>
                                @if ($lots->sum->to_do > 0)
                                    <span class="progress-number"><b> {{ number_format(round($lots->sum->ready / $lots->sum->to_do * 100, 2), 2, ",", ".")  }}%</b></span>
                                    <div class="progress" style="height: 1em;">
                                        <div class="progress-bar progress-bar-primary" style="background-color: #A9D0F5; width: {{ ceil($lots->sum->ready / $lots->sum->to_do * 100) }}%;"></div>
                                    </div>
                                @else
                                    <span class="progress-number"><b> ---%</b></span>
                                    <div class="progress" style="height: 1em;">
                                        <div class="progress-bar progress-bar-primary" style="width: 0%;"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table id="tb_lotes" class="table table-striped">
                <thead>
                    <tr>
                        <th>LOTE</th>
                        <th>QUANTIDADE</th>
                        <th>CORRIGIDAS</th>
                        <th>STATUS</th>
                        <th>INICIADO</th>
                        <th>FINALIZADO</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lots as $l)
                    <tr>
                        <td>{{ str_pad($l->lot, 6, "0", STR_PAD_LEFT) }}</td>
                        <td>{{ number_format($l->to_do, 0, ",", ".") }}</td>
                        <td>{{ number_format($l->ready, 0, ",", ".") }}</td>
                        <td>
                            {{-- <table>
                            <tr>
                                <td>{{ round($l->ready / $l->to_do * 100, 2) }}%</td>
                                <td style="width: 90%;">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: {{ ceil($l->ready / $l->to_do * 100) }}%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </table> --}}
                            <div class="progress">
                                <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="background-color: #CEF6D8; width: {{ ceil($l->ready / $l->to_do * 100) }}%;">
                                    @if ($l->ready == 0)
                                    <span style="color: #000; font-weight: bold; margin-left: 1em;">{{ round($l->ready / $l->to_do * 100, 2) }}%</span>
                                    @else
                                    <span style="color: #000; font-weight: bold;">{{ ceil($l->ready / $l->to_do * 100) }}%</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ ($l->start == null) ? '' : Carbon\Carbon::parse($l->start)->format('d/m/Y H:i:s') }}</td>
                        <td>@if ($l->to_do == $l->ready) {{ Carbon\Carbon::parse($l->end)->format('d/m/Y H:i:s') }} @endif</td>
                        <td>
                            <form class="form-inline" >
                                <div class="btn-group">
                                    <abbr title="Abrir lote"><a href="{{ route('redaction.rate_lot', $l->lot) }}" class="btn btn-default btn-sm" style="color:black"><i class="fas fa-folder-open"></i></a></abbr>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>
            {{-- {!! $redacoes->links() !!} --}}
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="dialog_exibir">
        <div class="modal-dialog" role="document" style="width:80vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Exibir Redação</h4>
                </div>
                <div class="modal-body">
                
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="delete-btn">Confirmar</button> --}}
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ asset('vendor/DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready( function () {
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
            /* $("#tb_lotes").on("click", ".btn-exibir", function() {
                var key = $(this).attr('chave');
                $("#dialog_exibir .modal-body").load(key, function(){
                    $("#dialog_exibir").modal({show:true});
                });
            }); */
        });

    </script>
@stop