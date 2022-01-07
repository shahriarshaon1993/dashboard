<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Language" content="en">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{ setting('site_title', 'Laravel') }} @yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
        <meta name="description" content="This is an example dashboard created using build-in elements and components.">
        <meta name="msapplication-tap-highlight" content="no">

        <link href="{{ asset('backend/main.css') }}" rel="stylesheet">
        <link href="{{ asset('css/iziToast.css') }}" rel="stylesheet">
        <link href="{{ asset('css/backend.css') }}" rel="stylesheet">
        @stack('css')
    </head>

    <body>
        <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
            {{-- Include Navigation --}}
            @include('layouts.backend.partials.navigation')

            <div class="app-main">
                {{-- Include sidebar --}}
                @include('layouts.backend.partials.sidebar')


                <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                {{ $header }}
                            </div>
                        </div>

                        {{ $slot }}
                    </div>
                    <div class="app-wrapper-footer">
                        {{-- Include footer --}}
                        @include('layouts.backend.partials.footer')
                    </div>
                </div>
                <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/iziToast.js') }}"></script>
        <script src="{{ asset('backend/assets/scripts/main.js') }}"></script>
        <script src="{{ asset('js/script.js') }}"></script>

        @include('vendor.lara-izitoast.toast')
        @stack('js')
    </body>
</html>
