{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.master')

@section('title') Dashboard - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') Dashboards @endslot
    @slot('title') Dashboard @endslot
@endcomponent

<div class="dashboard-page">
<div class="row g-4">
    <div class="col-lg-4 col-12 dashboard-page__profile-column">
        <!-- Welcome Card - Mobile Optimized -->
        <div class="card border-0 shadow-sm hover-lift overflow-hidden mb-4 dashboard-welcome-card">
            <div class="dashboard-welcome-card__banner">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="text-white p-3">
                            <p class="dashboard-eyebrow mb-1">Sistem Informasi Digital</p>
                            <h5 class="text-white mb-0">Selamat datang, {{ Str::before(trim(Auth::user()->name), ' ') }}!</h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <span class="dashboard-welcome-card__mark" aria-hidden="true"><i class="mdi mdi-view-dashboard-outline"></i></span>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-lg profile-user-wid mb-3 mb-md-0 dashboard-avatar">
                            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('build/images/avatar-1.jpg') }}" alt="Foto profil {{ Auth::user()->name }}" class="img-thumbnail rounded-circle">
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="font-size-16 mb-1">{{ Str::ucfirst(Auth::user()->name) }}</h5>
                        <p class="text-muted mb-2">NUIST ID: {{ Auth::user()->nuist_id ?? '-' }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            <small class="badge bg-primary-subtle text-primary">{{ Auth::user()->statusKepegawaian?->name ?? 'Pengguna NUIST' }}</small>
                            <small class="badge bg-info-subtle text-info">{{ Auth::user()->ketugasan ?? Str::headline(Auth::user()->role) }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Madrasah Location and Map - Positioned below welcome card on left side --}}
        @if(Auth::user()->role === 'admin' && isset($madrasahData))
        <div class="dashboard-location-card card border-0 shadow-sm hover-lift">
            {{-- Address Information --}}
            <div class="dashboard-location-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="card-title mb-1 text-dark">
                                    <i class="mdi mdi-map-marker text-primary me-2"></i>
                                    Alamat Madrasah
                                </h5>
                                <p class="text-muted mb-0 small">Informasi lokasi dan kontak madrasah</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                    <i class="mdi mdi-school fs-5"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6 class="mb-3 text-primary fw-semibold">{{ $madrasahData->name }}</h6>
                            <div class="d-flex align-items-start mb-3">
                                <i class="mdi mdi-map-marker-outline text-success me-2 mt-1"></i>
                                <div>
                                    <p class="text-muted mb-1 small">Alamat Lengkap</p>
                                    <p class="mb-0 fw-medium">{{ $madrasahData->alamat ?? 'Alamat belum diisi' }}</p>
                                </div>
                            </div>
                            @if($madrasahData->map_link)
                            <a href="{{ $madrasahData->map_link }}" target="_blank" class="btn btn-primary btn-sm d-inline-flex align-items-center">
                                <i class="mdi mdi-google-maps me-1"></i>
                                Lihat di Google Maps
                            </a>
                            @endif
                        </div>
            </div>
            </div>

            {{-- Map Display --}}
            <div class="dashboard-location-map">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="card-title mb-1 text-dark">
                                    <i class="mdi mdi-map text-success me-2"></i>
                                    Lokasi Madrasah
