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
    <div>
        @foreach($medias as $key => $media)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            @if(false)
                                <img src="{{ str_replace('normal','400x400', $media['user_profile_image_url']) }}" alt="Imagem de Perfil" class="rounded-pill img-perfil">      
                            @else
                                <img src="{{ url('img/user.png') }}" alt="Imagem de Perfil" class="rounded-pill">
                            @endif
                            <p class="mt-2 mb-0">{{ $media->user }}</p>                                     
                        
                            @switch($media->rede)
                                @case('instagram')
                                    <h3><i class="fa fa-instagram text-pink"></i></h3>
                                    @break
                                @case('facebook')
                                    <h3 class="text-center"><i class="fa fa-facebook text-facebook"></i></h3>
                                    @break
                                @case('twitter')
                                    <h3 class="text-center"><i class="fa fa-twitter text-info"></i></h3>
                                    @break
                                @default                                        
                            @endswitch
                        </div>
                        <div class="col-md-10">
                            <div class="mb-2">                                   
                                <span class="badge badge-pill badge-primary">
                                    <i class="fa fa-thumbs-up"></i> 
                                </span>
                                @if($media->rede == 'instagram' || $media->rede == 'facebook' )
                                    <span class="badge badge-pill badge-danger">
                                        <i class="fa fa-comments"></i> 
                                    </span>   
                                @endif
                                @if($media->rede == 'twitter')
                                    <span class="badge badge-pill badge-success">
                                        <i class="fa fa-share"></i> 
                                    </span> 
                                @endif
                                @if($media->rede == 'facebook')
                                    <span class="badge badge-pill badge-info">
                                        <i class="fa fa-users"></i>
                                    </span> 
                                @endif
                                <span class="badge badge-pill badge-warning">
                                    <i class="fa fa-link"></i> <a href="" target="_blank" >Mídia</a>  
                                </span>
                                <span class="float-right">{{ Carbon\Carbon::parse($media->date)->format('d/m/Y H:i') }}</span>
                            </div>
                            <p>{{ $media->text }}</p>
                            @include('layouts.sentiment-obj')
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <footer>
        Relatório gerado em {{ date("d/m/Y") }} às {{ date("H:i:s") }} 
    </footer>
@endsection