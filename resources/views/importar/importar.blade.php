@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="card-title">
                        <i class="fa fa-download ml-3"></i> Importação 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Importar Dados Knewin
                    </h4>
                </div>
                <div class="col-md-4">
                    
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    {!! Form::open(['id' => 'frm-pautas', 'class' => 'form-horizontal', 'url' => ['importar/processar']]) !!}
                        <div class="form-group m-3 w-70">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label>Rede Social <span class="text-danger">Obrigatório</span></label>
                                        <select class="form-control select2" name="cliente" id="cliente" required="required">
                                            <option value="">Selecione uma rede social</option>
                                            <option value="twitter">Twitter (X)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label>Cliente <span class="text-danger">Obrigatório</span></label>
                                        <select class="form-control select2" name="cliente" id="cliente" required="required">
                                            <option value="">Selecione um cliente</option>
                                            @foreach($clientes as $cliente)
                                                <option value="{!! $cliente->id !!}">{!! $cliente->name !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="arquivo">Planilha de Dados <span class="text-info">Dados originados da Knewin</span></label>
                                    <div style="min-height: 100px;" class="dropzone" id="dropzone"><div class="dz-message" data-dz-message><span>CLIQUE AQUI<br/> ou <br/>ARRASTE</span></div></div>
                                    <input type="hidden" name="arquivo" id="arquivo">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 center">
                                    <button type="submit" id="btn-find" class="btn btn-primary mt-4"><i class="fa fa-download"></i> Importar</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>               
               
            </div>
        </div>
    </div>
</div> 
@endsection
@section('script')
    <script>

        Dropzone.autoDiscover = false;

        $(document).ready(function() { 

            

            var token = $('meta[name="csrf-token"]').attr('content');
            var host =  $('meta[name="base-url"]').attr('content');

            $(".dropzone").dropzone({ 
                acceptedFiles: ".xls, .xlsx",
                init: function() { 
                    myDropzone = this;                   
                },
                maxFiles: 1,
                url: host+"/importar/upload",
                headers: {
                    'x-csrf-token': token,
                },
                success: function(file, responseText){
                    $("#arquivo").val(responseText.arquivo);
                }
            });
            
        });
    </script>
@endsection