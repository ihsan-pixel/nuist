{{-- resources/views/mgmp/dashboard.blade.php --}}
@extends('layouts.master')

@section('title') Dashboard MGMP - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY @endsection

@section('content')
@php
    $scopeLabel = $isPrivileged
        ? 'seluruh MGMP yang sudah terdata'
        : (($mgmpGroup && $mgmpGroup->name) ? $mgmpGroup->name : 'grup MGMP Anda');
    $topInsights = $mgmpInsights->take(3);
@endphp

@component('components.breadcrumb')
    @slot('li_1') MGMP @endslot
    @slot('title') Dashboard MGMP @endslot
@endcomponent

@include('mgmp.partials.ui-styles')

<div class="mgmp-page">
    <div class="mgmp-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="mgmp-kicker mb-2">Dashboard MGMP</div>
                <h4 class="mb-1">Informasi singkat dan detail MGMP</h4>
                <p class="mb-0 text-white-50">
                    Dashboard ini menampilkan ringkasan dan kondisi terkini untuk {{ $scopeLabel }}.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('mgmp.data-mgmp') }}" class="btn btn-light">
                    <i class="mdi mdi-office-building-cog-outline me-1"></i> Data MGMP
                </a>
                <a href="{{ route('mgmp.laporan') }}" class="btn btn-light">
                    <i class="mdi mdi-calendar-plus me-1"></i> Kelola Kegiatan
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
                        <div class="h5 mb-0">{{ $memberCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                            <i class="mdi mdi-school-outline fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Madrasah Terjangkau</div>
                        <div class="h5 mb-0">{{ $totalSchools ?? 0 }}</div>
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
                        <div class="text-muted small">Proposal Tercatat</div>
                        <div class="h5 mb-0">{{ $proposalCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="p-4 text-white" style="background: linear-gradient(135deg, #0e8549 0%, #0b6b4d 100%);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="avatar-lg profile-user-wid mb-0">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle mgmp-avatar-wrap">
                                    @if($mgmpGroup && !empty($mgmpGroup->logo))
                                        <img src="{{ url('/uploads/' . $mgmpGroup->logo) }}" alt="Logo MGMP" class="mgmp-avatar-image" />
                                    @else
                                        <i class="mdi mdi-school fs-2"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="grow">
                            <h5 class="mb-1 text-white">{{ Str::title(Auth::user()->name ?? 'MGMP User') }}</h5>
                            <p class="mb-1 text-white-50 small">{{ Auth::user()->email ?? '-' }}</p>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <small class="badge bg-white bg-opacity-10 text-white">Role: {{ Str::ucfirst(Auth::user()->role ?? 'mgmp') }}</small>
                                <small class="badge bg-white bg-opacity-10 text-white">ID: {{ Auth::user()->nuist_id ?? '-' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Cakupan dashboard</p>
                        <h6 class="mb-0">{{ $scopeLabel }}</h6>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="p-2 bg-light rounded text-center">
                                <div class="text-muted small">Kegiatan</div>
                                <div class="h5 mb-0">{{ $totalReports ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded text-center">
                                <div class="text-muted small">Rata-rata Anggota</div>
                                <div class="h5 mb-0">{{ $dashboardSummary['average_members'] ?? '0' }}</div>
                            </div>
                        </div>
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
                            <span>Perlu perhatian</span>
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
                            <h6 class="mb-1">Informasi Singkat MGMP</h6>
                            <p class="text-muted small mb-0">Sorotan grup yang paling aktif atau paling lengkap datanya.</p>
                        </div>
                        <span class="mgmp-chip">{{ $mgmpInsights->count() }} grup</span>
                    </div>

                    @if($topInsights->isNotEmpty())
                        <div class="row g-3">
                            @foreach($topInsights as $group)
                                <div class="col-md-4">
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
                                                    <h6 class="mb-0">{{ Str::limit($group->name, 24) }}</h6>
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
                                                <div class="fw-semibold">{{ Str::limit($group->latest_report_title, 42) }}</div>
                                                <small class="text-muted">
                                                    {{ optional($group->latest_report_date)->format('d M Y') ?? '-' }}
                                                </small>
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
                            <small>Tambahkan grup MGMP untuk menampilkan ringkasan dashboard.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
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
                                        <div class="text-muted small">{{ Str::limit($activity->deskripsi ?? 'Belum ada deskripsi kegiatan.', 100) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="mgmp-empty-state py-5">
                            <i class="mdi mdi-information-outline"></i>
                            <strong>Belum ada aktivitas</strong>
                            <small>Kegiatan MGMP yang dibuat akan muncul pada panel ini.</small>
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
                            <p class="text-muted small mb-0">Detail tiap MGMP yang sudah ada, termasuk pengelola dan aktivitas terakhir.</p>
                        </div>
                        <a href="{{ route('mgmp.data-anggota') }}" class="btn btn-sm btn-outline-primary">Data Anggota</a>
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
                                                <div class="fw-semibold">{{ Str::limit($group->latest_report_title, 32) }}</div>
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
                                                <small>Data akan tampil di sini setelah grup MGMP dibuat.</small>
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
</div>
@endsection

@section('script')
<style>
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }

    .mgmp-avatar-wrap {
        margin-top: 20px;
        width: 72px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .mgmp-avatar-image {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 50%;
    }

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
