<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="base-url" content="{{ env('BASE_URL') }}">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link href="images/favicon.png" rel="shortcut icon">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>{{ config('app.name', 'Studio K Sistema de Gerenciamento de Eventos') }}</title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <!-- CSS Files -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/paper-dashboard.css?v=2.0.1') }}" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="{{ asset('css/list.css') }}" rel="stylesheet" />
  <link href="{{ asset('demo/demo.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/schedule.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/croppie.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/jqcloud.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/jquery.loader.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/inputTags.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/bootstrap-multiselect.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/bootstrap-duallistbox.css') }}" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
  @yield('style')
</head>

<body class="">
    <div class="wrapper ">
        <div class="sidebar" data-color="white" data-active-color="danger">
            <div class="logo">
                <a style="padding-left: 8px;" href="{{ url('perfil') }}" class="simple-text logo-normal">
                  <i class="fa fa-user"></i> {{ (Auth::user()) ? explode(" ", Auth::user()->name)[0] : 'Não identificado' }}
                </a>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                <li class="{{ (Session::has('url') and Session::get('url') == 'home') ? 'active' : '' }}">
                    <a href="{{ url('/') }}">
                    <i class="nc-icon nc-chart-pie-36"></i>
                    <p>DASHBOARD</p>
                    </a>
                </li>
                @role('administradores')
                  <li class="{{ (Session::has('url') and Session::get('url') == 'clientes') ? 'active' : '' }}">
                    <a href="{{ url('clientes') }}">
                    <i class="nc-icon nc-briefcase-24"></i>
                    <p>CLIENTES</p>
                    </a>
                  </li>
                @endrole
                @role('cliente')
                  <li class="{{ (Session::has('url') and Session::get('url') == 'contas') ? 'active' : '' }}">
                    <a href="{{ url('cliente/contas') }}">
                    <i class="fa fa-comments"></i>
                    <p>CONTAS</p>
                    </a>
                  </li>
                @endrole
                @role('administradores')
                  <li class="{{ (Session::has('url') and Session::get('url') == 'monitoramento') ? 'active' : '' }}">
                    <a href="{{ url('monitoramento') }}">
                    <i class="nc-icon nc-sound-wave"></i>
                    <p>MONITORAMENTO</p>
                    </a>
                  </li>
                @endrole
                @permission('notificacoes')
                <li class="{{ (Session::has('url') and Session::get('url') == 'notificacoes') ? 'active' : '' }}">
                  <a href="{{ url('notificacoes') }}">
                  <i class="nc-icon nc-send"></i>
                  <p>NOTIFICAÇÕES</p>
                  </a>
                </li>
                @endpermission
                @permission('coletas')
                <li class="{{ (Session::has('url') and Session::get('url') == 'coletas') ? 'active' : '' }}">
                  <a href="{{ url('coletas') }}">
                  <i class="nc-icon nc-tag-content"></i>
                  <p>COLETAS</p>
                  </a>
                </li>
                @endpermission
                @permission('social-search')
                <li class="{{ (Session::has('url') and Session::get('url') == 'search') ? 'active' : '' }}">
                  <a href="{{ url('social-search') }}">
                  <i class="nc-icon nc-zoom-split"></i>
                  <p>SOCIAL SEARCH</p>
                  </a>
                </li>
                @endpermission
                @permission('relatorios')
                  <li class="{{ (Session::has('url') and Session::get('url') == 'relatorios') ? 'active' : '' }}">
                    <a href="{{ url('relatorios') }}">
                    <i class="nc-icon nc-chart-bar-32"></i>
                    <p>RELATÓRIOS</p>
                    </a>
                  </li>
                @endpermission
                @permission('nuvem-de-palavras')
                  <li class="{{ (Session::has('url') and Session::get('url') == 'nuvem-palavras') ? 'active' : '' }}">
                    <a href="{{ url('nuvem-palavras') }}">
                    <i class="fa fa-cloud"></i>
                    <p>NUVEM PALAVRAS</p>
                    </a>
                  </li>
                @endpermission
                </li>
                @permission('regras')
                  <li class="{{ (Session::has('url') and Session::get('url') == 'regras') ? 'active' : '' }}">
                    <a href="{{ url('regras') }}">
                    <i class="nc-icon fa nc-ruler-pencil"></i>
                    <p>Regras</p>
                    </a>
                  </li>
                @endpermission
                @role('administradores')
                  <li class="{{ (Session::has('url') and Session::get('url') == 'boletins') ? 'active' : '' }}">
                    <a href="{{ url('boletins') }}">
                    <i class="fa fa-file-o"></i>
                    <p>Boletins</p>
                    </a>
                  </li>
                @endrole
                @role('administradores')
                  <li class="{{ (Session::has('url') and Session::get('url') == 'transcricao') ? 'active' : '' }}">
                    <a href="{{ url('transcricao') }}">
                    <i class="fa fa-commenting-o" aria-hidden="true"></i>
                    <p>Transcrição</p>
                    </a>
                  </li>
                @endrole
                @role('administradores')
                  <li class="{{ (Session::has('url') and Session::get('url') == 'facebook-paginas') ? 'active' : '' }}">
                    <a href="{{ url('facebook-paginas') }}">
                    <i class="fa fa-at" aria-hidden="true"></i>
                    <p>Facebook Páginas</p>
                    </a>
                  </li>
                @endrole
                <hr/>
                @role('administradores')
                  <li class="{{ (Session::has('url') and Session::get('url') == 'configuracoes') ? 'active' : '' }}">
                      <a href="{{ url('configuracoes') }}">
                      <i class="nc-icon nc-settings-gear-65"></i>
                      <p>Configurações</p>
                      </a>
                  </li>
                @endrole
                @role('administradores')
                  <li class="{{ (Session::has('url') and Session::get('url') == 'auditoria') ? 'active' : '' }}">
                      <a href="{{ url('auditoria') }}">
                        <i class="fa fa-shield"></i>
                      <p>Autidoria</p>
                      </a>
                  </li>
                @endrole
                @role('administradores')
                  <li class="{{ (Session::has('url') and Session::get('url') == 'perfis') ? 'active' : '' }}">
                      <a href="{{ url('perfis') }}">
                      <i class="fa fa-group"></i>
                      <p>Perfis</p>
                      </a>
                  </li>
                @endrole
                @role('administradores')
                  <li class="{{ (Session::has('url') and Session::get('url') == 'permissoes') ? 'active' : '' }}">
                      <a href="{{ url('permissoes') }}">
                      <i class="nc-icon nc-lock-circle-open"></i>
                      <p>Permissões</p>
                      </a>
                  </li>
                @endrole
                @role('administradores')
                  <li class="{{ (Session::has('url') and Session::get('url') == 'usuarios') ? 'active' : '' }}">
                      <a href="{{ url('usuarios') }}">
                      <i class="nc-icon nc-circle-10"></i>
                      <p>Usuários</p>
                      </a>
                  </li>  
                @endrole               
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                      <i class="nc-icon nc-button-power"></i>
                      <p>Sair</p>
                    </a>
                </li>
              </ul>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </div>
        </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand upper" href="{{ url('dashboard') }}">Studio Social</a>
            @role('administradores')
            <div><span class="flag_regras" data-value="{{ Session::get('flag_regras') }}">
              @if(Session::get('flag_regras'))
                  <span class="badge badge-pill badge-info">POSTAGENS FILTRADAS POR REGRAS</span>
              @else
                  <span class="badge badge-pill badge-danger">POSTAGENS NÃO FILTRADAS</span>
              @endif
            </span>

            </div>
              <div class="mb-1 ml-2">
                

                @if(Session::get('cliente'))
                  <p>{{ Session::get('cliente')['nome'] }}</p>
                @else
                  <p>Nenhum cliente selecionado</p>
                @endif
                <span class="troca_cliente"><i class="fa fa-refresh ml-1"></i></span>

              </div>
            @endrole
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                  <i class="fa fa-sign-out"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">       
        @yield('content')          
      </div>

      <footer class="footer footer-black  footer-white ">
        <div class="container-fluid">
          <div class="row">
            
            <div class="credits ml-auto">
              <span class="copyright">
                © <script>
                  document.write(new Date().getFullYear())
                </script> - Studio Social
              </span>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="{{ asset('js/core/jquery.min.js') }}"></script>
  <script src="{{ asset('js/core/popper.min.js') }}"></script>
  <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/core/moment.min.js') }}"></script>
  <script src="{{ asset('js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
 
  <!-- Chart JS -->
  <script src="{{ asset('js/plugins/chartjs.min.js') }}"></script>
  <!--  Notifications Plugin    -->
  <script src="{{ asset('js/plugins/bootstrap-notify.js') }}"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
 
  <script src="{{ asset('js/paper-dashboard.min.js?v=2.0.1') }}" type="text/javascript"></script>
  <script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('js/plugins/bootstrap-datetimepicker.js') }}"></script>
  <script src="{{ asset('js/plugins/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('js/plugins/jqcloud.min.js') }}"></script>
  <script src="{{ asset('js/plugins/jquery.loader.min.js') }}"></script>
  <script src="{{ asset('js/plugins/inputTags.jquery.min.js') }}"></script>
  <script src="{{ asset('js/sweetalert2.js') }}"></script>
  <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
  <script src="{{ asset('demo/demo.js') }}"></script>
  <script src="{{ asset('js/custom.js') }}"></script>
  <script src="{{ asset('js/croppie.min.js') }}"></script>
  <script src="{{ asset('js/upload-image.js') }}"></script>
  <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap-multiselect.js') }}"></script>
  <script src="{{ asset('js/jquery.bootstrap-duallistbox.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
  <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
  <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
  <script>
    function setFormValidation(id) {
      $(id).validate({
        highlight: function(element) {
          $(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
          $(element).closest('.form-check').removeClass('has-success').addClass('has-danger');
        },
        success: function(element) {
          $(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
          $(element).closest('.form-check').removeClass('has-danger').addClass('has-success');
        },
        errorPlacement: function(error, element) {
          $(element).closest('.form-group').append(error);
        },
      });
    }

    $(document).ready(function() {
      // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
      //demo.initChartsPages();
      demo.initDateTimePicker();
      setFormValidation('#RegisterValidation');
    });
  </script>
  <script>
    $(document).ready(function() {

      let APP_URL = {!! json_encode(url('/')) !!}

      $('.select2').select2();

      $('#frm_notification_create').validate();
      $('#frm_social_search').validate();
      
      jQuery.extend(jQuery.validator.messages, {
        required: "Campo obrigatório",
        minlength: jQuery.validator.format("Tamanho mínimo do campo é de {0} cadacteres")
      });
      
      $('#datatable').DataTable({
        "pagingType": "full_numbers",
        
        "lengthMenu": [
          [10, 25, 50, -1],
          [10, 25, 50, "All"]
        ],
        responsive: true,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Filtrar",
        }

      });

      $('.datatable_pages').DataTable({
        "pagingType": "full_numbers",
        
        "lengthMenu": [
          [10, 25, 50, -1],
          [10, 25, 50, "All"]
        ],
        responsive: false,
        ordering: false,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Filtrar",
        }

      });
      
    });
  </script>
  @yield('script')
</body>

</html>