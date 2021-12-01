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
                            <label>Todas essas expressões <span class="text-danger"></span></label>
                            <input type="text" class="form-control tags" name="todas" value="{{ old('name') }}">
                        </div>
                    </div>
                </div>  
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Algumas dessas expressões <span class="text-danger"></span></label>
                            <input type="text" class="form-control tags" name="algumas" value="{{ old('name') }}">
                        </div>
                    </div>
                </div>  
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nenhuma dessas expressões <span class="text-danger"></span></label>
                            <input type="text" class="form-control tags" name="nenhuma" value="{{ old('name') }}">
                        </div>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-6">
                            <h4>Expressão:</h4>
                            <p id='expressao' ></p>
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

        let expressoes = [];
        expressoes['todas'] = '';
        expressoes['algumas'] = '';
        expressoes['nenhuma'] = '';

        $('.tags').inputTags({
            max: 8,
            minLength: 3,
            maxLength: 100,
            change: function($elem) {   

               let expressao = $('#expressao');

                if($elem[0].name == 'todas') {
                    if($elem.tags.length > 0) {
                        expressoes['todas'] = 'Todas essas expressões ( '+$elem.tags.join(' , ')+' )';
                    } else {
                        expressoes['todas'] = '';
                    }
                }

                if($elem[0].name == 'algumas') {

                    if($elem.tags.length > 0) {
                        expressoes['algumas'] = 'Algumas dessas expressões ( '+$elem.tags.join(' , ')+' )';
                    } else {
                        expressoes['algumas'] = '';
                    }
                    
                }

                if($elem[0].name == 'nenhuma') {
                    if($elem.tags.length > 0) {
                        expressoes['nenhuma'] = 'Nenhuma dessas expressões ( '+$elem.tags.join(' , ')+' )';
                    } else {
                        expressoes['nenhuma'] = '';
                    }
                }

                conector = ''; 
                expressao.text(''); 

                expressao.text(expressao.text() + expressoes['todas']);

                if(expressoes['todas'] != '' && expressoes['algumas'] != '') {
                    conector = ' OU ';
                }

                expressao.text(expressao.text() + conector + expressoes['algumas']);

                conector = ''; 
                if((expressoes['todas'] != '' || expressoes['algumas'] != '') && expressoes['nenhuma'] != '') {
                    conector = ' E ';
                }

                expressao.text(expressao.text() + conector + expressoes['nenhuma']);

                console.log(expressoes);             
                //console.log($elem.tags);
            },
        });

        $(document).on('keypress',function(e) {
            if(e.which == 13) {
                return false;
            }
        });
    });

     
</script>
   
@endsection