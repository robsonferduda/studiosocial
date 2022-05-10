@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <h4 class="card-title ml-2">
                        <i class="nc-icon nc-zoom-split"></i> Social Search 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Buscar Mídias 
                    </h4>
                </div>
                <div class="col-md-6">
                    
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')            
            </div>
            <div class="col-md-12">
                <div class="card">
                    {!! Form::open(['id' => 'frm_search_page', 'class' => 'form-horizontal', 'url' => ['social-search']]) !!}
                        <div class="form-group m-3 w-70">
                            <div class="row">
                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <label>Data Inicial</label>
                                        <input type="text" class="form-control datepicker" name="dt_inicial" value="{{ (old("dt_inicial")) ? old("dt_inicial") : date("d/m/Y") }}" placeholder="__/__/____">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <label>Data Final</label>
                                        <input type="text" class="form-control datepicker" name="dt_final" value="{{ (old("dt_final")) ? old("dt_final") : date("d/m/Y") }}" placeholder="__/__/____">
                                    </div>
                                </div>
                                <div class="col-md-8 col-sm-12">
                                    <div class="form-group">
                                        <label>Termo para busca <span class="text-primary">Mínimo de 3 caracteres</span></label>
                                        <input type="text" class="form-control" name="termo" placeholder="Termo" value="{{ old('termo') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 checkbox-radios mb-0">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="facebook">
                                        <span class="form-check-sign"></span>
                                            Facebook
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="instagram">
                                        <span class="form-check-sign"></span>
                                            Instagram
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="twitter">
                                        <span class="form-check-sign"></span>
                                            Twitter
                                        </label>
                                    </div>
                                    <button type="submit" id="btn-find" class="btn btn-primary mb-3"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!} 
                </div>
            </div> 
            <div class="col-md-12">
                <h6 class="ml-1 mt-5 mb-3">Mostrando {{ $medias->count() }} de {{ $medias->total() }} Páginas</h6>
                @if($term)
                    {{ $medias->onEachSide(1)->appends(['term' => $term])->links('vendor.pagination.bootstrap-4') }} 
                @else
                    {{ $medias->onEachSide(1)->links('vendor.pagination.bootstrap-4') }} 
                @endif
                
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
                                    <p>Usuário</p>                                     
                                   
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
                @if($term)
                    {{ $medias->onEachSide(1)->appends(['term' => $term])->links('vendor.pagination.bootstrap-4') }} 
                @else
                    {{ $medias->onEachSide(1)->links('vendor.pagination.bootstrap-4') }} 
                @endif
            </div>  
        </div>
    </div>
</div> 
@endsection