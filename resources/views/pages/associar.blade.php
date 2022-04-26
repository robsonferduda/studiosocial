@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2"><i class="fa fa-at"></i> Páginas do Facebook</h4>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('facebook-paginas') }}" class="btn btn-info pull-right" style="margin-right: 12px;"><i class="nc-icon nc-sound-wave"></i> Páginas</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            <div class="col-md-12">
                {!! Form::open(['id' => 'frm_connect_client', 'url' => ['facebook-pagina/associar-cliente']]) !!}
                    <div class="row">
                        <div class="col-md-6"> 
                            <h6 class="mb-3"><i class="nc-icon nc-briefcase-24"></i> SELECIONE OS CLIENTES</h6>     
                            <select multiple id="clientes" class="form-control listagem_clientes" name="clientes[]" style="height: 250px;">
                                @foreach($clientes as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>      
                        </div>
                        <div class="col-md-6">    
                            <h6 class="mb-3"><i class="fa fa-at"></i> SELECIONE AS PÁGINAS</h6> 
                            <select multiple id="paginas" class="form-control listagem_paginas" name="paginas[]" style="height: 250px;">
                                @foreach($paginas as $page)
                                    <option value="{{ $page->id }}">{{ $page->name }}</option>
                                @endforeach
                            </select>           
                        </div>
                        <div class="col-md-12 mt-3 center"> 
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar Dados</button>
                        </div>
                    </div>
                {!! Form::close() !!} 
            </div>
        </div>
    </div>
</div> 
@endsection
@section('script')
<script>
    $(document).ready(function() {     
        var duallistboxClientes = $('.listagem_clientes').bootstrapDualListbox({
            nonSelectedListLabel: 'Clientes Disponíveis',
            selectedListLabel: 'Clientes Selecionados',
            infoText: 'Mostrando {0} registros',
            filterTextClear: 'Mostrar Todos',
            infoTextFiltered: '<span class="label label-warning">Filtrados</span> {0} de {1}',
            infoTextEmpty: 'Não há registros',
            filterPlaceHolder: 'Filtrar Clientes',
            moveSelectedLabel: 'Mover Clientes Selecionadas',
            moveAllLabel: 'Mover Todos Clientes',
            removeSelectedLabel: 'Remover Clientes Selecionadas',
            removeAllLabel: 'Remover Todas Clientes',
            //preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
            iconsPrefix: 'fa',
            iconMove: 'fa-check'
        });

        var duallistboxPaginas = $('.listagem_paginas').bootstrapDualListbox({
            nonSelectedListLabel: 'Páginas Disponíveis',
            selectedListLabel: 'Páginas Selecionadas',
            infoText: 'Mostrando {0} registros',
            filterTextClear: 'Mostrar Todos',
            infoTextFiltered: '<span class="label label-warning">Filtrados</span> {0} de {1}',
            infoTextEmpty: 'Não há registros',
            filterPlaceHolder: 'Filtrar Páginas',
            moveSelectedLabel: 'Mover Páginas Selecionadas',
            moveAllLabel: 'Mover Todas Páginas',
            removeSelectedLabel: 'Remover Páginas Selecionadas',
            removeAllLabel: 'Remover Todas Páginas',
            //preserveSelectionOnMove: 'moved',
            moveOnSelect: false
        });
    });
</script>
@endsection