@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title">
                        <i class="nc-icon nc-sound-wave"></i> Relatórios 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Evolução Por Rede Social 
                    </h4>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('relatorios') }}" class="btn btn-info pull-right"><i class="nc-icon nc-chart-bar-32"></i> Relatórios</a>
                </div>
            </div>
        </div>
        <div class="card-body"> 
            @include('layouts/regra')          
            <p class="ml-1">Volume diário de mensagens dividido por rede social no período de {{ $periodo_relatorio['data_inicial'] }} à {{ $periodo_relatorio['data_final'] }}</p>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card car-chart">
                        <div class="card-body">
                          <canvas id="chartActivity"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection
@section('script')
<script>
    $(document).ready(function() {

        var dados = null;
        var periodo_padrao = 7;
        var host =  $('meta[name="base-url"]').attr('content');
        var myChart = null;

        $("#periodo").change(function(){

            var periodo = $(this).val();
            loadDados(periodo);

        });

        loadDados(periodo_padrao);

        function loadDados(periodo){

            $.ajax({
                url: host+'/monitoramento/medias/historico/'+periodo,
                type: 'GET',
                success: function(response) {
                    if(myChart) myChart.destroy();
                    dados = response;
                    initDashboardPageCharts();
                }
            }); 
        }

        function initDashboardPageCharts() {

            chartColor = "#FFFFFF";

            var cardStatsMiniLineColor = "#fff",
            cardStatsMiniDotColor = "#fff";

            ctx = document.getElementById('chartActivity').getContext("2d");

            gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
            gradientStroke.addColorStop(0, '#80b6f4');
            gradientStroke.addColorStop(1, chartColor);

            gradientFill = ctx.createLinearGradient(0, 170, 0, 50);
            gradientFill.addColorStop(0, "rgba(128, 182, 244, 0)");
            gradientFill.addColorStop(1, "rgba(249, 99, 59, 0.40)");

            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dados.data_formatada,
                    datasets: [
                    {
                        label: "Instagram",
                            borderColor: '#e91ea1',
                            fill: true,
                            backgroundColor: '#e91ea1',
                            hoverBorderColor: '#fcc468',
                            borderWidth: 8,
                            stack: '1',
                            data: dados.dados_instagram,
                        },
                        {
                            label: "Facebook",
                            borderColor: '#3f51b5',
                            fill: true,
                            backgroundColor: '#3f51b5',
                            hoverBorderColor: '#3f51b5',
                            borderWidth: 8,
                            stack: '1',
                            data: dados.dados_facebook,
                        },
                        {
                            label: "Twitter",
                            borderColor: '#51bcda',
                            fill: true,
                            backgroundColor: '#51bcda',
                            hoverBorderColor: '#51bcda',
                            borderWidth: 8,
                            stack: '1',
                            data: dados.dados_twitter
                        }
                    ]
                },
                options: {

                    tooltips: {
                    tooltipFillColor: "rgba(0,0,0,0.5)",
                    tooltipFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
                    tooltipFontSize: 14,
                    tooltipFontStyle: "normal",
                    tooltipFontColor: "#fff",
                    tooltipTitleFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
                    tooltipTitleFontSize: 14,
                    tooltipTitleFontStyle: "bold",
                    tooltipTitleFontColor: "#fff",
                    tooltipYPadding: 6,
                    tooltipXPadding: 6,
                    tooltipCaretSize: 8,
                    tooltipCornerRadius: 6,
                    tooltipXOffset: 10,
                    },


                    legend: {

                    display: false
                    },
                    scales: {

                    yAxes: [{
                        ticks: {
                        fontColor: "#9f9f9f",
                        fontStyle: "bold",
                        beginAtZero: true,
                        maxTicksLimit: 5,
                        padding: 20
                        },
                        gridLines: {
                        zeroLineColor: "transparent",
                        display: true,
                        drawBorder: false,
                        color: '#9f9f9f',
                        }

                    }],
                    xAxes: [{
                        barPercentage: 0.4,
                        gridLines: {
                        zeroLineColor: "white",
                        display: false,

                        drawBorder: false,
                        color: 'transparent',
                        },
                        ticks: {
                        padding: 20,
                        fontColor: "#9f9f9f",
                        fontStyle: "bold"
                        }
                    }]
                    }
                }
            });
        }
    });
</script>
@endsection