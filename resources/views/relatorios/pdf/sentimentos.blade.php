@extends('layouts.relatorios')
@section('content')
    <style>
        @page {
            margin: 1cm 1cm;
        }

        footer {
            position: fixed; 
            bottom: 0cm; 
            left: 0cm; 
            right: 0cm;
            height: 1cm;
            font-size: 12px;
            color: black;
            text-align: center;
        }
    </style>
    <div>
        <div class="col-lg-3 col-md-3">
            <img style="width: 10%" src="{{ url('img/logo.jpeg') }}"/>
        </div>
        <div class="col-lg-9 col-md-9 " style="margin-top: -100px;">
            <h5 class="text-center">Sistema de Monitoramento de Redes Sociais</h5>
        </div>
    </div>   
    <div class="mt-3 mb-3 py-2 text-center" style="background:#f7f7f7;">
        <strong class="d-block">TERMO DE PESQUISA</strong>
        "Libertadores da América" e "Flamengo"
    </div>
    <div>
        <h6 class="text-center">Relatório de Sentimentos de Postagens</h6>
        <table class="table">
            <thead>
                <tr>
                    <th>Rede Social</th>
                    <th class="text-center">Positivos</th>
                    <th class="text-center">Negativos</th>
                    <th class="text-center">Neutros</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Facebook</td>
                    <td class="text-center">63%</td>
                    <td class="text-center">27%</td>
                    <td class="text-center">10%</td>
                </tr>
                <tr>
                    <td>Instagram</td>
                    <td class="text-center">63%</td>
                    <td class="text-center">27%</td>
                    <td class="text-center">10%</td>
                </tr>
                <tr>
                    <td>Twitter</td>
                    <td class="text-center">63%</td>
                    <td class="text-center">27%</td>
                    <td class="text-center">10%</td>
                </tr>
            </tbody>
        </table>
        <img src="{{ $chart }}">
    </div>
    <footer>
        Relatório gerado em {{ date("d/m/Y H:i:s") }} 
    </footer>
@endsection