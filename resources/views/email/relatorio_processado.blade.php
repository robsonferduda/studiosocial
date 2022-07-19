<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Mídias</title>
</head>
<body>
    @component('mail::message')

        @foreach ($introLines as $line)
            <p>{!! $line !!}</p>
        @endforeach

        @component('mail::button', ['url' => $url, 'color' => 'green'])
        Button 1 Text
        @endcomponent

    @endcomponent
</body>
</html>