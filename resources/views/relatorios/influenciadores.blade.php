@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title"><i class="fa fa-group"></i> Relatórios <i class="fa fa-angle-double-right" aria-hidden="true"></i> Principais Influenciadores</h4>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('relatorios') }}" class="btn btn-info pull-right"><i class="nc-icon nc-chart-bar-32"></i> Relatórios</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('layouts/regra')
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <h3><i class="fa fa-smile-o text-success"></i> Positivos</h3>

                            <div class="row">
                                <div class="col-md-2 text-center">
                                    <img src="{{ url('img/user.png') }}" alt="Imagem de Perfil" class="rounded-pill">                          
                                </div>
                                <div class="col-md-10">

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <h3><i class="fa fa-frown-o text-danger"></i> Negativos</h3>
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