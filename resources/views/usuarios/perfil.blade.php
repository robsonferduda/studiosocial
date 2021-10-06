@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="author">
                        <a href="#">
                            <h5 class="title">{{ $user->name }}</h5>
                        </a>
                        <p class="description">
                            {{ $user->email }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title ml-2">Editar Dados</h4>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('usuarios') }}" class="btn btn-primary pull-right" style="margin-right: 12px;"><i class="fa fa-table"></i> Usuários</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        @include('layouts.mensagens')
                    </div>
                    
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection