@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2">
                        <i class="nc-icon nc-zoom-split"></i> Social Search 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Buscar Mídias 
                    </h4>
                </div>
                <div class="col-md-6">
                    
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')            
            </div>
            <div class="col-md-12">
                <div class="card">
                    {!! Form::open(['id' => 'frm_search_page', 'class' => 'form-horizontal', 'url' => ['social-search/buscar']]) !!}
                        <div class="form-group m-3 w-70">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="__/__/____">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="__/__/____">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Termo">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 checkbox-radios">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox">
                                        <span class="form-check-sign"></span>
                                            Facebook Páginas
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox">
                                        <span class="form-check-sign"></span>
                                            Facebook Perfil
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox">
                                        <span class="form-check-sign"></span>
                                            Instagram
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox">
                                        <span class="form-check-sign"></span>
                                            Twitter
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mx-sm-3 mb-2 w-50">
                            <button type="submit" id="btn-find" class="btn btn-primary mb-3"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    {!! Form::close() !!} 
                </div>
            </div>   
        </div>
    </div>
</div> 
@endsection