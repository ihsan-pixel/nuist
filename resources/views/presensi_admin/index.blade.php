@extends('layouts.master')

@section('title', 'Data Presensi')

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />

<!-- DataTables -->
<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

<style>
/* Modern PPDB Style CSS - Enhanced with !important for better specificity */
.welcome-section {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important;
    border-radius: 15px !important;
    padding: 2rem !important;
    margin-bottom: 2rem !important;
    color: white !important;
    position: relative !important;
    overflow: hidden !important;
    box-shadow: 0 4px 15px rgba(0, 75, 76, 0.2) !important;
}

.welcome-section::before {
    content: '' !important;
    position: absolute !important;
    top: 0 !important;
    right: 0 !important;
    width: 200px !important;
    height: 200px !important;
    background: rgba(255, 255, 255, 0.1) !important;
    border-radius: 50% !important;
    transform: translate(50px, -50px) !important;
}

.welcome-content {
    position: relative !important;
    z-index: 1 !important;
}

.stat-card {
    background: white !important;
    border-radius: 15px !important;
    padding: 1.5rem !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    border: none !important;
    transition: all 0.3s ease !important;
    position: relative !important;
    overflow: hidden !important;
}

.stat-card:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.stat-card.total-sekolah {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
}

.stat-card.sekolah-buka {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
    color: white !important;
}

.stat-card.pending {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
    color: white !important;
}

.stat-number {
    font-size: 2.5rem !important;
    font-weight: bold !important;
    margin-bottom: 0.5rem !important;
    position: relative !important;
    z-index: 1 !important;
}

.stat-label {
    font-size: 0.9rem !important;
    opacity: 0.9 !important;
    margin-bottom: 0 !important;
    position: relative !important;
    z-index: 1 !important;
}

.stat-icon {
    position: relative !important;
    z-index: 1 !important;
}

.kabupaten-group {
    background: white !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 15px !important;
    margin-bottom: 1.5rem !important;
    overflow: hidden !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
}

.kabupaten-header {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important;
    color: white !important;
    padding: 1rem 1.5rem !important;
    font-weight: 600 !important;
    font-size: 1.1rem !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
}

.kabupaten-header i {
    margin-right: 0.5rem !important;
    opacity: 0.9 !important;
}

.kabupaten-table {
    background: white !important;
}

.kabupaten-table .table {
    margin-bottom: 0 !important;
    border-radius: 0 !important;
}

.kabupaten-table .table thead th {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important;
    color: white !important;
    border: none !important;
    font-weight: 600 !important;
    padding: 1rem !important;
    border-bottom: 2px solid #dee2e6 !important;
}

.kabupaten-table .table tbody tr {
    transition: background-color 0.3s ease !important;
    border-bottom: 1px solid #f1f3f4 !important;
}

.kabupaten-table .table tbody tr:hover {
    background-color: rgba(0, 75, 76, 0.05) !important;
}

.sekolah-name {
    font-weight: 600 !important;
    color: #004b4c !important;
    margin-bottom: 0.25rem !important;
}

.kabupaten-info {
    font-size: 0.85rem !important;
    color: #6c757d !important;
    font-weight: 500 !important;
}

.action-btn {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important;
    border: 1px solid #004b4c !important;
    border-radius: 8px !important;
    padding: 0.5rem 1rem !important;
    color: white !important;
    text-decoration: none !important;
    transition: all 0.3s ease !important;
    position: relative !important;
    overflow: hidden !important;
    font-size: 0.9rem !important;
}

.action-btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 15px rgba(0, 75, 76, 0.3) !important;
    color: white !important;
    background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%) !important;
}

.hover-lift {
    transition: all 0.3s ease !important;
}

.hover-lift:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.animate-fade-in {
    animation: fadeIn 0.8s ease-out !important;
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out !important;
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

.status-badge {
    padding: 0.25rem 0.5rem !important;
    border-radius: 4px !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
}

@media (max-width: 768px) {
    .welcome-section {
        padding: 1.5rem !important;
    }

    .stat-card {
        margin-bottom: 1rem !important;
        padding: 1rem !important;
    }

    .stat-number {
        font-size: 2rem !important;
    }

    .kabupaten-header {
        padding: 0.75rem 1rem !important;
        font-size: 1rem !important;
    }
}

.text-dark {
    color: #004b4c !important;
}

.text-muted {
    color: #6c757d !important;
}

.fw-semibold {
    font-weight: 600 !important;
}

.fw-medium {
    font-weight: 500 !important;
}

/* Additional specificity for modal styles */
.modal-content {
    border-radius: 15px !important;
    border: none !important;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1) !important;
}

.modal-header {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important;
    color: white !important;
    border-radius: 15px 15px 0 0 !important;
    border-bottom: none !important;
}

.modal-header .modal-title {
    font-weight: 600 !important;
}

.btn-close-white {
    filter: invert(1) !important;
}

.modal-footer {
    border-top: none !important;
    border-radius: 0 0 15px 15px !important;
}

/* Enhanced Modal Styles for Comprehensive School Details */
.comprehensive-modal .modal-dialog {
    max-width: 95% !important;
    margin: 1rem auto !important;
}

.school-info-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%) !important;
    border-radius: 12px !important;
    padding: 1.5rem !important;
    margin-bottom: 1.5rem !important;
    border: 1px solid #dee2e6 !important;
}

.school-info-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
    gap: 1rem !important;
}

.info-item {
    display: flex !important;
    flex-direction: column !important;
    gap: 0.25rem !important;
}

.info-label {
    font-weight: 600 !important;
    color: #004b4c !important;
    font-size: 0.9rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
}

.info-value {
    font-size: 1rem !important;
    color: #495057 !important;
    word-break: break-word !important;
}

