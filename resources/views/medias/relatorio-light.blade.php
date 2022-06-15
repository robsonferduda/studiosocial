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
    <div style="clear:both; margin-top: 20px;">
        <div style="width: 85%; float: left;">
            <h6 style="margin-bottom: 0px; padding-bottom: 5px; margin-top: 26px; font-size: 17px; border-bottom: 3px solid #b5b4b4;">{{ $nome }}</h6>
            <p style="color: #eb8e06; margin: 0;"><strong>Período: {{ $dt_inicial }} à {{ $dt_final }}</strong></p>
            <p style="color: #eb8e06; margin: 0; margin-top: -3px;">{{ session('cliente')['nome'] }}</p>        
        </div>
        <div style="width: 15%; float: right; text-align: right;">
            <img style="width: 90%" src="{{ url('img/studio_social.png') }}"/>
        </div>
    </div> 
    <div style="clear:both; margin-top: 150px; ">
        @foreach($medias as $key => $media)
        
            <div class="mb-2">  
                
                <div style="position: relative;">
                   

                    <span style="position: absolute; top: -5px; font-size: 12px;">{{ $media['username'] }}</span>

                    <span class="pull-right" style="font-size: 16px;">
                        
                                    <i class="fa fa-frown-o text-danger"></i>
                                    <i class="fa fa-ban op-2"></i>
                                    <i class="fa fa-smile-o op-2"></i>
                               
                    </span>
                </div>

                <p style="font-size: 12px;">{!! $media['text'] !!}</p>

                <span class="badge badge-pill badge-primary">
                    <i class="fa fa-thumbs-up"></i> {{ $media['like_count'] }}
                </span>
                
                <span class="badge badge-pill badge-warning">
                    <i class="fa fa-link text-white"></i> <a href="{{ $media['link'] }}" target="_blank" >Post</a>  
                </span>
                <span class="float-right" style="font-size: 12px;">{{ Carbon\Carbon::parse($media['created_at'])->format('d/m/Y H:i') }}</span>
            
            </div>           

            <hr/>

        @endforeach
    </div>
    <footer>
        Relatório gerado em {{ date("d/m/Y") }} às {{ date("H:i:s") }} 
    </footer>
@endsection