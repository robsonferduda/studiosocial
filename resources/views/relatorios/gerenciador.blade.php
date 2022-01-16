@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title">
                        <i class="nc-icon nc-sound-wave"></i> Relatórios 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Evolução Diária 
                    </h4>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('relatorios') }}" class="btn btn-info pull-right"><i class="nc-icon nc-chart-bar-32"></i> Relatórios</a>
                </div>
            </div>
        </div>
        <div class="card-body"> 
            @include('layouts/regra')          
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h6 class="mb-3">Selecione os relatórios que deseja gerar simultaneamente</h6>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-check">
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="true">
                                Evolução Diária
                            <span class="form-check-sign"></span>
                        </label>
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="true">
                                Evolução rede Social
                            <span class="form-check-sign"></span>
                        </label>
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="true">
                                Sentimentos
                            <span class="form-check-sign"></span>
                        </label>
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="true">
                                Nuvem de Palavras
                            <span class="form-check-sign"></span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-check">
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="true">
                                Reactions
                            <span class="form-check-sign"></span>
                        </label>
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="true">
                                Hashtags
                            <span class="form-check-sign"></span>
                        </label>
                        <label class="form-check-label d-block mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="true">
                                Influenciadores
                            <span class="form-check-sign"></span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 text-center">
                    <button type="button" class="btn btn-danger btn-relatorio"><i class="fa fa-file-pdf-o"></i> Gerar Relatórios</button>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection
@section('script')
<script src="{{ asset('js/relatorios.js') }}"></script>
<script>
    $(document).ready(function() {

    });
</script>
@endsection