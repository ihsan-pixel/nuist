@extends('layouts.master-without-nav')

@section('title', 'PPDB NUIST 2025')

@section('body')
<body class="scroll-smooth bg-gray-50">
@endsection

{{-- Tambahkan CSS di sini --}}
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/ppdb-custom.css') }}" />
@endsection

{{-- Mulai konten halaman --}}
@section('content')

    {{-- Navbar --}}
    @include('partials.ppdb.navbar')


@section('script-bottom')
    <!-- Play template scripts -->
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script>
        if (typeof WOW !== 'undefined') { new WOW().init(); }
    </script>
@endsection

@endsection
