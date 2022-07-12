<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <!--     Fonts and icons     -->
        <!-- CSS Files -->
        <link href="{{ public_path('css/bootstrap.min.css') }}" rel="stylesheet" />
        <link href="{{ public_path('css/paper-dashboard.css?v=2.0.1') }}" rel="stylesheet" />
        <!-- CSS Just for demo purpose, don't include it in your project -->
        <link href="{{ public_path('demo/demo.css') }}" rel="stylesheet" />
        <link href="{{ public_path('css/custom.css') }}" rel="stylesheet" />
    </head>
    <body>
        <div class="content">
            @yield('content')
        </div>
    </body>
</html>


