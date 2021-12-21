@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title">
                        <i class="nc-icon nc-sound-wave"></i> Relatórios 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Evolução Diária 
                    </h4>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('relatorios') }}" class="btn btn-info pull-right"><i class="nc-icon nc-chart-bar-32"></i> Relatórios</a>
                </div>
            </div>
        </div>
        <div class="card-body"> 
            @include('layouts/regra')          
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

        loadDados(periodo_padrao);

        $("#periodo").change(function(){

            var periodo = $(this).val();
            inicializaDatas(periodo);
            loadDados(periodo);           

        });

        function calculaPeriodo(dataInicial, dataFinal)
        {
            var a = dataInicial.split("/");
            var date1 = new Date(a[2], a[1] - 1, a[0]);

            var b = dataFinal.split("/");
            var date2 = new Date(b[2], b[1] - 1, b[0]);

            var diffTime = Math.abs(date2 - date1);
            var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            diffDays = (diffDays < 1) ? 1 : diffDays;

            return diffDays;
        }

        $(document).on('keypress',function(e) {
            if(e.which == 13) {
            
                var dataInicial = $(".dt_inicial_relatorio").val();
                var dataFinal = $(".dt_final_relatorio").val();
                
                periodo = calculaPeriodo(dataInicial, dataFinal);
                atualizaLabels(dataInicial, dataFinal);
                loadDados(periodo);
                $("#periodo").val('custom');
            }
        });

        function formataData(data)
        {
            var dia = String(data.getDate()).padStart(2, '0');
            var mes = String(data.getMonth() + 1).padStart(2, '0');
            var ano = data.getFullYear();

            return dia + '/' + mes + '/' + ano;
        }
        
        function inicializaDatas(periodo)
        {
            var dataFinal = new Date();
            var dataInicial = new Date();    
            
            if(periodo != 'custom'){
                dataInicial.setDate(dataFinal.getDate() - periodo);
            }

            $(".dt_inicial_relatorio").val(formataData(dataInicial));
            $(".dt_final_relatorio").val(formataData(dataFinal));

            $(".label_data_inicial").html(formataData(dataInicial));
            $(".label_data_final").html(formataData(dataFinal));   
        }

        function atualizaLabels(dataInicial, dataFinal)
        {
            $(".label_data_inicial").html(dataInicial);
            $(".label_data_final").html(dataFinal);   
        }

        function loadDados(periodo){

            $.ajax({
                url: host+'/relatorios/dados/sentimentos/periodo/'+periodo,
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
                        label: "Positivo",
                            borderColor: '#4caf50',
                            fill: true,
                            backgroundColor: '#4caf50',
                            hoverBorderColor: '#4caf50',
                            borderWidth: 8,
                            stack: '1',
                            data: dados.dados_positivos,
                        },
                        {
                            label: "Negativo",
                            borderColor: '#f44336',
                            fill: true,
                            backgroundColor: '#f44336',
                            hoverBorderColor: '#f44336',
                            borderWidth: 8,
                            stack: '1',
                            data: dados.dados_negativos,
                        },
                        {
                            label: "Neutro",
                            borderColor: '#ffcc33',
                            fill: true,
                            backgroundColor: '#ffcc33',
                            hoverBorderColor: '#ffcc33',
                            borderWidth: 8,
                            stack: '1',
                            data: dados.dados_neutros
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