@extends('layouts.relatorio-postagens')
@section('content')
    <style>
        

        body {
            color: #66615b;
            font-family: Montserrat, "Helvetica Neue", Arial, sans-serif;            
        }

        a {          
            text-decoration: none;
            background-color: transparent;
        }

        a, a:focus, a:hover {
            color: #51cbce;
        }

        .op-2{
            opacity: 0.3;
        }

        .text-danger, a.text-danger:focus, a.text-danger:hover {
            color: #ef8157!important;
        }

        .text-info, a.text-info:focus, a.text-info:hover {
            color: #51bcda!important;
        }

        .text-facebook {
            color: #3f51b5;
        }

        .text-pink {
            color: #e91ea1;
        }

        .mb-2 {
            margin-bottom: .5rem!important;
        }

        .float-right {
            float: right!important;
        }

        .text-primary, a.text-primary:focus, a.text-primary:hover {
            color: #51cbce!important;
        }

        .text-white {
            color: #fff!important;
        }

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
    <div style="margin-top: 20px;">
        <div style="width: 80%; float: left;">
            <h6 style="margin-bottom: 0px; padding-bottom: 5px; margin-top: 26px; font-size: 17px; border-bottom: 3px solid #b5b4b4;">{{ $nome }}</h6>
            <p style="color: #eb8e06; margin: 0;"><strong>Período: {{ $dt_inicial }} à {{ $dt_final }}</strong></p>
            <p style="color: #eb8e06; margin: 0; margin-top: -3px;">{{ session('cliente')['nome'] }}</p>
            <p style="color: #eb8e06; margin: 0; margin-top: -3px;">FORAM COLETADOS {{ count($dados) }} POSTS</p>
        </div>
        <div style="width: 15%; float: right; text-align: right;">
            <img style="width: 90%" src='data:image/png;base64,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' alt="" />
        </div>
    </div>
    <div style="clear:both; margin-top: 50px; ">
        @for($i = 0; $i < count($dados); $i++)

            <div class="mb-2">

                <div style="position: relative;">
                    {!! $dados[$i]['tipo'] !!}
                    <span style="position: absolute; top: -5px; font-size: 12px;">{{ $dados[$i]['username'] }}</span>
                    <span class="pull-right" style="font-size: 16px;">
                        {!! $dados[$i]['sentimento'] !!}
                    </span>
                </div>

                <p style="font-size: 12px;">{!! $dados[$i]['text'] !!}</p>

                <span>
                    <img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAAAmJLR0QA/4ePzL8AAADCSURBVBgZdcG/K0RxAADwD5HhFaMk+2ORv8DyjH4MBgYpdeWmq6vrJoNBrGKwKNJdFpPc8iabP+BkF+UyiF0P9U3nee/zkbPvTdugEpEPk7rmlJjxgHOrSqy5wpGqEtcqaFlRaEvXCO4da9o0rk9kx6PYjxOHDlx4NiHY0NM25a9T24J3s/IiT+YFmSIdy4JMkdSiIPNfpCcWfBqSF3s1Jui4s2tav6ZLv4Yt2PPizLpEIrHkVk3OqLqWVCp1o2HAty+jty0eH7w57wAAAABJRU5ErkJggg=='/> {{ $dados[$i]['like_count'] }}
                </span>

                <span>
                    <a href="{{ $dados[$i]['link'] }}" target="_blank" >Post Link</a>
                </span>
                <span class="float-right" style="font-size: 12px;">{{ Carbon\Carbon::parse($dados[$i]['created_at'])->format('d/m/Y H:i') }}</span>
            </div>

            <hr/>

        @endfor
    </div>
    <footer>
        Relatório gerado em {{ date("d/m/Y") }} às {{ date("H:i:s") }}
    </footer>
@endsection
