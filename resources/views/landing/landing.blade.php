@extends('layouts.master-without-nav')

@section('landing_shell', '1')
@section('title', 'Home')
@section('description', 'Sistem Informasi Digital LPMNU PWNU DIY')

@php
    $homeStats = [
        [
            'id' => 'count1',
            'value' => $countMadrasah,
            'label' => 'Sekolah/Madrasah',
        ],
        [
            'id' => 'count2',
            'value' => $countTenagaPendidik . '+',
            'label' => 'Tenaga Pendidik Aktif',
        ],
        [
            'id' => 'count3',
            'value' => $countAdmin,
            'label' => 'Admin Operator Aktif',
        ],
    ];
@endphp

@section('content')
<div class="landing-page landing-home-page" data-landing-page="landing">
    @include('landing.partials.home.hero', ['landing' => $landing])
    @include('landing.partials.home.carousel', ['madrasahs' => $madrasahs])
    @include('landing.partials.home.profile', ['landing' => $landing])
    @include('landing.partials.home.statistics', ['stats' => $homeStats])
    @include('landing.partials.home.features', ['landing' => $landing])
</div>
@endsection

@section('css')
    @include('landing.partials.asset-style', [
        'buildAsset' => 'build/css/landing-home.min.css',
        'resourcePath' => resource_path('css/landing-home.css'),
    ])
@endsection
