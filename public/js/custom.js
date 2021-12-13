$(document).ready(function() {

    $('#nu_cpf_par').mask('000.000.000-00');
    $('.dt_inicial_relatorio').mask('00/00/0000',{ "placeholder": "dd/mm/YYYY" });
    $('.dt_final_relatorio').mask('00/00/0000',{ "placeholder": "dd/mm/YYYY" });
   
    $('body').on("click", ".button-remove-hashtag", function(e) {
        e.preventDefault();
        var form = $(this).closest("form");
        Swal.fire({
            title: "Tem certeza que deseja excluir a hashtag?",
            text: "Essa ação irá excluir a hashtag, mas não o conteúdo relacionado a ela durante a coleta.",
            type: "warning",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            confirmButtonText: "Sim, excluir!",
            cancelButtonText: "Cancelar"
        }).then(function(result) {
            if (result.value) {
                form.submit();
            }
        });
    }); 

    $('body').on("click", ".button-remove", function(e) {
        e.preventDefault();
        var form = $(this).closest("form");
        Swal.fire({
            title: "Tem certeza que deseja excluir?",
            text: "Você não poderá recuperar o registro excluído",
            type: "warning",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            confirmButtonText: "Sim, excluir!",
            cancelButtonText: "Cancelar"
        }).then(function(result) {
            if (result.value) {
                form.submit();
            }
        });
    }); 

    $('body').on("click", ".button-remove-evento", function(e) {
        e.preventDefault();
        var link = $(this).attr('href');

        Swal.fire({
            title: "Tem certeza que deseja remover a participação neste evento?",
            text: "Você não poderá recuperar o registro excluído",
            type: "warning",
            icon: "warning",
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                  return 'You need to write something!'
                }
            },
            confirmButtonColor: "#28a745",
            confirmButtonText: "Sim, excluir!",
            cancelButtonText: "Cancelar"
        }).then(function(result) {
            if (result.value) {
                window.location.href = link;
            }
        });
    });

    var host =  $('meta[name="base-url"]').attr('content');

    var inputOptionsPromise = new Promise(function (resolve) {
        
        var options = {};
        $.ajax({
            url: host+'/cliente/get/json',
            type: 'GET',
            success: function(response) {

                $.map(response,
                    function(o) {
                        options[o.id] = o.name;
                    });

                resolve(options)               
            }
        });
    });

    $('body').on("click", ".config_periodo", function(e) {

        var periodo_atual = $(".periodo_atual").text();
        e.preventDefault();
        Swal.fire({
            title: "Informe o período em dias",
            text: "Digite ou selecione um valor",
            input: 'number',
            inputValue: periodo_atual,
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                  return 'Você precisa informar um valor para o período'
                }
            },
            confirmButtonColor: "#28a745",
            confirmButtonText: '<i class="fa fa-check"></i> Confirmar',
            cancelButtonText: '<i class="fa fa-times"></i> Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {

                var periodo = $(".swal2-input").val();

                $.ajax({
                    url: host+'/configuracoes/periodo/selecionar',
                       type: 'POST',
                       data: {
                            "_token": $('meta[name="csrf-token"]').attr('content'),
                            "periodo": periodo
                    },
                    success: function(response) {
                        window.location.reload();                                
                    },
                    error: function(response){
                        console.log(response);
                    }
                });
            }
        });
    });

    $('body').on("click", ".config_cliente", function(e) {
        var cliente_atual = $(".periodo_atual").text();
        e.preventDefault();
        Swal.fire({
            title: "Selecione um cliente",
            input: 'select',
            inputValue: cliente_atual,
            inputOptions: inputOptionsPromise,
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            confirmButtonText: '<i class="fa fa-check"></i> Confirmar',
            cancelButtonText: '<i class="fa fa-times"></i> Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {

                var cliente = $(".swal2-select").val();

                $.ajax({
                    url: host+'/configuracoes/cliente/selecionar',
                       type: 'POST',
                       data: {
                            "_token": $('meta[name="csrf-token"]').attr('content'),
                            "cliente": cliente
                    },
                    success: function(response) {
                        window.location.reload();                                
                    },
                    error: function(response){
                        console.log(response);
                    }
                });
            }
        });
    });

    $('body').on("click", ".troca_cliente", function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Selecione um cliente",
            input: 'select',
            inputOptions: inputOptionsPromise,
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            confirmButtonText: "Confirmar",
            cancelButtonText: "Cancelar"
        }).then(function(result) {
            if (result.isConfirmed) {

                var cliente = $(".swal2-select").val();

                $.ajax({
                    url: host+'/cliente/selecionar',
                       type: 'POST',
                       data: {
                            "_token": $('meta[name="csrf-token"]').attr('content'),
                            "cliente": cliente
                    },
                    success: function(response) {
                        window.location.reload();                                
                    },
                    error: function(response){
                        console.log(response);
                    }
                });
            }
        });
    });
    
    $("#is_password").change(function(){

        if($(this).is(':checked'))
            $('.box-password').css("display","block");
        else
            $('.box-password').css("display","none");

    });

    $(".load_expression").change(function(){

        var expression = $('#regra option').filter(':selected').data('expression');
        $(".display_regra").html(expression);
    });

    var dataFinal = new Date();
    var dataInicial = new Date();    
    dataInicial.setDate(dataFinal.getDate() - 7);

    $(".dt_inicial_relatorio").val(formataData(dataInicial));
    $(".dt_final_relatorio").val(formataData(dataFinal));

    $(".label_data_inicial").val(formataData(dataInicial));
    $(".label_data_final").val(formataData(dataInicial));    

    function formataData(data)
    {
        var dia = String(data.getDate()).padStart(2, '0');
        var mes = String(data.getMonth() + 1).padStart(2, '0');
        var ano = data.getFullYear();

        return dia + '/' + mes + '/' + ano;
    }
    
});