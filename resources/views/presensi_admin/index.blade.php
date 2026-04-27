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

/* Search Input Styles */
.search-input-group {
    max-width: 400px;
    margin-bottom: 1rem;
}

.search-input-group .input-group-text {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    color: white;
    border: 1px solid #004b4c;
    border-radius: 8px 0 0 8px;
}

.search-input-group .form-control {
    border: 1px solid #dee2e6;
    border-left: none;
    border-radius: 0 8px 8px 0;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.search-input-group .form-control:focus {
    border-color: #004b4c;
    box-shadow: 0 0 0 0.2rem rgba(0, 75, 76, 0.25);
}

@media (max-width: 768px) {
    .search-input-group {
        max-width: 100%;
        margin-bottom: 1rem;
    }
}

.presensi-admin-page {
    --pa-surface: #ffffff;
    --pa-surface-muted: #f8fafc;
    --pa-line: #dbe4ee;
    --pa-line-strong: #c7d3df;
    --pa-text: #0f172a;
    --pa-muted: #64748b;
    --pa-primary: #0f766e;
    --pa-primary-strong: #115e59;
    --pa-primary-soft: #e7f5f3;
    --pa-success: #15803d;
    --pa-success-soft: #edf9f0;
    --pa-danger: #b42318;
    --pa-danger-soft: #fff0ee;
    --pa-warning: #b45309;
    --pa-warning-soft: #fff7ed;
    --pa-info: #1d4ed8;
    --pa-info-soft: #eff6ff;
    --pa-shadow: 0 24px 48px -36px rgba(15, 23, 42, 0.45);
    color: var(--pa-text);
}

.presensi-admin-page .welcome-section {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important;
    border-radius: 15px !important;
    padding: 2rem !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(0, 75, 76, 0.2) !important;
}

.presensi-admin-page .welcome-section::before {
    width: 200px !important;
    height: 200px !important;
    right: 0 !important;
    top: 0 !important;
    background: rgba(255, 255, 255, 0.1) !important;
    transform: translate(50px, -50px) !important;
}

.presensi-admin-page .welcome-content {
    z-index: 1 !important;
}

.presensi-admin-page .dashboard-input,
.presensi-admin-page .inline-filter .form-control,
.presensi-admin-page .inline-filter .form-select,
.presensi-admin-page #monthSelector .form-control {
    min-height: 42px;
    border: 1px solid var(--pa-line);
    border-radius: 12px;
    color: var(--pa-text);
    box-shadow: none;
}

.presensi-admin-page .dashboard-input:focus,
.presensi-admin-page .inline-filter .form-control:focus,
.presensi-admin-page .inline-filter .form-select:focus,
.presensi-admin-page #monthSelector .form-control:focus {
    border-color: rgba(15, 118, 110, 0.35);
    box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.08);
}

.presensi-admin-page .btn-dashboard-primary,
.presensi-admin-page .btn-dashboard-secondary,
.presensi-admin-page .btn-table-primary,
.presensi-admin-page .btn-table-secondary {
    border-radius: 999px;
    font-weight: 600;
    padding-inline: 1rem;
}

.presensi-admin-page .btn-dashboard-primary {
    background: var(--pa-primary);
    border-color: var(--pa-primary);
    color: #ffffff;
}

.presensi-admin-page .btn-dashboard-primary:hover,
.presensi-admin-page .btn-dashboard-primary:focus {
    background: var(--pa-primary-strong);
    border-color: var(--pa-primary-strong);
    color: #ffffff;
}

.presensi-admin-page .btn-dashboard-secondary {
    background: #ffffff;
    border: 1px solid var(--pa-line);
    color: #334155;
}

.presensi-admin-page .btn-dashboard-secondary:hover,
.presensi-admin-page .btn-dashboard-secondary:focus {
    border-color: var(--pa-line-strong);
    color: var(--pa-text);
}

.presensi-admin-page .stat-card {
    border-radius: 15px !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    padding: 1.5rem !important;
}

.presensi-admin-page .stat-card.total-sekolah {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
}

.presensi-admin-page .stat-card.sekolah-buka {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
    color: white !important;
}

.presensi-admin-page .stat-card.pending {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
    color: white !important;
}

.presensi-admin-page .stat-number {
    font-size: 2.5rem !important;
    font-weight: bold !important;
    margin-bottom: 0.5rem !important;
    position: relative !important;
    z-index: 1 !important;
}

.presensi-admin-page .stat-label {
    font-size: 0.9rem !important;
    opacity: 0.9 !important;
    margin-bottom: 0 !important;
    position: relative !important;
    z-index: 1 !important;
}

.presensi-admin-page .stat-icon {
    position: relative !important;
    z-index: 1 !important;
}

.presensi-admin-page .kabupaten-group {
    border: 1px solid var(--pa-line) !important;
    border-radius: 22px !important;
    box-shadow: var(--pa-shadow) !important;
    overflow: hidden !important;
}

