@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="card-title"><i class="fa fa-comments-o"></i> Relatórios <i class="fa fa-angle-double-right" aria-hidden="true"></i> Postagens</h4>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('relatorios') }}" class="btn btn-info pull-right"><i class="nc-icon nc-chart-bar-32"></i> Relatórios</a>
                            <button type="button" class="btn btn-danger pull-right mr-2 btn-relatorio"><i class="fa fa-file-pdf-o"></i> Gerar Relatório</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('layouts/regra')
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <h6>Relatórios Gerados</h6>
                            <table id="" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Nome</th>
                                        <th class="text-center">Tamanho</th>
                                        <th class="text-center">Baixar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($arquivos as $key => $arquivo)
                                        <tr>
                                            <td>{{ date("d/m/Y H:i", filemtime($arquivo)) }}</td>
                                            <td>{{ $arquivo->getFilename() }}</td>
                                            <td class="text-center">{{ number_format($arquivo->getSize() / 1048576, 2) }} MB</td>
                                            <td class="text-center"><a href="{{ url('file/'.$client_id.'/'.$arquivo->getFilename()) }}">Baixar</a></td>
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

        $("#periodo").change(function(){
            periodo = $(this).val();
        });

        $(document).on('keypress',function(e) {
            if(e.which == 13) {
                periodo = 0;
            }
        });

        $("#regra").change(function(){
            regra = $(this).val();
        });

        $(".btn-relatorio").click(function(){

            var data_inicial = $(".dt_inicial_relatorio").val();
            var data_final = $(".dt_final_relatorio").val();
            var regra = $("#regra").val();
            $('.card').loader('show');

            $.ajax({
                url: host+'/media/relatorio',
                type: 'POST',
                data: { "_token": token,
                        "periodo": periodo,
                        "data_inicial": data_inicial,
                        "data_final": data_final,
                        "regra": regra },
                success: function(status, xhr) {
                    $('.card').loader('hide');

                    Swal.fire({
                        title: "Relatório Requisitado",
                        text: "Estamos preparando seu relatório! Depois de pronto, você receberá um email informando que ele foi finalizado. Além disso, uma cópia do relatório ficará disponível nesta tela para download",
                        type: "success",
                        icon: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: '<i class="fa fa-check"></i> Ok'
                    });
                },
                error: function(response){
                    $('.card').loader('hide');

                    Swal.fire({
                        title: "Erro ao gerar relatório",
                        text: "Ocorreu um erro na geração do relatório",
                        type: "error",
                        icon: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: '<i class="fa fa-check"></i> Ok'
                    });
                },
                complete: function(){
                    $('.card').loader('hide');
                }
            });
            
        });

});
</script>
@endsection
