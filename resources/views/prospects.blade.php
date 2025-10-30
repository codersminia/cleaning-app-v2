<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard — My App</title>

    {{-- Load compiled CSS from Mix --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
    
    <div id="app">
        <navbar-component></navbar-component>
        <prospect-component></prospect-component>
    </div>

    {{-- Load compiled JS from Mix --}}
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
