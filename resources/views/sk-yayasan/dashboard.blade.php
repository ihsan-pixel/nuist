@extends('layouts.master')

@section('title')Dashboard SK Yayasan @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Dashboard SK Yayasan @endslot
@endcomponent

@php
    $cards = [
        ['label' => 'Pengajuan Masuk', 'value' => $statusCounts['submitted'] ?? 0, 'icon' => 'bx bx-send', 'class' => 'primary'],
        ['label' => 'Sedang Direview', 'value' => $statusCounts['reviewed'] ?? 0, 'icon' => 'bx bx-time', 'class' => 'warning'],
        ['label' => 'Disetujui', 'value' => $statusCounts['approved'] ?? 0, 'icon' => 'bx bx-check-circle', 'class' => 'success'],
        ['label' => 'SK Terbit', 'value' => $statusCounts['published'] ?? 0, 'icon' => 'bx bx-file', 'class' => 'info'],
    ];
@endphp

<div class="row">
    @foreach($cards as $card)
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-2">{{ $card['label'] }}</p>
                            <h3 class="mb-0">{{ $card['value'] }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-{{ $card['class'] }}-subtle text-{{ $card['class'] }}">
                                <i class="{{ $card['icon'] }} fs-4"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Ringkasan Penerbitan</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Draft Dokumen</span>
                        <strong>{{ $documentCounts['draft'] ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Dokumen Terbit Bulan Ini</span>
                        <strong>{{ $publishedThisMonth }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Template Aktif</span>
                        <strong>{{ $activeTemplates }}</strong>
                    </div>
                </div>

                <div class="alert alert-info mb-0">
                    Placeholder utama template:
                    <code>{{ '{{nama_pegawai}}' }}</code>,
                    <code>{{ '{{nama_sekolah}}' }}</code>,
                    <code>{{ '{{tanggal_mulai}}' }}</code>,
                    <code>{{ '{{tanggal_selesai}}' }}</code>.
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pengajuan Terbaru</h5>
                <a href="{{ route('sk-yayasan.pengajuan.index') }}" class="btn btn-sm btn-primary">Kelola Pengajuan</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No Pengajuan</th>
                                <th>Sekolah</th>
                                <th>Pegawai/Guru</th>
                                <th>Status</th>
                                <th>Template</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestRequests as $submission)
                                <tr>
                                    <td>{{ $submission->request_number }}</td>
                                    <td>{{ $submission->madrasah?->name ?? '-' }}</td>
                                    <td>{{ $submission->employee?->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary text-uppercase">
                                            {{ $submission->current_status }}
                                        </span>
                                    </td>
                                    <td>{{ $submission->template?->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada pengajuan SK Yayasan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Rekap Per Sekolah</h5>
        <a href="{{ route('sk-yayasan.generate.index') }}" class="btn btn-sm btn-outline-primary">Generate SK</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Sekolah</th>
                        <th>Total Pengajuan</th>
                        <th>Pending</th>
                        <th>SK Terbit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schoolSummaries as $school)
                        <tr>
                            <td>{{ $school->name }}</td>
                            <td>{{ $school->total_pengajuan_sk }}</td>
                            <td>{{ $school->total_pengajuan_pending }}</td>
                            <td>{{ $school->total_sk_terbit }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada sekolah yang mengirim pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
