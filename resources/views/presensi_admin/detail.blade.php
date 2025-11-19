@extends('layouts.master')

@section('title', 'Detail Madrasah - ' . $madrasah->name)

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Responsive Table css -->
<link href="{{ asset('build/libs/admin-resources/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css" />

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

/* Table Styling */
.table-card {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.table-card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-bottom: 2px solid #dee2e6;
    padding: 1.25rem;
}

.table-card-body {
    padding: 0;
}

.search-input-group {
    max-width: 300px;
}

.attendance-data-table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.attendance-data-table thead th {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    color: white;
    border: none;
    font-weight: 600;
    padding: 1rem 0.75rem;
    font-size: 0.85rem;
    text-transform: capitalize;
    white-space: nowrap;
    position: sticky;
    top: 0;
    z-index: 10;
}

.attendance-data-table tbody tr {
    border-bottom: 1px solid #f1f3f4;
    transition: background-color 0.2s ease;
}

.attendance-data-table tbody tr:hover {
    background-color: rgba(0, 75, 76, 0.03);
}

.attendance-data-table tbody td {
    padding: 0.85rem 0.75rem;
    vertical-align: middle;
    font-size: 0.9rem;
}

.attendance-data-table .badge {
    font-weight: 600;
    padding: 0.35rem 0.65rem;
    font-size: 0.75rem;
}

.attendance-data-table code {
    background: #f5f5f5;
    padding: 0.2rem 0.4rem;
    border-radius: 3px;
    font-size: 0.85rem;
    color: #666;
    font-family: 'Courier New', monospace;
}

.empty-table-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #6c757d;
}

