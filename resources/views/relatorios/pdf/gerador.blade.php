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
    
    <div>

        @if(in_array('evolucao_diaria', $relatorios))
            @php $nome = "Relatório de Evolução Diária" @endphp
            @include("relatorios/pdf/cabecalho")
            @include("relatorios/pdf/partials/evolucao_diaria")
            @if($page_break > 1)
                <div class="page_break"></div>
            @endif
            @php $page_break--; @endphp
        @endif

        @if(in_array('evolucao_rede', $relatorios))
            @php $nome = "Relatório de Evolução Por Rede Social" @endphp
            @include("relatorios/pdf/cabecalho")
            @include("relatorios/pdf/partials/evolucao_rede")
            @if($page_break > 1)
                <div class="page_break"></div>
            @endif
            @php $page_break--; @endphp
        @endif

        @if(in_array('sentimentos', $relatorios))
            @php $nome = "Relatório de Sentimentos" @endphp
            @include("relatorios/pdf/cabecalho")
            @include("relatorios/pdf/partials/sentimento")
            @if($page_break > 1)
                <div class="page_break"></div>
            @endif
            @php $page_break--; @endphp
        @endif

        @if(in_array('nuvem', $relatorios))
            @php $nome = "Nuvem de Palavras" @endphp
            @include("relatorios/pdf/cabecalho")
            @include("relatorios/pdf/partials/wordcloud")
            @if($page_break > 1)
                <div class="page_break"></div>
            @endif
            @php $page_break--; @endphp
        @endif

        @if(in_array('reactions', $relatorios))
            @php $nome = "Relatório de Reactions" @endphp
            @include("relatorios/pdf/cabecalho")
            @include("relatorios/pdf/partials/reactions")
            @if($page_break > 1)
                <div class="page_break"></div>
            @endif
            @php $page_break--; @endphp
        @endif

        @if(in_array('influenciadores', $relatorios))
            @php $nome = "Relatório de Principais Influenciadores" @endphp
            @include("relatorios/pdf/cabecalho")
            @include("relatorios/pdf/partials/influenciadores")
            @if($page_break > 1)
                <div class="page_break"></div>
            @endif
            @php $page_break--; @endphp
        @endif
        
    </div>
    <footer>
        Relatório gerado em {{ date("d/m/Y") }} às {{ date("H:i:s") }} 
    </footer>
@endsection