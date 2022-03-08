@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-9">
                    <h4 class="card-title ml-2">
                        <i class="fa fa-commenting-o"></i> Transcrição
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Resultados 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> {{ $radio->emissora }} 
                    </h4>
                </div>
                <div class="col-md-3">
                    <a href="{{ url('transcricao') }}" class="btn btn-primary pull-right" style="margin-right: 12px;"><i class="fa fa-commenting-o"></i> Emissoras</a>
                    <a href="{{ url('transcricao/audios',$radio->pasta) }}" class="btn btn-warning pull-right" style="margin-right: 12px;"><i class="fa fa-file"></i> Arquivos</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            
        </div>
    </div>
</div> 
@endsection