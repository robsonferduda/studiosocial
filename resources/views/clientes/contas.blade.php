@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title">
                        <i class="nc-icon nc-briefcase-24"></i> Clientes 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Contas 
                    </h4>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('/') }}" class="btn btn-primary pull-right"><i class="nc-icon nc-chart-pie-36"></i> Dashboard</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            <table id="datatable_off" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                      <th>Nome</th>
                      <th>Email</th>
                      <th class="disabled-sorting text-center">Conexões</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $cliente->name }}</td>
                        <td>{{ $cliente->email }}</td>
                        <td class="text-center">
                            @if(count($cliente->fbAccounts))
                                <a title="Contas do Facebook" href="{{ url('client/accounts/facebook',$cliente->id) }}" class="btn btn-primary btn-link btn-icon btn-social btn-facebook"><i  class="fa fa-plug font-25"></i></a>
                            @endif
                            <a href="https://studiosocial.app/login/facebook/client/{{ $cliente->id }}" class="btn btn-social btn-facebook">
                                <i class="fa fa-facebook fa-fw"></i> CONECTAR Facebook
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div> 
@endsection