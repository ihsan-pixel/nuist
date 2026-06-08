@extends('layouts.master')

@section('title')Dashboard SK Yayasan @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Dashboard SK Yayasan @endslot
@endcomponent

@include('sk-yayasan.partials.ui-styles')

@php
    $cards = [
        ['label' => 'Pengajuan Masuk', 'value' => $statusCounts['submitted'] ?? 0, 'icon' => 'bx bx-send', 'class' => 'primary'],
        ['label' => 'Sedang Direview', 'value' => $statusCounts['reviewed'] ?? 0, 'icon' => 'bx bx-time', 'class' => 'warning'],
        ['label' => 'Disetujui', 'value' => $statusCounts['approved'] ?? 0, 'icon' => 'bx bx-check-circle', 'class' => 'success'],
        ['label' => 'SK Terbit', 'value' => $statusCounts['published'] ?? 0, 'icon' => 'bx bx-file', 'class' => 'info'],
    ];
@endphp

<div class="sky-page">
    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="sky-kicker mb-2">Dashboard SK Yayasan</div>
                <h4 class="mb-1">Monitoring pengajuan, template, dan penerbitan SK</h4>
                <p class="mb-0 text-white-50">
                    Gunakan halaman ini untuk melihat antrean pengajuan dari sekolah, status review, dan progres penerbitan SK Yayasan.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('sk-yayasan.pengajuan.index') }}" class="btn btn-light">
                    <i class="mdi mdi-clipboard-text-outline me-1"></i> Kelola Pengajuan
                </a>
                <a href="{{ route('sk-yayasan.generate.index') }}" class="btn btn-light">
                    <i class="mdi mdi-file-document-edit-outline me-1"></i> Generate SK
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        @foreach($cards as $card)
            <div class="col-xl-3 col-md-6">
                <div class="card sky-stat-card p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md me-3">
                            <div class="avatar-title bg-{{ $card['class'] }}-subtle text-{{ $card['class'] }} rounded-circle">
                                <i class="{{ $card['icon'] }} fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="text-muted small">{{ $card['label'] }}</div>
                            <div class="h4 mb-0">{{ $card['value'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3">
        <div class="col-xl-4">
            <div class="card h-100 overflow-hidden">
                <div class="p-4 text-white" style="background: linear-gradient(135deg, #0e8549 0%, #0b6b4d 100%);">
                    <div class="sky-kicker mb-2">Ringkasan Penerbitan</div>
                    <h5 class="mb-1 text-white">Kondisi dokumen saat ini</h5>
                    <p class="mb-0 text-white-50 small">
                        Draft, template aktif, dan jumlah SK yang sudah terbit pada periode berjalan.
                    </p>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="sky-metric">
                                <div class="value">{{ $documentCounts['draft'] ?? 0 }}</div>
                                <div class="label">Draft Dokumen</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="sky-metric">
                                <div class="value">{{ $publishedThisMonth }}</div>
                                <div class="label">Terbit Bulan Ini</div>
                            </div>
                        </div>
                    </div>
                    <div class="sky-summary-stack mb-3">
                        <div class="sky-summary-row">
                            <span>Template aktif</span>
                            <strong>{{ $activeTemplates }}</strong>
                        </div>
                        <div class="sky-summary-row">
                            <span>Dokumen publish</span>
                            <strong>{{ $documentCounts['published'] ?? 0 }}</strong>
                        </div>
                        <div class="sky-summary-row">
                            <span>Perlu digenerate</span>
                            <strong>{{ $statusCounts['approved'] ?? 0 }}</strong>
                        </div>
                    </div>
                    <div class="alert alert-info mb-0">
                        <div class="fw-semibold mb-1">Placeholder utama template</div>
                        <div class="small">
                            <code>{{ '{{nama_pegawai}}' }}</code>,
                            <code>{{ '{{nama_sekolah}}' }}</code>,
                            <code>{{ '{{status_kepegawaian}}' }}</code>,
                            <code>{{ '{{tanggal_terbit}}' }}</code>.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Pengajuan Terbaru</div>
                            <h6 class="mb-0">Antrean pengajuan yang baru masuk</h6>
                        </div>
                        <span class="sky-chip">{{ $latestRequests->count() }} data</span>
                    </div>

                    @if($latestRequests->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>No Pengajuan</th>
                                        <th>Sekolah</th>
                                        <th>Pegawai/Guru</th>
                                        <th>Status</th>
                                        <th>Template</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestRequests as $submission)
                                        <tr>
                                            <td class="fw-semibold">{{ $submission->request_number }}</td>
                                            <td>{{ $submission->madrasah?->name ?? '-' }}</td>
                                            <td>{{ $submission->employee?->name ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-secondary-subtle text-secondary text-uppercase">
                                                    {{ $submission->current_status }}
                                                </span>
                                            </td>
                                            <td>{{ $submission->template?->name ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-inbox"></i>
                            <strong>Belum ada pengajuan</strong>
                            <small>Pengajuan dari sekolah akan muncul di panel ini.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Rekap Sekolah</div>
                    <h6 class="mb-0">Sekolah dengan pengajuan dan status penerbitan</h6>
                </div>
                <a href="{{ route('sk-yayasan.template.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="mdi mdi-text-box-edit-outline me-1"></i> Kelola Template
                </a>
            </div>

            @if($schoolSummaries->isNotEmpty())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Sekolah</th>
                                <th>Total Pengajuan</th>
                                <th>Pending</th>
                                <th>SK Terbit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schoolSummaries as $school)
                                <tr>
                                    <td class="fw-semibold">{{ $school->name }}</td>
                                    <td>{{ $school->total_pengajuan_sk }}</td>
                                    <td>{{ $school->total_pengajuan_pending }}</td>
                                    <td>{{ $school->total_sk_terbit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="sky-empty-state">
                    <i class="bx bx-building-house"></i>
                    <strong>Belum ada sekolah yang mengirim pengajuan</strong>
                    <small>Rekap akan muncul setelah admin sekolah mulai mengajukan perpanjangan SK.</small>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