.presensi-admin-page .kabupaten-header {
    padding: 1.25rem 1.5rem !important;
    background: #ffffff !important;
    color: var(--pa-text) !important;
    border-bottom: 1px solid var(--pa-line);
}

.presensi-admin-page .kabupaten-header i {
    margin-right: 0 !important;
    opacity: 1 !important;
}

.presensi-admin-page .header-title {
    display: flex;
    align-items: center;
    gap: 0.9rem;
}

.presensi-admin-page .header-icon {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--pa-primary-soft);
    color: var(--pa-primary);
    font-size: 1.15rem;
}

.presensi-admin-page .header-title h5 {
    margin: 0 0 0.15rem;
    font-size: 1rem;
    font-weight: 700;
}

.presensi-admin-page .header-title p {
    margin: 0;
    color: var(--pa-muted);
    font-size: 0.82rem;
}

.presensi-admin-page .section-chip {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 0.5rem 0.85rem;
    background: var(--pa-primary-soft);
    color: var(--pa-primary-strong);
    font-size: 0.8rem;
    font-weight: 700;
}

.presensi-admin-page .kabupaten-table {
    background: #ffffff !important;
}

.presensi-admin-page .kabupaten-table .table {
    margin-bottom: 0 !important;
}

.presensi-admin-page .kabupaten-table .table thead th {
    background: var(--pa-surface-muted) !important;
    color: var(--pa-muted) !important;
    border-top: 0 !important;
    border-bottom: 1px solid var(--pa-line) !important;
    font-size: 0.76rem;
    font-weight: 700 !important;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

.presensi-admin-page .kabupaten-table .table tbody td {
    padding-block: 1rem;
    border-color: #edf2f7;
    vertical-align: middle;
}

.presensi-admin-page .kabupaten-table .table tbody tr:hover {
    background: #fbfdff !important;
}

.presensi-admin-page .sekolah-name {
    margin-bottom: 0.2rem !important;
    color: var(--pa-text) !important;
    font-weight: 700 !important;
}

.presensi-admin-page .kabupaten-info {
    color: var(--pa-muted) !important;
}

.presensi-admin-page .count-pill,
.presensi-admin-page .attendance-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 999px;
    padding: 0.42rem 0.78rem;
    font-size: 0.8rem;
    font-weight: 700;
}

.presensi-admin-page .count-pill {
    background: var(--pa-primary-soft);
    color: var(--pa-primary-strong);
}

.presensi-admin-page .attendance-pill-good {
    background: var(--pa-success-soft);
    color: var(--pa-success);
}

.presensi-admin-page .attendance-pill-medium {
    background: var(--pa-warning-soft);
    color: var(--pa-warning);
}

.presensi-admin-page .attendance-pill-low {
    background: var(--pa-danger-soft);
    color: var(--pa-danger);
}

.presensi-admin-page .action-cluster .btn {
    min-height: 36px;
}

.presensi-admin-page .btn-table-primary {
    background: var(--pa-primary-soft);
    border: 1px solid transparent;
    color: var(--pa-primary-strong);
}

.presensi-admin-page .btn-table-primary:hover,
.presensi-admin-page .btn-table-primary:focus {
    background: #d9efea;
    color: var(--pa-primary-strong);
}

.presensi-admin-page .btn-table-secondary {
    background: #ffffff;
    border: 1px solid var(--pa-line);
    color: #334155;
}

.presensi-admin-page .btn-table-secondary:hover,
.presensi-admin-page .btn-table-secondary:focus {
    border-color: var(--pa-line-strong);
    color: var(--pa-text);
}

.presensi-admin-page .content-card,
.presensi-admin-page .dashboard-mini-stat {
    border: 1px solid var(--pa-line);
    border-radius: 22px;
    box-shadow: var(--pa-shadow);
    overflow: hidden;
}

.presensi-admin-page .content-card .card-header,
.presensi-admin-page .dashboard-mini-stat .card-header {
    background: #ffffff;
    border-bottom: 1px solid var(--pa-line);
    padding: 1rem 1.25rem;
}

.presensi-admin-page .content-card .card-body,
.presensi-admin-page .dashboard-mini-stat .card-body {
    padding: 1.25rem;
}

.presensi-admin-page .dashboard-mini-stat .stat-mini-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--pa-primary-soft);
    color: var(--pa-primary);
    font-size: 1.25rem;
}

.presensi-admin-page .dashboard-mini-stat.danger .stat-mini-icon {
    background: var(--pa-danger-soft);
    color: var(--pa-danger);
}

.presensi-admin-page .summary-panel {
    height: 100%;
    padding: 1rem 1.1rem;
    border: 1px solid var(--pa-line);
    border-radius: 18px;
    background: var(--pa-surface-muted);
}

.presensi-admin-page .summary-panel .summary-label {
    color: var(--pa-muted);
    font-size: 0.8rem;
    font-weight: 600;
}

