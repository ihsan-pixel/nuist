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
                    <div class="nuist-attendance-chart" data-colors='["--bs-success", "--bs-warning", "--bs-danger"]' class="apex-charts" style="height: 200px;"></div>
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

    @if(in_array(Auth::user()->role, ['super_admin', 'pengurus', 'admin_yayasan']) && isset($superAdminStats))
    <div class="col-12 dashboard-teacher-status-card">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark"><i class="mdi mdi-account-group text-success me-2"></i>Statistik Tenaga Pendidik</h5>
                        <p class="text-muted mb-0 small">Pengelompokan berdasarkan status kepegawaian</p>
                    </div>
                    <span class="badge bg-success-subtle text-success">{{ $superAdminStats['total_teachers'] }} Orang</span>
                </div>
                <div class="row g-3">
                    @forelse($superAdminStats['total_by_status'] as $status)
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="dashboard-teacher-status-item h-100">
                            <div class="avatar-sm mb-2"><div class="avatar-title bg-success-subtle text-success rounded-circle"><i class="mdi mdi-account-tie"></i></div></div>
                            <div class="h4 mb-1 text-dark">{{ $status['count'] }}</div>
                            <div class="small text-muted">{{ $status['status_name'] }}</div>
                            <div class="progress mt-2" style="height:5px"><div class="progress-bar bg-success" style="width:{{ $superAdminStats['total_teachers'] > 0 ? round($status['count'] / $superAdminStats['total_teachers'] * 100) : 0 }}%"></div></div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted py-4">Belum ada data tenaga pendidik.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif

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
                <div class="nuist-attendance-chart" data-colors='["--bs-success", "--bs-warning", "--bs-danger"]' class="apex-charts" style="height: 220px;"></div>
            </div>
        </div>
    </div>
    @endif

    {{-- Super Admin Statistics Section --}}
    @if(false && in_array(Auth::user()->role, ['super_admin', 'pengurus', 'admin_yayasan']) && isset($superAdminStats))
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
        <div class="row g-3 mb-4 dashboard-super-primary-stats">
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
        <div class="card border-0 shadow-sm dashboard-super-status-summary" style="border-radius: 15px;">
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
        <div class="card border-0 shadow-sm dashboard-super-detail-table" style="border-radius: 15px;">
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

        <div class="card border-0 shadow-sm dashboard-super-attendance" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="card-title mb-1 text-dark"><i class="mdi mdi-calendar-check text-success me-2"></i>Grafik Presensi</h5>
                        <p class="text-muted mb-0 small">Ringkasan presensi</p>
                    </div>
                    <span class="badge bg-light text-muted">Bulan ini</span>
                </div>
                <div class="dashboard-attendance-rings">
                    @foreach([['label' => 'Hadir', 'value' => round($attendanceData['kehadiran'] ?? 0), 'color' => '#20a464'], ['label' => 'Izin / Sakit', 'value' => round($attendanceData['izin_sakit'] ?? 0), 'color' => '#f4b52b'], ['label' => 'Tidak Hadir', 'value' => round($attendanceData['alpha'] ?? 0), 'color' => '#e66a6a']] as $attendance)
                    <div class="text-center">
                        <div class="dashboard-attendance-ring" style="--ring-color: {{ $attendance['color'] }}; --ring-value: {{ $attendance['value'] }}%;"><span>{{ $attendance['value'] }}%</span></div>
                        <small class="text-muted fw-semibold">{{ $attendance['label'] }}</small>
                    </div>
                    @endforeach
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
/* NUIST Dashboard — desktop-first, responsive, scoped to this page */
.dashboard-page{--nuist-green:#08745a;--nuist-dark:#064b3c;--nuist-ink:#192d37;--nuist-muted:#6c7c86;--nuist-border:#e6edf0;background:#f5f8fa;padding:18px;border-radius:16px;margin-bottom:24px}
.dashboard-page,.dashboard-page *{box-sizing:border-box}
.dashboard-page>.row{display:flex;flex-wrap:wrap;gap:18px;margin:0}
.dashboard-page>.row>.dashboard-page__profile-column,.dashboard-page>.row>.dashboard-page__main-column{padding:0;max-width:none;width:100%;flex:0 0 100%;min-width:0}
.dashboard-page__profile-column{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.2fr);gap:18px;align-items:stretch}
.dashboard-page__profile-column>.card{margin:0!important;min-width:0;height:100%}
.dashboard-page .card{border:1px solid var(--nuist-border)!important;border-radius:14px!important;box-shadow:0 3px 14px rgba(25,45,55,.045)!important;background:#fff;overflow:hidden}
.dashboard-page .card-body{padding:20px!important}
.dashboard-page h3,.dashboard-page h4,.dashboard-page h5,.dashboard-page h6{color:var(--nuist-ink);overflow-wrap:anywhere}
.dashboard-page .text-muted{color:var(--nuist-muted)!important}
.dashboard-welcome-card__banner{background:linear-gradient(110deg,#08674e,#15946b);min-height:112px;position:relative}
.dashboard-welcome-card__banner .p-3{padding:22px!important}
.dashboard-welcome-card__banner h5{color:#fff!important;font-size:20px}
.dashboard-eyebrow{font-size:12px;letter-spacing:.06em;opacity:.8;text-transform:uppercase}
.dashboard-welcome-card__mark{font-size:48px;color:rgba(255,255,255,.22);margin-right:20px}
.dashboard-avatar img{width:76px;height:76px;object-fit:cover;border:4px solid #fff;box-shadow:0 4px 15px #0002}
.dashboard-welcome-card .profile-user-wid,
.dashboard-welcome-card .dashboard-avatar{position:static!important;top:auto!important;left:auto!important;margin:0!important;flex:0 0 96px;width:96px;height:96px;display:flex;align-items:center;justify-content:center;background:#fff;border-radius:50%;box-shadow:0 3px 12px rgba(25,45,55,.12)}
.dashboard-welcome-card .dashboard-avatar img{width:82px;height:82px;border:0;box-shadow:none}
.dashboard-welcome-card .card-body{padding-top:20px!important}
.dashboard-location-card{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr)}
.dashboard-location-card .card-body{height:100%}
.dashboard-location-info{border-right:1px solid var(--nuist-border)}
.dashboard-location-map #map-container,.dashboard-location-map #foundation-map-container{height:200px!important;box-shadow:none!important;border-radius:10px!important}
.dashboard-page__main-column{display:grid;grid-template-columns:minmax(0,1.65fr) minmax(300px,.9fr);gap:18px;align-items:start}
.dashboard-page__main-column>.card,.dashboard-page__main-column>.row{margin:0!important;min-width:0}
.dashboard-section-heading{grid-column:1/-1;background:#fff!important}
.dashboard-section-heading .card-body{padding:18px 22px!important}
.dashboard-section-heading h4{color:var(--nuist-ink)!important;font-size:19px;margin-bottom:4px!important}
.dashboard-section-heading p{color:var(--nuist-muted)!important}
.dashboard-section-heading .avatar-lg{display:none}
.dashboard-admin-primary-stats,.dashboard-super-admin-stats>.row{grid-column:1/-1;display:grid!important;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin:0!important}
.dashboard-super-admin-stats>.row{grid-template-columns:repeat(3,minmax(0,1fr))}
.dashboard-admin-primary-stats>[class*=col-],.dashboard-super-admin-stats>.row>[class*=col-]{width:100%;max-width:none;min-width:0;padding:0}
.dashboard-page .dashboard-stat-card{background:#fff!important;min-height:122px}
.dashboard-page .dashboard-stat-card .card-body{padding:18px!important}
.dashboard-page .dashboard-stat-card h3,.dashboard-page .dashboard-stat-card h4{color:var(--nuist-ink)!important;font-size:24px!important;font-weight:700;line-height:1.25}
.dashboard-page .dashboard-stat-card p{color:var(--nuist-muted)!important;font-size:13px!important}
.dashboard-page .dashboard-stat-card .avatar-title{background:#e6f5ee!important;color:var(--nuist-green)!important}
.dashboard-page .dashboard-stat-card .progress{display:none}
.dashboard-status-summary,.dashboard-super-status-summary{grid-column:2;grid-row:auto}
.dashboard-detail-table,.dashboard-super-detail-table{grid-column:1;grid-row:auto}
.dashboard-attendance-card{grid-column:2}
.dashboard-super-attendance{grid-column:2;grid-row:auto}
.dashboard-attendance-rings{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;align-items:start}
.dashboard-attendance-ring{width:76px;height:76px;margin:0 auto 8px;display:grid;place-items:center;border-radius:50%;background:conic-gradient(var(--ring-color) var(--ring-value),#edf1f2 0);position:relative;font-weight:700;color:var(--nuist-ink)}
.dashboard-attendance-ring:after{content:'';position:absolute;inset:8px;border-radius:50%;background:#fff}
.dashboard-attendance-ring span{position:relative;z-index:1}
.dashboard-page .dashboard-status-card{box-shadow:none!important;background:#f8fafb!important}
.dashboard-page .dashboard-status-summary .row,.dashboard-page .dashboard-super-status-summary .row{gap:10px}
.dashboard-page .dashboard-status-summary .row>[class*=col-],.dashboard-page .dashboard-super-status-summary .row>[class*=col-]{width:100%;flex:0 0 100%}
.dashboard-page .dashboard-status-card .card-body{display:grid;grid-template-columns:40px 1fr auto;align-items:center;text-align:left!important;gap:8px;padding:10px 12px!important}
.dashboard-page .dashboard-status-card .avatar-md{grid-column:1;grid-row:1/3;margin:0!important}
.dashboard-page .dashboard-status-card h5{grid-column:3;grid-row:1/3;margin:0!important}
.dashboard-page .dashboard-status-card p{grid-column:2;grid-row:1/3;margin:0!important}
.dashboard-page .dashboard-status-card .progress,.dashboard-page .dashboard-status-card small{display:none!important}
.dashboard-page .table{font-size:13px}
.dashboard-page .table td,.dashboard-page .table th{padding-top:12px!important;padding-bottom:12px!important}
.dashboard-page .progress{background:#e9eff1}
.dashboard-page .progress-bar{background:var(--nuist-green)!important}
.dashboard-page .btn-primary,.dashboard-page .btn-success{background:var(--nuist-green);border-color:var(--nuist-green)}
.dashboard-page .badge{font-weight:500}
.dashboard-page .hover-lift{transition:transform .18s ease,box-shadow .18s ease}
.dashboard-page .hover-lift:hover{transform:translateY(-2px);box-shadow:0 8px 24px #193b3415!important}
@media(max-width:1199.98px){.dashboard-page__main-column{grid-template-columns:minmax(0,1fr) minmax(260px,.85fr)}.dashboard-admin-primary-stats{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:991.98px){.dashboard-page__profile-column{grid-template-columns:1fr}.dashboard-location-card{grid-template-columns:1fr 1fr}.dashboard-page__main-column{grid-template-columns:1fr}.dashboard-section-heading,.dashboard-admin-primary-stats,.dashboard-super-admin-stats>.row,.dashboard-status-summary,.dashboard-super-status-summary,.dashboard-detail-table,.dashboard-super-detail-table,.dashboard-attendance-card,.dashboard-super-attendance{grid-column:1;grid-row:auto}.dashboard-super-admin-stats>.row{grid-template-columns:repeat(3,minmax(0,1fr))}}
@media(max-width:575.98px){.dashboard-page{padding:10px;margin:0 -10px 16px;border-radius:0}.dashboard-page>.row,.dashboard-page__profile-column,.dashboard-page__main-column{gap:12px}.dashboard-location-card{grid-template-columns:1fr}.dashboard-location-info{border-right:0;border-bottom:1px solid var(--nuist-border)}.dashboard-admin-primary-stats,.dashboard-super-admin-stats>.row{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.dashboard-page .card-body{padding:15px!important}.dashboard-page .dashboard-stat-card h3,.dashboard-page .dashboard-stat-card h4{font-size:19px!important}.dashboard-page .dashboard-stat-card .card-body{padding:13px!important}.dashboard-page .table{min-width:460px}}
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

        document.querySelectorAll(".nuist-attendance-chart").forEach(function (chartElement) {
            new ApexCharts(chartElement, options).render();
        });


    });
</script>
@endsection
