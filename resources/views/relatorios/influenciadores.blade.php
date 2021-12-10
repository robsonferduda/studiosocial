@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="card-title"><i class="fa fa-group"></i> Relatórios <i class="fa fa-angle-double-right" aria-hidden="true"></i> Principais Influenciadores</h4>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('relatorios') }}" class="btn btn-info pull-right"><i class="nc-icon nc-chart-bar-32"></i> Relatórios</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('layouts/regra')
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <h3><i class="fa fa-smile-o text-success"></i> Positivos</h3>
                            @foreach($positivos as $user)
                                <div class="card">
                                    <div class="row mb-3">
                                        <div class="col-lg-2 col-md-2 m-auto">
                                            @if($user->user_profile_image_url)
                                                <img src="{{ str_replace('normal','400x400', $user->user_profile_image_url) }}" alt="Imagem de Perfil" class="rounded-pill">      
                                            @else
                                                <img src="{{ url('img/user.png') }}" alt="Imagem de Perfil" class="rounded-pill">
                                            @endif
                                        </div>
                                        <div class="col-md-9">
                                            <p class="mb-1 mt-2"><a href="https://twitter.com/{{ $user->user_name }}" target="_BLANK">{{ $user->user_name }}</a></p>
                                            <p class="mb-1">{{ $user->total }} postagens</p>
                                            <a class="mb-1" href="{{ url('twitter/postagens/user/'.$user->user_name.'/sentimento/1') }}">Ver Postagens</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <h3><i class="fa fa-frown-o text-danger"></i> Negativos</h3>
                            @foreach($negativos as $user)
                                <div class="card">
                                    <div class="row mb-3">
                                        <div class="col-lg-2 col-md-2 m-auto">
                                            @if($user->user_profile_image_url)
                                                <img src="{{ str_replace('normal','400x400', $user->user_profile_image_url) }}" alt="Imagem de Perfil" class="rounded-pill">      
                                            @else
                                                <img src="{{ url('img/user.png') }}" alt="Imagem de Perfil" class="rounded-pill">
                                            @endif
                                        </div>
                                        <div class="col-md-9">
                                            <p class="mb-1 mt-2"><a href="https://twitter.com/{{ $user->user_name }}" target="_BLANK">{{ $user->user_name }}</a></p>
                                            <p class="mb-1">{{ $user->total }} postagens</p>
                                            <a class="mb-1" href="{{ url('twitter/postagens/user/'.$user->user_name.'/sentimento/-1') }}">Ver Postagens</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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

        $("#regra").change(function(){

            var regra = $(this).val();
            var expression = $('#regra option').filter(':selected').data('expression');

            
        });
    });
</script>
@endsection    