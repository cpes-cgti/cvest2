@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css')}} ">  
@stop

@section('content_header')
    <h1>Avaliadores</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fas fa-home"></i></a></li>
        <li><a href="#">Redações</a></li>
        <li><a href="{{ route('corrector.index') }}">Avaliadores</a></li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <a href="{{ route('corrector.create') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> Adicionar Avaliador</a>
        </div>
        <div class="box-body" style="height: 70vh">
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
                            <a href="{{ route('corrector.show', $a->id) }}" class="btn btn-info"><i class="far fa-eye"></i></a>
                            <a href="{{ route('corrector.edit', $a->id) }}" class="btn btn-warning"><i class="fas fa-user-edit"></i></a>
                        </td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>
            {{-- {!! $avaliadores->links() !!} --}}
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
    </script>
@stop