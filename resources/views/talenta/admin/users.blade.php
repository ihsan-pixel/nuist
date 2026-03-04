@extends('layouts.master-without-nav')

@section('title', 'Users')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
@include('talenta.partials.hero')
@include('talenta.navbar')

<section class="section-clean">
    <div class="container">
        <h2>Users (placeholder)</h2>
        <p>Halaman daftar users - belum diimplementasikan sepenuhnya.</p>
    </div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
