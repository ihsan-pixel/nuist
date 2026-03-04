@extends('layouts.master-without-nav')

@section('title', 'Admin Dashboard - School Level')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
@include('talenta.partials.hero')
@include('talenta.navbar')

<section class="section-clean">
    <div class="container">
        <h1>Admin Dashboard - School Level</h1>
        <p>Ringkasan singkat (placeholder). Total sekolah, total tenaga pendidik, total pemateri, total assessment terisi.</p>
    </div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
