@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2"><i class="nc-icon nc-ruler-pencil"></i> Nuvem de Palavras - Expressões Removidas</h4>
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
                        <th>Expressão Removida</th>                      
                        <th class="disabled-sorting text-center">Ações</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Expressão Removida</th>                      
                        <th class="disabled-sorting text-center">Ações</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($words_execption as $exception)                        
                        <tr>
                            <td>{{ $exception->word }}</td>                                                      
                            <td class="text-center">                               
                                <form class="form-delete" style="display: inline;" action="{{ url('nuvem-palavras/excecao/remove/'.$exception->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button title="Refazer" type="submit" class="btn btn-link btn-icon button-redo" title="Delete">
                                        <i class="fa fa-repeat"></i>
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
@endsection