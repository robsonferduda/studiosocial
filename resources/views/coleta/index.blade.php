@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title">
                        <i class="nc-icon nc-tag-content"></i> Coletas 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Dashboard 
                    </h4>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <a class="btn btn-twitter btn-coleta" href="{{ url('coletas/twitter') }}">
                            <i class="fa fa-twitter"></i> Coletar Twitter
                        </a>
                        <a class="btn btn-facebook btn-coleta disabled" href="">
                            <i class="fa fa-facebook"></i> Coletar Facebook Perfil
                        </a>
                        <a class="btn btn-facebook btn-coleta disabled" href="">
                            <i class="fa fa-facebook"></i> Coletar Facebook Páginas
                        </a>
                        <a class="btn btn-dribbble btn-coleta" href="{{ url('coletas/instagram') }}">
                            <i class="fa fa-instagram"></i> Coletar Instagram
                        </a>
                        <a class="btn btn-success btn-notificacao" href="{{ url('notificacoes/verificacao') }}">
                            <i class="fa fa-send"></i> Notificar Coletas
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">                
                <div class="col-lg-12 col-md-12">
                    <div class="card card-stats card-result">
                        <div class="card-body ">
                            <h6 class="text-left">Últimas Coletas</h6>
                            <table class="table">
                                <thead class="">
                                    <tr>
                                        <th>Data</th>
                                        <th>Cliente</th>
                                        <th>Rede Social</th>
                                        <th>Mídia</th>
                                        <th>Tipo de Coleta</th>
                                        <th>Termo</th>
                                        <th class="center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coletas as $coleta)
                                        <tr>
                                            <td>{{ date('d/m/Y H:i:s', strtotime($coleta->created_at )) }}</td>
                                            <td>{{ ($coleta->client) ? $coleta->client->name : "" }}</td>
                                            <td>{{ $coleta->socialMedia->name }}</td>
                                            <td>{{ $coleta->typeMessage->type }}</td>
                                            <td>{{ $coleta->type->ds_type_collect }}</td>
                                            <td>{{ $coleta->description }}</td>
                                            <td class="center">{{ $coleta->total }}</td>
                                        </tr>
                                @endforeach                                    
                                </tbody>
                            </table>   
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">                
                <div class="col-lg-12 col-md-12">
                    <div class="card card-stats card-notificacao">
                        <div class="card-body ">
                            <h6 class="text-left">Últimas Notificações</h6>
                            <table class="table">
                                <thead class="">
                                    <tr>
                                        <th>Data</th>
                                        <th>Cliente</th>
                                        <th>Rede Social</th>
                                        <th>Mídia</th>
                                        <th>Tipo de Notificação</th>
                                        <th>Termo</th>
                                        <th class="center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notificacoes as $notificacao)
                                        <tr>
                                            <td>{{ date('d/m/Y H:i:s', strtotime($notificacao->created_at )) }}</td>
                                            <td>{{ ($notificacao->client) ? $notificacao->client->name : "" }}</td>
                                            <td>{{ $notificacao->socialMedia->name }}</td>
                                            <td>{{ $notificacao->typeMessage->type }}</td>
                                            <td>{{ $notificacao->notification->name }}</td>
                                            <td>{{ $notificacao->description }}</td>
                                            <td class="center">{{ $notificacao->total }}</td>
                                        </tr>
                                @endforeach                                    
                                </tbody>
                            </table>   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection
@section('script')
<script>
    $(document).ready(function() {

        $(".btn-coleta").click(function(){
            $('.card-result').loader('show');
        });

        $(".btn-notificacao").click(function(){
            $('.card-notificacao').loader('show');
        });

    });
</script>
@endsection