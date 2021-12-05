@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-5 col-md-4">
                            <div class="icon-big text-center icon-warning">
                                <i class="nc-icon nc-briefcase-24 text-info"></i>
                            </div>
                        </div>
                        <div class="col-7 col-md-8">
                            <div class="numbers">
                                <p class="card-category">Clientes</p>
                                <p class="card-title"><a href="{{ url('clientes') }}">{{ $clientes }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <a href="{{ url('clientes/create') }}"><i class="fa fa-plus text-info"></i> Novo</a>                
                        </div>
                </div>
            </div>
        </div>  
        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-5 col-md-4">
                            <div class="icon-big text-center icon-warning">
                                <i class="fa fa-hashtag text-warning"></i>
                            </div>
                        </div>
                        <div class="col-7 col-md-8">
                            <div class="numbers">
                                <p class="card-category">Hashtags Ativas</p>
                                <p class="card-title"><a href="#">{{ count($hashtags) }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer ">
                    <hr>
                    <span class="text-info"><i class="fa fa-clock-o"></i> Atualizado em {{ date('d/m/Y H:i:s') }}</span>
                </div>
            </div>
        </div>  
        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-5 col-md-4">
                            <div class="icon-big text-center icon-warning">
                                <i class="fa fa-font"></i>
                            </div>
                        </div>
                        <div class="col-7 col-md-8">
                            <div class="numbers">
                                <p class="card-category">Termos Ativos</p>
                                <p class="card-title"><a href="#">{{ count($terms) }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer ">
                    <hr>
                    <span class="text-info"><i class="fa fa-clock-o"></i> Atualizado em {{ date('d/m/Y H:i:s') }}</span>
                </div>
            </div>
        </div>      
    </div> 
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="card card-stats">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <h6 class="mt-2 mb-3">Termos Ativos</h6>
                            @foreach($terms as $term)
                                <p>{{ $term->term }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>   
  
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="card card-stats">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <h6 class="mt-2 mb-3">Hashtags Ativas</h6>
                            @foreach($hashtags as $hashtag)
                                <p>#{{ $hashtag->hashtag }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>   

        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="card card-stats">
                <div class="card-body ">
                    <div class="row">
                        <div id='cloud' style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function() {

        $('body').loader('show');

        var APP_URL = {!! json_encode(url('/')) !!}

        fetch(APP_URL+'/nuvem-palavras/words', {
            method: 'GET', 
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        }).then(function(response) {
            return response.json();
            //words = JSON.stringify(words);

        }).then(function(response){
            
            let words = [];

            $('body').loader('hide');

            Object.entries(response).forEach(element => {
                words.push(
                    {
                        text: element[0], 
                        weight: element[1],
                        html: {
                            class: 'cloud-word'
                        },
                        handlers: {
                            click: function(e) {
                                for( var i = 0; i < words.length; i++){ 
                                   if (words[i].text === this.textContent) { 
                                        words.splice(i, 1); 
                                       break; 
                                   }
                               }

                               $('#cloud').jQCloud('update', words);

                            }
                        },
                        
                    }
                );
            });

            let cloud = $('#cloud').jQCloud(words, {
                fontSize: function (width, height, step) {
                    if (step == 1)
                    return width * 0.01 * step + 'px';

                    return width * 0.009 * step + 'px';
                }
            });            
        });
    });
</script>
@endsection