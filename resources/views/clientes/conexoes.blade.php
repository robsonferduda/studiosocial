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

            @foreach($client->fbAccount as $key => $account)
                <h5>{{ $account->name }} ({{ $account->user_id }})</h5>  
            @endforeach
           
        </div>
    </div>
</div> 
@endsection