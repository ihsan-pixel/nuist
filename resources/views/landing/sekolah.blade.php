@extends('layouts.master-without-nav')

@section('landing_shell', '1')
@section('title', 'Sekolah/Madrasah - NUIST')
@section('description', 'Daftar Sekolah/Madrasah di bawah Naungan LPMNU PWNU DIY')

@section('css')
@include('landing._sekolah_styles')
@endsection

@section('content')
@php
    $mapLocations = $madrasahs
        ->filter(fn ($madrasah) => $madrasah->latitude && $madrasah->longitude)
        ->map(fn ($madrasah) => [
            'lat' => (float) $madrasah->latitude,
            'lng' => (float) $madrasah->longitude,
            'name' => $madrasah->name,
            'kabupaten' => $madrasah->kabupaten,
            'detailUrl' => route('landing.sekolah.detail', $madrasah->id),
        ])
        ->values();
@endphp

<div class="landing-page landing-school-page" data-landing-page="sekolah">
    <!-- HERO -->
    <section id="hero" class="hero">
        <div class="container">
            <h1 class="hero-title">Sekolah/Madrasah</h1>
            <h2 class="hero-subtitle" style="color: #eda711">Di bawah Naungan LPMNU PWNU DIY</h2>
            <p>Temukan sekolah dan madrasah yang menjadi bagian dari ekosistem pendidikan kami. Klik pada sekolah untuk melihat profil lengkapnya.</p>
        </div>
    </section>

    <!-- MAP SECTION -->
    <section id="map-section" class="map-section">
        <div class="container">
            <h2 class="section-title animate fade-up" style="margin-bottom:50px; font-size:24px;">Peta Lokasi Madrasah di Yogyakarta</h2>
            <div id="map"></div>
        </div>
    </section>

    <!-- SEKOLAH LIST -->
    <section id="sekolah-list" class="sekolah-list">
        <div class="container">
            <h2 class="section-title animate fade-up" style="margin-bottom:50px; font-size:24px;">Daftar Sekolah/Madrasah</h2>

            @foreach($groupedMadrasahs as $kabupaten => $madrasahList)
                <div class="kabupaten-section animate fade-up">
                    <h3 class="kabupaten-header">
                        <span class="kabupaten-icon">📍</span>
                        {{ $kabupaten }}
                        <span class="kabupaten-count">({{ count($madrasahList) }} sekolah)</span>
                    </h3>
                    <div class="schools-grid">
                        @foreach($madrasahList as $madrasah)
                            <a href="{{ route('landing.sekolah.detail', $madrasah->id) }}" class="school-card-link">
                                <div class="school-card">
                                    <div class="school-logo">
                                        <img src="{{ asset('storage/' . $madrasah->logo) }}" alt="{{ $madrasah->name }}" loading="lazy" decoding="async">
                                    </div>
                                    <div class="school-info">
                                        <h3>{{ $madrasah->name }}</h3>
                                        <p>{{ $madrasah->kabupaten }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <script type="application/json" data-sekolah-map-locations>
        @json($mapLocations)
    </script>
</div>
@endsection
