@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="card-title"><i class="fa fa-group"></i> Relatórios <i class="fa fa-angle-double-right" aria-hidden="true"></i> Hashtags</h4>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('relatorios') }}" class="btn btn-info pull-right"><i class="nc-icon nc-chart-bar-32"></i> Relatórios</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('layouts/regra')
                    <div class="row">
                        <div class="col-lg-7 col-md-7">
                            <div id='cloud' style="height: 450px;"></div>
                        </div>
                        <div class="col-lg-5 col-md-5">
                            <table class="table table-hover table_hashtags">
                                <thead class="">
                                    <tr>
                                        <th>Hashtag</th>
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

        $('.card').loader('show');
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
            var ctrl = 0;
            $(".table_hashtags tbody").empty();

            fetch(host+'/nuvem-palavras/hashtags', {
                method: 'POST',
                body: JSON.stringify({ "_token": token,
                        "periodo": periodo,
                        "data_inicial": data_inicial,
                        "data_final": data_final,
                        "regra": regra }), 
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            }).then(function(response) {
                return response.json();
            }).then(function(response){
                
                $('.card').loader('hide');
                let words = [];
            
                Object.entries(response).forEach(element => {

                    if(ctrl < 10)
                        $(".table_hashtags tbody").append('<tr><td>'+element[0]+'</td><td class="center">'+element[1]+'</td></tr>');

                    ctrl++;

                    words.push(
                        {
                            text: element[0], 
                            weight: element[1],
                            html: {
                                class: 'cloud-word'
                            },
                            handlers: {
                                click: function(e) {
                                    for( var i = 0; i < words.length; i++){ 
                                    if (words[i].text === this.textContent) { 
                                            words.splice(i, 1); 
                                        break; 
                                    }
                                }

                                $('#cloud').jQCloud('update', words);

                                }
                            },
                            
                        }
                    );
                });

                $('#cloud').jQCloud(words, {
                    autoResize: true,
                    colors: ["#66C2A5", "#FC8D62", "#8DA0CB", "#E78AC3", "#A6D854", "#FFD92F", "#E5C494", "#B3B3B3"],
                    fontSize: function (width, height, step) {
                        if (step == 1)
                            return width * 0.01 * step + 'px';

                        return width * 0.009 * step + 'px';
                    }
                });       
                
                $('#cloud').jQCloud('update', words);
            });

        }
});
</script>
@endsection    