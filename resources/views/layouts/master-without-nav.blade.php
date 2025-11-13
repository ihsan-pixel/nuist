<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Nuist.id - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta -->
    <meta name="description" content="Nuist.id adalah Sistem Informasi Digital LP. Ma'arif NU PWNU DIY untuk manajemen data madrasah, tenaga pendidik, dan laporan pendidikan berbasis web.">
    <meta name="keywords" content="nuist.id, sistem informasi madrasah, LP Ma'arif NU, pendidikan DIY, aplikasi sekolah, aplikasi madrasah, pendidikan digital">
    <meta name="author" content="Nuist.id">

    <!-- Open Graph -->
    <meta property="og:title" content="Nuist.id - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY" />
    <meta property="og:description" content="Sistem Informasi Madrasah & Pendidikan berbasis web untuk LP. Ma'arif NU PWNU DIY." />
    <meta property="og:url" content="https://nuist.id" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ asset('build/images/logo favicon 1.png') }}" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('build/images/logo favicon 1.png') }}">

    <!-- Prevent caching -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    {{-- Head CSS (Bootstrap/Tailwind/Core) --}}
    @include('layouts.head-css')

    {{-- Tambahan CSS dari halaman --}}
    @yield('css')
</head>

{{-- Body Section --}}
@yield('body')

    {{-- Konten utama halaman --}}
    @yield('content')

    {{-- Vendor Script (default JS) --}}
    @include('layouts.vendor-scripts')

    {{-- Tambahan script dari halaman --}}
    @yield('script-bottom')

</body>
</html>
