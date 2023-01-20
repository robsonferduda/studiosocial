@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2"><i class="fa fa-file-o"></i> Boletins</h4>
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
                {!! Form::open(['id' => 'frm_social_search', 'class' => 'form-horizontal', 'url' => ['boletins']]) !!}
                    <div class="form-group m-3 w-70">
                        <div class="row">
                            <div class="col-md-2 col-sm-6">
                                <div class="form-group">
                                    <label>Data de Criação</label>
                                    <input type="text" class="form-control datepicker" name="data" required="true" value="{{ date("d-m-Y") }}" placeholder="__/__/____">
                                </div>
                            </div>
                            <div class="col-md-3 checkbox-radios mb-0 mt-3">
                                <button type="submit" id="btn-find" class="btn btn-primary mb-3"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>     
                    </div>
                {!! Form::close() !!} 

                <table class="table table-hover">
                    <thead class="">
                        <tr>
                            <th class="center">Código</th>
                            <th class="center">Data da Criação</th>
                            <th>Cliente</th>
                            <th>Boletim</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($boletins as $boletim)
                            <tr>
                                <td class="center">{{ $boletim->id }}</td>
                                <td class="center">{{ Carbon\Carbon::parse($boletim->data)->format('d/m/Y H:i') }}</td>
                                <td>
                                    {{ $boletim->cliente->nome }}
                                </td>
                                <td>{{ $boletim->titulo }}</td>
                                <td class="text-center">
                                    @if($boletim->status_envio == 'enviado')
                                        <span class="badge badge-success">Enviado</span>
                                    @else
                                        <span class="badge badge-danger">Não enviado</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ url('boletim', $boletim->id) }}"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>   
            </div>        
        </div>
    </div>
</div> 
@endsection