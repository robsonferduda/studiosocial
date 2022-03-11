@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="card-title ml-2">
                        <i class="fa fa-commenting-o"></i> Transcrição
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Resultados 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> {{ $radio->emissora }} 
                    </h4>
                </div>
                <div class="col-md-4">
                    <a href="{{ url('transcricao') }}" class="btn btn-primary pull-right" style="margin-right: 12px;"><i class="fa fa-commenting-o"></i> Emissoras</a>
                    <a href="{{ url('transcricao/audios',$radio->pasta) }}" class="btn btn-warning pull-right" style="margin-right: 12px;"><i class="fa fa-file"></i> Arquivos</a>
                    <a href="{{ url('transcricao/baixar',$radio->pasta) }}" class="btn btn-success pull-right" style="margin-right: 12px;"><i class="fa fa-file-excel-o"></i> Baixar</a>
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
                        <th>Arquivo</th>
                        <th class="center">Tempo</th>
                        <th>Chaves</th>
                        <th class="center">Ocorrências</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Arquivo</th>
                        <th class="center">Tempo</th>
                        <th>Chaves</th>
                        <th class="center">Ocorrências</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($ocorrencias as $o)
                        <tr>
                            <td><a href="{{ asset('/audios/'.$radio->pasta.'/'.$o['arquivo']) }}" target="BLANK">{{ $o['arquivo'] }}</a></td>
                            <td class="center">{{ $o['tempo'] }}</td>
                            <td>
                                @for($i = 0; $i < count($o['ocorrencias']); $i++)
                                    <span class="badge badge-pill badge-default">{{ $o['ocorrencias'][$i] }}</span>
                                @endfor
                            </td>
                            <td class="center">{{ count($o['ocorrencias']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 
@endsection