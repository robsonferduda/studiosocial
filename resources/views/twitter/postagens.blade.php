@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="card-title"><i class="fa fa-group"></i> Relatórios <i class="fa fa-angle-double-right" aria-hidden="true"></i> Principais Influenciadores</h4>
                </div>
                <div class="col-md-4">
                    <a href="{{ url('relatorios/influenciadores') }}" class="btn btn-info pull-right"><i class="fa fa-group"></i> Principais Influenciadores</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            @foreach($medias as $key => $media)
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-1 col-md-1 text-center">
                                @if($media->user_profile_image_url)
                                    <img src="{{ str_replace('normal','400x400', $media->user_profile_image_url) }}" alt="Imagem de Perfil" class="rounded-pill">      
                                @else
                                    <img src="{{ url('img/user.png') }}" alt="Imagem de Perfil" class="rounded-pill">
                                @endif
                                <p class="mb-1 mt-2"><a href="https://twitter.com/{{ $media->user_name }}" target="_BLANK">{{ $media->user_name }}</a></p>                               
                            </div>
                            <div class="col-md-10">
                                <div class="mb-2">
                                    <span class="badge badge-pill badge-primary">
                                        <i class="fa fa-thumbs-up"></i> {{ $media['like_count'] }}
                                    </span>
                                    <span class="badge badge-pill badge-danger">
                                        <i class="fa fa-comments"></i> {{ $media['comments_count'] }}
                                    </span>   
                                    <span class="badge badge-pill badge-success">
                                        <i class="fa fa-share"></i> {{ $media['comments_count'] }}
                                    </span> 
                                    <span class="badge badge-pill badge-info">
                                        <i class="fa fa-users"></i> {{ $media['comments_count'] }}
                                    </span> 
                                    <span class="badge badge-pill badge-warning">
                                        <i class="fa fa-link"></i> <a href="{{ $media['link'] }}" target="_blank" >Mídia</a>  
                                    </span>
                                    <span class="float-right">{{ Carbon\Carbon::parse($media['created_at'])->format('d/m/Y H:i') }}</span>
                                </div>
                                <p>{{ $media->full_text }}</p>
                                <h3>
                                    @switch($media->sentiment)
                                        @case(-1)
                                                <i class="fa fa-frown-o text-danger"></i>
                                                <a href="{{ url('media/'.$media->twitter_id.'/tipo/twitter/sentimento/0/atualizar') }}"><i class="fa fa-smile-o op-2"></i></a>
                                                <a href=""><i class="fa fa-ban op-2"></i></a>
                                            @break
                                        @case(0)
                                                <i class="fa fa-frown-o op-2"></i>
                                                <i class="fa fa-smile-o op-2"></i>
                                                <i class="fa fa-ban text-primary"></i>
                                            @break
                                        @case(1)
                                                <i class="fa fa-frown-o op-2"></i>
                                                <i class="fa fa-smile-o text-success"></i>
                                                <i class="fa fa-ban op-2"></i>
                                            @break                                            
                                    @endswitch
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div> 
@endsection