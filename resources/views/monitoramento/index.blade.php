@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="card-title">
                        <i class="nc-icon nc-sound-wave"></i> Monitoramento 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Cliente 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> {{ session('cliente')['nome'] }}
                    </h4>
                </div>
                <div class="col-md-4">
                    <a href="{{ url('media/relatorio/todos') }}" class="btn btn-danger pull-right"><i class="fa fa-file-pdf-o"></i> Baixar Relatório</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('layouts.aviso-regra')
            <div class="row">                
                <div class="col-lg-6 col-md-6">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="card card-stats">
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-4 col-md-4">
                                            <div class="icon-big text-center icon-warning">
                                                <i class="fa fa-instagram text-pink"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 col-md-8 mt-3">
                                            <div class="numbers">
                                                <p class="card-title"><a href="{{ url('monitoramento/media/instagram') }}">{{ $totais['total_insta'] }}</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="card card-stats">
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4">
                                            <div class="icon-big text-center icon-warning">
                                                <i class="fa fa-facebook text-facebook"></i>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-8 mt-3">
                                            <div class="numbers">
                                                <p class="card-title"><a href="{{ url('monitoramento/media/facebook') }}">{{ $totais['total_face'] }}</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="card card-stats">
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-5 col-md-4">
                                            <div class="icon-big text-center icon-warning">
                                                <i class="fa fa-twitter text-info"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 col-md-8 mt-3">
                                            <div class="numbers">
                                                <p class="card-title"><a href="{{ url('monitoramento/media/twitter') }}">{{ $totais['total_twitter'] }}</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="card card-stats">
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-5 col-md-4">
                                            <div class="icon-big text-center icon-warning">
                                                <img src="{{ asset('img/icon/tiktok.png') }}" >
                                            </div>
                                        </div>
                                        <div class="col-7 col-md-8 mt-3">
                                            <div class="numbers">
                                                <p class="card-title"><a href="{{ url('monitoramento/media/tiktok') }}">{{ $totais['total_tiktok'] }}</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    </div>
                    <div class="card car-chart">
                        <div class="card-header">
                          <h5 class="card-title">Monitoramento de Redes Sociais</h5>
                          <p class="">Total de coletas diárias por rede social no período de {{ $periodo_relatorio['data_inicial'] }} à {{ $periodo_relatorio['data_final'] }}</p>
                        </div>
                        <div class="card-body">
                          <canvas id="chartActivity"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <h6 class="text-center">Termos Ativos</h6>
                            <table class="table">
                                <thead class="">
                                    <tr>
                                        <th>Mídia Social</th>
                                        <th>Termo</th>
                                        <th class="text-center">Menções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($terms as $term)
                                        <tr>
                                            <td>{{ $term->name }}</td>
                                            <td>{{ $term->term }}</td>
                                            <td class="text-center">
                                                {{ $term->term_count }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>   
                        </div>
                    </div>

                    <div class="card card-stats">
                        <div class="card-body ">
                            <h6 class="text-center">Hashtags Ativas</h6>
                            <table class="table">
                                <thead class="">
                                    <tr>
                                        <th>Mídia Social</th>
                                        <th>Hashtag</th>
                                        <th class="text-center">Menções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hashtags as $hashtag)
                                        <tr>
                                            <td>{{ $hashtag->name }}</td>
                                            <td>#{{ $hashtag->hashtag }}</td>
                                            <td class="text-center">
                                                {{ $hashtag->hashtag_count }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>   
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
        var host =  $('meta[name="base-url"]').attr('content');

        $.ajax({
            url: host+'/monitoramento/medias/historico/{{ $periodo_padrao }}',
            type: 'GET',
            success: function(response) {
                dados = response;
                initDashboardPageCharts();
            }
        }); 

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
                    data: dados.dados_instagram,
                },
                {
                    label: "Facebook",
                    borderColor: '#3f51b5',
                    fill: true,
                    backgroundColor: '#3f51b5',
                    hoverBorderColor: '#3f51b5',
                    borderWidth: 8,
                    data: dados.dados_facebook,
                },
                {
                    label: "Twitter",
                    borderColor: '#51bcda',
                    fill: true,
                    backgroundColor: '#51bcda',
                    hoverBorderColor: '#51bcda',
                    borderWidth: 8,
                    data: dados.dados_twitter,
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