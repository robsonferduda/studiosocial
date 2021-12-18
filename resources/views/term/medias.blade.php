@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2"><i class="fa fa-font"></i> Termos > "{{ $term->term }}" </h4>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('terms/client/'.$term->client->id) }}" class="btn btn-primary pull-right" style="margin-right: 12px;"><i class="fa fa-font"></i> Termos</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            <h6 class="ml-3">Mostrando {{ $medias_temp->count() }} de {{ $medias_temp->total() }} mídias coletadas</h6>
            {{ $medias_temp->onEachSide(1)->links('vendor.pagination.bootstrap-4') }} 
            
            @foreach($medias as $key => $media)
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 text-center">
                                @if($media['user_profile_image_url'])
                                    <img src="{{ str_replace('normal','400x400', $media['user_profile_image_url']) }}" alt="Imagem de Perfil" class="rounded-pill img-perfil">      
                                @else
                                    <img src="{{ url('img/user.png') }}" alt="Imagem de Perfil" class="rounded-pill">
                                @endif
                                <p class="mb-1 mt-2"><a href="https://twitter.com/{{ $media['username'] }}" target="_BLANK">{{ $media['username'] }}</a></p> 

                                <p>{{ $media['username'] }}</p>                                
                                @switch($term['social_media_id'])
                                    @case(App\Enums\SocialMedia::INSTAGRAM)
                                        <h3><i class="fa fa-instagram text-pink"></i></h3>
                                        @break
                                    @case(App\Enums\SocialMedia::TWITTER)
                                        <h1 class="text-center"><i class="fa fa-twitter text-info"></i></h1>
                                        @break
                                    @default                                        
                                @endswitch
                            </div>
                            <div class="col-lg-10 col-md-10">
                                <div class="mb-2">
                                    <span class="badge badge-pill badge-primary">
                                        <i class="fa fa-thumbs-up"></i> {{ $media['like_count'] }}
                                    </span>

                                    @if($term['social_media_id'] == App\Enums\SocialMedia::INSTAGRAM)
                                        <span class="badge badge-pill badge-danger">
                                        <i class="fa fa-comments"></i> {{ $media['comments_count'] }}
                                        </span>   
                                    @endif

                                    @if($term['social_media_id'] == App\Enums\SocialMedia::TWITTER)
                                        <span class="badge badge-pill badge-default">
                                            <i class="fa fa-retweet"></i> {{ $media['retweet_count'] }}
                                        </span> 
                                    @endif

                                    <span class="badge badge-pill badge-warning">
                                        <i class="fa fa-link"></i> <a href="{{ $media['link'] }}" target="_blank" >Mídia</a>  
                                    </span>

                                    @if($term['social_media_id'] == App\Enums\SocialMedia::TWITTER)
                                        <span class="badge badge-pill badge-secondary count-relation">{{ $media['user_followers_count'] }} Seguidores </span><span class="badge badge-pill badge-light count-relation"> {{ $media['user_friends_count'] }} Seguindo</span> 
                                    @endif
                                   
                                    <span class="float-right">{{ Carbon\Carbon::parse($media['created_at'])->format('d/m/Y H:i') }}</span>
                                </div>
                                <p>{{ $media['text'] }}</p>
                                <h3>
                                    @switch($media['sentiment'])
                                        @case(-1)
                                                <i class="fa fa-frown-o text-danger"></i>
                                                <a href="{{ url('media/'.$media['id'].'/tipo/'.$media['type_message'].'/sentimento/0/atualizar') }}"><i class="fa fa-ban op-2"></i></a>
                                                <a href="{{ url('media/'.$media['id'].'/tipo/'.$media['type_message'].'/sentimento/1/atualizar') }}"><i class="fa fa-smile-o op-2"></i></a>
                                            @break
                                        @case(0)
                                                <a href="{{ url('media/'.$media['id'].'/tipo/'.$media['type_message'].'/sentimento/-1/atualizar') }}"><i class="fa fa-frown-o op-2"></i></a> 
                                                <i class="fa fa-ban text-primary"></i>
                                                <a href="{{ url('media/'.$media['id'].'/tipo/'.$media['type_message'].'/sentimento/1/atualizar') }}"><i class="fa fa-smile-o op-2"></i></a>                                                
                                            @break
                                        @case(1)
                                                <a href="{{ url('media/'.$media['id'].'/tipo/'.$media['type_message'].'/sentimento/-1/atualizar') }}"><i class="fa fa-frown-o op-2"></i></a>
                                                <a href="{{ url('media/'.$media['id'].'/tipo/'.$media['type_message'].'/sentimento/0/atualizar') }}"><i class="fa fa-ban op-2"></i></a>
                                                <i class="fa fa-smile-o text-success"></i>
                                            @break                                            
                                    @endswitch
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{ $medias_temp->onEachSide(1)->links('vendor.pagination.bootstrap-4') }} 
        </div>
    </div>
</div> 
@endsection