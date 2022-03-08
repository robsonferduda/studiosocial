@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2">
                        <i class="fa fa-commenting-o"></i> Transcrição
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> Emissoras 
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
            <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Cidade</th>
                        <th>Emissora</th>
                        <th>Situação</th>
                        <th class="disabled-sorting text-center">Ações</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Cidade</th>
                        <th>Emissora</th>
                        <th>Situação</th>
                        <th class="disabled-sorting text-center">Ações</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($emissoras as $e)
                        <tr>
                            <td>{{ $e->cidade }}</td>
                            <td>{{ $e->emissora }}</td>
                            <td>
                                @switch($e->situacao)
                                    @case('Processada')
                                        <span class="badge badge-success">{{ $e->situacao }}</span>
                                        @break
                                    @case('Pendente')
                                        <span class="badge badge-danger">{{ $e->situacao }}</span>
                                        @break
                                    @case('Em andamento')
                                        <span class="badge badge-warning">{{ $e->situacao }}</span>
                                        @break                                        
                                @endswitch
                            </td>
                            <td class="text-center">
                                @if($e->pasta)
                                    <a title="Arquivos" href="{{ url('transcricao/audios', $e->pasta) }}"><i class="fa fa-file-audio-o font-25" aria-hidden="true"></i></a>
                                @else
                                    <span class="icone-disabled"><i class="fa fa-file-audio-o font-25"></i></span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 
@endsection