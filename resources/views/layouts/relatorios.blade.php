<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <!--     Fonts and icons     -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
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


