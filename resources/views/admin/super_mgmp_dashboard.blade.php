@extends('layouts.master')

@section('title')
    Dashboard MGMP - Super Admin
@endsection

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@php
    $topInsights = $mgmpInsights->take(4);
@endphp

@component('components.breadcrumb')
    @slot('li_1') Admin @endslot
    @slot('title') Dashboard MGMP @endslot
@endcomponent

@include('mgmp.partials.ui-styles')

<div class="mgmp-page">
    <div class="mgmp-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="mgmp-kicker mb-2">Super Admin</div>
                <h4 class="mb-1">Dashboard MGMP Terpusat</h4>
                <p class="mb-0 text-white-50">Pantau seluruh MGMP, anggota, kegiatan, dan proposal dari satu halaman admin.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.create_mgmp_user') }}" class="btn btn-light">
                    <i class="mdi mdi-account-plus-outline me-1"></i> Buat User MGMP
                </a>
                <a href="{{ route('mgmp.data-mgmp') }}" class="btn btn-light">
                    <i class="mdi mdi-domain me-1"></i> Kelola Data MGMP
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="mdi mdi-google-circles-communities fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total MGMP</div>
                        <div class="h5 mb-0">{{ $dashboardSummary['total_groups'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                            <i class="mdi mdi-account-group fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total Anggota</div>
                        <div class="h5 mb-0">{{ $dashboardSummary['total_members'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                            <i class="mdi mdi-calendar-check-outline fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Kegiatan MGMP</div>
                        <div class="h5 mb-0">{{ $dashboardSummary['total_reports'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                            <i class="mdi mdi-file-document-multiple-outline fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Proposal Academica</div>
                        <div class="h5 mb-0">{{ $dashboardSummary['total_proposals'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0">Informasi Singkat</h6>
                        <span class="mgmp-chip">Admin Scope</span>
                    </div>
                    <div class="mgmp-summary-stack">
                        <div class="mgmp-summary-row">
                            <span>MGMP dengan anggota</span>
                            <strong>{{ $dashboardSummary['groups_with_members'] ?? 0 }}</strong>
                        </div>
                        <div class="mgmp-summary-row">
                            <span>MGMP dengan kegiatan</span>
                            <strong>{{ $dashboardSummary['groups_with_reports'] ?? 0 }}</strong>
                        </div>
                        <div class="mgmp-summary-row">
                            <span>Madrasah terjangkau</span>
                            <strong>{{ $dashboardSummary['total_schools'] ?? 0 }}</strong>
                        </div>
                        <div class="mgmp-summary-row">
                            <span>Rata-rata anggota per MGMP</span>
                            <strong>{{ $dashboardSummary['average_members'] ?? '0' }}</strong>
                        </div>
                        <div class="mgmp-summary-row">
                            <span>MGMP perlu perhatian</span>
                            <strong>{{ $dashboardSummary['needs_attention'] ?? 0 }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="mb-1">Sorotan MGMP</h6>
                            <p class="text-muted small mb-0">Grup dengan data paling aktif atau paling lengkap.</p>
                        </div>
                        <span class="mgmp-chip">{{ $mgmpInsights->count() }} grup</span>
                    </div>
                    @if($topInsights->isNotEmpty())
                        <div class="row g-3">
                            @foreach($topInsights as $group)
                                <div class="col-md-6">
                                    <div class="mgmp-spotlight-card h-100">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center gap-2">
                                                @if($group->logo)
                                                    <img src="{{ url('/uploads/' . $group->logo) }}" alt="Logo {{ $group->name }}" class="mgmp-mini-logo">
                                                @else
                                                    <div class="mgmp-mini-logo mgmp-mini-logo-placeholder">
                                                        <i class="mdi mdi-school-outline"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ Str::limit($group->name, 26) }}</h6>
                                                    <small class="text-muted">{{ $group->owner_name }}</small>
                                                </div>
                                            </div>
                                            <span class="badge bg-{{ $group->status_class }}-subtle text-{{ $group->status_class }}">{{ $group->status_label }}</span>
                                        </div>
                                        <div class="mgmp-spotlight-grid">
                                            <div>
                                                <small>Anggota</small>
                                                <strong>{{ $group->members_count }}</strong>
                                            </div>
                                            <div>
                                                <small>Sekolah</small>
                                                <strong>{{ $group->school_count }}</strong>
                                            </div>
                                            <div>
                                                <small>Kegiatan</small>
                                                <strong>{{ $group->reports_count }}</strong>
                                            </div>
                                            <div>
                                                <small>Proposal</small>
                                                <strong>{{ $group->proposal_count }}</strong>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-top">
                                            <small class="text-muted d-block mb-1">Kegiatan terakhir</small>
                                            @if($group->latest_report_title)
                                                <div class="fw-semibold">{{ Str::limit($group->latest_report_title, 44) }}</div>
                                                <small class="text-muted">{{ optional($group->latest_report_date)->format('d M Y') ?? '-' }}</small>
                                            @else
                                                <small class="text-muted">Belum ada kegiatan tercatat.</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="mgmp-empty-state py-5">
                            <i class="bx bx-data"></i>
                            <strong>Belum ada data MGMP</strong>
                            <small>Tambahkan data MGMP untuk mengisi dashboard admin.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0">Aktivitas Terbaru</h6>
                        <small class="text-muted">{{ $recentReports->count() }} kegiatan</small>
                    </div>
                    @if($recentReports->count() > 0)
                        <div class="mgmp-timeline">
                            @foreach($recentReports as $activity)
                                <div class="mgmp-timeline-item">
                                    <div class="mgmp-timeline-dot">
                                        <i class="mdi mdi-calendar-check-outline"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $activity->judul ?? '-' }}</div>
                                        <div class="text-muted small mb-1">
                                            {{ $activity->mgmpGroup->name ?? 'MGMP' }}
                                            @if($activity->tanggal)
                                                • {{ \Carbon\Carbon::parse($activity->tanggal)->format('d M Y') }}
                                            @endif
                                        </div>
                                        <div class="text-muted small">Peserta tercatat: {{ $activity->jumlah_peserta ?? 0 }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="mgmp-empty-state py-5">
                            <i class="mdi mdi-information-outline"></i>
                            <strong>Belum ada aktivitas</strong>
                            <small>Kegiatan MGMP terbaru akan tampil pada panel ini.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="mb-1">Informasi Detail MGMP</h6>
                            <p class="text-muted small mb-0">Status tiap MGMP, pengelola, anggota, proposal, dan kegiatan terakhir.</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>MGMP</th>
                                    <th>Pengelola</th>
                                    <th>Anggota</th>
                                    <th>Sekolah</th>
                                    <th>Kegiatan</th>
                                    <th>Proposal</th>
                                    <th>Kegiatan Terakhir</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mgmpInsights as $index => $group)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($group->logo)
                                                    <img src="{{ url('/uploads/' . $group->logo) }}" alt="Logo {{ $group->name }}" class="mgmp-table-logo">
                                                @else
                                                    <div class="mgmp-table-logo mgmp-mini-logo-placeholder">
                                                        <i class="mdi mdi-school-outline"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold">{{ $group->name }}</div>
                                                    <small class="text-muted">ID Grup: {{ $group->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $group->owner_name }}</div>
                                            <small class="text-muted">{{ $group->owner_email }}</small>
                                        </td>
                                        <td>{{ $group->members_count }}</td>
                                        <td>{{ $group->school_count }}</td>
                                        <td>
                                            <div>{{ $group->reports_count }}</div>
                                            @if($group->latest_participants_count > 0)
                                                <small class="text-muted">Peserta terakhir: {{ $group->latest_participants_count }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $group->proposal_count }}</td>
                                        <td>
                                            @if($group->latest_report_title)
                                                <div class="fw-semibold">{{ Str::limit($group->latest_report_title, 34) }}</div>
                                                <small class="text-muted">{{ optional($group->latest_report_date)->format('d M Y') ?? '-' }}</small>
                                            @else
                                                <small class="text-muted">Belum ada kegiatan</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $group->status_class }}-subtle text-{{ $group->status_class }}">
                                                {{ $group->status_label }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9">
                                            <div class="mgmp-empty-state">
                                                <i class="bx bx-data"></i>
                                                <strong>Belum ada detail MGMP</strong>
                                                <small>Data detail MGMP akan tampil di sini.</small>
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

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="mb-3">Data Anggota MGMP</h5>
            <div class="table-responsive">
                <table id="datatable-anggota" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Grup MGMP</th>
                            <th>Sekolah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $m->name }}</td>
                            <td>{{ $m->email }}</td>
                            <td>{{ $m->mgmpGroup->name ?? '-' }}</td>
                            <td>{{ $m->sekolah ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">Belum ada anggota MGMP.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Daftar Proposal Academica</h5>
            <div class="table-responsive">
                <table id="datatable-academica-admin" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Pengupload</th>
                            <th>File</th>
                            <th>Diunggah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proposals as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->user->name ?? 'User ID ' . $p->user_id }}</td>
                            <td>{{ $p->filename }}</td>
                            <td>{{ $p->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ url('/uploads/' . $p->path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">Belum ada proposal.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
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

<script>
$(document).ready(function () {
    ["#datatable-anggota","#datatable-academica-admin"].forEach(function(sel){
        if ($.fn.DataTable.isDataTable(sel)) { $(sel).DataTable().destroy(); }
        let table = $(sel).DataTable({ responsive: true, lengthChange: true, autoWidth: false, destroy: true, buttons: ["copy","excel","pdf","print","colvis"] });
        table.buttons().container().appendTo(sel + '_wrapper .col-md-6:eq(0)');
    });
});
</script>

<style>
    .mgmp-summary-stack {
        display: grid;
        gap: 10px;
    }

    .mgmp-summary-row {
        align-items: center;
        background: #f7faf8;
        border: 1px solid #e7f0eb;
        border-radius: 12px;
        display: flex;
        justify-content: space-between;
        padding: 12px 14px;
    }

    .mgmp-summary-row span {
        color: #5d6f67;
        font-size: 13px;
    }

    .mgmp-summary-row strong {
        color: #102d28;
        font-size: 16px;
    }

    .mgmp-spotlight-card {
        background: linear-gradient(180deg, #ffffff 0%, #f7fbf8 100%);
        border: 1px solid #e5eee9;
        border-radius: 18px;
        padding: 18px;
    }

    .mgmp-mini-logo,
    .mgmp-table-logo {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .mgmp-table-logo {
        width: 38px;
        height: 38px;
    }

    .mgmp-mini-logo-placeholder {
        align-items: center;
        background: rgba(14, 133, 73, .10);
        color: #0e8549;
        display: flex;
        justify-content: center;
    }

    .mgmp-spotlight-grid {
        display: grid;
        gap: 10px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .mgmp-spotlight-grid div {
        background: #fff;
        border: 1px solid #edf3ef;
        border-radius: 14px;
        padding: 10px 12px;
    }

    .mgmp-spotlight-grid small {
        color: #6d7b75;
        display: block;
        margin-bottom: 4px;
    }

    .mgmp-spotlight-grid strong {
        color: #102d28;
        font-size: 18px;
    }

    .mgmp-timeline {
        display: grid;
        gap: 18px;
    }

    .mgmp-timeline-item {
        display: grid;
        gap: 12px;
        grid-template-columns: 36px minmax(0, 1fr);
    }

    .mgmp-timeline-dot {
        align-items: center;
        background: rgba(14, 133, 73, .12);
        border-radius: 12px;
        color: #0e8549;
        display: flex;
        height: 36px;
        justify-content: center;
        width: 36px;
    }

    @media (max-width: 768px) {
        .mgmp-spotlight-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>
@endsection