.empty-table-state i {
    font-size: 3rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.empty-table-state p {
    margin: 0;
    font-weight: 500;
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

    .table-card-header {
        padding: 1rem;
    }

    .search-input-group {
        max-width: 100% !important;
        margin-top: 1rem;
    }

    .attendance-data-table {
        font-size: 0.85rem;
    }

    .attendance-data-table thead th {
        padding: 0.75rem 0.5rem !important;
        font-size: 0.75rem;
    }

    .attendance-data-table tbody td {
        padding: 0.65rem 0.5rem;
    }

    .attendance-data-table code {
        font-size: 0.75rem;
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

.info-card {
    background: white !important;
    border-radius: 12px !important;
    padding: 1.5rem !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
    border: 1px solid #dee2e6 !important;
    height: 100% !important;
    transition: all 0.3s ease !important;
}

.info-card:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
}

.info-card-title {
    color: #004b4c !important;
    font-weight: 600 !important;
    font-size: 1rem !important;
    margin-bottom: 1rem !important;
    border-bottom: 2px solid rgba(0, 75, 76, 0.1) !important;
    padding-bottom: 0.5rem !important;
}

.info-item {
    margin-bottom: 1rem !important;
}

.info-item:last-child {
    margin-bottom: 0 !important;
}

.info-label {
    display: block !important;
    font-weight: 600 !important;
    color: #004b4c !important;
    font-size: 0.85rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    margin-bottom: 0.25rem !important;
}

.info-value {
    display: block !important;
    font-size: 0.95rem !important;
    color: #495057 !important;
    word-break: break-word !important;
    line-height: 1.4 !important;
}

.attendance-summary {
    margin-top: 0.5rem !important;
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

.staff-details-expanded {
    margin-top: 0.75rem !important;
    padding-top: 0.75rem !important;
    border-top: 1px solid #dee2e6 !important;
}

.staff-detail-row {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin-bottom: 0.25rem !important;
    font-size: 0.8rem !important;
}

.staff-detail-label {
    color: #6c757d !important;
    font-weight: 500 !important;
}

.staff-detail-value {
    color: #495057 !important;
    font-weight: 600 !important;
    word-break: break-word !important;
}

.staff-coordinates {
    font-family: 'Courier New', monospace !important;
    font-size: 0.75rem !important;
    background: rgba(0, 75, 76, 0.05) !important;
    padding: 0.25rem 0.5rem !important;
    border-radius: 4px !important;
    margin-top: 0.25rem !important;
}

.fake-location-badge {
    background: #fff3cd !important;
    color: #856404 !important;
    padding: 0.125rem 0.375rem !important;
    border-radius: 3px !important;
    font-size: 0.7rem !important;
    font-weight: 600 !important;
    margin-left: 0.5rem !important;
}

.face-verification-badge {
    background: #d1ecf1 !important;
    color: #0c5460 !important;
    padding: 0.125rem 0.375rem !important;
    border-radius: 3px !important;
    font-size: 0.7rem !important;
    font-weight: 600 !important;
    margin-left: 0.5rem !important;
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
    @slot('title') Detail Madrasah @endslot
@endcomponent

@if(in_array($user->role, ['super_admin', 'pengurus']))
    <!-- Header Section - Modern PPDB Style -->
    <div class="welcome-section mb-4" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important; border-radius: 15px !important; padding: 2rem !important; margin-bottom: 2rem !important; color: white !important; position: relative !important; overflow: hidden !important; box-shadow: 0 4px 15px rgba(0, 75, 76, 0.2) !important;">
        <div class="welcome-content" style="position: relative !important; z-index: 1 !important;">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="mb-2">
                        <i class="mdi mdi-school me-2"></i>Detail Madrasah: {{ $madrasah->name }}
                    </h2>
                    <p class="mb-0 opacity-75">Pantau dan kelola presensi tenaga pendidik di {{ $madrasah->name }}</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex align-items-center justify-content-lg-end">
                        <i class="mdi mdi-calendar-clock me-2"></i>
                        <span class="fw-semibold">{{ $selectedDate->format('d F Y') }}</span>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <input type="date" wire:model.live="selectedDate" class="form-control form-control-sm rounded-pill"
                               value="{{ $selectedDate->format('Y-m-d') }}" style="min-width: 140px;">
                        <a href="{{ route('presensi_admin.index') }}" class="btn btn-success btn-sm rounded-pill px-3">
                            <i class="bx bx-arrow-back me-1"></i>Kembali
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
            {{-- Total Staff Card --}}
            <div class="col-lg-4">
                <div class="stat-card h-100 hover-lift total-sekolah" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; border-radius: 15px !important; padding: 1.5rem !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important; border: none !important; transition: all 0.3s ease !important; position: relative !important; overflow: hidden !important; color: white !important;">
                    <div class="card-body p-4" style="padding: 1.5rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1" style="color: white !important;">{{ $tenagaPendidik->total() }}</h3>
                                <p class="text-white-75 mb-0 fs-6" style="color: rgba(255, 255, 255, 0.75) !important;">Total Tenaga Pendidik</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle" style="background: rgba(255, 255, 255, 0.25) !important; color: white !important;">
                                    <i class="mdi mdi-account-group fs-3"></i>
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

            {{-- Hadir Card --}}
            <div class="col-lg-4">
                <div class="stat-card h-100 hover-lift sekolah-buka" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important; border-radius: 15px !important; padding: 1.5rem !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important; border: none !important; transition: all 0.3s ease !important; position: relative !important; overflow: hidden !important; color: white !important;">
                    <div class="card-body p-4" style="padding: 1.5rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1" style="color: white !important;">{{ $tenagaPendidikData->where('status', 'hadir')->count() }}</h3>
                                <p class="text-white-75 mb-0 fs-6" style="color: rgba(255, 255, 255, 0.75) !important;">Hadir</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle" style="background: rgba(255, 255, 255, 0.25) !important; color: white !important;">
                                    <i class="mdi mdi-account-check fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px !important; background: rgba(255, 255, 255, 0.25) !important;">
                                <div class="progress-bar bg-white" style="width: {{ $tenagaPendidik->total() > 0 ? round(($tenagaPendidikData->where('status', 'hadir')->count() / $tenagaPendidik->total()) * 100) : 0 }}% !important; background: white !important;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tidak Hadir Card --}}
            <div class="col-lg-4">
                <div class="stat-card h-100 hover-lift pending" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important; border-radius: 15px !important; padding: 1.5rem !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important; border: none !important; transition: all 0.3s ease !important; position: relative !important; overflow: hidden !important; color: white !important;">
                    <div class="card-body p-4" style="padding: 1.5rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1" style="color: white !important;">{{ $tenagaPendidikData->where('status', 'tidak_hadir')->count() }}</h3>
                                <p class="text-white-75 mb-0 fs-6" style="color: rgba(255, 255, 255, 0.75) !important;">Tidak Hadir</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle" style="background: rgba(255, 255, 255, 0.25) !important; color: white !important;">
                                    <i class="mdi mdi-account-remove fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px !important; background: rgba(255, 255, 255, 0.25) !important;">
                                <div class="progress-bar bg-white" style="width: {{ $tenagaPendidik->total() > 0 ? round(($tenagaPendidikData->where('status', 'tidak_hadir')->count() / $tenagaPendidik->total()) * 100) : 0 }}% !important; background: white !important;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- School Information Section -->
    <div class="school-info-section mb-4">
        <h4 class="mb-3">
            <i class="mdi mdi-information-outline me-2"></i>Informasi Madrasah
        </h4>
        <div class="row">
            <!-- Left Column: Basic Information -->
            <div class="col-lg-4">
                <div class="info-card">
                    <h6 class="info-card-title">
                        <i class="mdi mdi-school me-2"></i>Informasi Dasar
                    </h6>
                    <div class="info-item">
                        <span class="info-label">Nama Madrasah</span>
                        <span class="info-value">{{ $madrasah->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">SCOD</span>
                        <span class="info-value">{{ $madrasah->scod ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Kabupaten</span>
                        <span class="info-value">{{ $madrasah->kabupaten ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Hari KBM</span>
                        <span class="info-value">{{ $madrasah->hari_kbm ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Middle Column: Location Information -->
            <div class="col-lg-4">
                <div class="info-card">
                    <h6 class="info-card-title">
                        <i class="mdi mdi-map-marker me-2"></i>Lokasi & Area
                    </h6>
                    <div class="info-item">
                        <span class="info-label">Alamat Lengkap</span>
                        <span class="info-value">{{ $madrasah->alamat ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Koordinat GPS</span>
                        <span class="info-value">
                            @if($madrasah->latitude && $madrasah->longitude)
                                {{ $madrasah->latitude }}, {{ $madrasah->longitude }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Link Peta</span>
                        <span class="info-value">
                            @if($madrasah->map_link)
                                <a href="{{ $madrasah->map_link }}" target="_blank" class="text-primary">
                                    <i class="mdi mdi-open-in-new"></i> Lihat di Google Maps
                                </a>
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Area Polygon</span>
                        <span class="info-value">
                            @if($madrasah->polygon_koordinat)
                                Ada (Tersimpan)
                                @if($madrasah->enable_dual_polygon && $madrasah->polygon_koordinat_2)
                                    + Dual Polygon
                                @endif
                            @else
                                Tidak Ada
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Map and Attendance Locations -->
            <div class="col-lg-4">
                <div class="info-card">
                    <h6 class="info-card-title">
                        <i class="mdi mdi-map me-2"></i>Monitoring Lokasi Presensi
                    </h6>

                    <!-- Map Container -->
                    <div id="school-map" style="height: 300px; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); background: #f8f9fa; position: relative;">
                        <!-- Loading indicator -->
                        <div id="map-loading" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; color: #6c757d;">
                            <i class="mdi mdi-loading mdi-spin" style="font-size: 2rem;"></i>
                            <p class="mb-0 mt-2 small">Memuat peta...</p>
                        </div>
                        <!-- Fallback content -->
                        <div id="map-fallback" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #6c757d;">
                            <i class="mdi mdi-map-off" style="font-size: 2rem;"></i>
                            <p class="mb-0 mt-2">Peta tidak dapat dimuat</p>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="d-flex justify-content-center mt-2 mb-3" style="gap: 12px;">
                        <div class="d-flex align-items-center">
                            <div style="width: 12px; height: 12px; background: #0e8549; border-radius: 50%; margin-right: 4px;"></div>
                            <small style="font-size: 10px;">Sudah Presensi</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div style="width: 12px; height: 12px; background: #dc3545; border-radius: 50%; margin-right: 4px;"></div>
                            <small style="font-size: 10px;">Belum Presensi</small>
                        </div>
                    </div>

                    <!-- Summary Stats -->
                    <div class="attendance-summary" style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                        <div style="background: rgba(14, 133, 73, 0.1); padding: 8px; border-radius: 6px; text-align: center;">
                            <div style="font-weight: 600; font-size: 14px; color: #0e8549;">{{ $tenagaPendidikData->where('status', 'hadir')->count() }}</div>
                            <small style="font-size: 10px; color: #0e8549;">Sudah Presensi</small>
                        </div>
                        <div style="background: rgba(220, 53, 69, 0.1); padding: 8px; border-radius: 6px; text-align: center;">
                            <div style="font-weight: 600; font-size: 14px; color: #dc3545;">{{ $tenagaPendidikData->where('status', 'tidak_hadir')->count() }}</div>
                            <small style="font-size: 10px; color: #dc3545;">Belum Presensi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Admin and other roles: Original view -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-school me-2"></i>Detail Madrasah: {{ $madrasah->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Madrasah Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Nama Madrasah:</strong> {{ $madrasah->name }}
                            </div>
                            <div class="mb-3">
                                <strong>SCOD:</strong> {{ $madrasah->scod ?? '-' }}
                            </div>
                            <div class="mb-3">
                                <strong>Kabupaten:</strong> {{ $madrasah->kabupaten ?? '-' }}
                            </div>
                            <div class="mb-3">
                                <strong>Alamat Lengkap:</strong> {{ $madrasah->alamat ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Hari KBM:</strong> {{ $madrasah->hari_kbm ?? '-' }}
                            </div>
                            <div class="mb-3">
                                <strong>Koordinat GPS:</strong>
                                @if($madrasah->latitude && $madrasah->longitude)
                                    {{ $madrasah->latitude }}, {{ $madrasah->longitude }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="mb-3">
                                <strong>Link Peta:</strong>
                                @if($madrasah->map_link)
                                    <a href="{{ $madrasah->map_link }}" target="_blank" class="text-primary">
                                        <i class="mdi mdi-open-in-new"></i> Lihat di Google Maps
                                    </a>
                                @else
                                    -
                                @endif
                            </div>
                            <div class="mb-3">
                                <strong>Area Polygon:</strong>
                                @if($madrasah->polygon_koordinat)
                                    Ada (Tersimpan)
                                    @if($madrasah->enable_dual_polygon && $madrasah->polygon_koordinat_2)
                                        + Dual Polygon
                                    @endif
                                @else
                                    Tidak Ada
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Date Selection -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" action="{{ route('presensi_admin.show_detail', $madrasah->id) }}" class="d-flex align-items-center gap-3">
                                <label for="date" class="form-label mb-0 fw-semibold">Pilih Tanggal:</label>
                                <input type="date" name="date" id="date" class="form-control" style="max-width: 200px;" value="{{ $selectedDate->format('Y-m-d') }}" onchange="this.form.submit()">
                                <a href="{{ route('presensi_admin.index') }}" class="btn btn-outline-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Kembali
                                </a>
                            </form>
                        </div>
                    </div>
@endif

                <!-- Staff Attendance Data -->
                <div class="row">
                    <div class="col-12">
                        <div class="card table-card">
                            <div class="card-header table-card-header">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-6">
                                        <h5 class="card-title mb-0">
                                            <i class="mdi mdi-account-group me-2"></i>Data Presensi Tenaga Pendidik
                                            <span class="badge bg-primary ms-2">{{ $tenagaPendidik->total() }} Orang</span>
                                        </h5>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Search Form -->
                                        <form method="GET" action="{{ route('presensi_admin.show_detail', $madrasah->id) }}" class="d-flex align-items-center justify-content-md-end">
                                            <input type="hidden" name="date" value="{{ $selectedDate->format('Y-m-d') }}">
                                            <div class="input-group input-group-sm search-input-group">
                                                <input type="text" name="search" class="form-control" placeholder="Cari nama, NIP, NUPTK..." value="{{ $search }}">
                                                <button class="btn btn-outline-secondary" type="submit">
                                                    <i class="mdi mdi-magnify"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-card-body">
                                @if(count($tenagaPendidikData) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover attendance-data-table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>NIP</th>
                                                    <th>NUPTK</th>
                                                    <th>Status Kepegawaian</th>
                                                    <th>Status Presensi</th>
                                                    <th>Waktu Masuk</th>
                                                    <th>Waktu Keluar</th>
                                                    <th>Lokasi</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tenagaPendidikData as $index => $tp)
                                                <tr>
                                                    <td class="fw-bold text-muted">{{ $tenagaPendidik->firstItem() + $index }}</td>
                                                    <td class="fw-semibold">{{ $tp['nama'] }}</td>
                                                    <td><code>{{ $tp['nip'] ?? '-' }}</code></td>
                                                    <td><code>{{ $tp['nuptk'] ?? '-' }}</code></td>
                                                    <td><small>{{ $tp['status_kepegawaian'] }}</small></td>
                                                    <td>
                                                        @if($tp['status'] == 'hadir')
                                                            <span class="badge bg-success">Hadir</span>
                                                        @elseif($tp['status'] == 'izin')
                                                            <span class="badge bg-warning text-dark">Izin</span>
                                                        @elseif($tp['status'] == 'sakit')
                                                            <span class="badge bg-info">Sakit</span>
                                                        @elseif($tp['status'] == 'terlambat')
                                                            <span class="badge bg-warning text-dark">Terlambat</span>
                                                        @else
                                                            <span class="badge bg-danger">Tidak Hadir</span>
                                                        @endif
                                                    </td>
                                                    <td><small>{{ $tp['waktu_masuk'] ?? '-' }}</small></td>
                                                    <td><small>{{ $tp['waktu_keluar'] ?? '-' }}</small></td>
                                                    <td>
                                                        @if($tp['lokasi'])
                                                            <small class="text-muted">{{ Str::limit($tp['lokasi'], 25) }}</small>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td><small>{{ $tp['keterangan'] ?? '-' }}</small></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="empty-table-state">
                                        <i class="mdi mdi-account-off-outline"></i>
                                        <p>Tidak ada data tenaga pendidik</p>
                                    </div>
                                @endif

                                <!-- Pagination -->
                                @if($tenagaPendidik->hasPages())
                                    <div class="d-flex justify-content-center mt-4">
                                        {{ $tenagaPendidik->appends(['date' => $selectedDate->format('Y-m-d'), 'search' => $search])->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('build/js/app.js') }}"></script>

<!-- Responsive Table js -->
<script src="{{ asset('build/libs/admin-resources/rwd-table/rwd-table.min.js') }}"></script>

<!-- Init js -->
<script src="{{ asset('build/js/pages/table-responsive.init.js') }}"></script>

@if(in_array($user->role, ['super_admin', 'pengurus']))
<!-- Leaflet CSS and JS for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
$(document).ready(function() {
    @if($madrasah->latitude && $madrasah->longitude)
    // Show loading indicator
    $('#map-loading').show();
    $('#map-fallback').hide();

    try {
        // Initialize map with error handling
        var mapContainer = document.getElementById('school-map');
        if (mapContainer && typeof L !== 'undefined') {
            var map = L.map('school-map', {
                center: [{{ $madrasah->latitude }}, {{ $madrasah->longitude }}],
                zoom: 15,
                zoomControl: true,
                scrollWheelZoom: false
            });

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19,
                errorTileUrl: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=='
            }).addTo(map).on('load', function() {
                $('#map-loading').hide();
            }).on('error', function() {
                $('#map-loading').hide();
                $('#map-fallback').show();
            });

            // Add school marker with custom icon
            var schoolIcon = L.divIcon({
                html: '<div style="background: #004b4c; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"><i class="mdi mdi-school" style="color: white; font-size: 14px;"></i></div>',
                className: 'custom-school-marker',
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            var schoolMarker = L.marker([{{ $madrasah->latitude }}, {{ $madrasah->longitude }}], {icon: schoolIcon})
                .addTo(map)
                .bindPopup('<div style="text-align: center; padding: 5px;"><b>{{ addslashes($madrasah->name) }}</b><br><small>Lokasi Madrasah</small></div>');

            var markers = [schoolMarker];

            // Add attendance location markers
            @if(count($tenagaPendidikData) > 0)
                @foreach($tenagaPendidikData as $tp)
                    @php
                        $lat = $tp['latitude'] ?? $madrasah->latitude;
                        $lng = $tp['longitude'] ?? $madrasah->longitude;
                        $isPresent = $tp['status'] === 'hadir';
                    @endphp
                    @if($lat && $lng)
                        var attendanceIcon = L.divIcon({
                            html: '<div style="width: 14px; height: 14px; border-radius: 50%; background: {{ $isPresent ? '#0e8549' : '#dc3545' }}; border: 2px solid white; box-shadow: 0 0 6px rgba(0,0,0,0.3);"></div>',
                            className: 'custom-attendance-marker',
                            iconSize: [14, 14],
                            iconAnchor: [7, 7]
                        });

                        var attendanceMarker = L.marker([{{ $lat }}, {{ $lng }}], {icon: attendanceIcon})
                            .addTo(map)
                            .bindPopup(
                                '<div style="font-family: \'Poppins\', sans-serif; font-size: 12px; max-width: 220px; padding: 5px;">' +
                                '<strong style="color: #004b4c;">{{ addslashes($tp['nama']) }}</strong><br>' +
                                '<small style="color: #666;">{{ $tp['status_kepegawaian'] ?? '-' }}</small><br>' +
                                '<small><strong>Status:</strong> <span style="color: {{ $isPresent ? '#0e8549' : '#dc3545' }};">{{ $isPresent ? 'Sudah Presensi' : 'Belum Presensi' }}</span></small><br>' +
                                @if($isPresent)
                                '<small><strong>Masuk:</strong> {{ $tp['waktu_masuk'] ?? '-' }}</small><br>' +
                                '<small><strong>Keluar:</strong> {{ $tp['waktu_keluar'] ?? '-' }}</small><br>' +
                                @endif
                                '<small><strong>Lokasi:</strong> {{ addslashes($tp['lokasi'] ?? 'Lokasi tidak tersedia') }}</small>' +
                                '</div>'
                            );
                        markers.push(attendanceMarker);
                    @endif
                @endforeach
            @endif

            // Fit map to show all markers if there are multiple markers
            if (markers.length > 1) {
                try {
                    var group = new L.featureGroup(markers);
                    map.fitBounds(group.getBounds(), { padding: [30, 30] });
                } catch (e) {
                    console.log('Could not fit bounds, using default view');
                }
            }

            // Force map to resize after container is visible
            setTimeout(function() {
                if (map) {
                    map.invalidateSize();
                }
            }, 200);

            // Hide loading after successful initialization
            setTimeout(function() {
                $('#map-loading').hide();
            }, 1000);

            console.log('Map initialized successfully');
        } else {
            console.error('Map container not found or Leaflet not loaded');
            $('#map-loading').hide();
            $('#map-fallback').show();
        }
    } catch (error) {
        console.error('Error initializing map:', error);
        $('#map-loading').hide();
        $('#map-fallback').show();
    }
    @else
        console.log('School coordinates not available');
        $('#map-loading').hide();
        $('#map-fallback').show().html('<i class="mdi mdi-map-off" style="font-size: 2rem;"></i><p class="mb-0 mt-2">Koordinat tidak tersedia</p>');
    @endif
});
</script>

<style>
.custom-school-marker {
    background: none !important;
    border: none !important;
}

.custom-attendance-marker {
    background: none !important;
    border: none !important;
}
</style>
@endif
@endsection
