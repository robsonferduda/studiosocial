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
                    <a href="{{ url('facebook-paginas') }}" class="btn btn-info pull-right" style="margin-right: 12px;"><i class="fa fa-at"></i> Facebook Páginas</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                @include('layouts.mensagens')
            </div>
            <div class="col-md-12">
                <div class="card">
                    <form class="form-inline">
                        <div class="form-group mx-sm-3 mb-2 w-50">
                          <label for="termo" class="sr-only">Termo</label>
                          <input type="text" class="form-control w-100" name="termo" id="termo" placeholder="Digite um termo para buscar">
                          <input type="hidden" class="form-control w-100" name="pagination-termo" id="pagination-termo" >
                        </div>
                        <button type="button" id="btn-find" class="btn btn-primary mb-3"><i class="fa fa-search"></i> Buscar</button>
                    </form>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row mb-5">
                    <div class="col-md-12 box-paginas">
                        {{-- <ul class="pagination justify-content-between" style="display: none">
                            <li class="page-item" style="font-size:20px">
                                <a href="#" class='previou' data-previou="" rel="prev" aria-label="@lang('pagination.previous')"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>                                
                            </li>
                            <li class="page-item" style="font-size:20px">
                                <a href="#" class='next' data-next="" rel="next" aria-label="@lang('pagination.next')"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                            </li>
                        </ul> --}}
                        <div class="user-dashboard-info-box mb-0 bg-white p-4 shadow-sm">
                            <table class="table table-paginas manage-candidates-top mb-0">
                                <thead>
                                <tr>
                                    <th>Página</th>
                                    <th class="action text-center">Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                                                 
                                </tbody>
                            </table>
                        </div>
                        <ul class="pagination justify-content-between" style="display: none">
                            <li class="page-item" style="font-size:20px">
                                <a href="#" class='previou' data-previou="" rel="prev" aria-label="@lang('pagination.previous')"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>                                
                            </li>
                            <li class="page-item" style="font-size:20px">
                                <a href="#" class='next' data-next="" rel="next" aria-label="@lang('pagination.next')"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection
