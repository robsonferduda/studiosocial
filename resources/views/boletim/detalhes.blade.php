@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="card-title ml-2">
                        <i class="fa fa-file-o"></i> Boletim 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> {{ $boletim->titulo }}
                    </h4>
                </div>
                <div class="col-md-4">
                    <a href="{{ url('boletins') }}" class="btn btn-primary pull-right"><i class="fa fa-file-o"></i> Boletins</a>
                    <a href="{{ url('boletim/123466/enviar') }}" class="btn btn-success pull-right btn-enviar"><i class="fa fa-send"></i> Enviar Newsletter</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            <div class="col-md-12">
                <span>Foram encontradas {{ count($dados) }} notícias</span><hr/>
                @php
                    $area = "";
                    $tipo = "";
                    $tipo_formatado = "";
                    $flag = false;
                @endphp
                @foreach($dados as $key => $noticia)
                    @if($noticia->area != null)

                        @if($noticia->area != $area and $flag)
                            </div>
                        @endif

                        @if($noticia->area != $area)
                            <div class="px-3 py-2 mb-3" style="border: 1px solid #f9f9f9; background: #f9f9f9;">
                                <p style="color: #2196f3; font-size: 20px !important; text-transform: uppercase; border-bottom: 1px solid #2196f3;"><i class="fa fa-newspaper-o" aria-hidden="true"></i> {{ $noticia->area }}</p>
                            @php
                                $flag = true;
                            @endphp
                        @endif

                        @if($noticia->clipagem != $tipo)
                            @switch($noticia->clipagem)
                                @case('web')
                                    @php
                                        $tipo_formatado = 'Clipagens de Web';
                                    @endphp
                                @break
                                @case('tv')
                                    @php
                                        $tipo_formatado = 'Clipagens de TV';
                                    @endphp
                                @break
                                @case('radio')
                                    @php
                                        $tipo_formatado = 'Clipagens de Rádio';
                                    @endphp
                                @break
                                @case('jornal')
                                    @php
                                        $tipo_formatado = 'Clipagens de Jornal';
                                    @endphp
                                @break
                                @default
                                    @php
                                        $tipo_formatado = 'Clipagens';
                                    @endphp
                                @break                                    
                            @endswitch
                            <p style="text-transform: uppercase; font-weight: 600;">{{ $tipo_formatado }}</p>
                        @endif

                        @if($noticia->clipagem == 'tv')

                            <p>{{ $noticia->titulo }}</p>
                            <p>Emissora: {{ $noticia->INFO1 }}</p>
                            <p>Programa: {{ $noticia->INFO2 }}</p>
                            <p>{!! $noticia->sinopse !!}</p>

                            <video width="320" height="240" controls>
                                <source src="http://clipagens.com.br/fmanager/clipagem/tv/arquivo579760_1.mp4" type="video/mp4">
                                <source src="movie.ogg" type="video/ogg">
                              Your browser does not support the video tag.
                            </video>

                        @elseif($noticia->clipagem == 'radio')

                            <div class="p-2 mb-2">
                                <p>Emissora: {{ $noticia->INFO1 }}</p>
                                <p>Programa: {{ $noticia->INFO2 }}</p>
                                <p>{!! $noticia->sinopse !!}</p>
                                <p>Link: {{ $noticia->link }}</p>
                            </div>
                            <audio controls>
                                <source src="{{ $noticia->link }}" type="audio/ogg">
                                <source src="horse.mp3" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        @else

                            <div class="p-2 mb-2">
                                <p>{{ $noticia->titulo }}</p>
                                <p>{!! $noticia->sinopse !!}</p>
                            </div>
                        @endif

                        @php
                            $area = $noticia->area;
                            $tipo = $noticia->clipagem;
                        @endphp

                    @endif
                 @endforeach
            </div>        
        </div>
    </div>
</div> 
@endsection