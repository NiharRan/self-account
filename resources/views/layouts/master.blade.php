
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @yield('title')

        <!-- Favicon -->
        <link href="{{ asset('img/brand/favicon.png') }}" rel="icon" type="image/png">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=OpenSans:300,400,600,700" rel="stylesheet">
        <!-- Icons -->
        <link href="{{ asset('vendor/nucleo/css/nucleo.css') }}" rel="stylesheet">

         <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- Argon CSS -->
        <link type="text/css" href="{{ asset('css/argon.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <form id="logout-form" action="logout" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
            {{-- Side Navbar --}}
            @include('partials.sidebar')

            <div class="main-content">
                <!-- Top Navbar -->
                @include('partials.navbar')
                
                @yield('page-header')

                <div class="container-fluid mt--6">
                    {{-- Main Content --}}
                    @yield('content')
                    <!-- Footer -->
                    
                    @include('partials.footer')
                </div>
            </div>
        </div>
        
        <script src="{{ asset('vendor/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/js-cookie/js.cookie.js') }}"></script>
        <script src="{{ asset('vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
        <script src="{{ asset('vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
        <script src="{{ asset('vendor/lavalamp/js/jquery.lavalamp.min.js') }}"></script>
        <!-- Optional JS -->
        <script src="{{ asset('vendor/chart.js/dist/Chart.min.js') }}"></script>
        <script src="{{ asset('vendor/chart.js/dist/Chart.extension.js') }}"></script>

        <!-- Argon JS -->
        <script src="{{ asset('js/argon.js') }}"></script>
        <script src="{{ asset('js/demo.min.js') }}"></script>
    </body>
</html>