@section('script')
<script>
    $(document).ready(function() {  

        $(document).on('keypress',function(e) {
            if(e.which == 13) {
                $("#btn-find").trigger('click');
                return false;
            }
        });

        let success = function(response) {
            $(".table-paginas tbody tr").empty();

            response = JSON.parse(response)

            console.log(response);

            if(response.limit_exceeded == true) {                                    
                Swal.fire({
                    icon: 'warning',
                    title: 'Limite da API Excedido',
                    confirmButtonColor: "#28a745",
                    confirmButtonText: '<i class="fa fa-close"></i> Fechar',
                    html: 'Limite de uso da API atingido. Tente novamente mais tarde.'
                });

                return;
            }

            $(".previou").data('previou',response.info.before);
            $(".next").data('next',response.info.after);
            $("#pagination-termo").val(response.info.query);

            if (response.info.after || response.info.before ) {
                $('.pagination').css('display', 'flex');
            } else {
                $('.pagination').css('display', 'none');
            }

            $.each(response.data, function(index, value) {                          
                table(value);                    
            }); 
        }

        let table = function(value) {

            let button = (value.registered == false) ? '<button type="button" data-picture='+value.picture+' data-id='+value.id+' class="btn btn-sm btn-primary btn-cadastrar"><span class="btn-label"><i class="fa fa-plus"></i></span> Adicionar </button>' : '<span class="badge badge-success"><i class="fa fa-check"></i> Página Cadastrada</span>' ;                        

            $(".table-paginas tbody").append(
                '<tr class="candidates-list">' +
                    '<td class="title">' +
                        '<div class="thumb">' +
                            '<img class="img-fluid" src="'+value.picture+'" alt="">' +
                        '</div>' +
                        '<div class="candidate-list-details">' +
                              '<div class="candidate-list-info">' +
                                    '<div class="candidate-list-title">' +
                                        '<h5 class="mb-0">' +
                                            '<a class="action" href="'+value.link+'" target="BLANK">'+value.name+'</a>' +
                                        '</h5></div><div class="candidate-list-option"><ul class="list-unstyled"><li>'+value.category+'</li></ul>'+                                               
                                         '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</td>' +                                        
                    '<td class="text-center box-btn">'+button+'</td>' +
                '</tr>');  
        }

        
        var host =  $('meta[name="base-url"]').attr('content');
        var token = $('meta[name="csrf-token"]').attr('content');

        $("#btn-find").click(function(){

            var termo = $("#termo").val();

            $('.box-paginas').loader('show');
        
            $.ajax({
                url: host+'/facebook-pagina/buscar',
                type: 'POST',
                data: { "_token": token,
                        "termo": termo,
                        "after": ''                        
                    },
                success: function(response) {
                    $('.box-paginas').loader('hide');
                    success(response);    
                },
                error: function(response){
                    
                    if(response.status){
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro ao buscar dados',
                            confirmButtonColor: "#28a745",
                            confirmButtonText: '<i class="fa fa-close"></i> Fechar',
                            html: 'Entre em contato com o suporte e informe o seguinte código de erro: <strong>500</strong>'
                        })
                    }
                }
            }); 
        });  

        $("body").on("click", ".btn-cadastrar", function(){

            var name = $(this).closest("tr").find(".action").text();
            var link = $(this).closest("tr").find(".action").attr('href');
            let id = $(this).data('id');
            let picture = $(this).data('picture');
            let button = $(this);
            let row = $(this).closest("tr").find(".box-btn");

            $.ajax({
                url: host+'/facebook-pagina',
                type: 'POST',
                data: { "_token": token,
                        "name": name,
                        "link": link,
                        "id": id,
                        "picture": picture
                        },
                success: function(response) {
                    response = JSON.parse(response)
                    if(response.flag) {
                        button.remove();
                        row.html('<span class="badge badge-success"><i class="fa fa-check"></i> Página Cadastrada</span>');
                    }
                }
            });
        });


        $(".previou").click(function(event){

            event.preventDefault();

            var termo = $("#pagination-termo").val();
            var previou = $(this).data('previou');
            $('.box-paginas').loader('show');

            $.ajax({
                url: host+'/facebook-pagina/buscar',
                type: 'POST',
                data: { "_token": token,
                        "termo": termo,
                        "before": previou,
                        "after": ''                         
                    },
                success: function(response) {
                    success(response);  
                    $('.box-paginas').loader('hide');
                },
                error: function(response){
                    $('.box-paginas').loader('hide');
                    if(response.status){
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro ao buscar dados',
                            confirmButtonColor: "#28a745",
                            confirmButtonText: '<i class="fa fa-check"></i> Enviar',
                            html: 'Entre em contato com o suporte e informe o seguinte código de erro: <strong>500</strong>'
                        })
                    }
                }
            }); 
        });

        $(".next").click(function(event){

            event.preventDefault();

            var termo = $("#pagination-termo").val();
            var next = $(this).data('next');
            $('.box-paginas').loader('show');

            $.ajax({
                url: host+'/facebook-pagina/buscar',
                type: 'POST',
                data: { "_token": token,
                        "termo": termo,
                        "before": '',
                        "after": next                         
                    },
                success: function(response) {
                    success(response);  
                    $('.box-paginas').loader('hide');
                },
                error: function(response){
                    $('.box-paginas').loader('hide');
                    if(response.status){
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro ao buscar dados',
                            confirmButtonColor: "#28a745",
                            confirmButtonText: '<i class="fa fa-check"></i> Enviar',
                            html: 'Entre em contato com o suporte e informe o seguinte código de erro: <strong>500</strong>'
                        })
                    }
                }
            }); 
        });

    });
</script>
@endsection