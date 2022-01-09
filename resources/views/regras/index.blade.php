@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2"><i class="nc-icon nc-ruler-pencil"></i> Regras</h4>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('regras/create') }}" class="btn btn-primary pull-right" style="margin-right: 12px;"><i class="fa fa-plus"></i> Novo</a>
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
                      <th>Nome</th>
                      <th>Todas Expressões</th>
                      <th>Algumas Expressões</th>
                      <th>Nenhuma Expressão</th>
                      <th>Situação</th>
                      <th class="disabled-sorting text-center">Ações</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Nome</th>
                        <th>Todas Expressões</th>
                        <th>Algumas Expressões</th>
                        <th>Nenhuma Expressão</th>
                        <th>Situação</th>
                        <th class="disabled-sorting text-center">Ações</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($rules as $rule)
                        <tr>
                            <td>{{ $rule->name }}</td>
                            <td>{{ implode(', ', $rule->expressionsType(App\Enums\TypeRule::TODAS)->pluck('expression')->toArray()) }}</td>
                            <td>{{ implode(', ',  $rule->expressionsType(App\Enums\TypeRule::ALGUMAS)->pluck('expression')->toArray()) }}</td>
                            <td>{{ implode(', ', $rule->expressionsType(App\Enums\TypeRule::NENHUMA)->pluck('expression')->toArray()) }}</td>  
                            <td class="text-center">
                                @if($rule->fl_process)
                                    <span class="badge badge-success">Processada</span>
                                @else
                                    <span class="badge badge-warning">Pendente</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a title="Editar" href="{{ route('regras.edit',$rule->id) }}" class="btn btn-primary btn-link btn-icon"><i class="fa fa-edit fa-2x"></i></a>
                                <form class="form-delete" style="display: inline;" action="{{ route('client.destroy',$rule->id) }}" method="POST">
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
@endsection