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


        fetch('/public/nuvem-palavras/words', {
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

        // var words = [
        //     {text: "Lorem", weight: 13},
        //     {text: "Ipsum", weight: 10.5},
        //     {text: "Dolor", weight: 9.4},
        //     {text: "Sit", weight: 8},
        //     {text: "Amet", weight: 6.2},
        //     {text: "Consectetur", weight: 5},
        //     {text: "Adipiscing", weight: 5},
        // ];
        
        // let cloud = $('#cloud').jQCloud(words, {
            
        // });

        //console.log(cloud);
    });
</script>
@endsection