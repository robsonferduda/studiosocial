@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card card-main">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title"><i class="fa fa-smile-o"></i> Relatórios <i class="fa fa-angle-double-right" aria-hidden="true"></i> Reactions</h4>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('relatorios') }}" class="btn btn-info pull-right"><i class="nc-icon nc-chart-bar-32"></i> Relatórios</a>
                            <button type="button" class="btn btn-danger pull-right mr-2 btn-relatorio"><i class="fa fa-file-pdf-o"></i> Baixar Relatório</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('layouts/regra')
                    <div class="col-lg-12 col-md-12 msg"></div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <table class="table table-hover table_reactions d-none">
                                <thead class="">
                                    <tr>
                                        <th>Reação</th>
                                        <th class="center">Ícone</th>
                                        <th class="center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <canvas id="chart_reaction"></canvas>
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
        var colors = [];
        var valores = [];
        var legendas = [];
        var host =  $('meta[name="base-url"]').attr('content');
        var token = $('meta[name="csrf-token"]').attr('content');
        var periodo = {{ $periodo_padrao }};
        var myChart = null;
        var dados = null;

        loadDados(periodo, regra);

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

        function loadDados(periodo, regra){

            var data_inicial = $(".dt_inicial_relatorio").val();
            var data_final = $(".dt_final_relatorio").val();

            $.ajax({
                url: host+'/relatorios/dados/reactions',
                type: 'POST',
                data: { "_token": token,
                        "periodo": periodo,
                        "data_inicial": data_inicial,
                        "data_final": data_final,
                        "regra": regra },
                success: function(response) {

                    $(".msg").html("");

                    if(response.length){
                        valores = [];
                        legendas = [];
                        colors = [];
                        $(".table_reactions tbody tr").empty();
                        if(myChart) myChart.destroy();

                        $.each(response, function(index, value) {
                            valores.push(value.count);
                            legendas.push(value.icon);
                            colors.push(value.color);

                            $(".table_reactions tbody").append('<tr><td>'+value.name+'</td><td class="center">'+value.icon+'</td><td class="center">'+value.count+'</td></tr>');
                        });

                        $(".table_reactions").removeClass("d-none");
                        geraGrafico();

                    }else{
                        if(myChart) myChart.destroy();
                        $(".table_reactions").addClass("d-none");
                        $(".msg").html('<p class="ml-1 msg"><i class="fa fa-exclamation-circle mr-1"></i>Não existem dados para os parâmetros selecionados. Altere o período ou as regras e tente novamente.</p>');
                    }
                }
            });
        }

        function geraGrafico(){

            ctx = document.getElementById('chart_reaction').getContext("2d");

            myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: legendas,
                datasets: [{
                label: "Emails",
                pointRadius: 0,
                pointHoverRadius: 0,
                backgroundColor: colors,
                borderWidth: 0,
                data: valores
                }]
            },

            options: {

                legend: {
                    display: true,
                    position: 'bottom'
                },

                pieceLabel: {
                    render: 'percentage',
                    fontColor: ['white'],
                    precision: 2
                },

                tooltips: {
                enabled: true
                },

                scales: {
                    yAxes: [{

                        ticks: {
                        display: false
                        },
                        gridLines: {
                        drawBorder: false,
                        zeroLineColor: "transparent",
                        color: 'rgba(255,255,255,0.05)'
                        }

                    }],

                    xAxes: [{
                        barPercentage: 1.0,
                        gridLines: {
                        drawBorder: false,
                        color: 'rgba(255,255,255,0.1)',
                        zeroLineColor: "transparent"
                        },
                        ticks: {
                        display: false,
                        }
                    }]
                },
            }
            });
        }

        $(".btn-relatorio").click(function(){

            var data_inicial = $(".dt_inicial_relatorio").val();
            var data_final = $(".dt_final_relatorio").val();
            $('.card-main').loader('show');
    
            $.ajax({
                url: host+'/relatorios/pdf/reactions',
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
    });
</script>
@endsection
