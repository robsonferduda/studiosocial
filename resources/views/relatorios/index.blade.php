@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a href="">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-bar-chart text-success"></i>
                                </div>
                                <h4 class="mt-2">Evolução Diária</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a href="">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-line-chart text-info"></i>
                                </div>
                                <h4 class="mt-2">Evolução Por Rede Social</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a href="{{ url('relatorios/sentimentos') }}">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-heart text-danger"></i>
                                </div>
                                <h4 class="mt-2">Sentimentos</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a href="">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-cloud text-info"></i>
                                </div>
                                <h4 class="mt-2">Nuvem de Palavras</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a href="{{ url('relatorios/reactions') }}">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-smile-o text-warning"></i>
                                </div>
                                <h4 class="mt-2">Reactions</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a href="">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-hashtag text-default"></i>
                                </div>
                                <h4 class="mt-2">Hashtags</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a href="{{ url('relatorios/influenciadores') }}">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-users text-info"></i>
                                </div>
                                <h4 class="mt-2">Influenciadores</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection