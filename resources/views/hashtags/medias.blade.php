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
            <h6 class="ml-3">Total de mídias coletadas: {{ $hashtag->medias->count() }}</h6>
            @foreach($hashtag->medias as $key => $media)
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                <img src="{{ url('img/user.png') }}" alt="Imagem de Perfil" class="rounded-pill">
                                <p>{{ $media->username }}</p>
                                <!--<h1 class="text-center"><i class="fa fa-twitter text-info"></i></h1>-->
                                <h3><i class="fa fa-instagram text-pink"></i></h3>
                            </div>
                            <div class="col-md-10">
                                <p>{{ $media->caption }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="badge badge-pill badge-primary">
                                    <i class="fa fa-thumbs-up"></i> {{ $media->like_count }}
                                </span>
                                <span class="badge badge-pill badge-danger">
                                    <i class="fa fa-comments"></i> {{ $media->comments_count }}
                                </span>   
                                <span class="badge badge-pill badge-success">
                                    <i class="fa fa-share"></i> {{ $media->comments_count }}
                                </span> 
                                <span class="badge badge-pill badge-info">
                                    <i class="fa fa-users"></i> {{ $media->comments_count }}
                                </span> 
                            </div>
                            <div class="col-md-6">
                                <p class="text-right">{{ Carbon\Carbon::parse($media->created_at)->format('d/m/Y H:i') }}</p>  
                            </div>  
                        </div>  
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div> 
@endsection