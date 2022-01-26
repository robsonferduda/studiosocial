@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title">
                        <i class="fa fa-group"></i> Perfis
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Permissões 
                    </h4>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('perfis') }}" class="btn btn-primary pull-right" style="margin-right: 12px;"><i class="fa fa-group"></i> Perfis</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Permissão</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($role->permissions as $key => $permission)
                            <tr>
                                <td>{{ $permission->display_name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>  
            </div>                     
        </div>
    </div>
</div> 
@endsection