<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('vendor/nucleo/css/nucleo.css') }}">

    <link rel="stylesheet" href="{{ asset('css/argon.min.css') }}">


    <style>
        .form-control {
            line-height: 1 !important;
            height: calc(2rem + 2px) !important;
        }
    </style>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-default">
    <div id="app">
        <!-- Main content -->
        <div class="main-content">
            <!-- Page content -->
            <div class="container mt-4 pb-5">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
