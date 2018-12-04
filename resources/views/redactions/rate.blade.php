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
            <div class="row">
                <div class="col-sm-4">
                    <a @if(!$first) href="{{ route('redaction.rate', [$lot, $previous]) }}" @endif style="width: 7em;" id="btn_previous" class="btn btn-primary" @if ($first) disabled="disabled" @endif><i class="fas fa-arrow-left" disabled="disabled"></i> Anterior</a>
                    <a @if(!$last) href="{{ route('redaction.rate', [$lot, $next]) }}" @endif style="width: 7em;" id="btn_next" class="btn btn-primary" @if ($last) disabled="disabled" @endif> Próximo <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="col-sm-4" style="text-align: center;">
                    <span><b>NOTA:</b></span>&nbsp;&nbsp;
                    <a id="cor_nota" class="btn btn-danger"><span style="font-size: 1.5em; width: 6em; display: inline-block;" id="nota">0,00</span></a>
                </div>
                <div class="col-sm-4">
                    <div class="box-tools pull-right">
                        <a style="width: 7em;" id="btn_finish" class="btn btn-success" @if ($missing > 1) disabled="disabled" @endif><i class="fas fa-check"></i> Concluir</a>
                        <a style="width: 7em;" href="{{ route('redaction.rate_lots') }}" id="btn_close" class="btn btn-danger"><i class="fas fa-times"></i> Fechar</a>
                    </div>
                </div>
            </div>
            {{-- <button disabled="disabled"></button> --}}
            
            
            
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
                    <form method="post" action="{{ route('redaction.rate_save', [$lot, $id]) }}" enctype="multipart/form-data" id="form_rate">
                        {{ csrf_field() }}
                        <input type="hidden" name="action" id="action">
                        <input type="hidden" name="start" value="{{ $start }}">
                        <input type="hidden" id="isUpdated" value="0">
                        <label>Atribuir nota <b>ZERO</b> à redação:</label>
                        <span style="margin: 0.8em 0; display: block;"><input id="chk_zerar_1" name="zerar_1" type="checkbox" @if($r->zero_empty) checked @endif> Folha resposta em branco.</span>
                        <span style="margin: 0.8em 0; display: block;"><input id="chk_zerar_2" name="zerar_2" type="checkbox" @if($r->zero_identification) checked @endif> Folha resposta identificada pelo candidato(a).</span>
                        <span style="margin: 0.8em 0; display: block;"><input id="chk_zerar_3" name="zerar_3" type="checkbox" @if($r->zero_theme) checked @endif> Fuga total ao tema proposto.</span>
                        <span style="margin: 0.8em 0; display: block;"><input id="chk_zerar_4" name="zerar_4" type="checkbox" @if($r->zero_lines) checked @endif> Não atende ao número mínimo de linhas.</span>
                        <span style="margin: 0.8em 0; display: block;"><input id="chk_zerar_5" name="zerar_5" type="checkbox" @if($r->zero_offensive_content) checked @endif> Presença de conteúdo impróprio/ofensivo.</span>
                        <br>
                        <div class="avaliar_competence">
                            <label>Avaliar competências exigidas:</label>
                            <table id="tb_competence" class="table table-bordered" 
                                style="text-align: center; 
                                    {{ ($errors->has('competenceA') || 
                                        $errors->has('competenceB') || 
                                        $errors->has('competenceC') || 
                                        $errors->has('competenceD') ) ? 'border: 2px solid red;' : '' }}
                                    ">
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
                                    <td><input type="radio" name="competenceA" value="0.0" @if(old('competenceA') == '0.0' || $r->competenceA == '0.0') checked @endif> 0,0</td>
                                    <td><input type="radio" name="competenceA" value="0.5" @if(old('competenceA') == '0.5' || $r->competenceA == '0.5') checked @endif> 0,5</td>
                                    <td><input type="radio" name="competenceA" value="1.0" @if(old('competenceA') == '1.0' || $r->competenceA == '1.0') checked @endif> 1,0</td>
                                    <td><input type="radio" name="competenceA" value="1.5" @if(old('competenceA') == '1.5' || $r->competenceA == '1.5') checked @endif> 1,5</td>
                                    <td><input type="radio" name="competenceA" value="2.0" @if(old('competenceA') == '2.0' || $r->competenceA == '2.0') checked @endif> 2,0</td>
                                    <td><input type="radio" name="competenceA" value="2.5" @if(old('competenceA') == '2.5' || $r->competenceA == '2.5') checked @endif> 2,5</td>
                                </tr>
                                <tr>
                                    <td><b>B</b></td>
                                    <td><input type="radio" name="competenceB" value="0.0" @if(old('competenceB') == '0.0' || $r->competenceB == '0.0') checked @endif> 0,0</td>
                                    <td><input type="radio" name="competenceB" value="0.5" @if(old('competenceB') == '0.5' || $r->competenceB == '0.5') checked @endif> 0,5</td>
                                    <td><input type="radio" name="competenceB" value="1.0" @if(old('competenceB') == '1.0' || $r->competenceB == '1.0') checked @endif> 1,0</td>
                                    <td><input type="radio" name="competenceB" value="1.5" @if(old('competenceB') == '1.5' || $r->competenceB == '1.5') checked @endif> 1,5</td>
                                    <td><input type="radio" name="competenceB" value="2.0" @if(old('competenceB') == '2.0' || $r->competenceB == '2.0') checked @endif> 2,0</td>
                                    <td><input type="radio" name="competenceB" value="2.5" @if(old('competenceB') == '2.5' || $r->competenceB == '2.5') checked @endif> 2,5</td>
                                </tr>
                                <tr>
                                    <td><b>C</b></td>
                                    <td><input type="radio" name="competenceC" value="0.0" @if(old('competenceC') == '0.0' || $r->competenceC == '0.0') checked @endif> 0,0</td>
                                    <td><input type="radio" name="competenceC" value="0.5" @if(old('competenceC') == '0.5' || $r->competenceC == '0.5') checked @endif> 0,5</td>
                                    <td><input type="radio" name="competenceC" value="1.0" @if(old('competenceC') == '1.0' || $r->competenceC == '1.0') checked @endif> 1,0</td>
                                    <td><input type="radio" name="competenceC" value="1.5" @if(old('competenceC') == '1.5' || $r->competenceC == '1.5') checked @endif> 1,5</td>
                                    <td><input type="radio" name="competenceC" value="2.0" @if(old('competenceC') == '2.0' || $r->competenceC == '2.0') checked @endif> 2,0</td>
                                    <td><input type="radio" name="competenceC" value="2.5" @if(old('competenceC') == '2.5' || $r->competenceC == '2.5') checked @endif> 2,5</td>
                                </tr>
                                <tr>
                                    <td><b>D</b></td>
                                    <td><input type="radio" name="competenceD" value="0.0" @if(old('competenceD') == '0.0' || $r->competenceD == '0.0') checked @endif> 0,0</td>
                                    <td><input type="radio" name="competenceD" value="0.5" @if(old('competenceD') == '0.5' || $r->competenceD == '0.5') checked @endif> 0,5</td>
                                    <td><input type="radio" name="competenceD" value="1.0" @if(old('competenceD') == '1.0' || $r->competenceD == '1.0') checked @endif> 1,0</td>
                                    <td><input type="radio" name="competenceD" value="1.5" @if(old('competenceD') == '1.5' || $r->competenceD == '1.5') checked @endif> 1,5</td>
                                    <td><input type="radio" name="competenceD" value="2.0" @if(old('competenceD') == '2.0' || $r->competenceD == '2.0') checked @endif> 2,0</td>
                                    <td><input type="radio" name="competenceD" value="2.5" @if(old('competenceD') == '2.5' || $r->competenceD == '2.5') checked @endif> 2,5</td>
                                </tr>
                                @if ($errors->has('competenceA') || 
                                    $errors->has('competenceB') || 
                                    $errors->has('competenceC') || 
                                    $errors->has('competenceD') )
                                    <p class="text-red">Deve ser atribuído uma nota para cada competência avaliada.</p>
                                @endif
                            </table>
                        </div>
                        <label for="note">Observações:</label>
                        <textarea class="form-control" name="note" rows="5">{{$r->note}}</textarea>
                        <div class="avaliar_competence">
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
        function atualizarNota(){
            var c1 = $('#chk_zerar_1').is(':checked');
            var c2 = $('#chk_zerar_2').is(':checked');
            var c3 = $('#chk_zerar_3').is(':checked');
            var c4 = $('#chk_zerar_4').is(':checked');
            var c5 = $('#chk_zerar_5').is(':checked');
            if (c1 || c2 || c3 || c4 || c5){
                $('.avaliar_competence').css('display', 'none');
            } else {
                $('.avaliar_competence').css('display', 'block');
            }

            if (c1 || c2 || c3 || c4 || c5){
                t = 0; 
            } else {
                t = (typeof $('input[name="competenceA"]:checked').val() == 'undefined' ? 0 : $('input[name="competenceA"]:checked').val() * 1)
                    + (typeof $('input[name="competenceB"]:checked').val() == 'undefined' ? 0 : $('input[name="competenceB"]:checked').val() * 1)
                    + (typeof $('input[name="competenceC"]:checked').val() == 'undefined' ? 0 : $('input[name="competenceC"]:checked').val() * 1)
                    + (typeof $('input[name="competenceD"]:checked').val() == 'undefined' ? 0 : $('input[name="competenceD"]:checked').val() * 1);
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
        }
        $(document).ready( function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-blue'
            });
            atualizarNota();
        }).on('ifChanged', function(e) {
            atualizarNota();
            $("#isUpdated").val("1");
            $("#btn_previous").removeAttr("href");
            $("#btn_next").removeAttr("href");
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
        $("#btn_previous:not([disabled])").click(function(e){
            $("#action").val("previous");
            if ( $("#isUpdated").val() == "1"){
                $("#form_rate").submit();
            }
        });
        $("#btn_next:not([disabled])").click(function(e){
            $("#action").val("next");
            if ( $("#isUpdated").val() == "1"){
                $("#form_rate").submit();
            }
        });
        $("#btn_finish:not([disabled])").click(function(){
            $("#action").val("finish");
            $("#form_rate").submit();
        });
    </script>
@stop