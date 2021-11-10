@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2"><i class="nc-icon nc-briefcase-24"></i> Clientes > Conexões > {{ $client->name }}</h4>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('clientes') }}" class="btn btn-primary pull-right" style="margin-right: 12px;"><i class="fa fa-table"></i> Clientes</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            <div class="col-md-12">
                <table class="table">
                    <thead class="">
                        <tr>
                            <th>Data da Conexão</th>
                            <th>Conta</th>
                            <th>Páginas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($client->fbAccount as $key => $account)
                            <tr>
                                <td>{{ date('d/m/Y H:i:s', strtotime($account->name)) }}</td>
                                <td>{{ $account->name }}</td>
                                <td>
                                    @foreach($account->fbPages as $key => $page)
                                        <p>
                                            <i class="fa fa-facebook"></i> {{ $page->name }}
                                            @if($page->igPage)
                                                <i class="fa fa-instagram"></i> {{ $page->igPage->name }}
                                            @endif
                                        </p>
                                    @endforeach
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