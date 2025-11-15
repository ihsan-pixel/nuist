@extends('layouts.master')

@section('title', 'Dashboard PPDB - Super Admin')

@push('css')
<style>
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }

    .welcome-section {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 75, 76, 0.2);
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(50px, -50px);
    }

    .welcome-content {
        position: relative;
        z-index: 1;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card.total-sekolah {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stat-card.total-pendaftar {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .stat-card.sekolah-buka {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .stat-card.pending {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .stat-card.verifikasi {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #004b4c;
    }

    .stat-card.lulus {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .stat-card.tidak-lulus {
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        color: white;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }

    .stat-icon {
        position: relative;
        z-index: 1;
    }

    .kabupaten-group {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 15px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .kabupaten-header {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: between;
    }

    .kabupaten-header i {
        margin-right: 0.5rem;
        opacity: 0.9;
    }

    .kabupaten-stats {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }

    .kabupaten-stats .row > div {
        text-align: center;
        padding: 0.5rem 0;
    }

    .kabupaten-stats .fw-bold {
        font-size: 1.1rem;
        color: #004b4c;
    }

    .kabupaten-stats small {
        color: #6c757d;
        font-size: 0.8rem;
    }

    .kabupaten-table {
        background: white;
    }

    .kabupaten-table .table {
        margin-bottom: 0;
        border-radius: 0;
    }

    .kabupaten-table .table thead th {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        color: white;
        border: none;
        font-weight: 600;
        padding: 1rem;
        border-bottom: 2px solid #dee2e6;
    }

    .kabupaten-table .table tbody tr {
        transition: background-color 0.3s ease;
        border-bottom: 1px solid #f1f3f4;
    }

    .kabupaten-table .table tbody tr:hover {
        background-color: rgba(0, 75, 76, 0.05);
    }

    .badge-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
        border: 1px solid;
        font-size: 0.8rem;
    }

    .badge-buka {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-color: #28a745;
    }

    .badge-tutup {
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        color: white;
        border-color: #dc3545;
    }

    .badge-belum-buka {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: #004b4c;
        border-color: #ffc107;
    }

    .badge-tidak-aktif {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        border-color: #6c757d;
    }

    .sekolah-name {
        font-weight: 600;
        color: #004b4c;
        margin-bottom: 0.25rem;
    }

    .kabupaten-info {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
    }

    .action-btn {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        border: 1px solid #004b4c;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        font-size: 0.9rem;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 75, 76, 0.3);
        color: white;
        background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
    }

    .btn-outline-primary {
        border-color: #004b4c;
        color: #004b4c;
    }

    .btn-outline-primary:hover {
        background-color: #004b4c;
        border-color: #004b4c;
        color: white;
    }

    .btn-primary {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        border: none;
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        transform: translateY(-1px);
        color: white;
    }

    .table-responsive {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    #statusChart {
        min-height: 320px !important;
        height: 320px !important;
        width: 100% !important;
    }

    .animate-fade-in {
        animation: fadeIn 0.8s ease-out;
    }

    .animate-slide-up {
        animation: slideUp 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .welcome-section {
            padding: 1.5rem;
        }

        .stat-card {
            margin-bottom: 1rem;
            padding: 1rem;
        }

        .stat-number {
            font-size: 2rem;
        }

        .kabupaten-header {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .kabupaten-stats {
            padding: 0.75rem 1rem;
        }

        .kabupaten-stats .row > div {
            margin-bottom: 0.5rem;
        }
    }

    .text-dark {
        color: #004b4c !important;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .fw-semibold {
        font-weight: 600;
    }

    .fw-medium {
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Section -->
    <div class="section-wrapper mb-4">
        <div class="welcome-section animate-fade-in">
            <div class="welcome-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="mb-2">
                            <i class="mdi mdi-view-dashboard me-2"></i>
                            Dashboard PPDB Super Admin
                        </h2>
                        <p class="mb-0 opacity-75">Pantau dan kelola pendaftaran siswa baru di seluruh madrasah Ma'arif</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="d-flex align-items-center justify-content-lg-end">
                            <i class="mdi mdi-calendar-clock me-2"></i>
                            <span class="fw-semibold">Tahun {{ date('Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Overview Header -->
    <div class="section-wrapper mb-4">
        <div class="card border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="text-dark mb-1">Ringkasan PPDB</h4>
                        <p class="text-muted mb-0">Data pendaftaran siswa baru tahun {{ date('Y') }}</p>
                    </div>
                    <div class="avatar-lg">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="mdi mdi-chart-bar fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Primary Statistics Row -->
    <div class="section-wrapper mb-4">
        <div class="row g-3">
            {{-- Total Madrasah Card --}}
            <div class="col-lg-6 col-xl-3">
                <div class="stat-card h-100 hover-lift total-sekolah">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ number_format($statistik['total_sekolah']) }}</h3>
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

            {{-- Total Pendaftar Card --}}
            <div class="col-lg-6 col-xl-3">
                <div class="stat-card h-100 hover-lift total-pendaftar">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ number_format($statistik['total_pendaftar']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6">Total Pendaftar</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-plus fs-3"></i>
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

            {{-- PPDB Aktif Card --}}
            <div class="col-lg-6 col-xl-3">
                <div class="stat-card h-100 hover-lift sekolah-buka">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ number_format($statistik['total_buka']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6">PPDB Aktif</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-check-circle fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: {{ $statistik['total_sekolah'] > 0 ? round(($statistik['total_buka'] / $statistik['total_sekolah']) * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pending Card --}}
            <div class="col-lg-6 col-xl-3">
                <div class="stat-card h-100 hover-lift pending">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ number_format($statistik['pending']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6">Menunggu Verifikasi</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-clock-outline fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: {{ $statistik['total_pendaftar'] > 0 ? round(($statistik['pending'] / $statistik['total_pendaftar']) * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Statistics Row -->
    <div class="section-wrapper mb-4">
        <div class="row g-3">
            {{-- Verifikasi Card --}}
            <div class="col-lg-4">
                <div class="stat-card h-100 hover-lift verifikasi">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="text-dark mb-1">{{ number_format($statistik['verifikasi']) }}</h4>
                                <p class="text-muted mb-0 small">Dalam Verifikasi</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                    <i class="mdi mdi-magnify fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lulus Card --}}
            <div class="col-lg-4">
                <div class="stat-card h-100 hover-lift lulus">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="text-white mb-1">{{ number_format($statistik['lulus']) }}</h4>
                                <p class="text-white-75 mb-0 small">Lulus Seleksi</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-check-circle fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tidak Lulus Card --}}
            <div class="col-lg-4">
                <div class="stat-card h-100 hover-lift tidak-lulus">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="text-white mb-1">{{ number_format($statistik['tidak_lulus']) }}</h4>
                                <p class="text-white-75 mb-0 small">Tidak Lulus</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-close-circle fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Pendaftaran per Kabupaten -->
    <div class="section-wrapper">
        <div class="animate-slide-up">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 text-dark">
                        <i class="mdi mdi-map-marker-multiple me-2 text-primary"></i>
                        Detail Pendaftaran per Kabupaten
                    </h4>
                        <p class="text-muted mb-0">Data pendaftaran dikelompokkan berdasarkan kabupaten</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="window.location.reload()">
                            <i class="mdi mdi-refresh me-1"></i>Refresh
                        </button>
                        <button class="btn btn-primary" onclick="exportData()">
                            <i class="mdi mdi-download me-1"></i>Export Data
                        </button>
                    </div>
                </div>
            </div>



            @php
                $groupedByKabupaten = collect($detailSekolah)->groupBy(function($item) {
                    return $item['sekolah']->kabupaten;
                })->sortKeys();
            @endphp

            @forelse($groupedByKabupaten as $kabupaten => $sekolahs)
                @php
                    $kabupatenStats = [
                        'total_madrasah' => $sekolahs->count(),
                        'total_pendaftar' => $sekolahs->sum('total'),
                        'total_lulus' => $sekolahs->sum('lulus'),
                        'total_pending' => $sekolahs->sum('pending'),
                        'total_verifikasi' => $sekolahs->sum('verifikasi'),
                        'total_buka' => $sekolahs->where('status_ppdb', 'buka')->count()
                    ];
                @endphp

                <div class="kabupaten-group">
                    <div class="kabupaten-header">
                        <i class="mdi mdi-city"></i>
                        <span>{{ $kabupaten }}</span>
                        <div class="ms-auto">
                            <small class="badge bg-primary bg-opacity-10 text-primary me-2">
                                {{ $kabupatenStats['total_madrasah'] }} Madrasah
                            </small>
                            <small class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $kabupatenStats['total_pendaftar'] }} Pendaftar
                            </small>
                        </div>
                    </div>

                    <div class="kabupaten-stats">
                        <div class="row g-3">
                            <div class="col-md-2 col-6">
                                <div class="text-center">
                                    <div class="fw-bold text-dark">{{ $kabupatenStats['total_buka'] }}</div>
                                    <small class="text-muted">PPDB Aktif</small>
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="text-center">
                                    <div class="fw-bold text-dark">{{ $kabupatenStats['total_lulus'] }}</div>
                                    <small class="text-muted">Lulus</small>
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="text-center">
                                    <div class="fw-bold text-dark">{{ $kabupatenStats['total_pending'] }}</div>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="text-center">
                                    <div class="fw-bold text-dark">{{ $kabupatenStats['total_verifikasi'] }}</div>
                                    <small class="text-muted">Verifikasi</small>
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="text-center">
                                    <div class="fw-bold text-dark">
                                        {{ $kabupatenStats['total_pendaftar'] > 0 ? round(($kabupatenStats['total_lulus'] / $kabupatenStats['total_pendaftar']) * 100, 1) : 0 }}%
                                    </div>
                                    <small class="text-muted">Kelulusan</small>
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="text-center">
                                    <div class="fw-bold text-dark">{{ $kabupatenStats['total_madrasah'] }}</div>
                                    <small class="text-muted">Total Madrasah</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="kabupaten-table">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="mdi mdi-school me-1"></i>Nama Madrasah/Sekolah</th>
                                        <th><i class="mdi mdi-information me-1"></i>Status PPDB</th>
                                        <th class="text-center"><i class="mdi mdi-account-plus me-1"></i>Total</th>
                                        <th class="text-center"><i class="mdi mdi-clock-outline me-1"></i>Pending</th>
                                        <th class="text-center"><i class="mdi mdi-magnify me-1"></i>Verifikasi</th>
                                        <th class="text-center"><i class="mdi mdi-check-circle me-1"></i>Lulus</th>
                                        <th class="text-center"><i class="mdi mdi-close-circle me-1"></i>Tidak Lulus</th>
                                        <th class="text-center"><i class="mdi mdi-chart-line me-1"></i>Kelulusan</th>
                                        <th><i class="mdi mdi-cog me-1"></i>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sekolahs->sortBy(function($item) {
                                        return $item['sekolah']->name;
                                    }) as $detail)
                                    <tr>
                                        <td>
                                            <div class="sekolah-name">{{ $detail['sekolah']->name }}</div>
                                            <div class="kabupaten-info">{{ $detail['sekolah']->kabupaten }}, {{ $detail['sekolah']->provinsi }}</div>
                                        </td>
                                        <td>
                                            @if($detail['status_ppdb'] === 'buka')
                                                <span class="badge badge-buka">Buka</span>
                                            @elseif($detail['status_ppdb'] === 'tutup')
                                                <span class="badge badge-tutup">Tutup</span>
                                            @elseif($detail['status_ppdb'] === 'belum_buka')
                                                <span class="badge badge-belum-buka">Belum Buka</span>
                                            @else
                                                <span class="badge badge-tidak-aktif">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ number_format($detail['total']) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ number_format($detail['pending']) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ number_format($detail['verifikasi']) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ number_format($detail['lulus']) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">{{ number_format($detail['tidak_lulus']) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $detail['total'] > 0 ? ($detail['lulus'] / $detail['total'] > 0.5 ? 'bg-success' : 'bg-warning') : 'bg-secondary' }}">
                                                {{ $detail['total'] > 0 ? round(($detail['lulus'] / $detail['total']) * 100, 1) : 0 }}%
                                            </span>
                                        </td>
                                        <td>
                                            @if($detail['ppdb_setting'])
                                                <a href="{{ route('ppdb.sekolah.dashboard', $detail['ppdb_setting']->slug) }}" class="action-btn btn-sm">
                                                    <i class="mdi mdi-eye me-1"></i>Lihat Detail
                                                </a>
                                            @else
                                                <button class="btn btn-outline-secondary btn-sm" disabled>
                                                    <i class="mdi mdi-block-helper me-1"></i>Tidak Aktif
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle">
                                <i class="mdi mdi-information-outline fs-1"></i>
                            </div>
                        </div>
                        <h5 class="text-muted">Belum ada data madrasah</h5>
                    </div>
                </div>
            @endempty
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let statusChartInstance;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Chart
    const canvas = document.getElementById('statusChart');
    if (!canvas) return;

    // Pastikan wrapper memberi tinggi
    canvas.style.height = "320px";

    // Destroy jika sebelumnya ada instance
    if (statusChartInstance) {
        statusChartInstance.destroy();
    }

    statusChartInstance = new Chart(canvas.getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Pending', 'Verifikasi', 'Lulus', 'Tidak Lulus'],
            datasets: [{
                data: [
                    {{ $statistik['pending'] }},
                    {{ $statistik['verifikasi'] }},
                    {{ $statistik['lulus'] }},
                    {{ $statistik['tidak_lulus'] }}
                ],
                backgroundColor: [
                    '#ffc107',
                    '#0dcaf0',
                    '#198754',
                    '#dc3545'
                ],
                borderColor: [
                    '#ffc107',
                    '#0dcaf0',
                    '#198754',
                    '#dc3545'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // WAJIB!
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
});
</script>
<script>
function exportData() {
    // Implementasi export data
    const confirmed = confirm('Apakah Anda ingin mengexport data PPDB dalam format Excel?');
    if (confirmed) {
        // Simulasi loading
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i>Memproses...';
        btn.disabled = true;

        // Simulasi proses export
        setTimeout(() => {
            alert('Fitur export akan segera diimplementasikan. Data akan dapat didownload dalam format Excel.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 2000);
    }
}

// jQuery implementation for animations and interactions
$(document).ready(function() {
    let autoRefreshInterval;

    // Function to start auto refresh
    function startAutoRefresh() {
        autoRefreshInterval = setInterval(function() {
            // Uncomment untuk auto refresh otomatis setiap 5 menit
            // window.location.reload();
            console.log('Auto refresh would trigger here (5 minutes)');
        }, 300000); // 5 minutes
    }

    // Function to stop auto refresh
    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
    }

    // Add smooth animations on page load
    $('.stat-card').each(function(index) {
        $(this).css('opacity', '0').delay(index * 100).animate({opacity: 1}, 500);
    });

    $('.kabupaten-group').each(function(index) {
        $(this).css('opacity', '0').delay(index * 150).animate({opacity: 1}, 600);
    });

    $('.chart-container').css('opacity', '0').delay(300).animate({opacity: 1}, 800);

    // Start auto refresh
    startAutoRefresh();

    // Optional: Add click handlers for interactive elements
    $('.action-btn').on('click', function(e) {
        const href = $(this).attr('href');
        if (href && href !== '#') {
            // Add loading state
            const originalText = $(this).html();
            $(this).html('<i class="mdi mdi-loading mdi-spin me-1"></i>Loading...');

            // Navigate after short delay for UX
            setTimeout(() => {
                window.location.href = href;
            }, 300);
        }
    });

    // Add tooltip for badges
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Handle window focus/blur for auto refresh
    $(window).focus(function() {
        console.log('Window focused - auto refresh active');
    });

    $(window).blur(function() {
        console.log('Window blurred - auto refresh paused');
        stopAutoRefresh();
    });

    // Cleanup on page unload
    $(window).on('beforeunload', function() {
        stopAutoRefresh();
    });
});
</script>
@endpush
@endsection
