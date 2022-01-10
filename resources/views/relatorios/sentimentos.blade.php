@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title"><i class="fa fa-heart"></i> Relatórios <i class="fa fa-angle-double-right" aria-hidden="true"></i> Sentimentos</h4>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('relatorios') }}" class="btn btn-info pull-right"><i class="nc-icon nc-chart-bar-32"></i> Relatórios</a>
                            <a href="{{ url('pdf') }}" class="btn btn-danger pull-right mr-2"><i class="fa fa-file-pdf-o"></i> Baixar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('layouts/regra')
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <table class="table table-hover table_sentimentos d-none">
                                <thead class="">
                                    <tr>
                                        <th>Rede Social</th>
                                        <th class="center">Positivos</th>
                                        <th class="center">Negativos</th>
                                        <th class="center">Neutros</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table> 
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <canvas id="chart_sentiment"></canvas>
                        </div>
                    </div>
                </div>            
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="{{ asset('js/relatorios.js') }}"></script>
<script>
    $(document).ready(function() {

        var regra = 0;
        var dados_positivos = [];
        var dados_negativos = [];
        var dados_neutros = [];
        
        var periodo = {{ $periodo_padrao }};
        var host =  $('meta[name="base-url"]').attr('content');
        var token = $('meta[name="csrf-token"]').attr('content');
        var myChart = null;
        var dados = null;
        
        loadDados(periodo, regra); //Toda vez que carrega os dados, o gráfico é atualizado

        $("#periodo").change(function(){
            periodo = $(this).val();
            loadDados(periodo, regra);          
        });

        $(document).on('keypress',function(e) {
            if(e.which == 13) {
                periodo = 0;
                loadDados(periodo, regra);
            }
        });

        $("#regra").change(function(){
            loadDados(periodo, regra);
        });

        function loadDados(periodo, regra){

            var data_inicial = $(".dt_inicial_relatorio").val();
            var data_final = $(".dt_final_relatorio").val();

            $.ajax({
                url: host+'/relatorios/dados/sentimentos/rede',
                type: 'POST',
                data: { "_token": token,
                        "periodo": periodo,
                        "data_inicial": data_inicial,
                        "data_final": data_final,
                        "regra": regra },
                success: function(response) {
                    if(myChart) myChart.destroy();

                    $(".table_sentimentos tbody tr").empty();
                    $.each(response, function(index, value) {  
                        $(".table_sentimentos tbody").append('<tr><td>'+value.rede_social+'</td><td class="center">'+value.total_positivo+'</td><td class="center">'+value.total_negativo+'</td><td class="center">'+value.total_neutro+'</td></tr>');
                    }); 
                    $(".table_sentimentos").removeClass("d-none");

                    dados = response;
                    geraGrafico();
                }
            }); 
        }

        function geraGrafico(){

            chartColor = "#FFFFFF";

            var cardStatsMiniLineColor = "#fff",
            cardStatsMiniDotColor = "#fff";

            ctx = document.getElementById('chart_sentiment').getContext("2d");

            gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
            gradientStroke.addColorStop(0, '#80b6f4');
            gradientStroke.addColorStop(1, chartColor);

            gradientFill = ctx.createLinearGradient(0, 170, 0, 50);
            gradientFill.addColorStop(0, "rgba(128, 182, 244, 0)");
            gradientFill.addColorStop(1, "rgba(249, 99, 59, 0.40)");

            myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Positivos","Negativos","Neutros"],
                datasets: [
                {
                    label: "Instagram",
                        borderColor: '#e91ea1',
                        fill: true,
                        backgroundColor: '#e91ea1',
                        hoverBorderColor: '#fcc468',
                        borderWidth: 8,
                        data: [dados.instagram.total_positivo, dados.instagram.total_negativo, dados.instagram.total_neutro],
                    },
                    {
                        label: "Facebook",
                        borderColor: '#3f51b5',
                        fill: true,
                        backgroundColor: '#3f51b5',
                        hoverBorderColor: '#3f51b5',
                        borderWidth: 8,
                        data: [dados.facebook.total_positivo, dados.facebook.total_negativo, dados.facebook.total_neutro],
                    },
                    {
                        label: "Twitter",
                        borderColor: '#51bcda',
                        fill: true,
                        backgroundColor: '#51bcda',
                        hoverBorderColor: '#51bcda',
                        borderWidth: 8,
                        data: [dados.twitter.total_positivo, dados.twitter.total_negativo, dados.twitter.total_neutro],
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
                    display: true,
                    position: 'top',
                    margin: 10
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