@extends('layouts.app')
@section('content')
<div class="col-md-12">
    {!! Form::open(['id' => 'frm_client_create', 'url' => ['client']]) !!}
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title ml-2"><i class="nc-icon nc-briefcase-24"></i> <a href="{{ url('regra') }}">Regra</a> > Nova</h4>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ url('clientes') }}" class="btn btn-primary pull-right" style="margin-right: 12px;"><i class="fa fa-table"></i> Regras</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @include('layouts.mensagens')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Todas as palavras <span class="text-danger"></span></label>
                            <input type="text" class="form-control" name="name" id="tags" placeholder="Ex: bitcoin, HSBC, Mercado Livre" value="{{ old('name') }}">
                        </div>
                    </div>
                </div>  
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Senha <span class="text-danger">Obrigatório</span></label>
                            <input type="password" class="form-control" name="password" id="password" value="{{ old('password') }}">
                        </div>
                    </div>
                </div>  
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check mt-3">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" {{ ( old('is_active')) ? 'checked' : '' }} type="checkbox" name="is_active" value="true">
                                    CADASTRO ATIVO
                                    <span class="form-check-sign"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>          
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                <a href="{{ url('usuarios') }}" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar</a>
            </div>
        </div>
    {!! Form::close() !!} 
</div> 
@endsection
@section('script')
<script>

    $(document).ready(function(){
        $('#tags').inputTags({
            max: 8
        });
    });

     
</script>
   
@endsection