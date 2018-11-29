@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css')}} ">  
@stop

@section('content_header')
    <h1>Redações</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fas fa-home"></i></a></li>
        <li><a href="{{ route('redaction.index') }}">Redações</a></li>
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
            <table id="tb_redacoes" class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>INSCRIÇÃO</th>
                        <th>STATUS</th>
                        <th>MÉDIA</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                {{-- <tbody>
                    @foreach ($redacoes as $r)
                    <tr>
                        <td>{{$r->id}}</td>
                        <td>{{$r->entry}}</td>
                        <td>{{$r->status}}</td>
                        <td>{{$r->final_score}}</td>
                        <td>{{$r->lot_id}}</td>
                        <td>
                            <form class="form-inline" >
                                <div class="btn-group">
                                    <a href="{{ route('redaction.show', $r->id) }}" class="btn btn-default btn-sm" style="color:black"><i class="far fa-eye"></i></a>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach 
                </tbody> --}}
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
            $('#tb_redacoes').DataTable({
                serverSide: true,
                ajax: "{{ route('redaction.datatables') }}",
                columns: [
                    { name: 'id' },
                    { name: 'entry' },
                    { name: 'status' },
                    { name: 'final_score' },
                    { name: 'action', orderable: false, searchable: false }
                ],
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
            $("#tb_redacoes").on("click", ".btn-exibir", function() {
                var key = $(this).attr('chave');
                $("#dialog_exibir .modal-body").load(key, function(){
                    $("#dialog_exibir").modal({show:true});
                });
            });
        });

    </script>
@stop