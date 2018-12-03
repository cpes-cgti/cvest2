@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css')}} ">
    <link rel="stylesheet" href="{{ asset('vendor/icheck-1.0.2/skins/all.css')}} ">
@stop

@section('content_header')
    <h1>Corrigir redação:</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fas fa-home"></i></a></li>
        <li><a href="{{ route('redaction.index') }}">Redações</a></li>
        <li><a href="{{ route('redaction.rate_lots') }}">Lotes para correção</a></li>
        <li><a href="{{ route('redaction.rate', [$lot, $id]) }}">Lote:{{$lot}}/ ID:{{$id}}</a></li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <a href="{{ route('redaction.rate', [$lot, $id]) }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Anterior</a>
            <a href="{{ route('redaction.rate', [$lot, $id]) }}" class="btn btn-primary"> Próximo <i class="fas fa-arrow-right"></i></a>
            <div class="box-tools pull-right">
                <span><b>NOTA:</b></span>&nbsp;&nbsp;
                <a id="cor_nota" class="btn btn-danger"><span style="font-size: 1.5em; width: 6em; display: inline-block;" id="nota">0,00</span></a>
            </div>
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
                <div class="col-md-4">
                    <form>
                        {{ csrf_field() }}
                        <label>Atribuir nota <b>ZERO</b> à redação:</label>
                        <span style="margin: 0.8em 0; display: block;"><input id="chk_zerar_1" name="zerar_1" type="checkbox"> Folha resposta em branco.</span>
                        <span style="margin: 0.8em 0; display: block;"><input id="chk_zerar_2" name="zerar_2" type="checkbox"> Folha resposta identificada pelo candidato(a).</span>
                        <span style="margin: 0.8em 0; display: block;"><input id="chk_zerar_3" name="zerar_3" type="checkbox"> Fuga total ao tema proposto.</span>
                        <span style="margin: 0.8em 0; display: block;"><input id="chk_zerar_4" name="zerar_4" type="checkbox"> Não atende ao número mínimo de linhas.</span>
                        <span style="margin: 0.8em 0; display: block;"><input id="chk_zerar_5" name="zerar_5" type="checkbox"> Presença de conteúdo impróprio/ofensivo.</span>
                        <br>
                        <div id="avaliar_competencias">
                            <label>Avaliar competências exigidas:</label>
                            <table id="tb_competencias" class="table table-bordered" style="text-align: center;">
                                <tr>
                                    <td><b>NÍVEL</b></td>
                                    <td><b>0</b></td>
                                    <td><b>I</b></td>
                                    <td><b>II</b></td>
                                    <td><b>III</b></td>
                                    <td><b>IV</b></td>
                                    <td><b>V</b></td>
                                </tr>
                                <tr>
                                    <td><b>A</b></td>
                                    <td><input type="radio" id="ca1" name="competencia-A"> 0,0</td>
                                    <td><input type="radio" id="ca2" name="competencia-A"> 0,5</td>
                                    <td><input type="radio" id="ca3" name="competencia-A"> 1,0</td>
                                    <td><input type="radio" id="ca4" name="competencia-A"> 1,5</td>
                                    <td><input type="radio" id="ca5" name="competencia-A"> 2,0</td>
                                    <td><input type="radio" id="ca6" name="competencia-A"> 2,5</td>
                                </tr>
                                <tr>
                                    <td><b>B</b></td>
                                    <td><input type="radio" id="cb1" name="competencia-B"> 0,0</td>
                                    <td><input type="radio" id="cb2" name="competencia-B"> 0,5</td>
                                    <td><input type="radio" id="cb3" name="competencia-B"> 1,0</td>
                                    <td><input type="radio" id="cb4" name="competencia-B"> 1,5</td>
                                    <td><input type="radio" id="cb5" name="competencia-B"> 2,0</td>
                                    <td><input type="radio" id="cb6" name="competencia-B"> 2,5</td>
                                </tr>
                                <tr>
                                    <td><b>C</b></td>
                                    <td><input type="radio" id="cc1" name="competencia-C"> 0,0</td>
                                    <td><input type="radio" id="cc2" name="competencia-C"> 0,5</td>
                                    <td><input type="radio" id="cc3" name="competencia-C"> 1,0</td>
                                    <td><input type="radio" id="cc4" name="competencia-C"> 1,5</td>
                                    <td><input type="radio" id="cc5" name="competencia-C"> 2,0</td>
                                    <td><input type="radio" id="cc6" name="competencia-C"> 2,5</td>
                                </tr>
                                <tr>
                                    <td><b>D</b></td>
                                    <td><input type="radio" id="cd1" name="competencia-D"> 0,0</td>
                                    <td><input type="radio" id="cd2" name="competencia-D"> 0,5</td>
                                    <td><input type="radio" id="cd3" name="competencia-D"> 1,0</td>
                                    <td><input type="radio" id="cd4" name="competencia-D"> 1,5</td>
                                    <td><input type="radio" id="cd5" name="competencia-D"> 2,0</td>
                                    <td><input type="radio" id="cd6" name="competencia-D"> 2,5</td>
                                </tr>
                            </table>

                            <h5>A - Convenções da escrita</h5>
                            <p>Avaliação quanto ao domínio das convenções e normas do sistema de escrita formal da Língua Portuguesa.</p>
                            <h5>B - Tipo e gênero</h5>
                            <p>Avaliação quanto à produção de texto dissertativo-argumentativo em prosa, bem como quanto à mobilização de conhecimentos relativos aos limites estruturais do gênero.</p>
                            <h5>C - Tema e argumentação</h5>
                            <p>Avaliação quanto ao desenvolvimento de um texto com abordagem pertinente à proposta temática, e à apresentação de argumentos em defesa de um ponto de vista.</p>
                            <h5>D - Coesão</h5>
                            <p>Avaliação quanto a utilização de mecanismos linguísticos para construir texto coeso e significativo.</p>
                        </div>
                    </form>
                </div>
                <div class="col-md-8">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Redação</h3>
                            <div class="box-tools pull-right">
                                <button id="img_minus" type="button" class="btn btn-box-tool"><i class="fas fa-search-minus"></i></i></button>
                                <button id="img_plus" type="button" class="btn btn-box-tool"><i class="fas fa-search-plus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div style="background-color: gray; width: 100%; min-height: 75vh; overflow:auto;">
                                <img id="img_redacao" src="{{ $img_data->encoded }}" alt="" style="width: 90%; display: block; margin: 5% 5%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
    <script src="{{ asset('vendor/icheck-1.0.2/icheck.js') }}"></script>
    <script>
        $(document).ready( function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-blue'
            });
        }).on('ifChanged', function(e) {
            var c1 = $('#chk_zerar_1').is(':checked');
            var c2 = $('#chk_zerar_2').is(':checked');
            var c3 = $('#chk_zerar_3').is(':checked');
            var c4 = $('#chk_zerar_4').is(':checked');
            var c5 = $('#chk_zerar_5').is(':checked');
            if (c1 || c2 || c3 || c4 || c5){
                $('#avaliar_competencias').css('display', 'none');
            } else {
                $('#avaliar_competencias').css('display', 'block');
            }
            var ca = 0;
            if ($('#ca1').is(':checked')) ca = 0.0;
            if ($('#ca2').is(':checked')) ca = 0.5;
            if ($('#ca3').is(':checked')) ca = 1.0;
            if ($('#ca4').is(':checked')) ca = 1.5;
            if ($('#ca5').is(':checked')) ca = 2.0;
            if ($('#ca6').is(':checked')) ca = 2.5;
            var cb = 0;
            if ($('#cb1').is(':checked')) cb = 0.0;
            if ($('#cb2').is(':checked')) cb = 0.5;
            if ($('#cb3').is(':checked')) cb = 1.0;
            if ($('#cb4').is(':checked')) cb = 1.5;
            if ($('#cb5').is(':checked')) cb = 2.0;
            if ($('#cb6').is(':checked')) cb = 2.5;
            var cc = 0;
            if ($('#cc1').is(':checked')) cc = 0.0;
            if ($('#cc2').is(':checked')) cc = 0.5;
            if ($('#cc3').is(':checked')) cc = 1.0;
            if ($('#cc4').is(':checked')) cc = 1.5;
            if ($('#cc5').is(':checked')) cc = 2.0;
            if ($('#cc6').is(':checked')) cc = 2.5;
            var cd = 0;
            if ($('#cd1').is(':checked')) cd = 0.0;
            if ($('#cd2').is(':checked')) cd = 0.5;
            if ($('#cd3').is(':checked')) cd = 1.0;
            if ($('#cd4').is(':checked')) cd = 1.5;
            if ($('#cd5').is(':checked')) cd = 2.0;
            if ($('#cd6').is(':checked')) cd = 2.5;

            if (c1 || c2 || c3 || c4 || c5){
                t = 0; 
            } else {
                t = ca + cb + cc + cd;
            }
            tf = t.toFixed(2).replace(".",",");
            if (t>0){
                $('#cor_nota').removeClass('btn-danger');
                $('#cor_nota').addClass('btn-success');
            } else {
                $('#cor_nota').removeClass('btn-success');
                $('#cor_nota').addClass('btn-danger');
            }
            $('#nota').text(tf);

        });
        $( "#img_minus" ).click(function() {
            w = $("#img_redacao").width();
            w = w * 0.9;
            w = w + "px";
            $("#img_redacao").css("width", w);
            $("#img_redacao").css("margin", "5% auto");
        });
        $( "#img_plus" ).click(function() {
            w = $("#img_redacao").width();
            w = w * 1.1;
            w = w + "px";
            $("#img_redacao").css("width", w);
            $("#img_redacao").css("margin", "5% auto");
        });
    </script>
@stop