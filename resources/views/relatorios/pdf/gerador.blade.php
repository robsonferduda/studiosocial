@extends('layouts.relatorios')
@section('content')
    <style>
        @page {
            margin: 1cm 1cm;
        }

        .page_break { page-break-before: always; }

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

        @if(in_array('evolucao_diaria', $relatorios))
            @include("relatorios/pdf/partials/evolucao_diaria")
            <div class="page_break"></div>
        @endif

        @if(in_array('evolucao_rede', $relatorios))
            @include("relatorios/pdf/partials/evolucao_rede")
            <div class="page_break"></div>
        @endif

        @if(in_array('sentimentos', $relatorios))
            @include("relatorios/pdf/partials/sentimento")
            <div class="page_break"></div>
        @endif

        @if(in_array('nuvem', $relatorios))
            @include("relatorios/pdf/partials/wordcloud")
            <div class="page_break"></div>
        @endif

        @if(in_array('reactions', $relatorios))
            @include("relatorios/pdf/partials/reactions")
            <div class="page_break"></div>
        @endif

        @if(in_array('influenciadores', $relatorios))
            @include("relatorios/pdf/partials/influenciadores")
            <div class="page_break"></div>
        @endif
        
    </div>
    <footer>
        Relatório gerado em {{ date("d/m/Y") }} às {{ date("H:i:s") }} 
    </footer>
@endsection