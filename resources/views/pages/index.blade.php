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
                    <a href="{{ url('facebook-paginas/monitoramento') }}" class="btn btn-info pull-right" style="margin-right: 12px;"><i class="nc-icon nc-sound-wave"></i> Monitoramento</a>
                    <a href="{{ url('facebook-paginas/cadastrar') }}" class="btn btn-primary pull-right mr-2"><i class="fa fa-at"></i> Cadastrar</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            <div class="col-md-12">
                <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Página</th>
                            <th>URL</th>
                            <th class="center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $page)
                            <tr>
                                <td>{{$page->name}}</td>
                                <td><a href="{{ $page->url }}">{{ $page->url }}</a></td>                                
                                <td class="center">
                                    <button title="Associar Clientes" data-id="{{$page->id}}" data-clients="{{ implode(',',$page->clients()->pluck('clients.id')->toArray()) }}"  class="btn btn-primary btn-link btn-icon btn-connect-client"><i class="fa fa-list fa-2x"></i></button>
                                    <button title="Editar" data-id="{{$page->id}}"  class="btn btn-primary btn-link btn-icon btn-edit-page"><i class="fa fa-edit fa-2x"></i></button>
                                    <form class="form-delete" style="display: inline;" action="{{  route('facebook-pagina.destroy',$page->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button title="Excluir" type="submit" class="btn btn-danger btn-link btn-icon button-remove" title="Delete">
                                            <i class="fa fa-times fa-2x"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>  
                        @endforeach                                            
                    </tbody>
                </table>   
            </div>        
        </div>
    </div>
</div> 
<div class="modal fade modal-primary" id="modalPaginaCreate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel"><i class="fa fa-at"></i> <strong> Cadastrar Página do Facebook</strong></h5>
            </div>
            {!! Form::open(['id' => 'frm_page_create', 'url' => ['facebook-pagina']]) !!}                
                <div class="modal-body">
                    <div class="card-body">   
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Página <span class="text-danger">Obrigatório</span></label>
                                    <input required placeholder="Buscar Página" class="form-control name-autocomplete" name="name" id="name" >
                                </div>
                            </div>
                        </div>

                        {{--<div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nome da Página <span class="text-danger">Obrigatório</span></label>
                                    <input required type="text" class="form-control" name="name" id="name" value="">
                                </div>
                            </div>
                        </div>           
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>URL da Página <span class="text-danger">Obrigatório</span></label>
                                    <input required type="text" class="form-control" name="url" id="url" value="">
                                </div>
                            </div>
                        </div>    --}}              
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                    <button data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar</button>
                </div>
            {!! Form::close() !!} 
        </div>
    </div>
</div>

<div class="modal fade modal-primary" id="modalPaginaEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel"><i class="fa fa-at"></i> <strong> Editar Página do Facebook</strong></h5>
            </div>
            {!! Form::open(['id' => 'frm_page_edit', 'url' => ['facebook-pagina/atualizar']]) !!}       
                <input type="hidden" class="form-control" name="id" id="id" value="">       
                <div class="modal-body">
                    <div class="card-body">   
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Página <span class="text-danger">Obrigatório</span></label>
                                    <input required type="text" class="form-control name-autocomplete" name="name" id="name" value="">
                                </div>
                            </div>
                        </div>           
                        {{-- <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>URL da Página <span class="text-danger">Obrigatório</span></label>
                                    <input required type="text" class="form-control" name="url" id="url" value="">
                                </div>
                            </div>
                        </div>                    --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                    <button data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar</button>
                </div>
            {!! Form::close() !!} 
        </div>
    </div>
</div>

<div class="modal fade modal-primary" id="modalConnectClient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel"><i class="fa fa-list"></i> <strong> Associar Clientes</strong></h5>
            </div>
            {!! Form::open(['id' => 'frm_connect_client', 'url' => ['facebook-pagina/associar-cliente']]) !!}       
                <input type="hidden" class="form-control" name="id" id="id" value="">       
                <div class="modal-body">
                    <div class="card-body">   
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Clientes </label>
                                    <select class="select2 select_client" name="client[]" multiple="multiple" >
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>         
                                        @endforeach                                                                                                          
                                    </select>
                                </div>
                            </div>
                        </div>                                            
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                    <button data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar</button>
                </div>
            {!! Form::close() !!} 
        </div>
    </div>
</div>


@endsection
@section('script')
<script>
    $(document).ready(function() {     

        var availableTags = [
            {
                "nome":"Página Exemplo 1"
            },
            {
                "nome":"Página Exemplo 2"        
            },
            {
                "nome":"Página Exemplo 3"    
            }
        ];
        
        $(".name-autocomplete").autocomplete({
            source: function (request, response) {
            //data :: JSON list defined
                response($.map(availableTags, function (value, key) {                    
                    return {
                        label: value.nome,
                    }
                }));
            }
        });

        $( ".addresspicker" ).autocomplete( "option", "appendTo", ".eventInsForm" );

        var select = $('.select2').select2({
            tags: true,
            placeholder: 'Selecione um Cliente',           
        });

        $('.btn-connect-client').click(function(){
            
            id_clients = $(this).data('clients').toString().split(",");

            select.val(id_clients).trigger('change');   

            $('#modalConnectClient #id').val($(this).data("id"));                        
            $('#modalConnectClient').modal('show');  
        
        });

        $('.btn-edit-page').click(function(){
            var url = "facebook-pagina";
            var page_id= $(this).data('id');
            $.get(url + '/' + page_id, function (data) {
                //success data
                $('#modalPaginaEdit #id').val(data.id);
                $('#modalPaginaEdit #name').val(data.name);
                $('#modalPaginaEdit #url').val(data.url);
                
                $('#modalPaginaEdit').modal('show');
            }) 
        });
    });
</script>

@endsection