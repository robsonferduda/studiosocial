@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="card-title"><i class="fa fa-group"></i> Relatórios <i class="fa fa-angle-double-right" aria-hidden="true"></i> Principais Influenciadores</h4>
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
                            <h3><i class="fa fa-smile-o text-success"></i> Positivos</h3>
                            <div class="box_positivos"></div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <h3><i class="fa fa-frown-o text-danger"></i> Negativos</h3>
                            <div class="box_negativos"></div>
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
            loadDados(periodo, regra);
        });

        function loadDados(periodo, regra){

            var data_inicial = $(".dt_inicial_relatorio").val();
            var data_final = $(".dt_final_relatorio").val();

            $(".box_negativos").empty();
            $(".box_positivos").empty();

            $.ajax({
                url: host+'/relatorios/dados/influenciadores',
                type: 'POST',
                data: { "_token": token,
                        "periodo": periodo,
                        "data_inicial": data_inicial,
                        "data_final": data_final,
                        "regra": regra },
                success: function(response) {
                    
                    $.each(response.negativos, function(index, value) {

                        $(".box_negativos").append('<div class="card">'+
                                                        '<div class="row mb-3">' +
                                                            '<div class="col-lg-2 col-md-2 m-auto">' +
                                                                '<img src="'+value.url_image+'" alt="Imagem de Perfil" class="rounded-pill">' +
                                                            '</div>' +
                                                            '<div class="col-md-9">' +
                                                                '<p class="mb-1 mt-2"><a href="'+value.url_perfil+'" target="_BLANK">'+value.user_name+'</a></p>' +
                                                                '<p class="mb-1">'+value.total+' postagens</p>' +
                                                                '<a class="mb-1" href="../twitter/postagens/user/'+value.user_name+'/sentimento/-1">Ver Postagens</a>' +
                                                            '</div>' +
                                                        '</div>' +
                                                   '</div>');
                        
                        
                    });

                    $.each(response.positivos, function(index, value) {

                        $(".box_positivos").append('<div class="card">'+
                                                        '<div class="row mb-3">' +
                                                            '<div class="col-lg-2 col-md-2 m-auto">' +
                                                                '<img src="'+value.url_image+'" alt="Imagem de Perfil" class="rounded-pill">' +
                                                            '</div>' +
                                                            '<div class="col-md-9">' +
                                                                '<p class="mb-1 mt-2"><a href="'+value.url_perfil+'" target="_BLANK">'+value.user_name+'</a></p>' +
                                                                '<p class="mb-1">'+value.total+' postagens</p>' +
                                                                '<a class="mb-1" href="../twitter/postagens/user/'+value.user_name+'/sentimento/1">Ver Postagens</a>' +
                                                            '</div>' +
                                                        '</div>' +
                                                '</div>');


                        });
                }
            }); 
        }
    });
</script>
@endsection    