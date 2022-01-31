@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card card-main">
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
                    <button type="button" class="btn btn-danger pull-right mr-2 btn-relatorio"><i class="fa fa-file-pdf-o"></i> Baixar Relatório</button>
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
<script src="{{ asset('js/relatorios.js') }}"></script>
<script>
    $(document).ready(function() {

        var regra = 0;
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
            regra = $(this).val();
            loadDados(periodo, regra);
        });

        $(".btn-relatorio").click(function(){

            var data_inicial = $(".dt_inicial_relatorio").val();
            var data_final = $(".dt_final_relatorio").val();
            $('.card-main').loader('show');

            $.ajax({
                url: host+'/relatorios/pdf/evolucao-diaria',
                type: 'POST',
                data: { "_token": token,
                        "periodo": periodo,
                        "data_inicial": data_inicial,
                        "data_final": data_final,
                        "regra": regra },
                xhrFields: {
                    responseType: 'blob' // to avoid binary data being mangled on charset conversion
                },
                success: function(blob, status, xhr) {
                    
                    var filename = "";
                    var disposition = xhr.getResponseHeader('Content-Disposition');
                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(disposition);
                        if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                    }

                    if (typeof window.navigator.msSaveBlob !== 'undefined') {
                        // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                        window.navigator.msSaveBlob(blob, filename);
                    } else {
                        var URL = window.URL || window.webkitURL;
                        var downloadUrl = URL.createObjectURL(blob);

                        if (filename) {
                            // use HTML5 a[download] attribute to specify filename
                            var a = document.createElement("a");
                            // safari doesn't support this yet
                            if (typeof a.download === 'undefined') {
                                window.location.href = downloadUrl;
                            } else {
                                a.href = downloadUrl;
                                a.download = filename;
                                document.body.appendChild(a);
                                a.click();
                            }
                        } else {
                            window.location.href = downloadUrl;
                        }

                        setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 10); // cleanup
                    }

                    $('.card-main').loader('hide');
                }
            }); 

        });
        
        function loadDados(periodo, regra){

            var data_inicial = $(".dt_inicial_relatorio").val();
            var data_final = $(".dt_final_relatorio").val();
            
            $.ajax({
                url: host+'/relatorios/dados/medias/evolucao-diaria',
                type: 'POST',
                data: { "_token": token,
                        "periodo": periodo,
                        "data_inicial": data_inicial,
                        "data_final": data_final,
                        "regra": regra },
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
                        label: " Positivo",
                            borderColor: '#4caf50',
                            fill: true,
                            backgroundColor: '#4caf50',
                            hoverBorderColor: '#4caf50',
                            borderWidth: 8,
                            stack: '1',
                            data: dados.dados_positivos,
                        },
                        {
                            label: " Negativo",
                            borderColor: '#f44336',
                            fill: true,
                            backgroundColor: '#f44336',
                            hoverBorderColor: '#f44336',
                            borderWidth: 8,
                            stack: '1',
                            data: dados.dados_negativos,
                        },
                        {
                            label: " Neutro",
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

                        position: 'bottom',
                        labels: {
                            fontSize: 8,
                        }
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
                        barPercentage: 0.3,
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