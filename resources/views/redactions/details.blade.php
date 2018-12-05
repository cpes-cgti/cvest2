<div class="row">
    <div class="col-md-12">
        <fieldset>
            <legend>Redação:</legend>
            <div class="form-group">
                <label style="font-weight: normal;">ID: </label>
                <strong style="font-size: 1.2em;">{{ isset($redaction) ? str_pad($redaction->id, 6, "0", STR_PAD_LEFT) : '' }}</strong>
            </div>
            <div class="form-group">
                <label style="font-weight: normal;">Inscrição: </label>
                <strong style="font-size: 1.2em;">{{ isset($redaction) ? $redaction->entry : '' }}</strong>
            </div>
            <div class="form-group">
                <label style="font-weight: normal;">Status: </label>
                {!! $redaction->laratablesStatus() !!}
            </div>
            <div class="form-group">
                <label style="font-weight: normal;">Média Final: </label>
                <strong style="font-size: 1.2em;">{{ isset($redaction) ? $redaction->final_score : '' }}</strong>
            </div>
        </fieldset>
        <fieldset>
            <legend>Correção:</legend>
            @if ($corrections->count() > 0)
                <table class="table table-bordered table-striped" >
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle; text-align: center;">AVALIADOR</th>
                            <th colspan="4" style="text-align: center;">Competências Avaliadas</th>
                            <th colspan="5" style="text-align: center;">Atribuir Zero</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center;">Observação</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center;">NOTA</th>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <th style="text-align: center;"><abbr title="A - CONVENÇÕES DA ESCRITA">A</abbr></th>
                            <th style="text-align: center;"><abbr title="B - TIPO E GÊNERO">B</abbr></th>
                            <th style="text-align: center;"><abbr title="C - TEMA E ARGUMENTAÇÃO">C</abbr></th>
                            <th style="text-align: center;"><abbr title="D - COESÃO">D</abbr></th>
                            <th style="text-align: center;"><abbr title="atribuída NOTA ZERO à Redação que: contiver a folha-resposta em branco;">1</abbr></th>
                            <th style="text-align: center;"><abbr title="atribuída NOTA ZERO à Redação que: contiver a folha-resposta identificada;">2</abbr></th>
                            <th style="text-align: center;"><abbr title="atribuída NOTA ZERO à Redação que: fugir totalmente ao tema proposto pela Banca de Elaboração;">3</abbr></th>
                            <th style="text-align: center;"><abbr title="atribuída NOTA ZERO à Redação que: não atender ao número mínimo de linhas;">4</abbr></th>
                            <th style="text-align: center;"><abbr title="atribuída NOTA ZERO à Redação que: contiver impropérios ou outras formas propositais de anulação;">5</abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php dd($corrections); @endphp --}}
                        @foreach($corrections as $c)
                        <tr>
                            <td style="text-align: left;">{{ $c->name }}</td>
                            <td style="text-align: center;">{{ (is_null($c->competenceA)) ? '-' : number_format($c->competenceA, 2, ",", ".") }}</td>
                            <td style="text-align: center;">{{ (is_null($c->competenceB)) ? '-' : number_format($c->competenceB, 2, ",", ".") }}</td>
                            <td style="text-align: center;">{{ (is_null($c->competenceC)) ? '-' : number_format($c->competenceC, 2, ",", ".") }}</td>
                            <td style="text-align: center;">{{ (is_null($c->competenceD)) ? '-' : number_format($c->competenceD, 2, ",", ".") }}</td>
                            <td style="text-align: center; color: red;">{!! ($c->zero_empty) ? "<i class='fas fa-times'></i>" : "" !!}</td>
                            <td style="text-align: center; color: red;">{!! ($c->zero_identification) ? "<i class='fas fa-times'></i>" : "" !!}</td>
                            <td style="text-align: center; color: red;">{!! ($c->zero_theme) ? "<i class='fas fa-times'></i>" : "" !!}</td>
                            <td style="text-align: center; color: red;">{!! ($c->zero_lines) ? "<i class='fas fa-times'></i>" : "" !!}</td>
                            <td style="text-align: center; color: red;">{!! ($c->zero_offensive_content) ? "<i class='fas fa-times'></i>" : "" !!}</td>
                            <td style="text-align: justify;">{{ $c->note }}</td>
                            <td style="text-align: right;">{{ number_format($c->score, 2, ",", ".") }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <h4 style="color: darkgoldenrod;">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Esta redação ainda não foi avaliada/corrigida.
                </h4>
            @endif
        </fieldset>
    </div>
</div>