{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.master')

@section('title') Dashboard - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') Dashboards @endslot
    @slot('title') Dashboard @endslot
@endcomponent

<div class="row">
    <div class="col-4">
        <!-- Welcome Card - Mobile Optimized -->
        <div class="card overflow-hidden mb-3">
            <div class="bg-success-subtle">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="text-success p-3">
                            <h5 class="text-success">Selamat Datang!</h5>
                            <p class="mb-0">Aplikasi NUIST</p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        {{-- <img src="{{ asset('build/images/logo 1.png') }}" alt="" class="img-fluid" style="max-height: 60px;"> --}}
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-lg profile-user-wid mb-3 mb-md-0">
                            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}" alt="" class="img-thumbnail rounded-circle">
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="font-size-16 mb-1">{{ Str::ucfirst(Auth::user()->name) }}</h5>
                        <p class="text-muted mb-2">Nuist ID : {{ Auth::user()->nuist_id ?? '-' }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            <small class="badge bg-primary-subtle text-primary">{{ Auth::user()->statusKepegawaian ? Auth::user()->statusKepegawaian->name : '-' }}</small>
                            <small class="badge bg-info-subtle text-info">{{ Auth::user()->ketugasan ?? '-' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Madrasah Location and Map - Positioned below welcome card on left side --}}
        @if(Auth::user()->role === 'admin' && isset($madrasahData))
        <div class="row">
            {{-- Address Information --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-map-marker text-primary me-2"></i>
                            Alamat Madrasah
                        </h5>
                        <div class="mb-3">
                            <h6 class="mb-2">{{ $madrasahData->name }}</h6>
                            <p class="text-muted mb-2">
                                <i class="mdi mdi-map-marker-outline me-1"></i>
                                {{ $madrasahData->alamat ?? 'Alamat belum diisi' }}
                            </p>
                            @if($madrasahData->map_link)
                            <a href="{{ $madrasahData->map_link }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="mdi mdi-google-maps me-1"></i>
                                Lihat di Google Maps
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Map Display --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-map text-success me-2"></i>
                            Lokasi Madrasah
                        </h5>
                        <div id="map-container" style="height: 300px; border-radius: 8px; overflow: hidden;">
                            @if($madrasahData->latitude && $madrasahData->longitude)
                                <div id="map" style="height: 100%; width: 100%;"></div>
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light">
                                    <i class="mdi mdi-map-marker-off text-muted fs-1 mb-3"></i>
                                    <h6 class="text-muted">Koordinat belum tersedia</h6>
                                    <p class="text-muted text-center small">
                                        Koordinat latitude dan longitude belum diisi untuk menampilkan peta
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Foundation Location and Map for Super Admin and Pengurus --}}
        @if(in_array(Auth::user()->role, ['super_admin', 'pengurus']) && isset($foundationData))
        <div class="row">
            {{-- Address Information --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-map-marker text-primary me-2"></i>
                            Alamat Yayasan
                        </h5>
                        <div class="mb-3">
                            <h6 class="mb-2">{{ $foundationData->name }}</h6>
                            <p class="text-muted mb-2">
                                <i class="mdi mdi-map-marker-outline me-1"></i>
                                {{ $foundationData->alamat ?? 'Alamat belum diisi' }}
                            </p>
                            @if($foundationData->map_link)
                            <a href="{{ $foundationData->map_link }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="mdi mdi-google-maps me-1"></i>
                                Lihat di Google Maps
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Map Display --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-map text-success me-2"></i>
                            Lokasi Yayasan
                        </h5>
                        <div id="foundation-map-container" style="height: 300px; border-radius: 8px; overflow: hidden;">
                            @if($foundationData->latitude && $foundationData->longitude)
                                <div id="foundation-map" style="height: 100%; width: 100%;"></div>
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light">
                                    <i class="mdi mdi-map-marker-off text-muted fs-1 mb-3"></i>
                                    <h6 class="text-muted">Koordinat belum tersedia</h6>
                                    <p class="text-muted text-center small">
                                        Koordinat latitude dan longitude belum diisi untuk menampilkan peta
                                    </p>
                                </div>
                            @endif
                        </div>
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
    <div class="col-xl-8">
        <div class="row">
            {{-- Total Teachers Card --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <i class="mdi mdi-account-tie fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $adminStats['total_teachers'] }}</h5>
                                <p class="text-muted mb-0">Total Tenaga Pendidik</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- School Principal Card --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                    <i class="mdi mdi-account-tie fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                @if($schoolPrincipal)
                                    <h6 class="mb-1">{{ $schoolPrincipal->name }}</h6>
                                    <p class="text-muted mb-0">Kepala Sekolah</p>
                                @else
                                    <h6 class="mb-1 text-muted">-</h6>
                                    <p class="text-muted mb-0">Kepala Sekolah</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Madrasah Info Card --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                    <i class="mdi mdi-school fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ Auth::user()->madrasah ? Auth::user()->madrasah->name : 'N/A' }}</h6>
                                <p class="text-muted mb-0">Madrasah Saat Ini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Employment Status Breakdown --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Ringkasan Berdasarkan Status Kepegawaian</h5>
                <div class="row">
                    @if($adminStats['total_by_status']->count() > 0)
                        @foreach($adminStats['total_by_status'] as $status)
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="mdi mdi-account-tie fs-5"></i>
                                        </div>
                                    </div>
                                    <h6 class="mb-2">{{ $status['count'] }}</h6>
                                    <p class="text-muted mb-0">{{ $status['status_name'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="text-center py-4">
                                <i class="mdi mdi-information-outline text-muted fs-1"></i>
                                <p class="text-muted mt-2">Belum ada data status kepegawaian</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detailed Statistics Table --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Detail Statistik Tenaga Pendidik</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Status Kepegawaian</th>
                                <th>Jumlah</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($adminStats['total_by_status']->count() > 0)
                                @foreach($adminStats['total_by_status'] as $status)
                                <tr>
                                    <td>{{ $status['status_name'] }}</td>
                                    <td>{{ $status['count'] }}</td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ $adminStats['total_teachers'] > 0 ? round(($status['count'] / $adminStats['total_teachers']) * 100) : 0 }}%"
                                                 aria-valuenow="{{ $status['count'] }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="{{ $adminStats['total_teachers'] }}">
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            {{ $adminStats['total_teachers'] > 0 ? round(($status['count'] / $adminStats['total_teachers']) * 100, 1) : 0 }}%
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="table-info">
                                    <td><strong>Total</strong></td>
                                    <td><strong>{{ $adminStats['total_teachers'] }}</strong></td>
                                    <td><strong>100%</strong></td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <i class="mdi mdi-information-outline text-muted fs-4"></i>
                                        <p class="text-muted mt-2">Belum ada data untuk ditampilkan</p>
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

    {{-- Super Admin Statistics Section --}}
    @if(in_array(Auth::user()->role, ['super_admin', 'pengurus']) && isset($superAdminStats))
    <div class="col-xl-8">
        <!-- Statistics Overview Header -->
        <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="text-white mb-1">Dashboard Super Admin</h4>
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
            <div class="col-lg-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px;">
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
            <div class="col-lg-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px;">
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
            <div class="col-lg-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px;">
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
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 12px;">
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
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border-radius: 12px;">
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
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); border-radius: 12px;">
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
                            <div class="card border-0 shadow-sm h-100 hover-lift" style="border-radius: 12px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
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
                                            <div class="flex-grow-1 me-3">
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

    @if(!in_array(Auth::user()->role, ['admin', 'super_admin', 'pengurus']))
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
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ isset($user->avatar) ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                                     alt="Foto {{ $user->name }}"
                                     class="rounded-circle"
                                     width="50"
                                     height="50">
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $user->name }}</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <small class="badge bg-primary-subtle text-primary">{{ $user->ketugasan ?? '-' }}</small>
                                    <small class="badge bg-info-subtle text-info">{{ $user->statusKepegawaian ? $user->statusKepegawaian->name : '-' }}</small>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
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

<!-- Leaflet CSS and JS for map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
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

        // Initialize map if coordinates are available
        @if(isset($madrasahData) && $madrasahData->latitude && $madrasahData->longitude)
            var map = L.map('map').setView([{{ $madrasahData->latitude }}, {{ $madrasahData->longitude }}], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([{{ $madrasahData->latitude }}, {{ $madrasahData->longitude }}])
                .addTo(map)
                .bindPopup('<b>{{ $madrasahData->name }}</b><br>{{ $madrasahData->alamat ?? "Alamat tidak tersedia" }}')
                .openPopup();
        @endif

        // Initialize foundation map if coordinates are available
        @if(isset($foundationData) && $foundationData->latitude && $foundationData->longitude)
            var foundationMap = L.map('foundation-map').setView([{{ $foundationData->latitude }}, {{ $foundationData->longitude }}], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(foundationMap);

            var foundationMarker = L.marker([{{ $foundationData->latitude }}, {{ $foundationData->longitude }}])
                .addTo(foundationMap)
                .bindPopup('<b>{{ $foundationData->name }}</b><br>{{ $foundationData->alamat ?? "Alamat tidak tersedia" }}')
                .openPopup();
        @endif
    });
</script>
@endsection

