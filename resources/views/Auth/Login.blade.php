<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
<link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    {{-- load compiled css from Mix --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
    <div id="app" class="min-h-screen flex items-center justify-center">
        {{-- The Vue component will mount here --}}
        <login-component></login-component>
    </div>

    {{-- load compiled js from Mix --}}
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
