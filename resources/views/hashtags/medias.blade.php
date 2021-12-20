@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2"><i class="fa fa-hashtag"></i> Hashtags > {{ $hashtag->hashtag }} </h4>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('client/hashtags/'.$hashtag->client->id) }}" class="btn btn-primary pull-right" style="margin-right: 12px;"><i class="fa fa-hashtag"></i> Hashtags</a>
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
                            <div class="col-md-2 text-center">
                                @if($media['user_profile_image_url'])
                                    <img src="{{ str_replace('normal','400x400', $media['user_profile_image_url']) }}" alt="Imagem de Perfil" class="rounded-pill img-perfil">      
                                @else
                                    <img src="{{ url('img/user.png') }}" alt="Imagem de Perfil" class="rounded-pill">
                                @endif
                                <p class="mb-1 mt-2"><a href="https://twitter.com/{{ $media['username'] }}" target="_BLANK">{{ $media['username'] }}</a></p>                                
                                @switch($hashtag['social_media_id'])
                                    @case(App\Enums\SocialMedia::INSTAGRAM)
                                        <h3><i class="fa fa-instagram text-pink"></i></h3>
                                        @break
                                    @case(App\Enums\SocialMedia::TWITTER)
                                        <h1 class="text-center"><i class="fa fa-twitter text-info"></i></h1>
                                        @break
                                    @default                                        
                                @endswitch
                            </div>
                            <div class="col-md-10">
                                <div class="mb-2">                                
                                    <span class="badge badge-pill badge-primary">
                                        <i class="fa fa-thumbs-up"></i> {{ $media['like_count'] }}
                                    </span>
                                    @if($hashtag['social_media_id'] == App\Enums\SocialMedia::INSTAGRAM)
                                        <span class="badge badge-pill badge-danger">
                                            <i class="fa fa-comments"></i> {{ $media['comments_count'] }}
                                        </span>   
                                    @endif
                                    @if($hashtag['social_media_id'] == App\Enums\SocialMedia::TWITTER)
                                        <span class="badge badge-pill badge-success">
                                            <i class="fa fa-share"></i> {{ $media['retweet_count'] }}
                                        </span> 
                                    @endif
                                    <span class="badge badge-pill badge-warning">
                                        <i class="fa fa-link"></i> <a href="{{ $media['link'] }}" target="_blank" >Mídia</a>  
                                    </span>

                                    @if($hashtag['social_media_id'] == App\Enums\SocialMedia::TWITTER)
                                        <span class="badge badge-pill badge-secondary count-relation">{{ $media['user_followers_count'] }} Seguidores </span><span class="badge badge-pill badge-light count-relation"> {{ $media['user_friends_count'] }} Seguindo</span> 
                                    @endif

                                    <span class="float-right">{{ Carbon\Carbon::parse($media['created_at'])->format('d/m/Y H:i') }}</span>
                                </div>
                                <p>{{ $media['text'] }}</p>
                                @include('layouts.sentiment')
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