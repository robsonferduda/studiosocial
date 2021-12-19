@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2"><i class="nc-icon nc-send"></i> Notificações</h4>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-primary pull-right mr-2" data-toggle="modal" data-target="#modalNotificacao"><i class="fa fa-send"></i> Cadastrar</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead class="">
                    <tr>
                        <th>Data da Criação</th>
                        <th>Tipo de Notificação</th>
                        <th>Valor</th>
                        <th class="text-center">Situação</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>   
        </div>
    </div>
</div> 
<div class="modal fade modal-primary" id="modalNotificacao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel"><i class="fa fa-send"></i> <strong> Cadastrar Notificação</strong></h5>
            </div>
            {!! Form::open(['id' => 'frm_notification_create', 'url' => ['notification/create']]) !!}
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <div class="modal-body">
                    <div class="card-body"> 
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Tipo de Notificação</label>
                                    <select class="form-control load_expression" name="regra" id="regra">
                                        <option value="">Selecione um tipo</option>
                                        <option value="1">Número de menções</option>
                                        <option value="2">Engajamento nas redes sociais</option>
                                        <option value="3">Hashtag negativa</option>
                                        <option value="4">Palavra-chave</option>
                                    </select>
                                </div>
                            </div>
                        </div>             
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Valor Alvo <span class="text-danger">Obrigatório</span></label>
                                    <input type="text" class="form-control" name="term" id="term" value="">
                                </div>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-12">
                                
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