.presensi-admin-page .summary-panel .summary-value {
    margin-top: 0.35rem;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--pa-text);
}

.presensi-admin-page .summary-panel .summary-value.text-danger {
    color: var(--pa-danger) !important;
}

.presensi-admin-page .summary-panel .summary-value.text-warning {
    color: var(--pa-warning) !important;
}

.presensi-admin-page .dashboard-modal {
    border: 1px solid var(--pa-line) !important;
    border-radius: 24px !important;
    box-shadow: 0 30px 65px -42px rgba(15, 23, 42, 0.5) !important;
}

.presensi-admin-page .dashboard-modal .modal-header {
    padding: 1.2rem 1.5rem !important;
    background: #ffffff !important;
    color: var(--pa-text) !important;
    border-bottom: 1px solid var(--pa-line) !important;
}

.presensi-admin-page .dashboard-modal .modal-footer {
    padding: 1rem 1.5rem !important;
    background: #ffffff !important;
    border-top: 1px solid var(--pa-line) !important;
}

.presensi-admin-page .dashboard-modal .modal-body {
    padding: 1.5rem !important;
}

.presensi-admin-page .modal-tabs {
    border-bottom: 1px solid var(--pa-line);
}

.presensi-admin-page .modal-tabs .nav-link {
    border: 0;
    border-bottom: 2px solid transparent;
    color: var(--pa-muted);
    font-weight: 600;
    border-radius: 0;
}

.presensi-admin-page .modal-tabs .nav-link.active {
    color: var(--pa-primary);
    border-bottom-color: var(--pa-primary);
    background: transparent;
}

.presensi-admin-page .detail-field {
    height: 100%;
    padding: 1rem;
    border: 1px solid var(--pa-line);
    border-radius: 16px;
    background: var(--pa-surface-muted);
}

