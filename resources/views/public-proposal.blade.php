<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Proposal</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
</head>
<body class="bg-light">

    <div id="app">
        <router-view></router-view>
    </div>

    <script>
        window.Laravel = { csrfToken: '{{ csrf_token() }}' };
    </script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>