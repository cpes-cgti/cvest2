@extends('adminlte::page')

@section('content_header')
    <h1>Resumo</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            {{-- Caixa do avaliador --}}
            @if ($isCorrector)
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Prezado(a) avaliador(a):</h3>
                    </div>
                    <div class="box-body">
                        @if (!isset($corrector))
                            <h4 style="color: darkgoldenrod;">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Você ainda não possui redações para corrigir.
                            </h4>
                        @else
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-fw fa-file-signature "></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Você possui <b>{{ number_format($corrector->to_do, 0, ",", ".") }}</b> redações para corrigir.</span>
                                    
                                    <div class="progress-group">
                                        <span class="progress-text">Redações corrigidas: {{ number_format($corrector->ready, 0, ",", ".") }}</span>
                                        <span class="progress-number"><b> {{ round($corrector->ready / $corrector->to_do * 100, 2) }}%</b></span>
                                        <div class="progress" style="height: 1em;">
                                            <div class="progress-bar progress-bar-green" style="width: {{ ceil($corrector->ready / $corrector->to_do * 100) }}%; background-color: #00a65a;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            @if (\Auth::user()->profile > 1)
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Redações:</h3>
                    </div>
                    <div class="box-body">
                        @if ($redactions->count() < 1)
                            <h4 style="color: darkgoldenrod;">
                                <i class="fas fa-exclamation-triangle"></i> 
                                As redações ainda não foram digitalizadas e inseridas no sistema.
                            </h4>
                        @else
                            <div class="row">
                                <div class="col-md-8">
                                    <canvas id="pieChart" style="height: 187px; width: 374px;" width="467" height="233"></canvas>        
                                </div>
                                <div class="col-md-4">
                                    <ul class="chart-legend clearfix">
                                        <li>
                                            <i class="fas fa-square" style="color: #000;"></i> 
                                                Total: {{ number_format($redactions->sum->qtde, 0, ",", ".") }} ( 100% )
                                        </li>
                                        @foreach ($redactions as $r) 
                                            <li>
                                                <i class="fas fa-square" style="color: {{ $colors[$r->status] }};"></i> 
                                                {{ $r->status }}: {{ number_format($r->qtde, 0, ",", ".") }} <br>( <b>{{ number_format(round($r->qtde / $redactions->sum->qtde * 100, 2), 2, ",", ".") }}% </b> )
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            @if (\Auth::user()->profile > 1)
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <i class="fa fa-fw fa-users"></i>
                        <h3 class="box-title">Avaliadores:</h3>
                        <div class="progress-group">
                            <span class="progress-text">Redações corrigidas: {{ number_format($correctors->sum->ready, 0, ",", ".") }} / {{ number_format($correctors->sum->to_do, 0, ",", ".") }}</span>
                            @if ($correctors->sum->to_do > 0)
                                <span class="progress-number"><b> {{ number_format(round($correctors->sum->ready / $correctors->sum->to_do * 100, 2), 2, ",", ".") }}%</b></span>
                                <div class="progress" style="height: 1em;">
                                    <div class="progress-bar progress-bar-primary" style="width: {{ ceil($correctors->sum->ready / $correctors->sum->to_do * 100) }}%;"></div>
                                </div>
                            @else
                                <span class="progress-number"><b> ---%</b></span>
                                <div class="progress" style="height: 1em;">
                                    <div class="progress-bar progress-bar-primary" style="width: 0%;"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="box-body">
                        @if ($correctors->count() < 1)
                            <h4 style="color: darkgoldenrod;">
                                <i class="fas fa-exclamation-triangle"></i> 
                                As redações ainda não foram distribuídas para os avaliadores.
                            </h4>
                        @else
                            @foreach ($correctors as $c)
                            <div class="box box-solid">
                                <h4><i class="fa fa-fw fa-user"></i>{{ $c->name }}</h4>
                                <div class="progress-group">
                                    <span class="progress-text">Redações corrigidas: {{ number_format($c->ready, 0, ",", ".") }} / {{ number_format($c->to_do, 0, ",", ".") }}</span>
                                    <span class="progress-number"><b> {{ number_format(round($c->ready / $c->to_do * 100, 2),2,",",".") }}%</b></span>
                                    <div class="progress" style="height: 1em;">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped" style="width: {{ ceil($c->ready / $c->to_do * 100) }}%; "></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    
@stop

@section('js')
    <script src="{{ asset('js/Chart.js') }}"></script>
    <script>
        $(function () {
            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieChart       = new Chart(pieChartCanvas)
            var PieData        = [
            @foreach ($redactions as $r)    
                {
                    value    : '{{ $r->qtde }}',
                    color    : '{{ $colors[$r->status] }}',
                    highlight: '{{ $colors[$r->status] }}',
                    label    : '{{ $r->status }}'
                },
            @endforeach
            ]
            var pieOptions     = {
            //Boolean - Whether we should show a stroke on each segment
            segmentShowStroke    : true,
            //String - The colour of each segment stroke
            segmentStrokeColor   : '#fff',
            //Number - The width of each segment stroke
            segmentStrokeWidth   : 2,
            //Number - The percentage of the chart that we cut out of the middle
            percentageInnerCutout: 50, // This is 0 for Pie charts
            //Number - Amount of animation steps
            animationSteps       : 100,
            //String - Animation easing effect
            animationEasing      : 'easeOutBounce',
            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate        : true,
            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale         : false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive           : true,
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio  : true,
            //String - A legend template
            legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            pieChart.Doughnut(PieData, pieOptions)
        })
    </script>
@stop