@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2"><i class="nc-icon nc-lock-circle-open"></i> Permissões</h4>
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
                        <th>Permissão</th>
                        <th>Descrição</th>
                        <th>Opções</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Permissão</th>
                        <th>Descrição</th>
                        <th>Opções</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($permissions as $p)
                        <tr>
                            <td>{{ $p->display_name }}</td>
                            <td>{{ $p->description }}</td>
                            <td>
                                <a title="Usuários Habilitados" href="" class="btn btn-primary btn-link btn-icon"><i class="nc-icon nc-lock-circle-open"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 
@endsection