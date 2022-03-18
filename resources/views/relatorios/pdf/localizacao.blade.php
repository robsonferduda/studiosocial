@extends('layouts.relatorios')
@section('content')
    <style>
        @page {
            margin: 1cm 1cm;
        }

        table{
            width: 100%;
            font-size: 12px;
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

        .center{ text-align: center;}
    </style>
    @include("relatorios/pdf/cabecalho")
    <div style="margin-top: 20px;">
        <div style="width: 48%; float: left;">
            <h6 class="center">LOCALIZAÇÃO DOS USUÁRIOS</h6>
            @if(count($dados['location_user']))
                <table>
                    <thead>
                        <tr>
                            <th>Localização</th>
                            <th class="center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 0; $i < count($dados['location_user']); $i++)
                            <tr>
                                <td>{{ $dados['location_user'][$i]->user_location }}</td>
                                <td class="text-center">{{ $dados['location_user'][$i]->total }}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            @else
                <p class="center">Não existem dados para os parâmetros selecionados</p>
            @endif
        </div>
        <div style="width: 48%; float: right;">
            <h6 class="center">LOCALIZAÇÃO DOS TWEETS</h6>
            @if(count($dados['location_tweet']))
                <table>
                    <thead>
                        <tr>
                            <th>Localização</th>
                            <th class="center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 0; $i < count($dados['location_tweet']); $i++)
                            <tr>
                                <td>{{ $dados['location_tweet'][$i]->place_name }}</td>
                                <td class="text-center">{{ $dados['location_tweet'][$i]->total }}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            @else
                <p class="center">Não existem dados para os parâmetros selecionados</p>
            @endif
        </div>
    </div>
    <footer style="clear:both">
        Relatório gerado em {{ date("d/m/Y") }} às {{ date("H:i:s") }} 
    </footer>
@endsection