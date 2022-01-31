@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
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
                </div>
            </div>
        </div>
        <div class="card-body"> 
            @include('layouts/regra')          
            <div class="row px-1">
                <div class="col-lg-12 col-md-12">
                    <h6 class="mb-3">Selecione os relatórios que deseja gerar simultaneamente</h6>
                    <p>Essa operação pode levar alguns instantes devido ao volume de dados e quantidade de relatórios selecionados</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-check">
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="todos" id="todos" value="todos">
                                TODOS
                            <span class="form-check-sign"></span>
                        </label>
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="evolucao_diaria" value="evolucao_diaria">
                                Evolução Diária
                            <span class="form-check-sign"></span>
                        </label>
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="evolucao_rede" value="evolucao_rede">
                                Evolução Rede Social
                            <span class="form-check-sign"></span>
                        </label>
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="sentimentos" value="sentimentos">
                                Sentimentos
                            <span class="form-check-sign"></span>
                        </label>   
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="localizacao" value="localizacao">
                                Localização
                            <span class="form-check-sign"></span>
                        </label>                     
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-check">
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="nuvem" value="nuvem">
                                Nuvem de Palavras
                            <span class="form-check-sign"></span>
                        </label>
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="reactions" value="reactions">
                                Reactions
                            <span class="form-check-sign"></span>
                        </label>
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="hashtags" value="hashtags">
                                Hashtags
                            <span class="form-check-sign"></span>
                        </label>
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="influenciadores" value="influenciadores">
                                Influenciadores
                            <span class="form-check-sign"></span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 text-center">
                    <button type="button" class="btn btn-danger btn-relatorio"><i class="fa fa-file-pdf-o"></i> Gerar Relatórios</button>
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

        let relatorios = [];
        var regra = 0;
        var periodo = {{ $periodo_padrao }};
        var host =  $('meta[name="base-url"]').attr('content');
        var token = $('meta[name="csrf-token"]').attr('content');

        $("#regra").change(function(){
            regra = $(this).val();
        });

        $("#periodo").change(function(){
            periodo = $(this).val();          
        });

        $(".btn-relatorio").click(function(){

            relatorios = [];
            var data_inicial = $(".dt_inicial_relatorio").val();
            var data_final = $(".dt_final_relatorio").val();
            $('.card').loader('show');

            $(".form-check-input:checked").each(function(){
                relatorios.push($(this).val());               
            });
               
            $.ajax({
                url: host+'/relatorios/pdf/gerador',
                type: 'POST',
                data: { "_token": token,
                        "periodo": periodo,
                         "data_inicial": data_inicial,
                        "data_final": data_final,
                        "relatorios": relatorios,
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
                }
            });  
        });
    });
</script>
@endsection