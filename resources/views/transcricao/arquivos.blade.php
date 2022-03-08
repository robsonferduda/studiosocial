@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-9">
                    <h4 class="card-title ml-2">
                        <i class="fa fa-commenting-o"></i> Transcrição
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Arquivos 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> {{ $radio->emissora }} 
                    </h4>
                </div>
                <div class="col-md-3">
                    <a href="{{ url('transcricao') }}" class="btn btn-primary pull-right" style="margin-right: 12px;"><i class="fa fa-commenting-o"></i> Emissoras</a>
                    <a href="{{ url('transcricao/processar',$radio->pasta) }}" class="btn btn-warning pull-right" style="margin-right: 12px;"><i class="fa fa-cogs"></i> Processar</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Arquivos</th>
                        <th class="center">Duração</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Arquivos</th>
                        <th class="center">Duração</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($lista as $file)
                        <tr>
                            <td>{{ $file->getFilename() }}</td>
                            <td class="center">{{ ($file->tempo == '00:00:00') ? '00:00:30' : $file->tempo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 
@endsection