@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title"><i class="fa fa-cloud"></i> Relatórios <i class="fa fa-angle-double-right" aria-hidden="true"></i> Nuvem de Palavras</h4>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('relatorios') }}" class="btn btn-info pull-right"><i class="nc-icon nc-chart-bar-32"></i> Relatórios</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('layouts/regra')
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div id='cloud' style="height: 400px;"></div>
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
        var token = $('meta[name="csrf-token"]').attr('content');

        loadDados(periodo, regra); //Toda vez que carrega os dados, o gráfico é atualizado

        $("#regra").change(function(){
            loadDados(periodo, regra);
        });

        $("#periodo").change(function(){
            periodo = $(this).val();
            loadDados(periodo, regra);          
        });

        function loadDados(periodo, regra){

            $('body').loader('show');

            let APP_URL = {!! json_encode(url('/')) !!}
            let data_inicial = $(".dt_inicial_relatorio").val();
            let data_final = $(".dt_final_relatorio").val();

            fetch(APP_URL+'/relatorios/dados/wordcloud', {
                method: 'POST', 
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ "_token": token,
                    "periodo": periodo,
                    "data_inicial": data_inicial,
                    "data_final": data_final,
                    "regra": regra }),
            }).then(function(response) {
                return response.json();
                //words = JSON.stringify(words);

            }).then(function(response){
        
                let words = [];

                $('body').loader('hide');
                const _token = $('meta[name="csrf-token"]').attr('content');

                Object.entries(response).forEach(element => {

                    words.push(
                        {
                            text: element[0], 
                            weight: element[1],
                            html: {
                                class: 'cloud-word'
                            },
                            handlers: {
                                click: function(e) {

                                    let textContent = this.textContent;

                                    Swal.fire({
                                        title: "Deseja excluir definitivamente essa expressão?",
                                        text: "Você poderá reverter essa ação no menu configurações.",                                  
                                        icon: "warning",
                                        showCancelButton: true,
                                        confirmButtonColor: "#28a745",
                                        confirmButtonText: "Sim, excluir!",
                                        cancelButtonText: "Não, somente nessa visualização."
                                    }).then(function(result) {

                                        console.log(_token);

                                        if (result.value) {
                                            fetch(APP_URL+'/nuvem-palavras/remove', {
                                                method: 'POST',
                                                body: JSON.stringify({_token: _token, word: textContent}),
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'Accept': 'application/json',
                                                },
                                            }).then(function(response) {
                                                return response.json();
                                            }).then(function(data) {
                                                for( var i = 0; i < words.length; i++){ 
                                                    if (words[i].text === textContent) {                                                     
                                                        words.splice(i, 1); 
                                                        break; 
                                                    }
                                                }

                                                $('#cloud').jQCloud('update', words);
                                            });
                                        } else {                 

                                            for( var i = 0; i < words.length; i++){ 
                                                if (words[i].text === textContent) {                                                     
                                                    words.splice(i, 1); 
                                                    break; 
                                                }
                                            }

                                            $('#cloud').jQCloud('update', words);
                                        }
                                    });
                            
                                

                                }
                            },
                            
                        }
                    );
                });

                let cloud = $('#cloud').jQCloud(words, {
                    autoResize: true,
                    colors: ["#66C2A5", "#FC8D62", "#8DA0CB", "#E78AC3", "#A6D854", "#FFD92F", "#E5C494", "#B3B3B3"],
                    fontSize: function (width, height, step) {
                        if (step == 1)
                            return width * 0.007 * step + 'px';

                        return width * 0.006 * step + 'px';
                    }
                });   
                
                $('#cloud').jQCloud('update', words);
            });

        }



        // $("#regra").change(function(){ 
                
        // });
    });
</script>
@endsection