.presensi-admin-page .detail-label {
    display: block;
    margin-bottom: 0.4rem;
    color: var(--pa-muted);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.presensi-admin-page .detail-value {
    color: var(--pa-text);
    font-size: 0.95rem;
    font-weight: 600;
    word-break: break-word;
}

.presensi-admin-page .history-table {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid var(--pa-line);
    border-radius: 16px;
}

.presensi-admin-page .history-table .table {
    margin-bottom: 0;
}

.presensi-admin-page .history-table thead th {
    position: sticky;
    top: 0;
    background: var(--pa-surface-muted);
    z-index: 1;
}

.presensi-admin-page .export-choice {
    border-radius: 16px;
    padding: 0.9rem 1rem;
    text-align: left;
}

.presensi-admin-page .export-choice.btn-outline-primary {
    border-color: rgba(29, 78, 216, 0.15);
    color: var(--pa-info);
    background: var(--pa-info-soft);
}

.presensi-admin-page .export-choice.btn-outline-success {
    border-color: rgba(21, 128, 61, 0.15);
    color: var(--pa-success);
    background: var(--pa-success-soft);
}

.presensi-admin-page .export-choice:hover,
.presensi-admin-page .export-choice:focus {
    filter: brightness(0.98);
}

.presensi-admin-page .alert-info {
    border: 1px solid var(--pa-line);
    border-radius: 16px;
    background: var(--pa-surface-muted);
    color: #334155;
}

.presensi-admin-page .dataTables_wrapper .dt-buttons .btn {
    margin-right: 0.45rem;
    margin-bottom: 0.45rem;
    border-radius: 999px;
    border: 1px solid var(--pa-line);
    background: #ffffff;
    color: #334155;
    box-shadow: none;
}

.presensi-admin-page .dataTables_wrapper .dataTables_filter input,
.presensi-admin-page .dataTables_wrapper .dataTables_length select {
    border: 1px solid var(--pa-line);
    border-radius: 12px;
    min-height: 40px;
    box-shadow: none;
}

.presensi-admin-page .table.table-bordered,
.presensi-admin-page .table.table-bordered td,
.presensi-admin-page .table.table-bordered th {
    border-color: #e7edf4;
}

@media (max-width: 991.98px) {
    .presensi-admin-page .hero-toolbar {
        max-width: none;
        margin-left: 0;
    }
}

@media (max-width: 767.98px) {
    .presensi-admin-page .welcome-section {
        padding: 1.35rem !important;
        border-radius: 20px !important;
    }

    .presensi-admin-page .hero-date-pill {
        width: 100%;
        justify-content: center;
    }

    .presensi-admin-page .stat-card .card-body {
        padding: 1.2rem !important;
    }

    .presensi-admin-page .kabupaten-header {
        padding: 1rem !important;
    }

    .presensi-admin-page .header-title {
        align-items: flex-start;
    }

    .presensi-admin-page .section-chip {
        margin-top: 0.75rem;
    }
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Presensi Admin @endslot
    @slot('title') Data Presensi @endslot
@endcomponent
<div class="presensi-admin-page">

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
                    <div class="d-flex align-items-center gap-2 mt-3 justify-content-end w-100">
                        <form method="GET" action="{{ route('presensi_admin.index') }}" class="d-flex align-items-center gap-2 mb-0">
                            <input type="date" name="date" id="filterDate" class="form-control form-control-sm rounded-pill"
                                value="{{ $selectedDate->format('Y-m-d') }}" style="min-width: 140px;">

                            <button type="submit" id="filterBtn" class="btn btn-warning btn-sm rounded-pill px-3">
                                view
                            </button>
                        </form>

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
            <div class="col-lg-3">
                <div class="stat-card h-100 hover-lift total-sekolah" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; border-radius: 15px !important; padding: 1.5rem !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important; border: none !important; transition: all 0.3s ease !important; position: relative !important; overflow: hidden !important; color: white !important;">
                    <div class="card-body p-4" style="padding: 1.5rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1" style="color: white !important;">{{ number_format($summary['users_presensi']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6" style="color: rgba(255, 255, 255, 0.75) !important;">Users Presensi (Hadir)</p>
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

            <div class="col-lg-3">
                <div class="stat-card h-100 hover-lift" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important; border-radius: 15px !important; padding: 1.5rem !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important; border: none !important; transition: all 0.3s ease !important; position: relative !important; overflow: hidden !important; color: white !important;">
                    <div class="card-body p-4" style="padding: 1.5rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1" style="color: white !important;">{{ number_format($summary['users_izin']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6" style="color: rgba(255, 255, 255, 0.75) !important;">Users Izin</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle" style="background: rgba(255, 255, 255, 0.25) !important; color: white !important;">
                                    <i class="mdi mdi-calendar-exclamation fs-3"></i>
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

            <div class="col-lg-3">
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

            <div class="col-lg-3">
                <div class="stat-card h-100 hover-lift pending" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important; border-radius: 15px !important; padding: 1.5rem !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important; border: none !important; transition: all 0.3s ease !important; position: relative !important; overflow: hidden !important; color: white !important;">
                    <div class="card-body p-4" style="padding: 1.5rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1" style="color: white !important;">{{ number_format($summary['guru_tidak_presensi']) }}</h3>
                                <p class="text-white-75 mb-0 fs-6" style="color: rgba(255, 255, 255, 0.75) !important;">Belum Presensi (Tidak Hadir)</p>
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
        $kabupatenIndex = 0;
    @endphp

    @foreach($kabupatenOrder as $kabupaten)
        @php
            $kabupatenMadrasahData = collect($madrasahData)->filter(function($data) use ($kabupaten) {
                return $data['madrasah']->kabupaten === $kabupaten;
            });
            $madrasahIndex = 0;
        @endphp

        @if($kabupatenMadrasahData->count() > 0)
            <div class="kabupaten-group">
                <div class="kabupaten-header flex-column flex-md-row align-items-md-center gap-3">
                    <div class="header-title">
                        <span class="header-icon">
                            <i class="mdi mdi-city"></i>
                        </span>
                        <div>
                            <h5>{{ $kabupaten }}</h5>
                            <p>Ringkasan presensi madrasah pada wilayah ini.</p>
                        </div>
                    </div>
                    <span class="section-chip">{{ $kabupatenMadrasahData->count() }} madrasah</span>
                </div>

                <div class="kabupaten-table">
                    <div class="table-responsive">
                        <table id="madrasah-table-{{ $kabupatenIndex }}-{{ $madrasahIndex }}" class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Madrasah</th>
                                    <th>Tenaga Pendidik</th>
                                    <th>Status Presensi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kabupatenMadrasahData as $data)
                                <tr>
                                    <td>
                                        <div class="sekolah-name">{{ $data['madrasah']->name }}</div>
                                        <div class="kabupaten-info">{{ $kabupaten }}</div>
                                    </td>
                                    <td>
                                        <span class="count-pill">{{ count($data['presensi']) }} orang</span>
                                    </td>
                                    <td>
                                        @php
                                            $hadir = collect($data['presensi'])->where('status', 'hadir')->count();
                                            $total = count($data['presensi']);
                                            $persentase = $total > 0 ? round(($hadir / $total) * 100) : 0;
                                            $attendanceClass = $persentase >= 80 ? 'attendance-pill-good' : ($persentase >= 50 ? 'attendance-pill-medium' : 'attendance-pill-low');
                                        @endphp
                                        <span class="attendance-pill {{ $attendanceClass }}">
                                            {{ $hadir }}/{{ $total }} hadir ({{ $persentase }}%)
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 flex-wrap action-cluster">
                                            <a href="{{ route('presensi_admin.show_detail', $data['madrasah']->id) }}?date={{ $selectedDate->format('Y-m-d') }}" class="btn btn-table-primary btn-sm">
                                                <i class="mdi mdi-eye-outline me-1"></i>Lihat Detail
                                            </a>
                                            @if($user->role === 'super_admin')
                                            <button type="button" class="btn btn-table-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#exportModal" data-madrasah-id="{{ $data['madrasah']->id }}" data-madrasah-name="{{ $data['madrasah']->name }}">
                                                <i class="bx bx-download me-1"></i>Export
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $madrasahIndex++;
                                @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
        @php
            $kabupatenIndex++;
        @endphp
    @endforeach

    <div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content dashboard-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailModalLabel">
                        <i class="mdi mdi-account-details-outline me-2"></i>Detail Presensi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs modal-tabs" id="userDetailTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Informasi Pengguna</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">Riwayat Presensi</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="userDetailTabContent">
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <div class="row g-3 mt-1">
                                <div class="col-md-6 col-xl-4">
                                    <div class="detail-field">
                                        <span class="detail-label">Nama</span>
                                        <span class="detail-value" id="detail-name"></span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="detail-field">
                                        <span class="detail-label">Email</span>
                                        <span class="detail-value" id="detail-email"></span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="detail-field">
                                        <span class="detail-label">Madrasah</span>
                                        <span class="detail-value" id="detail-madrasah"></span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="detail-field">
                                        <span class="detail-label">Status Kepegawaian</span>
                                        <span class="detail-value" id="detail-status"></span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="detail-field">
                                        <span class="detail-label">NIP</span>
                                        <span class="detail-value" id="detail-nip"></span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="detail-field">
                                        <span class="detail-label">NUPTK</span>
                                        <span class="detail-value" id="detail-nuptk"></span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="detail-field">
                                        <span class="detail-label">No. HP</span>
                                        <span class="detail-value" id="detail-phone"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="history" role="tabpanel">
                            <div class="history-table mt-3">
                                <table class="table table-sm table-bordered align-middle">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-dashboard-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content dashboard-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">
                        <i class="mdi mdi-file-export-outline me-2"></i>Export Data Presensi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Pilih jenis export untuk <strong id="exportMadrasahName"></strong>.</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary export-choice" id="exportAllBtn">
                            <i class="bx bx-download me-2"></i>Export Semua Data
                        </button>
                        <button type="button" class="btn btn-outline-success export-choice" id="exportMonthBtn">
                            <i class="bx bx-calendar me-2"></i>Export Per Bulan
                        </button>
                    </div>
                    <div class="mt-3" id="monthSelector" style="display: none;">
                        <label for="exportMonth" class="form-label">Pilih Bulan</label>
                        <input type="month" class="form-control" id="exportMonth" value="{{ date('Y-m') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dashboard-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-1"></i>Batal
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
            <div class="card dashboard-mini-stat border-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="stat-mini-icon">
                        <i class="bx bx-user-check"></i>
                    </span>
                    <div>
                        <div class="h5 mb-1 fw-bold">{{ $summary['users_presensi'] }}</div>
                        <small class="text-muted d-block">Users Presensi</small>
                    </div>
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
            <div class="card dashboard-mini-stat danger border-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="stat-mini-icon">
                        <i class="bx bx-user-x"></i>
                    </span>
                    <div>
                        <div class="h5 mb-1 fw-bold">{{ $summary['guru_tidak_presensi'] }}</div>
                        <small class="text-muted d-block">Guru Belum Presensi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card content-card">
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
                        <a href="{{ route('izin.index') }}" class="btn btn-dashboard-primary">
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
            <div class="card content-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="bx bx-user-x me-2"></i>Belum Melakukan Presensi pada tanggal {{ $selectedDate->format('d-m-Y') }}
                    </h4>
                    <form method="GET" action="{{ route('presensi_admin.index') }}" class="d-flex align-items-center inline-filter">
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

<div class="row mt-4">
    <div class="col-12">
        <div class="card content-card mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <h4 class="card-title mb-1">
                        <i class="bx bx-time-five me-2"></i>Tenaga Pendidik Tidak Presensi 3 Bulan Berturut-turut
                    </h4>
                    <p class="text-muted mb-0">Periode {{ $threeMonthAbsenceData['label'] }}</p>
                </div>

                <div class="table-responsive">
                    <table id="datatable-three-month-absence" class="table table-bordered dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>SCOD</th>
                                <th>Nama User</th>
                                <th>Asal Sekolah</th>
                                <th>Periode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($threeMonthAbsenceData['rows'] as $teacher)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $teacher['scod'] }}</td>
                                    <td>{{ $teacher['name'] }}</td>
                                    <td>{{ $teacher['madrasah'] }}</td>
                                    <td>{{ $teacher['periode'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center p-4">
                                        <div class="alert alert-info d-inline-block text-center" role="alert">
                                            <i class="bx bx-info-circle bx-lg me-2"></i>
                                            <strong>Tidak ada tenaga pendidik yang memenuhi kriteria</strong><br>
                                            <small>Semua tenaga pendidik masih memiliki presensi hadir/terlambat dalam periode 3 bulan terakhir.</small>
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

@if($user->role === 'super_admin')
<div class="row mt-4">
    <div class="col-12">
        <div class="card content-card mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                    <div>
                        <h4 class="card-title mb-1">
                            <i class="bx bx-calendar-x me-2"></i>Rekap Tenaga Pendidik Tidak Presensi Mingguan dan Bulanan
                        </h4>
                        <p class="text-muted mb-0">Periode {{ $teacherAbsenceRecapData['label'] }}</p>
                    </div>

                    <form method="GET" action="{{ route('presensi_admin.index') }}" class="d-flex flex-column flex-sm-row align-items-sm-center gap-2 inline-filter">
                        <input type="hidden" name="date" value="{{ $selectedDate->format('Y-m-d') }}">

                        <select name="absence_recap_period" id="absenceRecapPeriod" class="form-select form-select-sm">
                            <option value="week" {{ $teacherAbsenceRecapData['period'] === 'week' ? 'selected' : '' }}>Mingguan</option>
                            <option value="month" {{ $teacherAbsenceRecapData['period'] === 'month' ? 'selected' : '' }}>Bulanan</option>
                        </select>

                        <input type="week"
                            name="absence_recap_week"
                            id="absenceRecapWeek"
                            class="form-control form-control-sm"
                            value="{{ $teacherAbsenceRecapData['week_value'] }}">

                        <input type="month"
                            name="absence_recap_month"
                            id="absenceRecapMonth"
                            class="form-control form-control-sm"
                            value="{{ $teacherAbsenceRecapData['month_value'] }}">

                        <button type="submit" class="btn btn-dashboard-primary btn-sm px-3">
                            <i class="bx bx-filter-alt me-1"></i>Filter
                        </button>
                    </form>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="summary-panel">
                            <div class="summary-label">Total Tenaga Pendidik</div>
                            <div class="summary-value">{{ number_format($teacherAbsenceRecapData['summary']['total_tenaga_pendidik']) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-panel">
                            <div class="summary-label">Tenaga Pendidik Tidak Presensi</div>
                            <div class="summary-value text-danger">{{ number_format($teacherAbsenceRecapData['summary']['total_tidak_presensi']) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-panel">
                            <div class="summary-label">Total Hari Tidak Presensi</div>
                            <div class="summary-value text-warning">{{ number_format($teacherAbsenceRecapData['summary']['total_hari_tidak_presensi']) }}</div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable-teacher-absence-recap" class="table table-bordered dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>SCOD</th>
                                <th>Nama User</th>
                                <th>Asal Sekolah</th>
                                <th>Hari KBM</th>
                                <th>Total Hari Kerja</th>
                                <th>Hadir</th>
                                <th>Izin Disetujui</th>
                                <th>Tidak Presensi</th>
                                <th>Persentase Tidak Presensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($teacherAbsenceRecapData['rows'] as $teacher)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $teacher['scod'] }}</td>
                                    <td>{{ $teacher['name'] }}</td>
                                    <td>{{ $teacher['madrasah'] }}</td>
                                    <td>{{ $teacher['hari_kbm'] }}</td>
                                    <td>{{ $teacher['total_hari_kerja'] }}</td>
                                    <td>{{ $teacher['total_hadir'] }}</td>
                                    <td>{{ $teacher['total_izin'] }}</td>
                                    <td>
                                        <span class="badge bg-danger">{{ $teacher['total_tidak_presensi'] }}</span>
                                    </td>
                                    <td>{{ number_format($teacher['persentase_tidak_presensi'], 1) }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center p-4">
                                        <div class="alert alert-info d-inline-block text-center" role="alert">
                                            <i class="bx bx-info-circle bx-lg me-2"></i>
                                            <strong>Tidak ada tenaga pendidik yang tidak presensi</strong><br>
                                            <small>Semua tenaga pendidik memiliki presensi hadir atau izin disetujui pada hari kerja periode ini.</small>
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
</div>
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

    let threeMonthAbsenceTable = $("#datatable-three-month-absence");
    if (threeMonthAbsenceTable.length) {
        let absenceTable = threeMonthAbsenceTable.DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            buttons: ["copy", "excel", "pdf", "print", "colvis"]
        });

        absenceTable.buttons().container()
            .appendTo('#datatable-three-month-absence_wrapper .col-md-6:eq(0)');
    }

    let teacherAbsenceRecapTable = $("#datatable-teacher-absence-recap");
    if (teacherAbsenceRecapTable.length) {
        let recapTable = teacherAbsenceRecapTable.DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            order: [[8, 'desc'], [1, 'asc']],
            buttons: ["copy", "excel", "pdf", "print", "colvis"]
        });

        recapTable.buttons().container()
            .appendTo('#datatable-teacher-absence-recap_wrapper .col-md-6:eq(0)');
    }

    function toggleAbsenceRecapFilters() {
        let period = $('#absenceRecapPeriod').val();
        $('#absenceRecapWeek').toggle(period === 'week');
        $('#absenceRecapMonth').toggle(period === 'month');
    }

    toggleAbsenceRecapFilters();
    $('#absenceRecapPeriod').on('change', toggleAbsenceRecapFilters);

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

    // function updateTables(data) {
    //     let globalIndex = 0;
    //     let kabupatenOrder = [
    //         'Kabupaten Gunungkidul',
    //         'Kabupaten Bantul',
    //         'Kabupaten Kulon Progo',
    //         'Kabupaten Sleman',
    //         'Kota Yogyakarta'
    //     ];

    //     kabupatenOrder.forEach(function(kabupaten, kabupatenIndex) {
    //         let kabupatenData = data.filter(function(madrasahData) {
    //             return madrasahData.madrasah && madrasahData.madrasah.kabupaten === kabupaten;
    //         });

    //         kabupatenData.forEach(function(madrasahData, localIndex) {
    //             let tableBody = $('#madrasah-table-' + kabupatenIndex + '-' + localIndex + ' tbody');
    //             tableBody.empty();

    //             if (madrasahData.presensi && madrasahData.presensi.length > 0) {
    //                 madrasahData.presensi.forEach(function(presensi) {
    //                     let statusBadge = '';
    //                     if (presensi.status === 'hadir') {
    //                         statusBadge = '<span class="badge bg-success">Hadir</span>';
    //                     } else if (presensi.status === 'terlambat') {
    //                         statusBadge = '<span class="badge bg-warning">Terlambat</span>';
    //                     } else if (presensi.status === 'izin') {
    //                         statusBadge = '<span class="badge bg-info">Izin</span>';
    //                     } else {
    //                         statusBadge = '<span class="badge bg-secondary">Tidak Hadir</span>';
    //                     }

    //                     // let row = '<tr>' +
    //                     //     '<td class="small">' +
    //                     //     '<span class="user-detail-link" style="cursor: pointer; text-decoration: underline;" data-user-id="' + presensi.user_id + '" data-user-name="' + presensi.nama + '">' +
    //                     //     presensi.nama +
    //                     //     '</span>' +
    //                     //     '</td>' +
    //                     //     '<td class="small">' + statusBadge + '</td>' +
    //                     //     '</tr>';
    //                     // tableBody.append(row);
    //                 });
    //             } else {
    //                 let emptyRow = '<tr>' +
    //                     '<td colspan="4" class="text-center text-muted small">' +
    //                     '<small>Tidak ada tenaga pendidik</small>' +
    //                     '</td>' +
    //                     '</tr>';
    //                 tableBody.append(emptyRow);
    //             }
    //         });
    //     });


    // }

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







    // Function to populate staff attendance data
    function populateStaffAttendance(tenagaPendidik) {
        if (!tenagaPendidik || !Array.isArray(tenagaPendidik)) {
            console.warn('Invalid tenaga pendidik data');
            return;
        }

        // Group staff by status
        let groupedStaff = {
            hadir: [],
            terlambat: [],
            tidak_hadir: [],
            izin: []
        };

        tenagaPendidik.forEach(staff => {
            let status = staff.status || 'tidak_hadir';
            if (groupedStaff[status]) {
                groupedStaff[status].push(staff);
            } else {
                groupedStaff.tidak_hadir.push(staff);
            }
        });

        // Update tab counts
        $('#hadir-count').text(groupedStaff.hadir.length);
        $('#terlambat-count').text(groupedStaff.terlambat.length);
        $('#tidak-hadir-count').text(groupedStaff.tidak_hadir.length);
        $('#izin-count').text(groupedStaff.izin.length);

        // Populate each tab content
        Object.keys(groupedStaff).forEach(status => {
            let staffList = groupedStaff[status];
            let containerId = `${status.replace('_', '-')}-staff-grid`;
            let staffHtml = '';

            if (staffList.length === 0) {
                staffHtml = `
                    <div class="text-center py-5 text-muted">
                        <i class="mdi mdi-account-off-outline fs-2 mb-3"></i>
                        <h6>Tidak ada data ${status.replace('_', ' ')}</h6>
                        <p class="mb-0 small">Belum ada tenaga pendidik dengan status ini</p>
                    </div>
                `;
            } else {
                staffHtml = '<div class="row g-3">';
                staffList.forEach(staff => {
                    let statusBadge = getStatusBadge(staff.status);
                    let timeInfo = staff.created_at ? new Date(staff.created_at).toLocaleString('id-ID', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : '-';

                staffHtml += `
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm staff-card ${status}">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title rounded-circle bg-primary text-white fw-bold">
                                                ${staff.nama ? staff.nama.charAt(0).toUpperCase() : '?'}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-1 fw-bold text-dark">${staff.nama || '-'}</h6>
                                            <small class="text-muted">${staff.nip || '-'}</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        ${statusBadge}
                                        ${staff.is_fake_location ? '<span class="fake-location-badge">Fake GPS</span>' : ''}
                                        ${staff.face_verified ? '<span class="face-verification-badge">Face ✓</span>' : ''}
                                    </div>
                                    <div class="staff-times">
                                        <div class="time-item">
                                            <span class="time-label">Waktu Presensi</span>
                                            <span class="time-value">${timeInfo}</span>
                                        </div>
                                        ${staff.latitude && staff.longitude ? `
                                        <div class="location-info">
                                            <i class="mdi mdi-map-marker text-success"></i>
                                            Lokasi tercatat
                                        </div>
                                        ` : ''}
                                    </div>
                                    <div class="staff-details-expanded">
                                        <div class="staff-detail-row">
                                            <span class="staff-detail-label">NUPTK</span>
                                            <span class="staff-detail-value">${staff.nuptk || '-'}</span>
                                        </div>
                                        <div class="staff-detail-row">
                                            <span class="staff-detail-label">Status Kepegawaian</span>
                                            <span class="staff-detail-value">${staff.status_kepegawaian || '-'}</span>
                                        </div>
                                        ${staff.waktu_masuk ? `
                                        <div class="staff-detail-row">
                                            <span class="staff-detail-label">Masuk</span>
                                            <span class="staff-detail-value">${staff.waktu_masuk}</span>
                                        </div>
                                        ` : ''}
                                        ${staff.waktu_keluar ? `
                                        <div class="staff-detail-row">
                                            <span class="staff-detail-label">Keluar</span>
                                            <span class="staff-detail-value">${staff.waktu_keluar}</span>
                                        </div>
                                        ` : ''}
                                        ${staff.latitude && staff.longitude ? `
                                        <div class="staff-detail-row">
                                            <span class="staff-detail-label">Koordinat</span>
                                            <div class="staff-detail-value staff-coordinates">${staff.latitude}, ${staff.longitude}</div>
                                        </div>
                                        ` : ''}
                                        ${staff.accuracy ? `
                                        <div class="staff-detail-row">
                                            <span class="staff-detail-label">Akurasi</span>
                                            <span class="staff-detail-value">${staff.accuracy}m</span>
                                        </div>
                                        ` : ''}
                                        ${staff.lokasi ? `
                                        <div class="staff-detail-row">
                                            <span class="staff-detail-label">Lokasi</span>
                                            <span class="staff-detail-value">${staff.lokasi}</span>
                                        </div>
                                        ` : ''}
                                        ${staff.keterangan ? `
                                        <div class="staff-detail-row">
                                            <span class="staff-detail-label">Keterangan</span>
                                            <span class="staff-detail-value">${staff.keterangan}</span>
                                        </div>
                                        ` : ''}
                                        ${staff.face_similarity_score ? `
                                        <div class="staff-detail-row">
                                            <span class="staff-detail-label">Face Similarity</span>
                                            <span class="staff-detail-value">${staff.face_similarity_score}%</span>
                                        </div>
                                        ` : ''}
                                        ${staff.liveness_score ? `
                                        <div class="staff-detail-row">
                                            <span class="staff-detail-label">Liveness Score</span>
                                            <span class="staff-detail-value">${staff.liveness_score}%</span>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                staffHtml += '</div>';
            }

            $(`#${containerId}`).html(staffHtml);
        });
    }

    // Function to get status badge HTML
    function getStatusBadge(status) {
        let badgeClass = 'bg-secondary';
        let statusText = status || 'Tidak Hadir';
        let iconClass = 'mdi-account';

        switch(status) {
            case 'hadir':
                badgeClass = 'bg-success';
                statusText = 'Hadir';
                iconClass = 'mdi-account-check';
                break;
            case 'terlambat':
                badgeClass = 'bg-warning text-dark';
                statusText = 'Terlambat';
                iconClass = 'mdi-account-clock';
                break;
            case 'tidak_hadir':
                badgeClass = 'bg-danger';
                statusText = 'Tidak Hadir';
                iconClass = 'mdi-account-remove';
                break;
            case 'izin':
                badgeClass = 'bg-info';
                statusText = 'Izin';
                iconClass = 'mdi-account-edit';
                break;
        }

        return `<span class="badge ${badgeClass} px-3 py-2 fs-6"><i class="mdi ${iconClass} me-1"></i>${statusText}</span>`;
    }

    // Update data every 30 seconds
    updateInterval = setInterval(updatePresensiData, 30000);

    // Handle date change
    $('input[type="date"]').on('change', function(e) {
        currentDate = $(this).val();
        // Update export link
        let exportLink = '{{ route('presensi_admin.export', ['date' => 'PLACEHOLDER']) }}'.replace('PLACEHOLDER', currentDate);
        $('a[href*="presensi_admin.export"]').attr('href', exportLink);
        updatePresensiData();
    });

    // Handle refresh button
    window.refreshData = function() {
        currentDate = $('input[type="date"]').val();
        updatePresensiData();
        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Data Diperbarui',
            text: 'Data presensi telah diperbarui sesuai tanggal yang dipilih',
            timer: 2000,
            showConfirmButton: false
        });
    };

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



    @endif
});



</script>
@endsection
