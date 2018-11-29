@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1>Resumo</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Redações</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <canvas id="pieChart" style="height: 187px; width: 374px;" width="467" height="233"></canvas>        
                        </div>
                        <div class="col-md-4">
                            <ul class="chart-legend clearfix">
                                <li><i class="fas fa-square text-gray"></i> Digitalizada</li>
                                <li><i class="fas fa-square text-yellow"></i> Para correção</li>
                                <li><i class="fas fa-square text-blue"></i> Corrigida (1x)</li>
                                <li><i class="fas fa-square text-green"></i> Corrigida (concluído)</li>
                                <li><i class="fas fa-square text-red"></i> Inconsistência</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3>4</h3>         
                    Avaliadores<br>&nbsp;
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer"><i class="fas fa-list-ul"></i> Detalhes</a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>0</h3>         
                    Redações para correção<br>&nbsp;
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <a href="#" class="small-box-footer"><i class="fas fa-list-ul"></i> Detalhes</a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>0</h3>         
                    Redações Corrigidas<br>(1 avaliador)
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <a href="#" class="small-box-footer"><i class="fas fa-list-ul"></i> Detalhes</a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>0</h3>         
                    Redações corrigidas<br>(2 avaliadores)
                </div>
                <div class="icon">
                    <i class="fas fa-check-double"></i>
                </div>
                <a href="#" class="small-box-footer"><i class="fas fa-list-ul"></i> Detalhes</a>
            </div>
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