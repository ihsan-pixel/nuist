<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Nuist - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY</title>
    <base href="{{ url('/') }}/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Informasi Digital LP. Ma'arif NU PWNU DIY - Platform terintegrasi untuk manajemen madrasah, presensi, dan data pendidikan." />
    <meta name="keywords" content="nuist, ma'arif, nu, pwnu diy, sistem informasi, madrasah, presensi, pendidikan" />
    <meta name="author" content="LP. Ma'arif NU PWNU DIY" />
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title') | Nuist - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY" />
    <meta property="og:description" content="Sistem Informasi Digital LP. Ma'arif NU PWNU DIY - Platform terintegrasi untuk manajemen madrasah, presensi, dan data pendidikan." />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ asset('images/logo favicon 1.png') }}" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/logo favicon 1.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.head-css')
    @yield('css')
</head>

<body data-topbar="light" data-layout-mode="light">

    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            @include('layouts.footer')
        </div>
    </div>

    @include('layouts.right-sidebar')

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')

    <!-- Leaflet Core -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Leaflet Draw Plugin -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <!-- Script khusus halaman -->
    @stack('scripts')

</body>

</html>
