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
    @include("cabecalho")
    <div class="text-center">
        <p>Período de {{ $dt_inicial }} à {{ $dt_final }}</p>
    </div>
    <div class="mt-0 mb-2 text-center" style="background:#f7f7f7;">
        <strong class="d-block">TERMO DE PESQUISA</strong>
        @if($rule)
            <p>{{ $rule->getExpression() }}</p>
        @else
            <p>Todas as Regras</p>
        @endif
    </div>
    <div>
        <img src="{{ $chart }}">
    </div>
    <footer>
        Relatório gerado em {{ date("d/m/Y") }} às {{ date("H:i:s") }} 
    </footer>
@endsection