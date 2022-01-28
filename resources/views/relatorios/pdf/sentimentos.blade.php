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
    @include("relatorios/pdf/cabecalho")
    <div>
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
                    <td class="text-center">{{ $sentimentos['facebook']['total_positivo'] }}</td>
                    <td class="text-center">{{ $sentimentos['facebook']['total_negativo'] }}</td>
                    <td class="text-center">{{ $sentimentos['facebook']['total_neutro'] }}</td>
                </tr>
                <tr>
                    <td>Instagram</td>
                    <td class="text-center">{{ $sentimentos['instagram']['total_positivo'] }}</td>
                    <td class="text-center">{{ $sentimentos['instagram']['total_negativo'] }}</td>
                    <td class="text-center">{{ $sentimentos['instagram']['total_neutro'] }}</td>
                </tr>
                <tr>
                    <td>Twitter</td>
                    <td class="text-center">{{ $sentimentos['twitter']['total_positivo'] }}</td>
                    <td class="text-center">{{ $sentimentos['twitter']['total_negativo'] }}</td>
                    <td class="text-center">{{ $sentimentos['twitter']['total_neutro'] }}</td>
                </tr>
            </tbody>
        </table>
        <img src="{{ $chart }}">
    </div>
    <footer>
        Relatório gerado em {{ date("d/m/Y") }} às {{ date("H:i:s") }} 
    </footer>
@endsection