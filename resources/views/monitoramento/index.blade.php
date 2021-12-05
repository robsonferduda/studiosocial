@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title">
                        <i class="nc-icon nc-sound-wave"></i> Monitoramento 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Cliente 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> {{ session('cliente')['nome'] }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <p>São mostrados os resultados das coletas das redes sociais para o cliente selecionado. Utilize a opção "Regras" para filtrar os resultados de acordo com as expressões desejadas.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="fa fa-instagram text-pink"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Instagram</p>
                                            <p class="card-title"><a href="{{ url('monitoramento/media/instagram') }}">{{ $totais['total_insta'] }}</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="fa fa-facebook text-facebook"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Facebook</p>
                                            <p class="card-title"><a href="{{ url('monitoramento/media/facebook') }}">{{ $totais['total_face'] }}</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="fa fa-twitter text-info"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Twitter</p>
                                            <p class="card-title"><a href="{{ url('monitoramento/media/twitter') }}">{{ $totais['total_twitter'] }}</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="col-lg-9 col-md-9">
                    <div class="card car-chart">
                      <div class="card-header">
                        <h5 class="card-title">Monitoramento de Redes Sociais</h5>
                        <p class="">Total de coletas diárias por rede social</p>
                      </div>
                      <div class="card-body">
                        <canvas id="chartActivity"></canvas>
                      </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                
            </div>
        </div>
    </div>
</div> 
@endsection