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

            {{-- Map Display --}}
            <div class="dashboard-location-map">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="card-title mb-1 text-dark">
                                    <i class="mdi mdi-map text-success me-2"></i>
                                    Lokasi Madrasah
                                </h5>
                                <p class="text-muted mb-0 small">Peta lokasi madrasah saat ini</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                    <i class="mdi mdi-map-marker fs-5"></i>
                                </div>
                            </div>
                        </div>
                        <div id="map-container" style="height: 300px; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: relative;">
                            @if($madrasahData->latitude && $madrasahData->longitude)
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3168.639!2d{{ $madrasahData->longitude }}!3d{{ $madrasahData->latitude }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s{{ urlencode($madrasahData->name) }}!2z{{ $madrasahData->latitude }},{{ $madrasahData->longitude }}!5e0!3m2!1sen!2sid!4v1234567890123!5m2!1sen!2sid&q={{ $madrasahData->latitude }},{{ $madrasahData->longitude }}"
                                        style="height: 100%; width: 100%; border-radius: 12px; border: none;"
                                        allowfullscreen=""
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                                {{-- @if($madrasahData->map_link)
                                <div class="position-absolute top-0 end-0 p-2">
                                    <a href="{{ $madrasahData->map_link }}" target="_blank" class="btn btn-success btn-sm shadow-sm">
                                        <i class="mdi mdi-google-maps me-1"></i>
                                        Buka Peta Lengkap
                                    </a>
                                </div>
                                @endif --}}
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light rounded">
                                    <div class="avatar-lg mb-3">
                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                            <i class="mdi mdi-map-marker-off fs-1"></i>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-2">Koordinat belum tersedia</h6>
                                    <p class="text-muted text-center small px-3">
                                        Latitude dan longitude belum diisi untuk menampilkan peta
                                    </p>
                                    @if($madrasahData->map_link)
                                    <a href="{{ $madrasahData->map_link }}" target="_blank" class="btn btn-primary btn-sm mt-2">
                                        <i class="mdi mdi-google-maps me-1"></i>
                                        Lihat di Google Maps
                                    </a>
                                    @endif
                                </div>
                            @endif
                        </div>
            </div>
        </div>
        @endif

        {{-- Foundation Location and Map for Super Admin and Pengurus --}}
        @if(in_array(Auth::user()->role, ['super_admin', 'pengurus', 'admin_yayasan']) && isset($foundationData))
        <div class="dashboard-location-card card border-0 shadow-sm hover-lift">
            {{-- Address Information --}}
            <div class="dashboard-location-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="card-title mb-1 text-dark">
                                    <i class="mdi mdi-map-marker text-primary me-2"></i>
                                    Alamat Yayasan
                                </h5>
                                <p class="text-muted mb-0 small">Informasi lokasi dan kontak yayasan</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                    <i class="mdi mdi-office-building fs-5"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6 class="mb-3 text-primary fw-semibold">{{ $foundationData->name }}</h6>
                            <div class="d-flex align-items-start mb-3">
                                <i class="mdi mdi-map-marker-outline text-success me-2 mt-1"></i>
                                <div>
                                    <p class="text-muted mb-1 small">Alamat Lengkap</p>
                                    <p class="mb-0 fw-medium">{{ $foundationData->alamat ?? 'Alamat belum diisi' }}</p>
                                </div>
                            </div>
                            @if($foundationData->map_link)
                            <a href="{{ $foundationData->map_link }}" target="_blank" class="btn btn-primary btn-sm d-inline-flex align-items-center">
                                <i class="mdi mdi-google-maps me-1"></i>
                                Lihat di Google Maps
                            </a>
                            @endif
                        </div>
            </div>

            {{-- Map Display --}}
            <div class="dashboard-location-map">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="card-title mb-1 text-dark">
                                    <i class="mdi mdi-map text-success me-2"></i>
                                    Lokasi Yayasan
                                </h5>
                                <p class="text-muted mb-0 small">Peta lokasi yayasan saat ini</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                    <i class="mdi mdi-map-marker fs-5"></i>
                                </div>
                            </div>
                        </div>
                        <div id="foundation-map-container" style="height: 300px; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: relative;">
                            @if($foundationData->latitude && $foundationData->longitude)
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3168.639!2d{{ $foundationData->longitude }}!3d{{ $foundationData->latitude }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s{{ urlencode($foundationData->name) }}!2z{{ $foundationData->latitude }},{{ $foundationData->longitude }}!5e0!3m2!1sen!2sid!4v1234567890123!5m2!1sen!2sid&q={{ $foundationData->latitude }},{{ $foundationData->longitude }}"
                                        style="height: 100%; width: 100%; border-radius: 12px; border: none;"
                                        allowfullscreen=""
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                                @if($foundationData->map_link)
                                <div class="position-absolute top-0 end-0 p-2">
                                    <a href="{{ $foundationData->map_link }}" target="_blank" class="btn btn-success btn-sm shadow-sm">
                                        <i class="mdi mdi-google-maps me-1"></i>
                                        Buka Peta Lengkap
                                    </a>
                                </div>
                                @endif
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light rounded">
                                    <div class="avatar-lg mb-3">
                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                            <i class="mdi mdi-map-marker-off fs-1"></i>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-2">Koordinat belum tersedia</h6>
                                    <p class="text-muted text-center small px-3">
                                        Latitude dan longitude belum diisi untuk menampilkan peta
                                    </p>
                                    @if($foundationData->map_link)
                                    <a href="{{ $foundationData->map_link }}" target="_blank" class="btn btn-primary btn-sm mt-2">
                                        <i class="mdi mdi-google-maps me-1"></i>
                                        Lihat di Google Maps
                                    </a>
                                    @endif
                                </div>
                            @endif
                        </div>
            </div>
        </div>
        @endif

        @if(Auth::user()->role === 'tenaga_pendidik')
        <!-- Attendance Card - Mobile Optimized -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-calendar-check text-success me-2"></i>
                        Keaktifan Bulan Ini
                    </h5>
                    <div class="text-end">
                        <h3 class="text-success mb-0">{{ round($attendanceData['kehadiran'] ?? 0) }}%</h3>
                        <small class="text-muted">Kehadiran</small>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="mb-3">
                    <div id="donut-chart" data-colors='["--bs-success", "--bs-warning", "--bs-danger"]' class="apex-charts" style="height: 200px;"></div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="bg-light rounded p-3 text-center">
                            <div class="avatar-sm mx-auto mb-2">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <i class="mdi mdi-check-circle fs-5"></i>
                                </div>
                            </div>
                            <h6 class="mb-1">{{ round($attendanceData['kehadiran'] ?? 0) }}%</h6>
                            <small class="text-muted">Hadir</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-3 text-center">
                            <div class="avatar-sm mx-auto mb-2">
                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                    <i class="mdi mdi-medical-bag fs-5"></i>
                                </div>
                            </div>
                            <h6 class="mb-1">{{ round($attendanceData['izin_sakit'] ?? 0) }}%</h6>
                            <small class="text-muted">Izin/Sakit</small>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-calendar-text text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Total Presensi</small>
                                <strong>{{ $attendanceData['total_presensi'] ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-close-circle text-danger me-2"></i>
                            <div>
                                <small class="text-muted d-block">Tidak Hadir</small>
                                <strong>{{ round($attendanceData['alpha'] ?? 0) }}%</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="text-center">
                    <a href="{{ route('mobile.presensi') }}" class="btn btn-success btn-lg w-100">
                        <i class="mdi mdi-eye me-2"></i>
                        Lihat Detail Presensi
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Admin Statistics Section - Right side --}}
    @if(Auth::user()->role === 'admin' && isset($adminStats))
    <div class="col-xl-8 col-12 dashboard-page__main-column dashboard-admin-stats">
        <!-- Statistics Overview Header -->
        <div class="card border-0 shadow-sm mb-4 dashboard-section-heading">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="text-white mb-1">Statistik Admin Sekolah</h4>
                        <p class="text-white-50 mb-0">Ringkasan Sistem Informasi NUIST</p>
                    </div>
                    <div class="avatar-lg">
                        <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                            <i class="mdi mdi-view-dashboard fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Primary Statistics Row -->
        <div class="row g-3 mb-4 dashboard-admin-primary-stats">
            {{-- Total Teachers Card --}}
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--teachers">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ $adminStats['total_teachers'] }}</h3>
                                <p class="text-white-75 mb-0 fs-6">Total Tenaga Pendidik</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-tie fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- School Principal Card --}}
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--principal">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                @if($schoolPrincipal)
                                    <h4 class="text-white mb-1">{{ $schoolPrincipal->name }}</h4>
                                    <p class="text-white-75 mb-0 small">Kepala Sekolah</p>
                                @else
                                    <h4 class="text-white mb-1">-</h4>
                                    <p class="text-white-75 mb-0 small">Kepala Sekolah</p>
                                @endif
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-tie fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Madrasah Info Card --}}
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--school">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="text-white mb-1">{{ Auth::user()->madrasah ? Auth::user()->madrasah->name : 'N/A' }}</h4>
                                <p class="text-white-75 mb-0 small">Madrasah Saat Ini</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-school fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Employment Categories Card --}}
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--admins">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ $adminStats['total_by_status']->count() }}</h3>
                                <p class="text-white-75 mb-0 fs-6">Status Kepegawaian</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-group fs-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Employment Status Breakdown --}}
        <div class="card border-0 shadow-sm dashboard-status-summary" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark">Ringkasan Status Kepegawaian</h5>
                        <p class="text-muted mb-0 small">Distribusi tenaga pendidik berdasarkan status</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="mdi mdi-chart-pie fs-5"></i>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    @if($adminStats['total_by_status']->count() > 0)
                        @foreach($adminStats['total_by_status'] as $status)
                        <div class="col-lg-6 col-xl-4">
                            <div class="card border-0 shadow-sm h-100 hover-lift dashboard-status-card">
                                <div class="card-body p-3 text-center">
                                    <div class="avatar-md mx-auto mb-3">
                                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                            <i class="mdi mdi-account-tie fs-4"></i>
                                        </div>
                                    </div>
                                    <h5 class="mb-2 text-dark">{{ $status['count'] }}</h5>
                                    <p class="text-muted mb-2 small">{{ $status['status_name'] }}</p>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                             style="width: {{ $adminStats['total_teachers'] > 0 ? round(($status['count'] / $adminStats['total_teachers']) * 100) : 0 }}%"
                                             aria-valuenow="{{ $status['count'] }}"
                                             aria-valuemin="0"
                                             aria-valuemax="{{ $adminStats['total_teachers'] }}">
                                        </div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">
                                        {{ $adminStats['total_teachers'] > 0 ? round(($status['count'] / $adminStats['total_teachers']) * 100, 1) : 0 }}%
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-light text-muted rounded-circle">
                                        <i class="mdi mdi-information-outline fs-1"></i>
                                    </div>
                                </div>
                                <h6 class="text-muted">Belum ada data status kepegawaian</h6>
                                <p class="text-muted small">Data akan muncul setelah ada tenaga pendidik terdaftar</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detailed Statistics Table --}}
        <div class="card border-0 shadow-sm dashboard-detail-table" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark">Detail Statistik Tenaga Pendidik</h5>
                        <p class="text-muted mb-0 small">Tabel lengkap distribusi status kepegawaian</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                            <i class="mdi mdi-table fs-5"></i>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="border-radius: 10px; overflow: hidden;">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold text-dark py-3 ps-4">Status Kepegawaian</th>
                                <th class="border-0 fw-semibold text-dark py-3 text-center">Jumlah</th>
                                <th class="border-0 fw-semibold text-dark py-3 pe-4">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($adminStats['total_by_status']->count() > 0)
                                @foreach($adminStats['total_by_status'] as $status)
                                <tr class="border-bottom border-light">
                                    <td class="py-3 ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-3">
                                                <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                                    <i class="mdi mdi-account-tie fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="fw-medium">{{ $status['status_name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-primary bg-opacity-10 text-primary fs-6 px-3 py-2">{{ $status['count'] }}</span>
                                    </td>
                                    <td class="py-3 pe-4">
                                        <div class="d-flex align-items-center">
                                            <div class="grow me-3">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                         style="width: {{ $adminStats['total_teachers'] > 0 ? round(($status['count'] / $adminStats['total_teachers']) * 100) : 0 }}%"
                                                         aria-valuenow="{{ $status['count'] }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="{{ $adminStats['total_teachers'] }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-medium small">
                                                {{ $adminStats['total_teachers'] > 0 ? round(($status['count'] / $adminStats['total_teachers']) * 100, 1) : 0 }}%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="table-primary bg-primary bg-opacity-10">
                                    <td class="py-3 ps-4 fw-bold text-primary">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-3">
                                                <div class="avatar-title bg-primary text-white rounded-circle">
                                                    <i class="mdi mdi-sigma fs-6"></i>
                                                </div>
                                            </div>
                                            Total Keseluruhan
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-primary text-white fs-6 px-3 py-2">{{ $adminStats['total_teachers'] }}</span>
                                    </td>
                                    <td class="py-3 pe-4 fw-bold text-primary">100%</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="mdi mdi-table-off fs-1"></i>
                                            </div>
                                        </div>
                                        <h6 class="text-muted">Belum ada data untuk ditampilkan</h6>
                                        <p class="text-muted small">Data statistik akan muncul setelah ada tenaga pendidik terdaftar</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm dashboard-attendance-card" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="card-title mb-1 text-dark"><i class="mdi mdi-calendar-check text-success me-2"></i>Grafik Presensi</h5>
                        <p class="text-muted mb-0 small">Ringkasan presensi bulan ini</p>
                    </div>
                    <span class="badge bg-success-subtle text-success">Bulan ini</span>
                </div>
                <div id="donut-chart" data-colors='["--bs-success", "--bs-warning", "--bs-danger"]' class="apex-charts" style="height: 220px;"></div>
            </div>
        </div>
    </div>
    @endif

    {{-- Super Admin Statistics Section --}}
    @if(in_array(Auth::user()->role, ['super_admin', 'pengurus', 'admin_yayasan']) && isset($superAdminStats))
    <div class="col-xl-8 col-12 dashboard-page__main-column dashboard-super-admin-stats">
        <!-- Statistics Overview Header -->
        <div class="card border-0 shadow-sm mb-4 dashboard-section-heading">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        @if(Auth::user()->role === 'super_admin')
                            <h4 class="text-white mb-1">Dashboard Super Admin</h4>
                        @elseif(Auth::user()->role === 'pengurus')
                            <h4 class="text-white mb-1">Dashboard Pengurus</h4>
                        @elseif(Auth::user()->role === 'admin_yayasan')
                            <h4 class="text-white mb-1">Dashboard Admin Yayasan</h4>
                        @elseif(Auth::user()->role === 'admin')
                            <h4 class="text-white mb-1">Dashboard Admin</h4>
                        @endif
                        <p class="text-white-50 mb-0">Ringkasan Sistem Informasi NUIST</p>
                    </div>
                    <div class="avatar-lg">
                        <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                            <i class="mdi mdi-view-dashboard fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Primary Statistics Row -->
        <div class="row g-3 mb-4">
            {{-- Total Madrasah Card --}}
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--schools">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ $superAdminStats['total_madrasah'] }}</h3>
                                <p class="text-white-75 mb-0 fs-6">Total Madrasah</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-school fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Teachers Card --}}
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--teachers">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ $superAdminStats['total_teachers'] }}</h3>
                                <p class="text-white-75 mb-0 fs-6">Total Tenaga Pendidik</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-tie fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Admins Card --}}
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--admins">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ $superAdminStats['total_admin'] }}</h3>
                                <p class="text-white-75 mb-0 fs-6">Total Admin</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-cog fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Statistics Row -->
        <div class="row g-3 mb-4">
            {{-- Total Super Admins Card --}}
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--admins">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="text-white mb-1">{{ $superAdminStats['total_super_admin'] }}</h4>
                                <p class="text-white-75 mb-0 small">Super Admin</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-shield-account fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Pengurus Card --}}
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--leaders">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="text-white mb-1">{{ $superAdminStats['total_pengurus'] }}</h4>
                                <p class="text-white-75 mb-0 small">Pengurus</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-group fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total School Principals Card --}}
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--principals">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="text-white mb-1">{{ $superAdminStats['total_school_principals'] }}</h4>
                                <p class="text-white-75 mb-0 small">Kepala Sekolah</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-tie fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Employment Status Breakdown --}}
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark">Ringkasan Status Kepegawaian</h5>
                        <p class="text-muted mb-0 small">Distribusi tenaga pendidik berdasarkan status</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="mdi mdi-chart-pie fs-5"></i>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    @if($superAdminStats['total_by_status']->count() > 0)
                        @foreach($superAdminStats['total_by_status'] as $status)
                        <div class="col-lg-6 col-xl-4">
                            <div class="card border-0 shadow-sm h-100 hover-lift dashboard-status-card">
                                <div class="card-body p-3 text-center">
                                    <div class="avatar-md mx-auto mb-3">
                                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                            <i class="mdi mdi-account-tie fs-4"></i>
                                        </div>
                                    </div>
                                    <h5 class="mb-2 text-dark">{{ $status['count'] }}</h5>
                                    <p class="text-muted mb-2 small">{{ $status['status_name'] }}</p>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                             style="width: {{ $superAdminStats['total_teachers'] > 0 ? round(($status['count'] / $superAdminStats['total_teachers']) * 100) : 0 }}%"
                                             aria-valuenow="{{ $status['count'] }}"
                                             aria-valuemin="0"
                                             aria-valuemax="{{ $superAdminStats['total_teachers'] }}">
                                        </div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">
                                        {{ $superAdminStats['total_teachers'] > 0 ? round(($status['count'] / $superAdminStats['total_teachers']) * 100, 1) : 0 }}%
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-light text-muted rounded-circle">
                                        <i class="mdi mdi-information-outline fs-1"></i>
                                    </div>
                                </div>
                                <h6 class="text-muted">Belum ada data status kepegawaian</h6>
                                <p class="text-muted small">Data akan muncul setelah ada tenaga pendidik terdaftar</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detailed Statistics Table --}}
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark">Detail Statistik Tenaga Pendidik</h5>
                        <p class="text-muted mb-0 small">Tabel lengkap distribusi status kepegawaian</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                            <i class="mdi mdi-table fs-5"></i>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="border-radius: 10px; overflow: hidden;">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold text-dark py-3 ps-4">Status Kepegawaian</th>
                                <th class="border-0 fw-semibold text-dark py-3 text-center">Jumlah</th>
                                <th class="border-0 fw-semibold text-dark py-3 pe-4">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($superAdminStats['total_by_status']->count() > 0)
                                @foreach($superAdminStats['total_by_status'] as $status)
                                <tr class="border-bottom border-light">
                                    <td class="py-3 ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-3">
                                                <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                                    <i class="mdi mdi-account-tie fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="fw-medium">{{ $status['status_name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-primary bg-opacity-10 text-primary fs-6 px-3 py-2">{{ $status['count'] }}</span>
                                    </td>
                                    <td class="py-3 pe-4">
                                        <div class="d-flex align-items-center">
                                            <div class="grow me-3">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                         style="width: {{ $superAdminStats['total_teachers'] > 0 ? round(($status['count'] / $superAdminStats['total_teachers']) * 100) : 0 }}%"
                                                         aria-valuenow="{{ $status['count'] }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="{{ $superAdminStats['total_teachers'] }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-medium small">
                                                {{ $superAdminStats['total_teachers'] > 0 ? round(($status['count'] / $superAdminStats['total_teachers']) * 100, 1) : 0 }}%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="table-primary bg-primary bg-opacity-10">
                                    <td class="py-3 ps-4 fw-bold text-primary">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-3">
                                                <div class="avatar-title bg-primary text-white rounded-circle">
                                                    <i class="mdi mdi-sigma fs-6"></i>
                                                </div>
                                            </div>
                                            Total Keseluruhan
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-primary text-white fs-6 px-3 py-2">{{ $superAdminStats['total_teachers'] }}</span>
                                    </td>
                                    <td class="py-3 pe-4 fw-bold text-primary">100%</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="mdi mdi-table-off fs-1"></i>
                                            </div>
                                        </div>
                                        <h6 class="text-muted">Belum ada data untuk ditampilkan</h6>
                                        <p class="text-muted small">Data statistik akan muncul setelah ada tenaga pendidik terdaftar</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(!in_array(Auth::user()->role, ['admin', 'super_admin', 'pengurus', 'admin_yayasan']))
    <div class="col-12">
        <!-- User Information Card - Mobile Optimized -->
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="mdi mdi-account-details text-primary me-2"></i>
                    Informasi Personal
                </h5>

                <!-- Basic Info -->
                <div class="mb-3">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block">Asal Madrasah/Sekolah</small>
                                <strong class="text-truncate d-block">{{ Auth::user()->madrasah ? Auth::user()->madrasah->name : '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block">Tempat Lahir</small>
                                <strong>{{ Auth::user()->tempat_lahir ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block">Tanggal Lahir</small>
                                <strong>{{ Auth::user()->tanggal_lahir ? \Carbon\Carbon::parse(Auth::user()->tanggal_lahir)->format('d F Y') : '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block">TMT</small>
                                <strong>{{ Auth::user()->tmt ? \Carbon\Carbon::parse(Auth::user()->tmt)->format('d F Y') : '-' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Info -->
                <div class="mb-3">
                    <h6 class="text-muted mb-3">Informasi Kepegawaian</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-primary-subtle rounded">
                                <small class="text-muted d-block">NUPTK</small>
                                <strong>{{ Auth::user()->nuptk ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-primary-subtle rounded">
                                <small class="text-muted d-block">NPK</small>
                                <strong>{{ Auth::user()->npk ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-success-subtle rounded">
                                <small class="text-muted d-block">Kartanu</small>
                                <strong>{{ Auth::user()->kartanu ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-success-subtle rounded">
                                <small class="text-muted d-block">NIP Ma'arif</small>
                                <strong>{{ Auth::user()->nip ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-info-subtle rounded">
                                <small class="text-muted d-block">Status Kepegawaian</small>
                                <strong>{{ Auth::user()->statusKepegawaian ? Auth::user()->statusKepegawaian->name : '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-info-subtle rounded">
                                <small class="text-muted d-block">Ketugasan</small>
                                <strong>{{ Auth::user()->ketugasan ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-warning-subtle rounded">
                                <small class="text-muted d-block">Pendidikan Terakhir</small>
                                <strong>{{ Auth::user()->pendidikan_terakhir ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-warning-subtle rounded">
                                <small class="text-muted d-block">Program Studi</small>
                                <strong>{{ Auth::user()->program_studi ?? '-' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Colleagues List - Mobile Optimized --}}
        @if($showUsers)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="mdi mdi-account-group text-info me-2"></i>
                    Rekan Guru/Pegawai Se-Madrasah/Sekolah
                </h5>

                <!-- Mobile-friendly list view -->
                <div class="list-group list-group-flush">
                    @foreach($users as $index => $user)
                    <div class="list-group-item px-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="shrink-0 me-3">
                                <img src="{{ isset($user->avatar) ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                                     alt="Foto {{ $user->name }}"
                                     class="rounded-circle"
                                     width="50"
                                     height="50">
                            </div>
                            <div class="grow">
                                <h6 class="mb-1">{{ $user->name }}</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <small class="badge bg-primary-subtle text-primary">{{ $user->ketugasan ?? '-' }}</small>
                                    <small class="badge bg-info-subtle text-info">{{ $user->statusKepegawaian ? $user->statusKepegawaian->name : '-' }}</small>
                                </div>
                            </div>
                            <div class="shrink-0">
                                <small class="text-muted">{{ $users->firstItem() + $index }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
</div>

{{-- Social Source, Activity, Top Cities --}}
{{-- <div class="row">
    <div class="col-xl-4">@include('dashboard.partials.social')</div>
    <div class="col-xl-4">@include('dashboard.partials.activity')</div>
    <div class="col-xl-4">@include('dashboard.partials.cities')</div>
</div> --}}

{{-- Latest Transaction --}}
{{-- <div class="row">
    <div class="col-lg-12">@include('dashboard.partials.transactions')</div>
</div> --}}

{{-- Modals --}}
{{-- @include('dashboard.partials.modals') --}}

@endsection

@section('script')
<!-- apexcharts -->
<script src="{{ asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>



<style>
.dashboard-page {
    --dashboard-brand: #075b52;
    --dashboard-brand-dark: #073b39;
    --dashboard-ink: #172b29;
    --dashboard-muted: #647571;
    --dashboard-line: #e6eeeb;
}

.dashboard-page {
    margin: -0.5rem -0.75rem -1.5rem;
    padding: 0.75rem;
    background: #f4f8f9;
}

.dashboard-page > .row {
    max-width: 100%;
    margin-inline: 0;
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 1rem;
}

.dashboard-page > .row > .dashboard-page__profile-column,
.dashboard-page > .row > .dashboard-page__main-column {
    width: 100%;
    flex: 0 0 100%;
    max-width: none;
    grid-column: 1;
}

.dashboard-page .card {
    border-radius: 1rem;
    border: 1px solid rgba(20, 70, 65, 0.08) !important;
    box-shadow: 0 0.25rem 1rem rgba(31, 61, 71, 0.07) !important;
}

.dashboard-page > .row {
    align-items: flex-start;
}

.dashboard-page__profile-column,
.dashboard-page__main-column {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.dashboard-page__profile-column {
    display: grid;
    grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr);
    gap: 1rem;
}

.dashboard-page__profile-column > .dashboard-welcome-card {
    grid-column: 1;
}

.dashboard-page__profile-column > .dashboard-location-card {
    grid-column: 2;
    grid-row: 1;
}

.dashboard-page__profile-column > .row {
    display: contents;
}

.dashboard-page__profile-column > .row > .col-12 {
    grid-column: 2;
}

.dashboard-page__main-column {
    margin-top: 0.25rem;
}

.dashboard-page__main-column:not(.dashboard-admin-stats) {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 1rem;
}

.dashboard-page__main-column:not(.dashboard-admin-stats) > .dashboard-section-heading,
.dashboard-page__main-column:not(.dashboard-admin-stats) > .row,
.dashboard-page__main-column:not(.dashboard-admin-stats) > .card {
    margin-bottom: 0 !important;
    min-width: 0;
}

.dashboard-super-admin-stats > .row {
    width: 100%;
    margin-inline: 0;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
}

.dashboard-super-admin-stats > .row > [class*="col-"] {
    width: auto;
    max-width: none;
    padding-inline: 0;
}

.dashboard-admin-stats {
    display: grid;
    grid-template-columns: minmax(0, 1.65fr) minmax(20rem, 0.85fr);
    gap: 1rem;
    align-items: start;
}

.dashboard-admin-stats > .dashboard-section-heading {
    grid-column: 1;
    margin-bottom: 0 !important;
}

.dashboard-admin-stats > .dashboard-admin-primary-stats {
    grid-column: 1;
    margin-bottom: 0 !important;
}

.dashboard-admin-stats > .dashboard-status-summary {
    grid-column: 2;
    grid-row: 1 / span 2;
}

.dashboard-admin-stats > .dashboard-detail-table {
    grid-column: 1;
}

.dashboard-admin-stats > .dashboard-attendance-card {
    grid-column: 2;
}

.dashboard-page__profile-column > .card,
.dashboard-page__main-column > .card,
.dashboard-page__main-column > .row {
    flex-shrink: 0;
}

.dashboard-page .row > [class*="col-"] {
    min-width: 0;
}

.dashboard-page .card-body {
    min-width: 0;
}

.dashboard-page .dashboard-welcome-card {
    border: 1px solid var(--dashboard-line) !important;
}

.dashboard-location-card {
    display: grid;
    grid-template-columns: minmax(15rem, 0.9fr) minmax(20rem, 1.1fr);
    min-height: 18rem;
    overflow: hidden;
}

.dashboard-location-info,
.dashboard-location-map {
    min-width: 0;
}

.dashboard-location-map {
    padding: 1rem;
}

.dashboard-location-map .card-body,
.dashboard-location-info .card-body {
    height: 100%;
    padding: 1.25rem;
}

.dashboard-location-map #foundation-map-container {
    height: 13rem !important;
}

.dashboard-welcome-card__banner,
.dashboard-section-heading {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
}

.dashboard-welcome-card__banner {
    padding: 0.35rem 0.5rem;
}

.dashboard-welcome-card .card-body {
    padding: 1.25rem 1.35rem;
}

.dashboard-eyebrow {
    color: rgba(255, 255, 255, 0.72);
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.dashboard-welcome-card__mark {
    display: inline-grid;
    width: 2.75rem;
    height: 2.75rem;
    margin-right: 1rem;
    place-items: center;
    border: 1px solid rgba(255, 255, 255, 0.28);
    border-radius: 50%;
    color: #fff;
    font-size: 1.45rem;
}

.dashboard-avatar img {
    border: 3px solid #fff;
    box-shadow: 0 0.4rem 1.2rem rgba(7, 59, 57, 0.16);
}

.dashboard-section-heading {
    border-radius: 1rem;
    min-height: 5.5rem;
}

.dashboard-section-heading .card-body {
    padding: 1.5rem;
}

.dashboard-section-heading h4 {
    font-size: clamp(1.15rem, 1.2vw, 1.45rem);
    font-weight: 700;
}

.dashboard-section-heading p {
    font-size: 0.85rem;
}

.dashboard-stat-card {
    min-height: 9rem;
    overflow: hidden;
    border-radius: 0.9rem;
    color: #fff;
}

.dashboard-stat-card .card-body > div:first-child {
    min-width: 0;
}

.dashboard-page .dashboard-stat-card .card-body {
    padding: 1.25rem;
}

.dashboard-page .dashboard-stat-card p {
    max-width: 15rem;
    overflow-wrap: anywhere;
}

.dashboard-stat-card .card-body {
    display: flex;
    align-items: center;
}

.dashboard-stat-card h3,
.dashboard-stat-card h4 {
    color: #fff;
    font-weight: 750;
    letter-spacing: -0.03em;
    overflow-wrap: anywhere;
}

.dashboard-stat-card h3 {
    font-size: clamp(1.8rem, 2.6vw, 2.35rem);
}

.dashboard-stat-card h4 {
    font-size: clamp(1.1rem, 1.4vw, 1.4rem);
}

.dashboard-stat-card--schools,
.dashboard-stat-card--school {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.dashboard-stat-card--teachers {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.dashboard-stat-card--admins {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.dashboard-stat-card--principal,
.dashboard-stat-card--principals {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.dashboard-stat-card--leaders {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.dashboard-status-card {
    border: 1px solid var(--dashboard-line) !important;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.dashboard-page .dashboard-stat-card .avatar-title {
    background: rgba(255, 255, 255, 0.25) !important;
    color: #fff !important;
}

.dashboard-page .table-responsive {
    border: 1px solid var(--dashboard-line);
    border-radius: 0.75rem;
}

.dashboard-page .dashboard-status-card .card-body {
    padding: 1.15rem;
}

.dashboard-page .dashboard-status-card .avatar-md {
    width: 3rem;
    height: 3rem;
}

.dashboard-page .dashboard-status-card .progress {
    background: #e8eef0;
}

.dashboard-page .dashboard-status-card {
    min-height: 10.5rem;
}

.dashboard-page .progress {
    overflow: hidden;
    border-radius: 999px;
}

.dashboard-page .table {
    min-width: 36rem;
}

.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.8rem 1.8rem rgba(18, 53, 47, 0.12) !important;
}

.gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.gradient-pink {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.gradient-blue {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.gradient-orange {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.gradient-mint {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.gradient-peach {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

@media (max-width: 991.98px) {
    .dashboard-admin-stats {
        display: flex;
    }

    .dashboard-admin-stats > .dashboard-section-heading,
    .dashboard-admin-stats > .dashboard-admin-primary-stats,
    .dashboard-admin-stats > .dashboard-status-summary,
    .dashboard-admin-stats > .dashboard-detail-table,
    .dashboard-admin-stats > .dashboard-attendance-card {
        width: 100%;
    }

    .dashboard-super-admin-stats > .row {
        display: flex;
    }

    .dashboard-page__profile-column {
        display: block;
    }

    .dashboard-page__profile-column > .dashboard-location-card,
    .dashboard-page__profile-column > .dashboard-welcome-card {
        margin-bottom: 1rem;
    }

    .dashboard-location-card {
        grid-template-columns: 1fr;
    }

    .dashboard-location-map {
        padding-top: 0;
    }

    .dashboard-page__profile-column,
    .dashboard-page__main-column {
        width: 100%;
    }

    .dashboard-page__profile-column {
        order: 1;
    }

    .dashboard-page__main-column {
        order: 2;
    }
}

@media (max-width: 575.98px) {
    .dashboard-page {
        margin-inline: -0.5rem;
        padding: 0.5rem;
    }

    .dashboard-page .card-body {
        padding: 1rem;
    }

    .dashboard-welcome-card .card-body {
        padding-top: 0 !important;
    }

    .dashboard-section-heading .card-body {
        padding: 1.1rem;
    }

    .dashboard-stat-card {
        min-height: 7.5rem;
    }

    .dashboard-page .dashboard-status-card {
        min-height: auto;
    }

    .dashboard-page .table th,
    .dashboard-page .table td {
        white-space: nowrap;
    }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var attendanceData = @json($attendanceData ?? ['kehadiran' => 0, 'izin_sakit' => 0, 'alpha' => 0]);

        var options = {
            chart: {
                height: 200,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: {
                            fontSize: '16px',
                        },
                        value: {
                            fontSize: '14px',
                            formatter: function (val) {
                                return val + "%";
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function () {
                                return 100 + "%";
                            }
                        }
                    }
                }
            },
            colors: ['#198754', '#ffc107', '#dc3545'],
            series: [
                attendanceData.kehadiran,
                attendanceData.izin_sakit,
                attendanceData.alpha
            ],
            labels: ['Kehadiran', 'Izin/Sakit', 'Tidak Hadir'],
            legend: {
                position: 'bottom',
                formatter: function (val, opts) {
                    return val + " - " + opts.w.globals.series[opts.seriesIndex] + "%";
                }
            }
        };

        var chartElement = document.querySelector("#donut-chart");
        if (chartElement) {
            var chart = new ApexCharts(
                chartElement,
                options
            );

            chart.render();
        }


    });
</script>
@endsection
