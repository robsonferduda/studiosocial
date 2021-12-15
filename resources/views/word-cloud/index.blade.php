@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div id='cloud' style="height: 800px;"></div>
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
                autoResize: true,
                colors: ["#66C2A5", "#FC8D62", "#8DA0CB", "#E78AC3", "#A6D854", "#FFD92F", "#E5C494", "#B3B3B3"],
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