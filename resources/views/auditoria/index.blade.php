@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2"><i class="fa fa-shield"></i> Auditoria</h4>
                </div>
                <div class="col-md-6">
                   
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Nível</th>
                            <th>Usuário</th>
                            <th>Operação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($audits as $audit)
                            <tr>
                                <td>{{ date('d/m/Y H:i:s', strtotime($audit->created_at)) }}</td>
                                <td>
                                    @forelse($audit->user->roles()->get() as $role)
                                        <span class="badge badge-{{ $role->display_color }}">{{ $role->display_name }}</span>
                                    @empty
                                        Nenhum perfil associado
                                    @endforelse
                                </td>
                                <td>{{ $audit->user->name }}</td>
                                <td>{{ $audit->event }}</td>
                                <td>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>   
            </div>        
        </div>
    </div>
</div> 
@endsection