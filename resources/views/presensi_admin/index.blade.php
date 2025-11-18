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
                <div class="stat-card h-100 hover-lift total-sekolah">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ number_format($summary['users_presensi']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6">Users Presensi</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-check fs-3"></i>
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

            {{-- Sekolah Presensi Card --}}
            <div class="col-lg-4">
                <div class="stat-card h-100 hover-lift sekolah-buka">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ number_format($summary['sekolah_presensi']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6">Sekolah Presensi</p>
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

            {{-- Belum Presensi Card --}}
            <div class="col-lg-4">
                <div class="stat-card h-100 hover-lift pending">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1">{{ number_format($summary['guru_tidak_presensi']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6">Belum Presensi</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-clock fs-3"></i>
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
            <div class="kabupaten-group">
                <div class="kabupaten-header">
                    <i class="mdi mdi-city"></i>
                    <span>{{ $kabupaten }}</span>
                    <div class="ms-auto">
                        <small class="badge bg-primary bg-opacity-10 text-primary me-2">
                            {{ $kabupatenMadrasahData->count() }} Madrasah
                        </small>
                    </div>
                </div>

                <!-- Madrasah Table - Modern PPDB Style -->
                <div class="kabupaten-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
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
                                            <span class="madrasah-detail-link fw-medium" data-madrasah-id="{{ $data['madrasah']->id }}" data-madrasah-name="{{ $data['madrasah']->name }}">
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
                                            <a href="#" class="btn btn-outline-info btn-sm madrasah-detail-link" data-madrasah-id="{{ $data['madrasah']->id }}" data-madrasah-name="{{ $data['madrasah']->name }}">
                                                <i class="mdi mdi-eye me-1"></i>Lihat
                                            </a>
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
            <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none;">
                    <h5 class="modal-title" id="userDetailModalLabel" style="font-weight: 600;">
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
                <div class="modal-footer" style="border-top: none; border-radius: 0 0 15px 15px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 0.5rem 1.5rem;">
                        <i class="mdi mdi-close me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal - Modern PPDB Style -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none;">
                    <h5 class="modal-title" id="exportModalLabel" style="font-weight: 600;">
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
                <div class="modal-footer" style="border-top: none; border-radius: 0 0 15px 15px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 0.5rem 1.5rem;">
                        <i class="mdi mdi-close me-1"></i>Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Madrasah Detail Modal - Modern PPDB Style -->
    <div class="modal fade" id="madrasahDetailModal" tabindex="-1" aria-labelledby="madrasahDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none;">
                    <h5 class="modal-title" id="madrasahDetailModalLabel" style="font-weight: 600;">
                        <i class="mdi mdi-school me-2"></i>Detail Madrasah
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-2"><strong>Nama Madrasah:</strong> <span id="madrasah-detail-name"></span></div>
                            <div class="mb-2"><strong>SCOD:</strong> <span id="madrasah-detail-scod"></span></div>
                            <div class="mb-2"><strong>Kabupaten:</strong> <span id="madrasah-detail-kabupaten"></span></div>
                            <div class="mb-2"><strong>Alamat:</strong> <span id="madrasah-detail-alamat"></span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2"><strong>Hari KBM:</strong> <span id="madrasah-detail-hari-kbm"></span></div>
                            <div class="mb-2"><strong>Latitude:</strong> <span id="madrasah-detail-latitude"></span></div>
                            <div class="mb-2"><strong>Longitude:</strong> <span id="madrasah-detail-longitude"></span></div>
                            <div class="mb-2"><strong>Map Link:</strong> <a id="madrasah-detail-map-link" href="#" target="_blank">Lihat Peta</a></div>
                            <div class="mb-2"><strong>Polygon Koordinat:</strong> <span id="madrasah-detail-polygon">-</span></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Area Poligon Presensi</label>
                        <div id="madrasah-detail-map" style="height: 250px; width: 100%; margin-top: 15px; border: 1px solid #ddd; border-radius: 4px;"></div>
                        <small class="text-muted">Area poligon presensi madrasah ini.</small>
                    </div>

                    <h6>Daftar Tenaga Pendidik:</h6>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Status Kepegawaian</th>
                                    <th>Status Presensi</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Keluar</th>
                                </tr>
                            </thead>
                            <tbody id="madrasah-detail-guru-body">
                                <!-- Data will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none; border-radius: 0 0 15px 15px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 0.5rem 1.5rem;">
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

    // Handle madrasah detail modal
    $(document).on('click', '.madrasah-detail-link', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let madrasahId = $(this).data('madrasah-id');
        let madrasahName = $(this).data('madrasah-name');
        $('#madrasahDetailModalLabel').text('Detail Madrasah: ' + madrasahName);

        $.ajax({
            url: '{{ url('/presensi-admin/madrasah-detail') }}/' + madrasahId,
            type: 'GET',
            data: { date: currentDate },
            success: function(data) {
                // Populate madrasah info
                $('#madrasah-detail-name').text(data.madrasah.name);
                $('#madrasah-detail-scod').text(data.madrasah.scod || '-');
                $('#madrasah-detail-kabupaten').text(data.madrasah.kabupaten || '-');
                $('#madrasah-detail-alamat').text(data.madrasah.alamat || '-');
                $('#madrasah-detail-hari-kbm').text(data.madrasah.hari_kbm || '-');
                $('#madrasah-detail-latitude').text(data.madrasah.latitude || '-');
                $('#madrasah-detail-longitude').text(data.madrasah.longitude || '-');
                if (data.madrasah.map_link) {
                    $('#madrasah-detail-map-link').attr('href', data.madrasah.map_link).show();
                } else {
                    $('#madrasah-detail-map-link').hide();
                }
                if (data.madrasah.polygon_koordinat) {
                    let polygonText = 'Ada (Tersimpan)';
                    if (data.madrasah.enable_dual_polygon && data.madrasah.polygon_koordinat_2) {
                        polygonText += ' + Dual Polygon';
                    }
                    $('#madrasah-detail-polygon').text(polygonText);
                } else {
                    $('#madrasah-detail-polygon').text('Tidak Ada');
                }



                // Initialize map for polygon display
                initializeMadrasahMap(data.madrasah);

                // Populate guru list
                let guruRows = '';
                data.tenaga_pendidik.forEach(function(guru) {
                    let statusBadge = '';
                    if (guru.status === 'hadir') {
                        statusBadge = '<span class="badge bg-success">Hadir</span>';
                    } else if (guru.status === 'terlambat') {
                        statusBadge = '<span class="badge bg-warning">Terlambat</span>';
                    } else if (guru.status === 'izin') {
                        statusBadge = '<span class="badge bg-info">Izin</span>';
                    } else {
                        statusBadge = '<span class="badge bg-secondary">Tidak Hadir</span>';
                    }

                    guruRows += '<tr>' +
                        '<td>' + guru.nama + '</td>' +
                        '<td>' + (guru.status_kepegawaian || '-') + '</td>' +
                        '<td>' + statusBadge + '</td>' +
                        '<td>' + (guru.waktu_masuk || '-') + '</td>' +
                        '<td>' + (guru.waktu_keluar || '-') + '</td>' +
                        '</tr>';
                });
                $('#madrasah-detail-guru-body').html(guruRows);

                // Show modal
                $('#madrasahDetailModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.log('Error loading madrasah detail:', error);
                console.log('Response:', xhr.responseText);
                let errorMessage = 'Gagal memuat detail madrasah';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
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
            window.location.href = '{{ url('/presensi-admin/export-madrasah') }}/' + currentMadrasahId + '?type=all';
        }
    });

    $('#exportMonthBtn').on('click', function() {
        $('#monthSelector').show();
    });

    $('#exportMonth').on('change', function() {
        if (currentMadrasahId) {
            let month = $(this).val();
            window.location.href = '{{ url('/presensi-admin/export-madrasah') }}/' + currentMadrasahId + '?type=month&month=' + month;
        }
    });

    // Initial update
    updatePresensiData();


    // Function to initialize map for madrasah detail
    function initializeMadrasahMap(madrasah) {
        // Clear any existing map
        if (window.madrasahMap) {
            window.madrasahMap.remove();
        }

        // Initialize Leaflet map with default center
        let defaultLat = -7.7956;
        let defaultLon = 110.3695;
        let lat = madrasah.latitude || defaultLat;
        let lon = madrasah.longitude || defaultLon;
        window.madrasahMap = L.map('madrasah-detail-map').setView([lat, lon], 16);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(window.madrasahMap);

        let drawnItems = new L.FeatureGroup();
        window.madrasahMap.addLayer(drawnItems);

        // Add marker if coordinates exist
        if (madrasah.latitude && madrasah.longitude) {
            L.marker([lat, lon])
                .addTo(window.madrasahMap)
                .bindPopup('<b>' + madrasah.name + '</b><br/>' + (madrasah.alamat || ''));
        }

        // Load existing polygon like in edit modal (read-only)
        if (madrasah.polygon_koordinat) {
            try {
                let geometry = JSON.parse(madrasah.polygon_koordinat);
                let layer = L.geoJSON(geometry);
                layer.eachLayer(function(l) {
                    drawnItems.addLayer(l);
                });
                if (drawnItems.getLayers().length > 0) {
                    window.madrasahMap.fitBounds(drawnItems.getBounds());
                }
            } catch (e) {
                console.error("Invalid GeoJSON data for polygon:", e);
            }
        }

        // Fit map to show all elements and adjust zoom for better view
        setTimeout(() => {
            window.madrasahMap.invalidateSize();

            // Force a resize to ensure proper rendering
            window.madrasahMap._onResize();

            // If we have both marker and polygon, fit bounds to show everything
            if (drawnItems.getLayers().length > 0 && madrasah.latitude && madrasah.longitude) {
                // Create a group with both marker and polygon layers
                let allLayers = new L.FeatureGroup();
                drawnItems.eachLayer(layer => allLayers.addLayer(layer));

                // Add a temporary marker to the group for bounds calculation
                let tempMarker = L.marker([madrasah.latitude, madrasah.longitude]);
                allLayers.addLayer(tempMarker);

                // Fit bounds to show all elements
                window.madrasahMap.fitBounds(allLayers.getBounds(), { padding: [20, 20] });

                // Remove temporary marker
                allLayers.removeLayer(tempMarker);
            } else if (drawnItems.getLayers().length > 0) {
                // Only polygon exists, fit to polygon bounds
                window.madrasahMap.fitBounds(drawnItems.getBounds(), { padding: [20, 20] });
            } else if (madrasah.latitude && madrasah.longitude) {
                // Only marker exists, center on marker with appropriate zoom
                window.madrasahMap.setView([madrasah.latitude, madrasah.longitude], 18);
            } else {
                // No specific location, use default view
                window.madrasahMap.setView([defaultLat, defaultLon], 13);
            }

            // Additional invalidateSize after bounds fitting to ensure polygon renders
            setTimeout(() => {
                window.madrasahMap.invalidateSize();
            }, 200);
        }, 100);
    }
    @endif
});
</script>
@endsection