.map-section {
    background: white !important;
    border-radius: 12px !important;
    padding: 1.5rem !important;
    margin-bottom: 1.5rem !important;
    border: 1px solid #dee2e6 !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
}

.map-container {
    height: 400px !important;
    width: 100% !important;
    border-radius: 8px !important;
    border: 2px solid #dee2e6 !important;
    overflow: hidden !important;
}

.staff-attendance-section {
    background: white !important;
    border-radius: 12px !important;
    padding: 1.5rem !important;
    border: 1px solid #dee2e6 !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
}

.staff-tabs .nav-tabs {
    border-bottom: 2px solid #dee2e6 !important;
    margin-bottom: 1.5rem !important;
}

.staff-tabs .nav-link {
    border: none !important;
    border-bottom: 3px solid transparent !important;
    color: #6c757d !important;
    font-weight: 600 !important;
    padding: 0.75rem 1.5rem !important;
    transition: all 0.3s ease !important;
}

.staff-tabs .nav-link.active {
    background: none !important;
    border-bottom-color: #004b4c !important;
    color: #004b4c !important;
}

.staff-tabs .nav-link:hover {
    border-bottom-color: #0e8549 !important;
    color: #0e8549 !important;
}

.staff-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
    gap: 1rem !important;
}

.staff-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 10px !important;
    padding: 1rem !important;
    transition: all 0.3s ease !important;
    position: relative !important;
    overflow: hidden !important;
}

.staff-card:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
}

.staff-card::before {
    content: '' !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 4px !important;
    height: 100% !important;
    background: #004b4c !important;
}

.staff-card.hadir::before {
    background: #28a745 !important;
}

.staff-card.tidak-hadir::before {
    background: #dc3545 !important;
}

.staff-card.izin::before {
    background: #17a2b8 !important;
}

.staff-card.terlambat::before {
    background: #ffc107 !important;
}

.staff-header {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    margin-bottom: 0.75rem !important;
}

.staff-avatar {
    width: 40px !important;
    height: 40px !important;
    border-radius: 50% !important;
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: white !important;
    font-weight: 600 !important;
    margin-right: 0.75rem !important;
}

.staff-details {
    flex-grow: 1 !important;
}

.staff-name {
    font-weight: 600 !important;
    color: #004b4c !important;
    margin-bottom: 0.25rem !important;
    font-size: 1rem !important;
}

.staff-position {
    font-size: 0.85rem !important;
    color: #6c757d !important;
    margin-bottom: 0.5rem !important;
}

.staff-status-badge {
    padding: 0.25rem 0.75rem !important;
    border-radius: 20px !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
}

.staff-status-badge.hadir {
    background: #d4edda !important;
    color: #155724 !important;
}

.staff-status-badge.tidak-hadir {
    background: #f8d7da !important;
    color: #721c24 !important;
}

.staff-status-badge.izin {
    background: #cce5ff !important;
    color: #004085 !important;
}

.staff-status-badge.terlambat {
    background: #fff3cd !important;
    color: #856404 !important;
}

.staff-times {
    margin-top: 0.75rem !important;
    padding-top: 0.75rem !important;
    border-top: 1px solid #dee2e6 !important;
}

.time-item {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin-bottom: 0.25rem !important;
}

.time-label {
    font-size: 0.8rem !important;
    color: #6c757d !important;
    font-weight: 500 !important;
}

.time-value {
    font-size: 0.85rem !important;
    color: #495057 !important;
    font-weight: 600 !important;
}

.location-info {
    margin-top: 0.5rem !important;
    font-size: 0.75rem !important;
    color: #6c757d !important;
}

.location-info i {
    margin-right: 0.25rem !important;
}

.empty-state {
    text-align: center !important;
    padding: 3rem 1rem !important;
    color: #6c757d !important;
}

.empty-state i {
    font-size: 3rem !important;
    margin-bottom: 1rem !important;
    opacity: 0.5 !important;
}

.empty-state h5 {
    margin-bottom: 0.5rem !important;
    color: #495057 !important;
}

.date-display {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important;
    color: white !important;
    padding: 1rem 1.5rem !important;
    border-radius: 10px !important;
    margin-bottom: 1.5rem !important;
    text-align: center !important;
}

.date-display h4 {
    margin: 0 !important;
    font-weight: 600 !important;
}

