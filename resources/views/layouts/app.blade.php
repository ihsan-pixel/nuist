@extends('layouts.master-without-nav')

@section('title', config('app.name'))

@section('body')
<body>
    {{-- optional topbar or wrapper could be added here by pages that extend layouts.app --}}
@endsection

{{-- content of child views will be injected into master-without-nav via @yield('content') --}}

@section('script-bottom')
@endsection
