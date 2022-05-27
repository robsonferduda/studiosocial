@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title">
                        <i class="nc-icon nc-sound-wave"></i> Monitoramento 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Cliente 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> {{ session('cliente')['nome'] }}
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Mensagens 
                    </h4>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('monitoramento') }}" class="btn btn-primary pull-right" style="margin-right: 12px;"><i class="nc-icon nc-sound-wave"></i> Monitoramento</a>
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
                                <p>{{ $media['username'] }}</p> 
                                
                               
                                @switch($media['tipo'])
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
                                        <i class="fa fa-thumbs-up"></i> {{ $media['like_count'] }}
                                    </span>
                                    @if($media['tipo'] == 'instagram' || $media['tipo'] == 'facebook' )
                                        <span class="badge badge-pill badge-danger">
                                            <i class="fa fa-comments"></i> {{ $media['comments_count'] }}
                                        </span>   
                                    @endif
                                    @if($media['tipo'] == 'twitter')
                                        <span class="badge badge-pill badge-success">
                                            <i class="fa fa-share"></i> {{ $media['retweet_count'] }}
                                        </span> 
                                    @endif
                                    @if($media['tipo'] == 'facebook')
                                        <span class="badge badge-pill badge-info">
                                            <i class="fa fa-users"></i> {{ $media['share_count'] }}
                                        </span> 
                                    @endif
                                    <span class="badge badge-pill badge-warning">
                                        <i class="fa fa-link"></i> <a href="{{ $media['link'] }}" target="_blank" >Mídia</a>  
                                    </span>
                                    <span class="float-right">{{ Carbon\Carbon::parse($media['created_at'])->format('d/m/Y H:i') }}</span>
                                </div>
                                <p>{{ $media['text'] }}</p>

                                @if(!empty($media['comments']))
                                    <div class="card">
                                        <div class="card-body">
                                        <h6 class="card-subtitle mb-2 text-muted">Comentários Mencionados: </h6>
                                        @foreach($media['comments'] as $comment)                                                                               
                                            <p>{{ $comment['text'] }}</p>                                                                                                                                                      
                                        @endforeach
                                        </div>  
                                    </div>
                                @endif

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