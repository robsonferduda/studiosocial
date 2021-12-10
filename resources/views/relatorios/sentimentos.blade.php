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
<script>
    $(document).ready(function() {

        var host =  $('meta[name="base-url"]').attr('content');
        var dados_positivos = [];
        var dados_negativos = [];
        var dados_neutros = [];

        $.ajax({
                    url: host+'/relatorios/dados/sentimentos',
                    type: 'GET',
                    success: function(response) {
                        
                        $(".table_sentimentos tbody tr").empty();
                        dados_positivos = [];
                        dados_negativos = [];
                        dados_neutros = [];
                        
                        $.each(response, function(index, value) {  

                            dados_positivos.push(value.total_positivo);
                            dados_negativos.push(value.total_negativo);
                            dados_neutros.push(value.total_neutro);

                            $(".table_sentimentos tbody").append('<tr><td>'+value.rede_social+'</td><td class="center">'+value.total_positivo+'</td><td class="center">'+value.total_negativo+'</td><td class="center">'+value.total_neutro+'</td></tr>');
                        });  
                        
                        $(".table_sentimentos").removeClass("d-none");
                        
                        geraGrafico();
                    }
                });

        $("#regra").change(function(){

            var regra = $(this).val();
            var expression = $('#regra option').filter(':selected').data('expression');

            if(regra){

                $.ajax({
                    url: host+'/relatorios/dados/sentimentos',
                    type: 'GET',
                    success: function(response) {
                        
                        $(".table_sentimentos tbody tr").empty();
                        dados_positivos = [];
                        dados_negativos = [];
                        dados_neutros = [];
                        
                        $.each(response, function(index, value) {  

                            dados_positivos.push(value.total_positivo);
                            dados_negativos.push(value.total_negativo);
                            dados_neutros.push(value.total_neutro);

                            $(".table_sentimentos tbody").append('<tr><td>'+value.rede_social+'</td><td class="center">'+value.total_positivo+'</td><td class="center">'+value.total_negativo+'</td><td class="center">'+value.total_neutro+'</td></tr>');
                        });  
                        
                        $(".table_sentimentos").removeClass("d-none");
                        $(".display_regra").html(expression);
                        geraGrafico();
                    }
                });

            }else{
                $(".table_sentimentos tbody tr").empty();
                $(".table_sentimentos").addClass("d-none");
                $("#chart_sentiment").html("");
            }
        });

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
                        data: dados_positivos,
                    },
                    {
                        label: "Facebook",
                        borderColor: '#3f51b5',
                        fill: true,
                        backgroundColor: '#3f51b5',
                        hoverBorderColor: '#3f51b5',
                        borderWidth: 8,
                        data: dados_negativos,
                    },
                    {
                        label: "Twitter",
                        borderColor: '#51bcda',
                        fill: true,
                        backgroundColor: '#51bcda',
                        hoverBorderColor: '#51bcda',
                        borderWidth: 8,
                        data: dados_neutros,
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