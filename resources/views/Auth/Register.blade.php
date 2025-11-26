<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register — My App</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
    <div id="app" class="min-h-screen flex items-center justify-center">
        {{-- Mount the Vue Component --}}
        <register-component></register-component>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>