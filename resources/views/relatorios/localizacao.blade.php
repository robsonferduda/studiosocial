@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="card-title"><i class="fa fa-map-marker"></i> Relatórios <i class="fa fa-angle-double-right" aria-hidden="true"></i> Localização</h4>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('relatorios') }}" class="btn btn-info pull-right"><i class="nc-icon nc-chart-bar-32"></i> Relatórios</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('layouts/regra')
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <h6 class="center">Localização dos Tweets</h6>
                            <div class="col-lg-12 col-md-12 msg_tweets"></div>
                            <table class="table table-hover table_places d-none">
                                <thead class="">
                                    <tr>
                                        <th>Localização</th>
                                        <th class="center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table> 
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <h6 class="center">Localização dos Usuários</h6>
                            <div class="col-lg-12 col-md-12 msg_users"></div>
                            <table class="table table-hover table_location d-none">
                                <thead class="">
                                    <tr>
                                        <th>Localização</th>
                                        <th class="center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table> 
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

        //$('body').loader('show');
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

        function loadDados(periodo, regra){  

            var data_inicial = $(".dt_inicial_relatorio").val();
            var data_final = $(".dt_final_relatorio").val();
            $(".msg_tweets").html("");
            $(".msg_users").html("");
            $(".table_places tbody tr").empty();
            $(".table_location tbody tr").empty();

            $.ajax({
                url: host+'/relatorios/dados/localizacao',
                type: 'POST',
                data: { "_token": token,
                        "periodo": periodo,
                        "data_inicial": data_inicial,
                        "data_final": data_final,
                        "regra": regra },
                success: function(response) {
                    
                    if(response.location_tweet.length){
                        $.each(response.location_tweet, function(index, value) {  
                            $(".table_places tbody").append('<tr><td>'+value.place_name+'</td><td class="center">'+value.total+'</td></tr>');
                        }); 
                        $(".table_places").removeClass("d-none");
                    }else{
                        $(".table_places").addClass("d-none");
                        $(".msg_tweets").html('<p class="ml-1 msg"><i class="fa fa-exclamation-circle mr-1"></i>Não existem dados para os parâmetros selecionados. Altere o período ou as regras e tente novamente.</p>');
                    }

                    if(response.location_user.length){
                        $.each(response.location_user, function(index, value) {  
                            $(".table_location tbody").append('<tr><td>'+value.user_location+'</td><td class="center">'+value.total+'</td></tr>');
                        }); 
                        $(".table_location").removeClass("d-none");
                    }else{
                        $(".table_location").addClass("d-none");
                        $(".msg_users").html('<p class="ml-1 msg"><i class="fa fa-exclamation-circle mr-1"></i>Não existem dados para os parâmetros selecionados. Altere o período ou as regras e tente novamente.</p>');
                    }
                }
            });         

        }
});
</script>
@endsection    