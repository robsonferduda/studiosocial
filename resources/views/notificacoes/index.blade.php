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
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            <table id="datatable" class="table">
                <thead class="">
                    <tr>
                        <th>Início</th>
                        <th>Término</th>
                        <th>Tipo de Notificação</th>
                        <th class="center">Termo/Valor</th>
                        <th class="center">Contagem Atual</th>
                        <th class="text-center">Situação</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notifications_client as $notification)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($notification->dt_inicio)) }}</td>
                            <td>{{ date('d/m/Y', strtotime($notification->dt_termino)) }}</td>
                            <td>{{ $notification->notification->name }}</td>
                            <td class="center">{{ $notification->valor }}</td>
                            <td class="center">{{ $notification->valor_atual }}</td>
                            <td class="center">
                                @if($notification->status)
                                    <span class="badge badge-success">Ativo</span>
                                @else
                                    <span class="badge badge-warning">Inativo</span>
                                @endif
                            </td>
                            <td>
                                <a title="Editar" href="{{ route('notification.edit',$notification->id) }}" class="btn btn-primary btn-link btn-icon"><i class="fa fa-edit fa-2x"></i></a>
                                <form class="form-delete" style="display: inline;" action="{{ route('notification.destroy',$notification->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button title="Excluir" type="submit" class="btn btn-danger btn-link btn-icon button-remove" title="Delete">
                                        <i class="fa fa-times fa-2x"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
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
            {!! Form::open(['id' => 'frm_notification_create', 'url' => ['notification']]) !!}
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <div class="modal-body p-0">
                    <div class="card-body"> 
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <span class="text-info">Todos os campos são de preenchimento obrigatório.</span>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Tipo de Notificação</label>
                                    <select class="form-control load_expression" name="notification_id" id="notification"  required="true">
                                        <option value="">Selecione um tipo</option>
                                        @foreach($notifications as $notification)
                                            <option value="{{ $notification->id }}">{{ $notification->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>      
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group notification-descricao">
                                </div>                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Data Inicial</label>
                                    <input type="text" class="form-control dt_inicio" name="dt_inicio" id="dt_inicio" required="true">
                                </div>
                            </div>
                       
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Data Final</label>
                                    <input type="text" class="form-control dt_termino" name="dt_termino" id="dt_termino" required="true">
                                </div>
                            </div>
                        </div>     
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Termo/Valor</label>
                                    <input type="text" class="form-control" name="valor" id="valor"  required="true">
                                </div>
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
@section('script')
<script>
    $(document).ready(function() {

        var dados = null;
        var host =  $('meta[name="base-url"]').attr('content');

        $('#notification').on('change', function() {

            var id = $(this).val();
            $('.notification-descricao').empty();

            $.ajax({
                url: host+'/notificacoes/'+id+'/descricao',
                type: 'GET',
                success: function(response) {
                    $('.notification-descricao').html(response);
                }
            }); 
        });
    });
</script>
@endsection