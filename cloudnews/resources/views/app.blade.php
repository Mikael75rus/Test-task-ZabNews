<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {{--    Подключение CSRF токена    --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Тестовое веб-приложение</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
        {{--    css пустой    --}}
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    </head>
    <body>
            <div id="app"></div>
            <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
