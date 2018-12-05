@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css')}} ">  
@stop

@section('content_header')
    <h1>Avaliadores</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fas fa-home"></i></a></li>
        <li><a href="{{ route('corrector.index') }}">Avaliadores</a></li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <a href="{{ route('corrector.create') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> Adicionar Avaliador</a>
        </div>
        <div class="box-body" style="min-height: 70vh">
            @if (session('erro'))
                <div class="alert alert-error alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="fas fa-exclamation-circle"></i> Erro: </h4>
                    <p>{{ session('erro') }}</p>
                </div>
            @endif
            <table id="tb_avaliadores" class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>CPF</th>
                        <th>SIAPE</th>
                        <th>NOME</th>
                        <th>EMAIL</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($avaliadores as $a)
                    <tr>
                        <td>{{$a->id}}</td>
                        <td>{{$a->cpf}}</td>
                        <td>{{$a->siape}}</td>
                        <td>{{$a->user->name}}</td>
                        <td>{{$a->user->email}}</td>
                        <td>
                            <form action="{{ route('corrector.destroy', $a->id) }}" class="form-inline" method="POST" >
                                {{ csrf_field() }}
                                {{ method_field('DELETE')}}
                                <div class="btn-group">
                                    <abbr title="Visualizar avaliador"><a href="{{ route('corrector.show', $a->id) }}" class="btn btn-default btn-sm" style="color:black"><i class="far fa-eye"></i></a></abbr>
                                    <abbr title="Modificar avaliador"><a href="{{ route('corrector.edit', $a->id) }}" class="btn btn-default btn-sm" style="color: darkgreen"><i class="fas fa-user-edit"></i></a></abbr>
                                    <abbr title="Remover avaliador"><button type="submit" class="btn btn-default btn-sm form-delete"><i class="fas fa-trash-alt" style="color: darkred"></i></button></abbr>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>
            {{-- {!! $avaliadores->links() !!} --}}
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="dialog_del">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Remover o avaliador?</h4>
                </div>
                <div class="modal-body">
                <p>Tem certeza que deseja remover o avaliador?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="delete-btn">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ asset('vendor/DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready( function () {
            $('#tb_avaliadores').DataTable({
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
        $('#tb_avaliadores').on('click', '.form-delete', function(e){
            e.preventDefault();
            var $form = $(this).parents("form");
            $('#dialog_del').modal()
                .on('click', '#delete-btn', function(){
                    $form.submit();
                });
        });
    </script>
@stop