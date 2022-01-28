@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats box-relatorio">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a class="link-relatorio" href="{{ url('relatorios/midias/evolucao-diaria') }}">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-bar-chart text-success"></i>
                                </div>
                                <h4 class="mt-2 text-dark">Evolução Diária</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats box-relatorio">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a class="link-relatorio" href="{{ url('relatorios/midias/evolucao-redes-sociais') }}">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-line-chart text-info"></i>
                                </div>
                                <h4 class="mt-2 text-dark">Evolução Rede Social</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats box-relatorio">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a class="link-relatorio"  href="{{ url('relatorios/sentimentos') }}">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-heart text-danger"></i>
                                </div>
                                <h4 class="mt-2 text-dark">Sentimentos</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats box-relatorio">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a class="link-relatorio"  href="{{ url('relatorios/wordcloud') }}">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-cloud text-info"></i>
                                </div>
                                <h4 class="mt-2 text-dark">Nuvem de Palavras</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats box-relatorio">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a class="link-relatorio"  href="{{ url('relatorios/reactions') }}">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-smile-o text-warning"></i>
                                </div>
                                <h4 class="mt-2 text-dark">Reactions</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats box-relatorio">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a class="link-relatorio" href="{{ url('relatorios/hashtags') }}">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-hashtag text-default"></i>
                                </div>
                                <h4 class="mt-2 text-dark">Hashtags</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats box-relatorio">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a class="link-relatorio" href="{{ url('relatorios/influenciadores') }}">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-users text-info"></i>
                                </div>
                                <h4 class="mt-2 text-dark">Influenciadores</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats box-relatorio">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a class="link-relatorio" href="{{ url('relatorios/gerenciador') }}">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-cogs text-warning"></i>
                                </div>
                                <h4 class="mt-2 text-dark">Gerenciador</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats box-relatorio">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-0">
                            <a class="link-relatorio"  href="{{ url('relatorios/localizacao') }}">
                                <div class="icon-big icon-warning">
                                    <i class="fa fa-map-marker text-danger"></i>
                                </div>
                                <h4 class="mt-2 text-dark">Localização</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection