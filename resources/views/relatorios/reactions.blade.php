@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title"><i class="fa fa-smile-o"></i> Relatórios <i class="fa fa-angle-double-right" aria-hidden="true"></i> Reactions</h4>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('relatorios') }}" class="btn btn-info pull-right"><i class="nc-icon nc-chart-bar-32"></i> Relatórios</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('layouts/regra')
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            @foreach ($reactions as $reaction)
                                <div class="total_reactions">
                                    <span>{{ $reaction->icon }}</span><br>
                                    <span>45</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>            
            </div>
        </div>
    </div>
@endsection