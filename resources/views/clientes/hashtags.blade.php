@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2"><i class="nc-icon nc-briefcase-24"></i> Clientes > Hashtags > {{ $client->name }}</h4>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('clientes') }}" class="btn btn-info pull-right" style="margin-right: 12px;"><i class="fa fa-table"></i> Clientes</a>
                    <button class="btn btn-primary pull-right mr-2" data-toggle="modal" data-target="#loginModal"><i class="fa fa-hashtag"></i> Cadastrar</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            <div class="col-md-12">
                <p><i class="nc-icon nc-alert-circle-i"></i> Clique sobre a <strong>Situação</strong> para alterar seu valor</p>
                <table class="table">
                    <thead class="">
                        <tr>
                            <th>Data da Criação</th>
                            <th>Mídia Social</th>
                            <th>Hashtag</th>
                            <th class="text-center">Situação</th>
                            <th class="text-right">Menções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($client->hashtags->sortBy('hashtag') as $key => $hashtag)
                            <tr>
                                <td>{{ Carbon\Carbon::parse($hashtag->created_at)->format('d/m/Y H:i') }}</td>
                                <td>{{ $hashtag->socialMedia->name }}</td>
                                <td><a href="{{ url('hashtag/medias/'.$hashtag->id) }}">#{{ $hashtag->hashtag }}</a></td>
                                <td class="text-center"><a href="{{ url('hashtag/situacao', $hashtag->id) }}">{!! ($hashtag->is_active) ? '<span class="badge badge-pill badge-success">ATIVO</span>' : '<span class="badge badge-pill badge-danger">INATIVO</span>' !!}</a></td>
                                <td class="text-right">
                                    @switch($hashtag->social_media_id)
                                        @case(App\Enums\SocialMedia::INSTAGRAM)
                                            {{ $hashtag->medias->count() }}
                                            @break
                                        @case(App\Enums\SocialMedia::TWITTER)
                                            {{ $hashtag->mediasTwitter->count() }}
                                            @break
                                        @default                        
                                    @endswitch
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>   
            </div>        
        </div>
    </div>
</div> 
<div class="modal fade modal-primary" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel"><i class="fa fa-hashtag"></i> <strong> Cadastrar Hashtag</strong></h5>
            </div>
            {!! Form::open(['id' => 'frm_hashtag_create', 'url' => ['hashtag/create']]) !!}
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <div class="modal-body">
                    <div class="card-body">            
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Hashtag <span class="text-danger">Obrigatório</span></label>
                                    <input type="text" class="form-control" name="hashtag" id="hashtag" value="">
                                </div>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-12">
                                @foreach($social_medias as $key => $sm)
                                    <div class="form-check mt-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="social_media[]" value="{{ $sm->id }}">
                                                {{ $sm->name }}
                                                <span class="form-check-sign"></span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>          
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                    <button data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar</button>
                </div>
            {!! Form::close() !!} 
        </div>
    </div>
</div>
@endsection