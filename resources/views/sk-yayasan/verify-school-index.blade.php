@extends('layouts.master')

@section('title')Verifikasi SK Yayasan - {{ $madrasah->name }} @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('li_2') Generate SK Yayasan @endslot
    @slot('title') Verifikasi SK @endslot
@endcomponent

@include('sk-yayasan.partials.ui-styles')
@include('sk-yayasan.partials.sweet-alert')

<div class="sky-page">
    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="sky-kicker mb-2">Verifikasi SK Yayasan</div>
                <h4 class="mb-1">{{ $madrasah->name }}</h4>
                <p class="mb-0 text-white-50">Periksa PDF setiap guru, lalu tentukan apakah SK sudah sesuai atau masih perlu penyesuaian.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('sk-yayasan.generate.index') }}" class="btn btn-light">
                    <i class="bx bx-arrow-back me-1"></i>Kembali
                </a>
                <a href="{{ route('sk-yayasan.generate.school', $madrasah) }}" class="btn btn-outline-light">
                    Halaman Generate
                </a>
            </div>
        </div>
    </div>

    <div class="alert {{ $uppmIsPaid ? 'alert-success' : 'alert-warning' }} border-0 mb-4">
        <div class="fw-semibold">Status UPPPM {{ $uppmYear }} — {{ $uppmPeriodLabel }}</div>
        <div>{{ $uppmStatusLabel }}. SK yang sudah sesuai {{ $uppmIsPaid ? 'akan langsung berstatus Siap Diambil.' : 'akan berstatus Menunggu Pembayaran UPPPM.' }}</div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Daftar Verifikasi</div>
                    <h6 class="mb-0">{{ $requests->count() }} pengajuan tersinkron</h6>
                </div>
                <span class="sky-chip">Belum dichecklist = Proses</span>
            </div>

            @if($requests->isNotEmpty())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Request</th>
                                <th>Guru/Pegawai</th>
                                <th>Nomor SK</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $submission)
                                @php
                                    $status = match ($submission->current_status) {
                                        'ready_for_pickup' => ['success', 'Siap Diambil'],
                                        'waiting_uppm_payment' => ['warning', 'Menunggu UPPPM'],
                                        'revision_required' => ['danger', 'Perlu Penyesuaian'],
                                        default => ['info', 'Proses'],
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $submission->request_number }}</div>
                                        <small class="text-muted">{{ $submission->submission_type_label ?? ucfirst($submission->request_type) }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $submission->employee?->name ?? '-' }}</div>
                                        <small class="text-muted">{{ $submission->employee?->statusKepegawaian?->name ?? '-' }}</small>
                                    </td>
                                    <td>{{ $submission->document?->document_number ?? 'Belum digenerate' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $status[0] }}-subtle text-{{ $status[0] }}">{{ $status[1] }}</span>
                                        @if($submission->skVerifier)
                                            <small class="text-muted d-block mt-1">
                                                {{ $submission->skVerifier->name }} · {{ optional($submission->sk_verified_at)->format('d/m/Y H:i') }}
                                            </small>
                                        @endif
                                    </td>
                                    <td style="min-width: 220px">
                                        <form id="verify-sk-{{ $submission->id }}" method="POST" action="{{ route('sk-yayasan.generate.verify.update', $submission) }}">
                                            @csrf
                                            @method('PATCH')
                                            <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Catatan penyesuaian (opsional)">{{ $submission->sk_verification_notes }}</textarea>
                                        </form>
                                    </td>
                                    <td style="min-width: 260px">
                                        <div class="d-flex flex-wrap gap-2">
                                            @if($submission->document)
                                                <a href="{{ route('sk-yayasan.documents.download', $submission->document) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                                                    <i class="bx bx-show me-1"></i>View SK
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled>View SK</button>
                                            @endif
                                            <button form="verify-sk-{{ $submission->id }}" name="decision" value="correct" class="btn btn-sm btn-success" @disabled(!$submission->document)>
                                                <i class="bx bx-check me-1"></i>Sesuai
                                            </button>
                                            <button form="verify-sk-{{ $submission->id }}" name="decision" value="needs_revision" class="btn btn-sm btn-outline-danger">
                                                <i class="bx bx-x me-1"></i>Belum Sesuai
                                            </button>
                                            @if($submission->sk_verification_status)
                                                <button form="verify-sk-{{ $submission->id }}" name="decision" value="processing" class="btn btn-sm btn-light">
                                                    Reset Checklist
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="sky-empty-state py-5">
                    <i class="bx bx-file-find"></i>
                    <strong>Belum ada pengajuan untuk diverifikasi</strong>
                    <small>Pengajuan akan tampil setelah batch sekolah berhasil disinkronkan.</small>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
