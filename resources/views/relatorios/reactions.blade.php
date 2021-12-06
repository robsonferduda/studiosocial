@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title"><i class="fa fa-smile-o"></i> Relatórios <i class="fa fa-angle-double-right" aria-hidden="true"></i> Reactions</h4>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('relatorios') }}" class="btn btn-info pull-right"><i class="nc-icon nc-chart-bar-32"></i> Relatórios</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('layouts/regra')
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
<script>
    $(document).ready(function() {

        var colors = [];
        var valores = [];
        var legensas = [];
        var host =  $('meta[name="base-url"]').attr('content');

        $("#regra").change(function(){

            var regra = $(this).val();
            var expression = $('#regra option').filter(':selected').data('expression');

            if(regra){

                $.ajax({
                    url: host+'/relatorios/dados/reactions',
                    type: 'GET',
                    success: function(response) {
                        
                        valores = [];
                        legendas = [];
                        colors = [];
                        $(".table_reactions tbody tr").empty();
                        
                        $.each(response, function(index, value) {
                            valores.push(value.count);
                            legendas.push(value.icon);
                            colors.push(value.color);
                            
                            $(".table_reactions tbody").append('<tr><td>'+value.name+'</td><td class="center">'+value.icon+'</td><td class="center">'+value.count+'</td></tr>');
                        });  
                        
                        $(".table_reactions").removeClass("d-none");
                        $(".display_regra").html(expression);
                        geraGrafico();
                    }
                });

            }else{
                $(".table_reactions tbody tr").empty();
                $(".table_reactions").addClass("d-none");
                $("#chart_reaction").html("");
            }
        });

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
                    barPercentage: 1.6,
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
    });
</script>
@endsection    