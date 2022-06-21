@extends('layouts.relatorios')
@section('content')
    <style>
        body {
           
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
    
    <footer>
        Relatório gerado em {{ date("d/m/Y") }} às {{ date("H:i:s") }} 
    </footer>
@endsection