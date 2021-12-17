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
                            <div class="col-md-2 text-center">
                                <img src="{{ url('img/user.png') }}" alt="Imagem de Perfil" class="rounded-pill">
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
                            <div class="col-md-10">
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
                                        <span class="badge badge-pill badge-success">
                                            <i class="fa fa-share"></i> {{ $media['retweet_count'] }}
                                        </span> 
                                    @endif

                                    <span class="badge badge-pill badge-warning">
                                        <i class="fa fa-link"></i> <a href="{{ $media['link'] }}" target="_blank" >Mídia</a>  
                                    </span>
                                   
                                    <span class="float-right">{{ Carbon\Carbon::parse($media['created_at'])->format('d/m/Y H:i') }}</span>
                                </div>
                                <p>{{ $media['text'] }}</p>
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