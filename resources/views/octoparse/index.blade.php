@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="card-title">
                        <i class="fa fa-plug ml-3"></i> Octoparse 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Importação de Dados
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
                <div class="col-lg-12 col-sm-12 ml-3">
                    <h6>Coletas realizadas hoje</h6>
                    @forelse($coletas as $key => $coleta)
                        <p>{{ $coleta->created_at }}:<strong>{{ $coleta->total_coletado }}</strong></p>
                    @empty
                        <p class="text-danger">Nenhuma coleta realizada hoje</p>
                    @endforelse
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    {!! Form::open(['id' => 'frm-pautas', 'class' => 'form-horizontal', 'url' => ['octoparse/importar']]) !!}
                        <div class="form-group m-3 w-70">
                            <div class="row">
                                
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

        });
    </script>
@endsection