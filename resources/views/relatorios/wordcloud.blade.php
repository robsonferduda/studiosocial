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
                            <button type="button" class="btn btn-danger pull-right mr-2 btn-relatorio"><i class="fa fa-file-pdf-o"></i> Baixar Relatório</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('layouts/regra')
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div id='cloud' style="height: 500px;"></div>
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

        var regra = '';
        var periodo = {{ $periodo_padrao }};
        var token = $('meta[name="csrf-token"]').attr('content');

        loadDados(periodo, regra); //Toda vez que carrega os dados, o gráfico é atualizado

        $("#regra").change(function(){
            regra = $(this).val();
            loadDados(periodo, regra);
        });

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

        function loadDados(periodo, regra){

            $('.card').loader('show');

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

                $('.card').loader('hide');
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



        $(".btn-relatorio").click(function(){

            let APP_URL = {!! json_encode(url('/')) !!}
            let data_inicial = $(".dt_inicial_relatorio").val();
            let data_final = $(".dt_final_relatorio").val();
            $('.card').loader('show');

            $.ajax({
                url: APP_URL+'/relatorios/pdf/wordcloud',
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

                    $('.card').loader('hide');
                },
                error: function(response){
                    $('.card').loader('hide');
                    if(response.status){
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro ao gerar relatório',
                            confirmButtonColor: "#28a745",
                            confirmButtonText: '<i class="fa fa-check"></i> Enviar',
                            html: 'Entre em contato com o suporte e informe o seguinte código de erro: <strong>500</strong>'
                        })
                    }
                }
            }); 

        });
    });
</script>
@endsection