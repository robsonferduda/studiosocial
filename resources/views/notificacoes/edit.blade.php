@extends('layouts.app')
@section('content')
<div class="col-md-12">
    {!! Form::open(['id' => 'frm_notification_create', 'url' => ['notification', $notification_client->id], 'method' => 'patch']) !!}
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title ml-2">
                            <i class="nc-icon nc-send"></i> Notificações
                            <i class="fa fa-angle-double-right" aria-hidden="true"></i> Editar
                        </h4>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ url('notificacoes') }}" class="btn btn-primary pull-right mr-2"><i class="fa fa-send"></i> Notificações</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @include('layouts.mensagens')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <span class="text-info">Todos os campos são de preenchimento obrigatório.</span>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Tipo de Notificação</label>
                            <select class="form-control" name="notification_id" id="notification" required="true">
                                <option value="">Selecione um tipo</option>
                                @foreach($notifications as $notification)
                                    <option value="{{ $notification->id }}" {{ ($notification_client->notification_id == $notification->id) ? 'selected' : '' }}>{{ $notification->name }}</option>
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
                            <input type="text" class="form-control dt_inicio" name="dt_inicio" id="dt_inicio" value="{{ date('d/m/Y', strtotime($notification_client->dt_inicio)) }}" required="true">
                        </div>
                    </div>
               
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Data Final</label>
                            <input type="text" class="form-control dt_termino" name="dt_termino" id="dt_termino" value="{{ date('d/m/Y', strtotime($notification_client->dt_termino)) }}" required="true">
                        </div>
                    </div>
                </div>     
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Termo/Valor</label>
                            <input type="text" class="form-control" name="valor" id="valor" value="{{ $notification_client->valor }}" required="true">
                        </div>
                    </div>
                </div>                   
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                <a href="{{ url('notificacoes') }}" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar</a>
            </div>
        </div>
    {!! Form::close() !!}
</div> 
@endsection
@section('script')
<script>
    $(document).ready(function() {

        var dados = null;
        var host =  $('meta[name="base-url"]').attr('content');

        var id = $("#notification").val();
        $('.notification-descricao').empty();

        $.ajax({
            url: host+'/notificacoes/'+id+'/descricao',
            type: 'GET',
            success: function(response) {
                $('.notification-descricao').html(response);
            }
        }); 

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