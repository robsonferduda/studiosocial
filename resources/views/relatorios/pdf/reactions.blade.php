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
            <thead class="">
                <tr>
                    <th>Reação</th>
                    <th class="center">Ícone</th>
                    <th class="center">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados as $reaction)
                    <tr>
                        <td>{{ $reaction->name }}</td>
                        <td class="center">{!! $reaction->icon !!}</td>
                        <td class="center">{{ $reaction->count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table> 
    </div>
    <div style="margin-top: 30px;">
        <img src="{{ $chart }}">
    </div>
    <footer>
        Relatório gerado em {{ date("d/m/Y") }} às {{ date("H:i:s") }} 
    </footer>
@endsection