.date-display p {
    margin: 0.25rem 0 0 0 !important;
    opacity: 0.9 !important;
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Presensi Admin @endslot
    @slot('title') Data Presensi @endslot
@endcomponent

@if(in_array($user->role, ['super_admin', 'pengurus']))
    <!-- Header Section - Modern PPDB Style -->
    <div class="welcome-section mb-4" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important; border-radius: 15px !important; padding: 2rem !important; margin-bottom: 2rem !important; color: white !important; position: relative !important; overflow: hidden !important; box-shadow: 0 4px 15px rgba(0, 75, 76, 0.2) !important;">
        <div class="welcome-content" style="position: relative !important; z-index: 1 !important;">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="mb-2">
                        <i class="mdi mdi-view-dashboard me-2"></i>
                        Data Presensi Tenaga Pendidik
                    </h2>
                    <p class="mb-0 opacity-75">Pantau dan kelola presensi tenaga pendidik di seluruh madrasah Ma'arif</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex align-items-center justify-content-lg-end">
                        <i class="mdi mdi-calendar-clock me-2"></i>
                        <span class="fw-semibold">{{ $selectedDate->format('d F Y') }}</span>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <input type="date" wire:model.live="selectedDate" class="form-control form-control-sm rounded-pill"
                               value="{{ $selectedDate->format('Y-m-d') }}" style="min-width: 140px;">
                        <a href="{{ route('presensi_admin.export', ['date' => $selectedDate->format('Y-m-d')]) }}"
                           class="btn btn-success btn-sm rounded-pill px-3">
                            <i class="bx bx-download me-1"></i>Export
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div style="content: '' !important; position: absolute !important; top: 0 !important; right: 0 !important; width: 200px !important; height: 200px !important; background: rgba(255, 255, 255, 0.1) !important; border-radius: 50% !important; transform: translate(50px, -50px) !important;"></div>
    </div>

    <!-- Primary Statistics Row -->
    <div class="section-wrapper mb-4">
        <div class="row g-3">
            {{-- Users Presensi Card --}}
            <div class="col-lg-4">
                <div class="stat-card h-100 hover-lift total-sekolah" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; border-radius: 15px !important; padding: 1.5rem !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important; border: none !important; transition: all 0.3s ease !important; position: relative !important; overflow: hidden !important; color: white !important;">
                    <div class="card-body p-4" style="padding: 1.5rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1" style="color: white !important;">{{ number_format($summary['users_presensi']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6" style="color: rgba(255, 255, 255, 0.75) !important;">Users Presensi</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle" style="background: rgba(255, 255, 255, 0.25) !important; color: white !important;">
                                    <i class="mdi mdi-account-check fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px !important; background: rgba(255, 255, 255, 0.25) !important;">
                                <div class="progress-bar bg-white" style="width: 100% !important; background: white !important;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sekolah Presensi Card --}}
            <div class="col-lg-4">
                <div class="stat-card h-100 hover-lift sekolah-buka" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important; border-radius: 15px !important; padding: 1.5rem !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important; border: none !important; transition: all 0.3s ease !important; position: relative !important; overflow: hidden !important; color: white !important;">
                    <div class="card-body p-4" style="padding: 1.5rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1" style="color: white !important;">{{ number_format($summary['sekolah_presensi']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6" style="color: rgba(255, 255, 255, 0.75) !important;">Sekolah Presensi</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle" style="background: rgba(255, 255, 255, 0.25) !important; color: white !important;">
                                    <i class="mdi mdi-school fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px !important; background: rgba(255, 255, 255, 0.25) !important;">
                                <div class="progress-bar bg-white" style="width: 100% !important; background: white !important;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Belum Presensi Card --}}
            <div class="col-lg-4">
                <div class="stat-card h-100 hover-lift pending" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important; border-radius: 15px !important; padding: 1.5rem !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important; border: none !important; transition: all 0.3s ease !important; position: relative !important; overflow: hidden !important; color: white !important;">
                    <div class="card-body p-4" style="padding: 1.5rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1" style="color: white !important;">{{ number_format($summary['guru_tidak_presensi']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6" style="color: rgba(255, 255, 255, 0.75) !important;">Belum Presensi</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle" style="background: rgba(255, 255, 255, 0.25) !important; color: white !important;">
                                    <i class="mdi mdi-account-clock fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px !important; background: rgba(255, 255, 255, 0.25) !important;">
                                <div class="progress-bar bg-white" style="width: 100% !important; background: white !important;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $kabupatenOrder = [
            'Kabupaten Gunungkidul',
            'Kabupaten Bantul',
            'Kabupaten Kulon Progo',
            'Kabupaten Sleman',
            'Kota Yogyakarta'
        ];
    @endphp

    @foreach($kabupatenOrder as $kabupaten)
        @php
            $kabupatenMadrasahData = collect($madrasahData)->filter(function($data) use ($kabupaten) {
                return $data['madrasah']->kabupaten === $kabupaten;
            });
        @endphp

        @if($kabupatenMadrasahData->count() > 0)
            <!-- Kabupaten Header - Modern PPDB Style -->
            <div class="kabupaten-group" style="background: white !important; border: 1px solid #dee2e6 !important; border-radius: 15px !important; margin-bottom: 1.5rem !important; overflow: hidden !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;">
                <div class="kabupaten-header" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important; color: white !important; padding: 1rem 1.5rem !important; font-weight: 600 !important; font-size: 1.1rem !important; display: flex !important; align-items: center !important; justify-content: space-between !important;">
                    <i class="mdi mdi-city"></i>
                    <span>{{ $kabupaten }}</span>
                    <div class="ms-auto">
                        <small class="badge bg-primary bg-opacity-10 text-primary me-2">
                            {{ $kabupatenMadrasahData->count() }} Madrasah
                        </small>
                    </div>
                </div>

                <!-- Madrasah Table - Modern PPDB Style -->
                <div class="kabupaten-table" style="background: white !important;">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="margin-bottom: 0 !important; border-radius: 0 !important;">
                            <thead style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important; color: white !important; border: none !important; font-weight: 600 !important; padding: 1rem !important; border-bottom: 2px solid #dee2e6 !important;">
                                <tr>
                                    <th><i class="mdi mdi-school me-1"></i>Nama Madrasah</th>
                                    <th><i class="mdi mdi-account-group me-1"></i>Tenaga Pendidik</th>
                                    <th><i class="mdi mdi-information me-1"></i>Status Presensi</th>
                                    <th><i class="mdi mdi-cog me-1"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kabupatenMadrasahData as $data)
                                <tr>
                                    <td>
                                        <div class="sekolah-name">
                                            <span class="fw-medium" style="color: #004b4c;">
                                                {{ $data['madrasah']->name }}
                                            </span>
                                        </div>
                                        <div class="kabupaten-info">{{ $kabupaten }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ count($data['presensi']) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $hadir = collect($data['presensi'])->where('status', 'hadir')->count();
                                            $total = count($data['presensi']);
                                            $persentase = $total > 0 ? round(($hadir / $total) * 100) : 0;
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <span class="badge {{ $persentase >= 80 ? 'bg-success' : ($persentase >= 50 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ $hadir }}/{{ $total }} ({{ $persentase }}%)
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <button type="button" class="btn btn-outline-info btn-sm comprehensive-detail-btn" data-madrasah-id="{{ $data['madrasah']->id }}" data-madrasah-name="{{ $data['madrasah']->name }}">
                                                <i class="mdi mdi-eye me-1"></i>Lihat Detail
                                            </button>
                                            @if($user->role === 'super_admin')
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exportModal" data-madrasah-id="{{ $data['madrasah']->id }}" data-madrasah-name="{{ $data['madrasah']->name }}">
                                                <i class="bx bx-download me-1"></i>Export
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- User Detail Modal - Modern PPDB Style -->
    <div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="border-radius: 15px !important; border: none !important; box-shadow: 0 10px 40px rgba(0,0,0,0.1) !important;">
                <div class="modal-header" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important; color: white !important; border-radius: 15px 15px 0 0 !important; border-bottom: none !important;">
                    <h5 class="modal-title" id="userDetailModalLabel" style="font-weight: 600 !important;">
                        <i class="mdi mdi-account-details me-2"></i>Detail Presensi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="userDetailTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Informasi Pengguna</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">Riwayat Presensi</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="userDetailTabContent">
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="mb-2"><strong>Nama:</strong> <span id="detail-name"></span></div>
                                    <div class="mb-2"><strong>Email:</strong> <span id="detail-email" class="text-muted"></span></div>
                                    <div class="mb-2"><strong>Madrasah:</strong> <span id="detail-madrasah"></span></div>
                                    <div class="mb-2"><strong>Status Kepegawaian:</strong> <span id="detail-status"></span></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2"><strong>NIP:</strong> <span id="detail-nip" class="text-muted"></span></div>
                                    <div class="mb-2"><strong>NUPTK:</strong> <span id="detail-nuptk" class="text-muted"></span></div>
                                    <div class="mb-2"><strong>No HP:</strong> <span id="detail-phone"></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="history" role="tabpanel">
                            <div class="table-responsive mt-3" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 100px;">Tanggal</th>
                                            <th style="width: 80px;">Masuk</th>
                                            <th style="width: 80px;">Keluar</th>
                                            <th style="width: 80px;">Status</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail-history-body">
                                        <!-- Data will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none !important; border-radius: 0 0 15px 15px !important;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px !important; padding: 0.5rem 1.5rem !important;">
                        <i class="mdi mdi-close me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal - Modern PPDB Style -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px !important; border: none !important; box-shadow: 0 10px 40px rgba(0,0,0,0.1) !important;">
                <div class="modal-header" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important; color: white !important; border-radius: 15px 15px 0 0 !important; border-bottom: none !important;">
                    <h5 class="modal-title" id="exportModalLabel" style="font-weight: 600 !important;">
                        <i class="mdi mdi-file-export me-2"></i>Export Data Presensi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Pilih jenis export untuk <strong id="exportMadrasahName"></strong>:</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" id="exportAllBtn">
                            <i class="bx bx-download me-2"></i>Export Semua Data
                        </button>
                        <button type="button" class="btn btn-outline-success" id="exportMonthBtn">
                            <i class="bx bx-calendar me-2"></i>Export Per Bulan
                        </button>
                    </div>
                    <div class="mt-3" id="monthSelector" style="display: none;">
                        <label for="exportMonth" class="form-label">Pilih Bulan:</label>
                        <input type="month" class="form-control" id="exportMonth" value="{{ date('Y-m') }}">
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none !important; border-radius: 0 0 15px 15px !important;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px !important; padding: 0.5rem 1.5rem !important;">
                        <i class="mdi mdi-close me-1"></i>Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Comprehensive School Detail Modal -->
    <div class="modal fade comprehensive-modal" id="comprehensiveDetailModal" tabindex="-1" aria-labelledby="comprehensiveDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="border-radius: 15px !important; border: none !important; box-shadow: 0 10px 40px rgba(0,0,0,0.1) !important;">
                <div class="modal-header" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important; color: white !important; border-radius: 15px 15px 0 0 !important; border-bottom: none !important;">
                    <h5 class="modal-title" id="comprehensiveDetailModalLabel" style="font-weight: 600 !important;">
                        <i class="mdi mdi-school me-2"></i>Detail Lengkap Madrasah
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Date Display -->
                    <div class="date-display">
                        <h4 id="modal-date-display"></h4>
                        <p id="modal-date-subtitle"></p>
                    </div>

                    <!-- School Information Section -->
                    <div class="school-info-section">
                        <h5 class="mb-3" style="color: #004b4c; font-weight: 600;">
                            <i class="mdi mdi-information-outline me-2"></i>Informasi Madrasah
                        </h5>
                        <div class="school-info-grid" id="school-info-grid">
                            <!-- School info will be populated here -->
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="map-section">
                        <h5 class="mb-3" style="color: #004b4c; font-weight: 600;">
                            <i class="mdi mdi-map-marker-radius me-2"></i>Lokasi & Area Presensi
                        </h5>
                        <div class="map-container" id="comprehensive-map"></div>
                        <small class="text-muted mt-2 d-block">
                            <i class="mdi mdi-information-outline me-1"></i>
                            Peta menampilkan lokasi madrasah dan area polygon presensi. Titik merah menunjukkan lokasi presensi pengguna.
                        </small>
                    </div>

                    <!-- Staff Attendance Section -->
                    <div class="staff-attendance-section">
                        <h5 class="mb-3" style="color: #004b4c; font-weight: 600;">
                            <i class="mdi mdi-account-group me-2"></i>Data Presensi Tenaga Pendidik
                        </h5>

                        <!-- Staff Tabs -->
                        <div class="staff-tabs">
                            <ul class="nav nav-tabs" id="staffAttendanceTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="hadir-tab" data-bs-toggle="tab" data-bs-target="#hadir-content" type="button" role="tab">
                                        <i class="mdi mdi-account-check me-1"></i>Hadir
                                        <span class="badge bg-success ms-1" id="hadir-count">0</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="terlambat-tab" data-bs-toggle="tab" data-bs-target="#terlambat-content" type="button" role="tab">
                                        <i class="mdi mdi-account-clock me-1"></i>Terlambat
                                        <span class="badge bg-warning ms-1" id="terlambat-count">0</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tidak-hadir-tab" data-bs-toggle="tab" data-bs-target="#tidak-hadir-content" type="button" role="tab">
                                        <i class="mdi mdi-account-remove me-1"></i>Tidak Hadir
                                        <span class="badge bg-danger ms-1" id="tidak-hadir-count">0</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="izin-tab" data-bs-toggle="tab" data-bs-target="#izin-content" type="button" role="tab">
                                        <i class="mdi mdi-account-edit me-1"></i>Izin
                                        <span class="badge bg-info ms-1" id="izin-count">0</span>
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="staffAttendanceTabContent">
                                <!-- Hadir Tab -->
                                <div class="tab-pane fade show active" id="hadir-content" role="tabpanel">
                                    <div class="staff-grid" id="hadir-staff-grid">
                                        <!-- Staff cards will be populated here -->
                                    </div>
                                </div>

                                <!-- Terlambat Tab -->
                                <div class="tab-pane fade" id="terlambat-content" role="tabpanel">
                                    <div class="staff-grid" id="terlambat-staff-grid">
                                        <!-- Staff cards will be populated here -->
                                    </div>
                                </div>

                                <!-- Tidak Hadir Tab -->
                                <div class="tab-pane fade" id="tidak-hadir-content" role="tabpanel">
                                    <div class="staff-grid" id="tidak-hadir-staff-grid">
                                        <!-- Staff cards will be populated here -->
                                    </div>
                                </div>

                                <!-- Izin Tab -->
                                <div class="tab-pane fade" id="izin-content" role="tabpanel">
                                    <div class="staff-grid" id="izin-staff-grid">
                                        <!-- Staff cards will be populated here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none !important; border-radius: 0 0 15px 15px !important;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px !important; padding: 0.5rem 1.5rem !important;">
                        <i class="mdi mdi-close me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Admin and other roles: Original view -->
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-body text-center py-2">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bx bx-user-check bx-sm text-primary me-2"></i>
                        <span class="h5 mb-0 text-primary fw-bold">{{ $summary['users_presensi'] }}</span>
                    </div>
                    <small class="text-muted d-block mt-1">Users Presensi</small>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center py-2">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bx bx-building bx-sm text-success me-2"></i>
                        <span class="h5 mb-0 text-success fw-bold">{{ $summary['sekolah_presensi'] }}</span>
                    </div>
                    <small class="text-muted d-block mt-1">Sekolah Presensi</small>
                </div>
            </div>
        </div> --}}
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-body text-center py-2">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bx bx-user-x bx-sm text-danger me-2"></i>
                        <span class="h5 mb-0 text-danger fw-bold">{{ $summary['guru_tidak_presensi'] }}</span>
                    </div>
                    <small class="text-muted d-block mt-1">Guru Belum Presensi</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="bx bx-list-ul me-2"></i>Data Presensi
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="mb-3 d-flex justify-content-end">
                        <a href="{{ route('izin.index') }}" class="btn btn-info">
                            <i class="bx bx-mail-send"></i> Kelola Izin
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama User</th>
                                    <th>Madrasah</th>
                                    <th>Status Kepegawaian</th>
                                    <th>Tanggal</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Keluar</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($presensis as $presensi)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $presensi->user->name ?? '-' }}</td>
                                    <td>{{ $presensi->user->madrasah->name ?? '-' }}</td>
                                    <td>{{ $presensi->statusKepegawaian->name ?? '-' }}</td>
                                        <td>{{ $presensi->tanggal }}</td>
                                        <td>
                                            @if($presensi->waktu_masuk)
                                                {{ $presensi->tanggal->copy()->setTimeFromTimeString($presensi->waktu_masuk->format('H:i:s'))->format('Y-m-d H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($presensi->waktu_keluar)
                                                {{ $presensi->tanggal->copy()->setTimeFromTimeString($presensi->waktu_keluar->format('H:i:s'))->format('Y-m-d H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    <td>
                                        @if($presensi->status == 'hadir')
                                            <span class="badge bg-success">Hadir</span>
                                        @elseif($presensi->status == 'terlambat')
                                            <span class="badge bg-warning">Terlambat</span>
                                        @elseif($presensi->status == 'izin')
                                            <span class="badge bg-info">Izin</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($presensi->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($presensi->keterangan && str_contains($presensi->keterangan, 'Terlambat'))
                                            <span class="text-danger">{{ $presensi->keterangan }}</span>
                                        @else
                                            {{ $presensi->keterangan }}
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center p-4">
                                        <div class="alert alert-info d-inline-block text-center" role="alert">
                                            <i class="bx bx-info-circle bx-lg me-2"></i>
                                            <strong>Belum ada data presensi</strong><br>
                                            <small>Silakan tambahkan data presensi terlebih dahulu untuk melanjutkan.</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="bx bx-user-x me-2"></i>Belum Melakukan Presensi pada tanggal {{ $selectedDate->format('d-m-Y') }}
                    </h4>
                    <form method="GET" action="{{ route('presensi_admin.index') }}" class="d-flex align-items-center">
                        <input type="date" name="date" class="form-control form-control-sm" value="{{ $selectedDate->format('Y-m-d') }}" onchange="this.form.submit()">
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dt-responsive nowrap w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    @if(in_array($user->role, ['super_admin', 'pengurus']))
                                    <th>Madrasah</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($belumPresensi as $userBelum)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $userBelum->name }}</td>
                                    @if(in_array($user->role, ['super_admin', 'pengurus']))
                                    <td>{{ $userBelum->madrasah->name ?? '-' }}</td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ in_array($user->role, ['super_admin', 'pengurus']) ? 3 : 2 }}" class="text-center p-4">
                                        <div class="alert alert-info d-inline-block text-center" role="alert">
                                            <i class="bx bx-info-circle bx-lg me-2"></i>
                                            <strong>Semua tenaga pendidik sudah melakukan presensi pada tanggal ini</strong><br>
                                            <small>Tidak ada data tenaga pendidik yang belum melakukan presensi.</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@section('script')
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<link href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<script>
$(document).ready(function () {
    @if(!in_array($user->role, ['super_admin', 'pengurus']))
    let table = $("#datatable-buttons").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
    @endif

    // Replace alert notifications with SweetAlert2
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif



    // Real-time update for super admin
    let currentDate = '{{ $selectedDate->format('Y-m-d') }}';
    let updateInterval;

    function updatePresensiData() {
        $.ajax({
            url: '{{ route('presensi_admin.data') }}',
            type: 'GET',
            data: { date: currentDate },
            success: function(data) {
                updateTables(data);
                // Update summary cards
                updateSummaryCards();
            },
            error: function(xhr, status, error) {
                console.log('Error updating data:', error);
            }
        });
    }

    function updateSummaryCards() {
        $.ajax({
            url: '{{ route('presensi_admin.summary') }}',
            type: 'GET',
            data: { date: currentDate },
            success: function(data) {
                // Update summary cards with JSON data
                $('.row.mb-4 .card .h5').eq(0).text(data.users_presensi);
                $('.row.mb-4 .card .h5').eq(1).text(data.sekolah_presensi);
                $('.row.mb-4 .card .h5').eq(2).text(data.guru_tidak_presensi);
            },
            error: function(xhr, status, error) {
                console.log('Error updating summary:', error);
            }
        });
    }

    function updateTables(data) {
        let globalIndex = 0;
        let kabupatenOrder = [
            'Kabupaten Gunungkidul',
            'Kabupaten Bantul',
            'Kabupaten Kulon Progo',
            'Kabupaten Sleman',
            'Kota Yogyakarta'
        ];

        kabupatenOrder.forEach(function(kabupaten, kabupatenIndex) {
            let kabupatenData = data.filter(function(madrasahData) {
                return madrasahData.madrasah && madrasahData.madrasah.kabupaten === kabupaten;
            });

            kabupatenData.forEach(function(madrasahData, localIndex) {
                let tableBody = $('#madrasah-table-' + kabupatenIndex + '-' + localIndex + ' tbody');
                tableBody.empty();

                if (madrasahData.presensi && madrasahData.presensi.length > 0) {
                    madrasahData.presensi.forEach(function(presensi) {
                        let statusBadge = '';
                        if (presensi.status === 'hadir') {
                            statusBadge = '<span class="badge bg-success">Hadir</span>';
                        } else if (presensi.status === 'terlambat') {
                            statusBadge = '<span class="badge bg-warning">Terlambat</span>';
                        } else if (presensi.status === 'izin') {
                            statusBadge = '<span class="badge bg-info">Izin</span>';
                        } else {
                            statusBadge = '<span class="badge bg-secondary">Tidak Hadir</span>';
                        }

                        let row = '<tr>' +
                            '<td class="small">' +
                            '<span class="user-detail-link" style="cursor: pointer; text-decoration: underline;" data-user-id="' + presensi.user_id + '" data-user-name="' + presensi.nama + '">' +
                            presensi.nama +
                            '</span>' +
                            '</td>' +
                            '<td class="small">' + statusBadge + '</td>' +
                            '</tr>';
                        tableBody.append(row);
                    });
                } else {
                    let emptyRow = '<tr>' +
                        '<td colspan="2" class="text-center text-muted small">' +
                        '<small>Tidak ada tenaga pendidik</small>' +
                        '</td>' +
                        '</tr>';
                    tableBody.append(emptyRow);
                }
            });
        });
    }

    @if(in_array($user->role, ['super_admin', 'pengurus']))
    // Handle user detail modal
    $(document).on('click', '.user-detail-link', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let userId = $(this).data('user-id');
        let userName = $(this).data('user-name');
        $('#userDetailModalLabel').text('Detail Presensi: ' + userName);

        $.ajax({
            url: '{{ url('/presensi-admin/detail') }}/' + userId,
            type: 'GET',
            success: function(data) {
                // Populate user info tab
                $('#detail-name').text(data.user.name);
                $('#detail-email').text(data.user.email);
                $('#detail-madrasah').text(data.user.madrasah);
                $('#detail-status').text(data.user.status_kepegawaian);
                $('#detail-nip').text(data.user.nip || '-');
                $('#detail-nuptk').text(data.user.nuptk || '-');
                $('#detail-phone').text(data.user.no_hp || '-');

                // Populate history tab
                let presensiRows = '';
                data.presensi_history.forEach(function(presensi) {
                    let statusBadge = '';
                    if (presensi.status === 'hadir') {
                        statusBadge = '<span class="badge bg-success">Hadir</span>';
                    } else if (presensi.status === 'terlambat') {
                        statusBadge = '<span class="badge bg-warning">Terlambat</span>';
                    } else if (presensi.status === 'izin') {
                        statusBadge = '<span class="badge bg-info">Izin</span>';
                    } else {
                        statusBadge = '<span class="badge bg-secondary">' + presensi.status + '</span>';
                    }

                    presensiRows += '<tr>' +
                        '<td>' + presensi.tanggal + '</td>' +
                        '<td>' + (presensi.waktu_masuk || '-') + '</td>' +
                        '<td>' + (presensi.waktu_keluar || '-') + '</td>' +
                        '<td>' + statusBadge + '</td>' +
                        '<td>' + (presensi.keterangan || '-') + '</td>' +
                        '</tr>';
                });
                $('#detail-history-body').html(presensiRows);

                // Show modal
                $('#userDetailModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.log('Error loading user detail:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal memuat detail pengguna'
                });
            }
        });
        return false;
    });

    // Handle comprehensive detail modal
    $(document).on('click', '.comprehensive-detail-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        console.log('Comprehensive detail button clicked');

        let madrasahId = $(this).data('madrasah-id');
        let madrasahName = $(this).data('madrasah-name');

        console.log('Madrasah ID:', madrasahId, 'Name:', madrasahName);

        $('#comprehensiveDetailModalLabel').text('Detail Lengkap: ' + madrasahName);

        // Show loading state
        Swal.fire({
            title: 'Memuat Data...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '{{ url('/presensi-admin/madrasah-detail') }}/' + madrasahId,
            type: 'GET',
            data: { date: currentDate },
            success: function(data) {
                console.log('AJAX success, data received:', data);
                Swal.close();

                // Set date display
                let dateObj = new Date(currentDate);
                let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                $('#modal-date-display').text(dateObj.toLocaleDateString('id-ID', options));
                $('#modal-date-subtitle').text('Data Presensi Tenaga Pendidik');

                // Populate school information
                populateSchoolInfo(data.madrasah);

                // Initialize comprehensive map
                initializeComprehensiveMap(data.madrasah, data.tenaga_pendidik);

                // Populate staff attendance data
                populateStaffAttendance(data.tenaga_pendidik);

                // Show modal
                console.log('Showing modal...');
                $('#comprehensiveDetailModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.log('AJAX error:', error);
                console.log('Status:', status);
                console.log('XHR:', xhr);
                console.log('Response text:', xhr.responseText);
                Swal.close();
                let errorMessage = 'Gagal memuat detail lengkap madrasah';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage + ' (Periksa console untuk detail error)'
                });
            }
        });

        return false;
    });

    // Update data every 30 seconds
    updateInterval = setInterval(updatePresensiData, 30000);

    // Handle date change
    $('input[type="date"]').on('change', function(e) {
        e.preventDefault();
        currentDate = $(this).val();
        // Update export link
        let exportLink = '{{ route('presensi_admin.export', ['date' => 'PLACEHOLDER']) }}'.replace('PLACEHOLDER', currentDate);
        $('a[href*="presensi_admin.export"]').attr('href', exportLink);
        updatePresensiData();
        return false;
    });

    // Handle export modal
    let currentMadrasahId = null;
    $(document).on('click', '[data-bs-target="#exportModal"]', function() {
        currentMadrasahId = $(this).data('madrasah-id');
        let madrasahName = $(this).data('madrasah-name');
        $('#exportMadrasahName').text(madrasahName);
        $('#monthSelector').hide();
    });

    $('#exportAllBtn').on('click', function() {
        if (currentMadrasahId) {
            $('#exportModal').modal('hide');
            window.location.href = '{{ url('/presensi-admin/export-madrasah') }}/' + currentMadrasahId + '?type=all';
        }
    });

    $('#exportMonthBtn').on('click', function() {
        $('#monthSelector').show();
    });

    $('#exportMonth').on('change', function() {
        if (currentMadrasahId) {
            let month = $(this).val();
            $('#exportModal').modal('hide');
            window.location.href = '{{ url('/presensi-admin/export-madrasah') }}/' + currentMadrasahId + '?type=month&month=' + month;
        }
    });

    // Initial update
    updatePresensiData();


    // Function to populate school information
    function populateSchoolInfo(madrasah) {
        const infoItems = [
            { label: 'Nama Madrasah', value: madrasah.name || '-' },
            { label: 'SCOD', value: madrasah.scod || '-' },
            { label: 'Kabupaten', value: madrasah.kabupaten || '-' },
            { label: 'Alamat Lengkap', value: madrasah.alamat || '-' },
            { label: 'Hari KBM', value: madrasah.hari_kbm || '-' },
            { label: 'Koordinat GPS', value: (madrasah.latitude && madrasah.longitude) ? `${madrasah.latitude}, ${madrasah.longitude}` : '-' },
            { label: 'Link Peta', value: madrasah.map_link ? `<a href="${madrasah.map_link}" target="_blank" class="text-primary">Lihat di Google Maps</a>` : '-' },
            { label: 'Area Polygon', value: madrasah.polygon_koordinat ? 'Ada (Tersimpan)' + (madrasah.enable_dual_polygon && madrasah.polygon_koordinat_2 ? ' + Dual Polygon' : '') : 'Tidak Ada' }
        ];

        let infoGrid = '';
        infoItems.forEach(item => {
            infoGrid += `
                <div class="info-item">
                    <div class="info-label">${item.label}</div>
                    <div class="info-value">${item.value}</div>
                </div>
            `;
        });

        $('#school-info-grid').html(infoGrid);
    }

    // Function to initialize comprehensive map with staff locations
    function initializeComprehensiveMap(madrasah, tenagaPendidik) {
        // Clear any existing map
        if (window.comprehensiveMap) {
            window.comprehensiveMap.remove();
        }

        // Initialize Leaflet map with default center
        let defaultLat = -7.7956;
        let defaultLon = 110.3695;
        let lat = madrasah.latitude || defaultLat;
        let lon = madrasah.longitude || defaultLon;
        window.comprehensiveMap = L.map('comprehensive-map').setView([lat, lon], 16);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(window.comprehensiveMap);

        let drawnItems = new L.FeatureGroup();
        window.comprehensiveMap.addLayer(drawnItems);

        // Add school marker if coordinates exist
        if (madrasah.latitude && madrasah.longitude) {
            L.marker([lat, lon], {
                icon: L.divIcon({
                    className: 'school-marker',
                    html: '<div style="background: #004b4c; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="mdi mdi-school" style="font-size: 16px;"></i></div>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                })
            })
            .addTo(window.comprehensiveMap)
            .bindPopup(`<div style="text-align: center;"><b>${madrasah.name}</b><br/><small>Lokasi Madrasah</small></div>`);
        }

        // Add staff attendance markers
        let staffMarkers = [];
        tenagaPendidik.forEach(function(staff) {
            if (staff.latitude && staff.longitude) {
                let markerColor = '#28a745'; // default green for hadir
                if (staff.status === 'terlambat') markerColor = '#ffc107';
                else if (staff.status === 'tidak_hadir') markerColor = '#dc3545';
                else if (staff.status === 'izin') markerColor = '#17a2b8';

                let marker = L.marker([staff.latitude, staff.longitude], {
                    icon: L.divIcon({
                        className: 'staff-marker',
                        html: `<div style="background: ${markerColor}; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3); font-size: 12px; font-weight: bold;">${staff.nama.charAt(0).toUpperCase()}</div>`,
                        iconSize: [24, 24],
                        iconAnchor: [12, 12]
                    })
                });

                let statusText = staff.status === 'hadir' ? 'Hadir' : (staff.status === 'terlambat' ? 'Terlambat' : (staff.status === 'izin' ? 'Izin' : 'Tidak Hadir'));
                let popupContent = `
                    <div style="min-width: 200px;">
                        <div style="text-align: center; margin-bottom: 8px;">
                            <b>${staff.nama}</b><br/>
                            <small>${staff.status_kepegawaian || '-'}</small>
                        </div>
                        <div style="background: ${markerColor}; color: white; padding: 4px 8px; border-radius: 4px; text-align: center; margin-bottom: 8px;">
                            <small><b>${statusText}</b></small>
                        </div>
                        ${staff.waktu_masuk ? `<div><small><i class="mdi mdi-clock-in"></i> Masuk: ${staff.waktu_masuk}</small></div>` : ''}
                        ${staff.waktu_keluar ? `<div><small><i class="mdi mdi-clock-out"></i> Keluar: ${staff.waktu_keluar}</small></div>` : ''}
                        ${staff.keterangan ? `<div><small><i class="mdi mdi-note-text"></i> ${staff.keterangan}</small></div>` : ''}
                    </div>
                `;

                marker.bindPopup(popupContent);
                marker.addTo(window.comprehensiveMap);
                staffMarkers.push(marker);
            }
        });

        // Load existing polygon
        if (madrasah.polygon_koordinat) {
            try {
                let geometry = JSON.parse(madrasah.polygon_koordinat);
                let layer = L.geoJSON(geometry, {
                    style: {
                        color: '#004b4c',
                        weight: 2,
                        opacity: 0.8,
                        fillColor: '#004b4c',
                        fillOpacity: 0.1
                    }
                });
                layer.eachLayer(function(l) {
                    drawnItems.addLayer(l);
                });
            } catch (e) {
                console.error("Invalid GeoJSON data for polygon:", e);
            }
        }

        // Fit map to show all elements
        setTimeout(() => {
            window.comprehensiveMap.invalidateSize();

            let allBounds = [];
            if (drawnItems.getLayers().length > 0) {
                allBounds.push(drawnItems.getBounds());
            }
            if (staffMarkers.length > 0) {
                staffMarkers.forEach(marker => {
                    allBounds.push(L.latLngBounds([marker.getLatLng()]));
                });
            }
            if (madrasah.latitude && madrasah.longitude) {
                allBounds.push(L.latLngBounds([[madrasah.latitude, madrasah.longitude]]));
            }

            if (allBounds.length > 0) {
                let combinedBounds = allBounds[0];
                for (let i = 1; i < allBounds.length; i++) {
                    combinedBounds.extend(allBounds[i]);
                }
                window.comprehensiveMap.fitBounds(combinedBounds, { padding: [20, 20] });
            } else {
                window.comprehensiveMap.setView([defaultLat, defaultLon], 13);
            }

            setTimeout(() => {
                window.comprehensiveMap.invalidateSize();
            }, 200);
        }, 100);
    }

    // Function to populate staff attendance data
    function populateStaffAttendance(tenagaPendidik) {
        // Group staff by status
        let staffByStatus = {
            hadir: [],
            terlambat: [],
            tidak_hadir: [],
            izin: []
        };

        tenagaPendidik.forEach(function(staff) {
            if (staff.status === 'hadir') staffByStatus.hadir.push(staff);
            else if (staff.status === 'terlambat') staffByStatus.terlambat.push(staff);
            else if (staff.status === 'tidak_hadir') staffByStatus.tidak_hadir.push(staff);
            else if (staff.status === 'izin') staffByStatus.izin.push(staff);
        });

        // Update tab counts
        $('#hadir-count').text(staffByStatus.hadir.length);
        $('#terlambat-count').text(staffByStatus.terlambat.length);
        $('#tidak-hadir-count').text(staffByStatus.tidak_hadir.length);
        $('#izin-count').text(staffByStatus.izin.length);

        // Populate staff grids
        populateStaffGrid('hadir-staff-grid', staffByStatus.hadir, 'hadir');
        populateStaffGrid('terlambat-staff-grid', staffByStatus.terlambat, 'terlambat');
        populateStaffGrid('tidak-hadir-staff-grid', staffByStatus.tidak_hadir, 'tidak-hadir');
        populateStaffGrid('izin-staff-grid', staffByStatus.izin, 'izin');
    }
    @endif
});
</script>
@endsection

