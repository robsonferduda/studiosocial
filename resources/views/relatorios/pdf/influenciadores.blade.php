@extends('layouts.relatorios')
@section('content')
    <style>
        @page {
            margin: 1cm 1cm;
        }

        table tr td{
            padding: 0px !important;
            margin: 0px !important;
            font-size; 10px;
        }

        .clearfix:after{
            content:"";
            display: block;
            clear: both;
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
    <div style="margin-top: 20px;">
        <div style="width: 48%; float: left;">
            <h6 class="center">POSITIVOS</h6>
            @foreach($dados['positivos'] as $key => $u)
                <div style="margin-bottom: 10px; font-size: 11px; {{ ($key < count($dados['positivos']) -1 ) ? 'border-bottom: 1px solid #d7d7d7;' : '' }}">
                    <img style="width: 40px; height: 40px;" src="{{ $u->url_image }}" alt="Imagem de Perfil" class="rounded-pill">
                    <strong style="margin-bottom: 20px;">{{ $u->user_name }}</strong>
                    <span>{{ $u->total }} postagens</span>
                </div>
            @endforeach
        </div>
        <div style="width: 48%; float: right;">
            <h6 class="center">NEGATIVOS</h6>
            @foreach($dados['negativos'] as $key => $u)
                <div style="margin-bottom: 10px; font-size: 11px; {{ ($key < count($dados['positivos']) -1 ) ? 'border-bottom: 1px solid #d7d7d7;' : '' }}">
                    <img style="width: 40px; height: 40px;" src="{{ $u->url_image }}" alt="Imagem de Perfil" class="rounded-pill">
                    <strong style="margin-bottom: 20px;">{{ $u->user_name }}</strong>
                    <span>{{ $u->total }} postagens</span>
                </div>
            @endforeach
        </div>
    </div>    
    <footer>
        Relatório gerado em {{ date("d/m/Y") }} às {{ date("H:i:s") }} 
    </footer>
@endsection