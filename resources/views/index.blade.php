@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-5 col-md-4">
                            <div class="icon-big text-center icon-warning">
                                <i class="nc-icon nc-circle-10 text-warning"></i>
                            </div>
                        </div>
                        <div class="col-7 col-md-8">
                            <div class="numbers">
                                <p class="card-category">Usuários Cadastrados</p>
                                <p class="card-title"><a href="{{ url('usuarios') }}">{{ $users }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <a href="{{ url('usuario/create') }}"><i class="fa fa-plus text-info"></i> Novo</a>                
                        </div>
                </div>
            </div>
        </div>   
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-5 col-md-4">
                            <div class="icon-big text-center icon-warning">
                                <i class="nc-icon nc-briefcase-24 text-success"></i>
                            </div>
                        </div>
                        <div class="col-7 col-md-8">
                            <div class="numbers">
                                <p class="card-category">Clientes Cadastrados</p>
                                <p class="card-title"><a href="{{ url('clientes') }}">{{ $clientes }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <a href="{{ url('clientes/create') }}"><i class="fa fa-plus text-info"></i> Novo</a>                
                        </div>
                </div>
            </div>
        </div>   
    </div>   
@endsection