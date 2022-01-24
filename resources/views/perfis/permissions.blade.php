@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title"><i class="fa fa-group"></i> Perfis > Permissões</h4>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('perfis') }}" class="btn btn-primary pull-right" style="margin-right: 12px;"><i class="fa fa-group"></i> Perfis</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.mensagens')
                </div>
            </div>
            @foreach($role->permissions as $key => $permission)
                <p>{{ $permission->display_name }}</p>
            @endforeach
            
        </div>
    </div>
</div> 
@endsection