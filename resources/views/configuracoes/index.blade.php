@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2"><i class="fa fa-cog"></i> Configurações </h4>
                </div>
                <div class="col-md-6">
                    
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            <table class="table">
                <thead>
                  <tr>
                    <th>Item</th>
                    <th>Valor</th>
                    <th>Opções</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($configs as $key => $conf)
                        <tr>
                            <td>{{ $conf->key }}</td>
                            <td>{{ $conf->value }}</td>
                            <td><a title="Editar" href="" class="btn btn-primary btn-link btn-icon"><i class="fa fa-edit fa-2x"></i></a></td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
           
        </div>
    </div>
</div> 
@endsection