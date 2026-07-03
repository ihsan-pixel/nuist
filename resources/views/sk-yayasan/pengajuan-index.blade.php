@extends('layouts.master')

@section('title')Pengajuan Perpanjangan SK @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Pengajuan Perpanjangan SK @endslot
@endcomponent

@include('sk-yayasan.partials.ui-styles')
@include('sk-yayasan.partials.sweet-alert')

<style>
    .sky-pagination-wrap {
        display: flex;
        justify-content: flex-end;
    }

    .sky-pagination-wrap nav {
        width: auto;
    }

    .sky-pagination-wrap .pagination {
        gap: .35rem;
        justify-content: flex-end;
        margin-bottom: 0;
    }

    .sky-pagination-wrap .page-item .page-link {
        align-items: center;
        border: 1px solid #dbe7e1;
        border-radius: 10px;
        color: #34524a;
        display: inline-flex;
        font-size: 12px;
        font-weight: 600;
        height: 34px;
        justify-content: center;
        min-width: 34px;
        padding: .35rem .65rem;
    }

    .sky-pagination-wrap .page-item.active .page-link {
        background: linear-gradient(135deg, #004b4c, #0e8549);
        border-color: transparent;
        color: #fff;
    }

    .sky-pagination-wrap .page-item.disabled .page-link {
        background: #f4f8f6;
        border-color: #e6efea;
        color: #9aa9a3;
    }

    .sky-pagination-wrap .page-link:hover {
        background: #eef7f2;
        border-color: #bfd7cb;
        color: #0e8549;
    }

    .sky-edit-cell {
        min-width: 130px;
    }

    .sky-edit-cell .form-control,
    .sky-edit-cell .form-select {
        min-width: 130px;
        padding: .35rem .55rem;
    }

    .sky-edit-cell-sm {
        min-width: 88px;
    }

    .sky-cell-error {
        background: #fff1f1 !important;
    }

    .sky-cell-error .form-control,
    .sky-cell-error .form-select {
        background: #fff7f7;
        border-color: #dc3545 !important;
        color: #842029;
    }

    .sky-cell-error-readonly {
        background: #fff1f1 !important;
        color: #842029 !important;
    }

    .sky-page {
        --sky-ink: #182a25;
        --sky-muted: #6a7b74;
        --sky-line: #dfe8e3;
        --sky-soft: #f5f8f6;
        --sky-soft-strong: #eef4f0;
        --sky-brand: #0f6b4f;
        --sky-brand-dark: #0a4d3f;
        --sky-warn: #9a6a00;
    }

    .sky-page .card,
    .sky-page .accordion-item,
    .sky-page .modal-content {
        background: #fff;
        border: 1px solid var(--sky-line) !important;
        border-radius: 18px !important;
        box-shadow: 0 10px 24px rgba(18, 40, 33, 0.04) !important;
    }

    .sky-page .modal-header,
    .sky-page .card-header,
    .sky-page .card-footer {
        background: #fff;
        border-color: var(--sky-line);
    }

    .sky-page .card-header:first-child {
        border-radius: 18px 18px 0 0 !important;
    }

    .sky-page h4,
    .sky-page h5,
    .sky-page h6,
    .sky-page .card-title,
    .sky-page .modal-title {
        color: var(--sky-ink);
    }

    .sky-page .btn {
        border-radius: 12px;
        font-weight: 600;
    }

    .sky-page .btn-primary,
    .sky-page .btn-success {
        background: var(--sky-brand-dark);
        border-color: var(--sky-brand-dark);
    }

    .sky-page .btn-primary:hover,
    .sky-page .btn-success:hover {
        background: var(--sky-brand);
        border-color: var(--sky-brand);
    }

    .sky-page .btn-light {
        background: #fff;
        border: 1px solid var(--sky-line);
        color: var(--sky-ink);
    }

    .sky-page .btn-outline-primary {
        border-color: rgba(15, 107, 79, .24);
        color: var(--sky-brand);
    }

    .sky-page .btn-outline-primary:hover {
        background: var(--sky-brand);
        border-color: var(--sky-brand);
        color: #fff;
    }

    .sky-page .form-control,
    .sky-page .form-select {
        border-color: #d7e2dc;
        border-radius: 12px;
        min-height: 44px;
    }

    .sky-page textarea.form-control {
        min-height: auto;
    }

    .sky-page .table {
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .sky-page .table thead th {
        background: #f7faf8 !important;
        border-bottom: 1px solid var(--sky-line) !important;
        color: var(--sky-ink);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        vertical-align: middle;
    }

    .sky-page .table tbody td {
        border-bottom: 1px solid #edf3ef;
        color: #2f463e;
        vertical-align: middle;
    }

    .sky-page .badge {
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        padding: 7px 10px;
    }

    .sky-header {
        background: linear-gradient(180deg, #ffffff 0%, #fbfcfb 100%);
        border: 1px solid var(--sky-line);
        border-radius: 22px;
        padding: 1.5rem;
    }

    .sky-section-kicker {
        color: var(--sky-brand);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .sky-page-title {
        color: var(--sky-ink);
        font-size: 1.65rem;
        font-weight: 700;
        margin-bottom: .35rem;
    }

    .sky-page-subtitle {
        color: var(--sky-muted);
        margin-bottom: 0;
        max-width: 760px;
    }

    .sky-summary-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    }

    .sky-summary-card {
        background: #fff;
        border: 1px solid var(--sky-line);
        border-radius: 18px;
        padding: 1rem 1.1rem;
    }

    .sky-summary-value {
        color: var(--sky-ink);
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1;
    }

    .sky-summary-label {
        color: var(--sky-muted);
        font-size: .8rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .sky-summary-note {
        color: var(--sky-muted);
        display: block;
        font-size: 12px;
        line-height: 1.5;
        margin-top: .65rem;
    }

    .sky-keterangan-grid {
        display: grid;
        gap: .85rem;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    }

    .sky-keterangan-card {
        background: var(--sky-soft);
        border: 1px solid var(--sky-line);
        border-radius: 16px;
        min-width: 0;
        padding: .8rem .85rem;
    }

    .sky-keterangan-card .sky-summary-label {
        font-size: .68rem;
        letter-spacing: .03em;
    }

    .sky-keterangan-value {
        color: var(--sky-brand-dark);
        font-size: 1.1rem;
        font-weight: 800;
        line-height: 1;
    }

    .sky-panel-label {
        color: var(--sky-muted);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
    }

    .sky-chip {
        background: var(--sky-soft);
        border: 1px solid var(--sky-line);
        border-radius: 999px;
        color: var(--sky-brand-dark);
        display: inline-flex;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 10px;
    }

    .sky-filter-shell {
        background: var(--sky-soft);
        border: 1px solid var(--sky-line);
        border-radius: 16px;
        padding: 1rem;
    }

    .sky-filter-shell .form-control,
    .sky-filter-shell .form-select {
        background: #fff;
    }

    .sky-empty-state {
        align-items: center;
        color: var(--sky-muted);
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 32px 12px;
        text-align: center;
    }

    .sky-empty-state i {
        color: rgba(15, 107, 79, .35);
        font-size: 42px;
    }

    .sky-soft-card {
        background: linear-gradient(180deg, #ffffff 0%, #fafcfb 100%);
        border: 1px solid var(--sky-line);
        border-radius: 18px;
    }

    .sky-inline-note {
        border: 1px solid var(--sky-line);
        border-radius: 14px;
        padding: 12px 14px;
    }

    .sky-inline-note-info {
        background: #eef7ff;
        border-color: #cfe6ff;
        color: #174a7c;
    }

    .sky-inline-note-success {
        background: #edf9f2;
        border-color: #cdeed9;
        color: #18633e;
    }

    .sky-inline-note-danger {
        background: #fff2f2;
        border-color: #f3c9c9;
        color: #8a1f1f;
    }

    .sky-inline-note-secondary {
        background: #f6f7f9;
        border-color: #e2e6ea;
        color: #475569;
    }

    .sky-inline-note-warning {
        background: #fff8e8;
        border-color: #f5dfac;
        color: #815e00;
    }

    .sky-compact-table thead th,
    .sky-compact-table tbody td {
        font-size: 12px;
        padding: 10px 12px;
        white-space: nowrap;
    }

    .sky-compact-table tbody td.wrap {
        white-space: normal;
    }

    .sky-modal-table-wrap {
        border: 1px solid var(--sky-line);
        border-radius: 16px;
        max-height: 420px;
        overflow: auto;
    }

    .sky-mini-stat {
        background: var(--sky-soft);
        border: 1px solid var(--sky-line);
        border-radius: 14px;
        padding: 12px 14px;
    }

    .sky-mini-stat .value {
        color: var(--sky-ink);
        font-size: 18px;
        font-weight: 700;
        line-height: 1.1;
    }

    .sky-mini-stat .label {
        color: var(--sky-muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        margin-bottom: 4px;
        text-transform: uppercase;
    }

    .sky-file-meta {
        color: var(--sky-muted);
        font-size: 12px;
    }

    .sky-data-primary {
        color: var(--sky-ink);
        font-weight: 600;
    }

    .sky-data-secondary {
        color: var(--sky-muted);
        display: block;
        font-size: 12px;
        line-height: 1.5;
        margin-top: .2rem;
    }

    .sky-row-select-col {
        min-width: 42px;
        text-align: center;
        width: 42px;
    }

    .sky-table-actions {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        justify-content: space-between;
        margin-bottom: .75rem;
    }

    .sky-section-divider {
        border-top: 1px solid var(--sky-line);
        margin: 1.5rem 0;
    }

    @media (max-width: 768px) {
        .sky-header {
            padding: 1.1rem;
        }

        .sky-page-title {
            font-size: 1.35rem;
        }
    }
</style>

@php
    $keteranganOptions = \App\Support\SkYayasanImportSynchronizer::allowedKeteranganOptions();
    $importPreviewFieldMap = [
        'No' => 'excel_no',
        'NUIST ID' => 'source_nuist_id',
        'Nama' => 'source_nama',
        'Gelar' => 'source_gelar',
        'Tempat Lahir' => 'source_tempat_lahir',
        'Tanggal Lahir' => 'source_tanggal_lahir',
        "NIP Ma'arif" => 'source_nip_maarif',
        'NUPTK' => 'source_nuptk',
        'Nomor Kartanu' => 'source_nomor_kartanu',
        'TMT Pertama' => 'source_tmt_pertama',
        'Masa Kerja' => 'source_masa_kerja',
        'Pendidikan Terakhir' => 'source_pendidikan_terakhir',
        'Tahun Lulus' => 'source_tahun_lulus',
        'Program Studi' => 'source_program_studi',
        'Mapel/Tugas yang Diampu' => 'source_mapel_tugas',
        'Penilaian Kinerja' => 'source_penilaian_kinerja',
        'Keterangan' => 'source_keterangan',
    ];

    $importBatchModalItems = $pendingImportBatches->getCollection()
        ->merge($syncedImportBatches->getCollection())
        ->unique('id');

    $statusOptions = [
        'submitted' => 'Diajukan',
        'reviewed' => 'Direview',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'published' => 'Terbit',
    ];

    $statusBadgeMap = [
        'submitted' => ['color' => 'warning', 'label' => 'Diajukan'],
        'reviewed' => ['color' => 'info', 'label' => 'Direview'],
        'approved' => ['color' => 'primary', 'label' => 'Disetujui'],
        'published' => ['color' => 'success', 'label' => 'Terbit'],
        'rejected' => ['color' => 'danger', 'label' => 'Ditolak'],
    ];

    $batchStatusBadgeMap = [
        'pending_review' => ['color' => 'warning', 'label' => 'Pending Review'],
        'synced' => ['color' => 'success', 'label' => 'Tersinkron'],
        'rejected' => ['color' => 'danger', 'label' => 'Ditolak'],
    ];

    $resolveImportErrorFields = function ($row) {
        $errors = collect($row->validation_errors ?? [])->map(fn ($error) => (string) $error);
        $fields = [];
        $identifierFields = ['source_nuist_id', 'source_nama', 'source_nip_maarif', 'source_nuptk'];

        if ($errors->contains(fn ($error) => str_contains($error, 'Isi minimal salah satu data pencocokan'))) {
            $fields = array_merge($fields, $identifierFields);
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'User tidak ditemukan'))) {
            $fields = array_merge($fields, $identifierFields, ['matched_name']);
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'Tanggal Lahir tidak valid'))) {
            $fields[] = 'source_tanggal_lahir';
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'TMT Pertama tidak valid'))) {
            $fields[] = 'source_tmt_pertama';
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'Tahun Lulus harus 4 digit'))) {
            $fields[] = 'source_tahun_lulus';
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'Penilaian Kinerja harus berupa angka'))) {
            $fields[] = 'source_penilaian_kinerja';
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'Keterangan wajib diisi'))) {
            $fields[] = 'source_keterangan';
        }

        return array_values(array_unique($fields));
    };
@endphp

<div class="sky-page">
    <div class="sky-header mb-4">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <div class="sky-section-kicker mb-2">SK Yayasan</div>
                <h4 class="sky-page-title">Pengajuan perpanjangan SK</h4>
                <p class="sky-page-subtitle">
                    Halaman ini merangkum pengajuan aktif, hasil sinkronisasi batch, serta kondisi tiap sekolah agar proses review yayasan lebih rapi dan cepat ditindaklanjuti.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('sk-yayasan.template.index') }}" class="btn btn-light">
                    <i class="mdi mdi-text-box-edit-outline me-1"></i>Template
                </a>
                <a href="{{ route('sk-yayasan.generate.index') }}" class="btn btn-light">
                    <i class="mdi mdi-file-document-multiple-outline me-1"></i>Generate
                </a>
                <a href="{{ route('sk-yayasan.pengajuan.export-school-summary') }}" class="btn btn-primary">
                    <i class="mdi mdi-microsoft-excel me-1"></i>Rekap Sekolah
                </a>
            </div>
        </div>
    </div>

    <div class="sky-summary-grid mb-4">
        <div class="sky-summary-card">
            <div class="sky-summary-label">Sekolah Sudah Mengajukan</div>
            <div class="sky-summary-value mt-2">{{ number_format($schoolSubmissionSummaryCards['submitted_schools'] ?? 0) }}</div>
            <span class="sky-summary-note">Sekolah yang sudah memiliki pengajuan atau batch aktif.</span>
        </div>
        <div class="sky-summary-card">
            <div class="sky-summary-label">Sekolah Belum Mengajukan</div>
            <div class="sky-summary-value mt-2">{{ number_format($schoolSubmissionSummaryCards['not_submitted_schools'] ?? 0) }}</div>
            <span class="sky-summary-note">Sekolah yang belum mengirim batch aktif ke yayasan.</span>
        </div>
        <div class="sky-summary-card">
            <div class="sky-summary-label">Total Pengajuan Aktif</div>
            <div class="sky-summary-value mt-2">{{ number_format($schoolSubmissionSummaryCards['total_requests'] ?? 0) }}</div>
            <span class="sky-summary-note">Seluruh pengajuan yang sedang diproses pada tahap review atau penerbitan.</span>
        </div>
        <div class="sky-summary-card">
            <div class="sky-summary-label">Belum Match Akun NUist</div>
            <div class="sky-summary-value mt-2">{{ number_format($schoolSubmissionSummaryCards['requests_without_nuist_account'] ?? 0) }}</div>
            <span class="sky-summary-note">Jumlah baris pada batch terakhir yang masih perlu dicocokkan dengan akun.</span>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Komposisi Keterangan</div>
                    <h6 class="mb-0">Jenis pengajuan yang sedang berjalan</h6>
                </div>
                <span class="sky-chip">{{ number_format(collect($keteranganSummaryCounts)->sum()) }} data terpetakan</span>
            </div>

            @if(!empty($keteranganSummaryCounts))
                <div class="sky-keterangan-grid">
                    @foreach($keteranganSummaryCounts as $label => $count)
                        <div class="sky-keterangan-card">
                            <div class="sky-summary-label mb-2">{{ $label }}</div>
                            <div class="sky-keterangan-value">{{ number_format($count) }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="sky-empty-state py-4">
                    <i class="bx bx-receipt"></i>
                    <strong>Belum ada kategori pengajuan yang terpetakan</strong>
                    <small>Ringkasan akan muncul setelah batch aktif berhasil dibaca sistem.</small>
                </div>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Daftar Pengajuan</div>
                    <h6 class="mb-0">Semua pengajuan yang masuk ke yayasan, termasuk yang ditolak</h6>
                </div>
                <span class="sky-chip">{{ number_format($submissions->total()) }} pengajuan</span>
            </div>

            <form method="GET" class="sky-filter-shell mb-3">
                <div class="row g-2 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label mb-1">Cari sekolah, pegawai, atau nomor surat</label>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            class="form-control"
                            placeholder="Contoh: SMA Ma'arif, Ahmad, atau nomor surat">
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label mb-1">Sekolah</label>
                        <select name="madrasah_id" class="form-select">
                            <option value="">Semua sekolah</option>
                            @foreach($madrasahs as $madrasah)
                                <option value="{{ $madrasah->id }}" @selected((string) request('madrasah_id') === (string) $madrasah->id)>{{ $madrasah->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label mb-1">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua status</option>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Terapkan</button>
                            <a href="{{ route('sk-yayasan.pengajuan.index') }}" class="btn btn-light">Reset</a>
                        </div>
                    </div>
                </div>
            </form>

            @if($submissions->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Pengajuan</th>
                                <th>Pegawai</th>
                                <th>Sekolah</th>
                                <th>Surat Pengajuan</th>
                                <th>Status</th>
                                <th>Template</th>
                                <th>Catatan Review</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $submission)
                                @php
                                    $submissionBadge = $statusBadgeMap[$submission->current_status] ?? ['color' => 'secondary', 'label' => ucfirst(str_replace('_', ' ', (string) $submission->current_status))];
                                @endphp
                                <tr>
                                    <td>
                                        <span class="sky-data-primary">{{ $submission->request_number }}</span>
                                        <span class="sky-data-secondary">Masuk {{ optional($submission->submitted_at)->format('d/m/Y H:i') ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="sky-data-primary">{{ $submission->employee?->name ?? '-' }}</span>
                                        <span class="sky-data-secondary">{{ $submission->employee?->statusKepegawaian?->name ?? ($submission->employee?->ketugasan ?? 'Status belum tersedia') }}</span>
                                    </td>
                                    <td>
                                        <span class="sky-data-primary">{{ $submission->madrasah?->name ?? '-' }}</span>
                                        <span class="sky-data-secondary">Dikirim oleh {{ $submission->submitter?->name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="sky-data-primary">{{ $submission->submission_letter_number ?? '-' }}</span>
                                        <span class="sky-data-secondary">{{ optional($submission->submission_letter_date)->translatedFormat('d M Y') ?? 'Tanggal belum diisi' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $submissionBadge['color'] }}-subtle text-{{ $submissionBadge['color'] }}">{{ $submissionBadge['label'] }}</span>
                                        <span class="sky-data-secondary">
                                            @if($submission->reviewer)
                                                Review oleh {{ $submission->reviewer->name }}{{ $submission->reviewed_at ? ' • ' . $submission->reviewed_at->format('d/m/Y H:i') : '' }}
                                            @else
                                                Menunggu review yayasan
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="sky-data-primary">{{ $submission->template?->name ?? 'Belum dipilih' }}</span>
                                        <span class="sky-data-secondary">{{ $submission->document ? 'Draft/PDF tersedia' : 'Belum ada dokumen' }}</span>
                                    </td>
                                    <td>
                                        <span class="sky-data-secondary mt-0">{{ \Illuminate\Support\Str::limit($submission->review_notes ?: 'Belum ada catatan review.', 110) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                            @if($submission->document)
                                                <a href="{{ route('sk-yayasan.documents.download', $submission->document) }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">
                                                    PDF
                                                </a>
                                            @endif
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $submission->id }}">
                                                Review
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="sky-empty-state py-5">
                    <i class="bx bx-search-alt"></i>
                    <strong>Tidak ada pengajuan yang sesuai filter</strong>
                    <small>Coba ubah kata kunci, sekolah, atau status termasuk filter ditolak untuk melihat data lain.</small>
                </div>
            @endif
        </div>

        @if($submissions->hasPages())
            <div class="card-footer">
                <div class="sky-pagination-wrap">
                    {{ $submissions->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

    <div class="row g-3">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Batch Pending</div>
                            <h6 class="mb-0">Import yang masih menunggu review</h6>
                        </div>
                        <span class="sky-chip">{{ $pendingImportBatches->total() }} batch</span>
                    </div>

                    @if($pendingImportBatches->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>File Import</th>
                                        <th>Sekolah</th>
                                        <th>Upload</th>
                                        <th>Validasi</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingImportBatches as $batch)
                                        <tr>
                                            <td>
                                                <span class="sky-data-primary">{{ $batch->original_filename }}</span>
                                            </td>
                                            <td>
                                                <span class="sky-data-primary">{{ $batch->madrasah?->name ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="sky-data-primary">{{ $batch->uploader?->name ?? '-' }}</span>
                                                <span class="sky-data-secondary">{{ optional($batch->uploaded_at)->format('d/m/Y H:i') }}</span>
                                            </td>
                                            <td>
                                                <span class="sky-data-primary">{{ $batch->valid_rows }} valid / {{ $batch->invalid_rows }} salah</span>
                                                <span class="sky-data-secondary">{{ $batch->headings_valid ? 'Kolom sesuai template' : 'Kolom belum sesuai template' }}</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#importBatchModal{{ $batch->id }}">
                                                        Review
                                                    </button>
                                                    <form method="POST"
                                                          action="{{ route('sk-yayasan.import-batches.destroy', $batch) }}"
                                                          data-sk-swal-confirm
                                                          data-sk-swal-title="Hapus pengajuan ini?"
                                                          data-sk-swal-text="Semua request, dokumen, dan lampiran pada batch ini akan dihapus permanen."
                                                          data-sk-swal-confirm-text="Ya, hapus"
                                                          data-sk-swal-icon="warning">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-spreadsheet"></i>
                            <strong>Tidak ada batch pending review</strong>
                            <small>Batch baru dari sekolah akan muncul di sini sebelum disinkronkan.</small>
                        </div>
                    @endif
                </div>

                @if($pendingImportBatches->hasPages())
                    <div class="card-footer">
                        <div class="sky-pagination-wrap">
                            {{ $pendingImportBatches->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Batch Tersinkron</div>
                            <h6 class="mb-0">Import yang sudah masuk ke aplikasi</h6>
                        </div>
                        <span class="sky-chip">{{ $syncedImportBatches->total() }} batch • {{ $syncedImportBatchSchoolCount }} sekolah</span>
                    </div>

                    @if($syncedImportBatches->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>File Import</th>
                                        <th>Sekolah</th>
                                        <th>Tersinkron</th>
                                        <th>Data Sinkron</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($syncedImportBatches as $batch)
                                        @php
                                            $matchedValidRowsCount = $batch->rows
                                                ->filter(fn ($row) => $row->is_valid && $row->matched_user_id)
                                                ->unique('matched_user_id')
                                                ->count();
                                            $displaySubmissionCount = $batch->requests_count > 0
                                                ? $batch->requests_count
                                                : $matchedValidRowsCount;
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="sky-data-primary">{{ $batch->original_filename }}</span>
                                            </td>
                                            <td>
                                                <span class="sky-data-primary">{{ $batch->madrasah?->name ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="sky-data-primary">{{ $batch->reviewer?->name ?? '-' }}</span>
                                                <span class="sky-data-secondary">{{ optional($batch->synced_at)->format('d/m/Y H:i') ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="sky-data-primary">{{ number_format($displaySubmissionCount) }} pengajuan</span>
                                                <span class="sky-data-secondary">{{ number_format($batch->valid_rows) }} dari {{ number_format($batch->total_rows) }} baris valid</span>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importBatchModal{{ $batch->id }}">
                                                    Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-check-shield"></i>
                            <strong>Belum ada batch tersinkron</strong>
                            <small>Batch yang sudah berhasil disinkronkan ke aplikasi akan tampil di sini.</small>
                        </div>
                    @endif
                </div>

                @if($syncedImportBatches->hasPages())
                    <div class="card-footer">
                        <div class="sky-pagination-wrap">
                            {{ $syncedImportBatches->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Monitoring Sekolah</div>
                    <h6 class="mb-0">Status ringkas pengajuan per sekolah</h6>
                </div>
                <span class="sky-chip">{{ count($schoolSubmissionSummaryRows) }} sekolah</span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle sky-compact-table">
                    <thead>
                        <tr>
                            <th>SCOD</th>
                            <th>Sekolah</th>
                            <th>Status</th>
                            <th>Pengajuan</th>
                            <th>Batch Aktif</th>
                            <th>Batch Terakhir</th>
                            <th>Ditolak</th>
                            <th>Belum Match</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schoolSubmissionSummaryRows as $row)
                            @php
                                $schoolStatusBadge = match ($row['submission_status_label']) {
                                    'Sudah Mengajukan' => ['color' => 'success', 'label' => 'Sudah Mengajukan'],
                                    'Ditolak' => ['color' => 'danger', 'label' => 'Ditolak'],
                                    default => ['color' => 'secondary', 'label' => 'Belum Mengajukan'],
                                };
                                $latestBatchBadge = $batchStatusBadgeMap[$row['latest_batch_status'] ?? ''] ?? ['color' => 'secondary', 'label' => 'Belum ada batch'];
                                $hasRejectedHistory = ($row['rejected_requests_count'] ?? 0) > 0 || ($row['rejected_batch_count'] ?? 0) > 0;
                            @endphp
                            <tr>
                                <td>{{ $row['scod'] ?: '-' }}</td>
                                <td>
                                    <span class="sky-data-primary">{{ $row['school_name'] ?: '-' }}</span>
                                    <span class="sky-data-secondary">{{ $row['kabupaten'] ?: '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $schoolStatusBadge['color'] }}-subtle text-{{ $schoolStatusBadge['color'] }}">{{ $schoolStatusBadge['label'] }}</span>
                                    @if($hasRejectedHistory && $row['submission_status_label'] !== 'Ditolak')
                                        <span class="badge bg-danger-subtle text-danger mt-1">Ada Riwayat Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ number_format($row['total_requests']) }}</td>
                                <td>{{ number_format($row['active_batch_count']) }}</td>
                                <td>
                                    <span class="badge bg-{{ $latestBatchBadge['color'] }}-subtle text-{{ $latestBatchBadge['color'] }}">{{ $latestBatchBadge['label'] }}</span>
                                    <span class="sky-data-secondary">{{ optional($row['latest_batch_uploaded_at'])->format('d/m/Y H:i') ?: 'Belum ada upload' }}</span>
                                </td>
                                <td>
                                    <span class="sky-data-primary">{{ number_format($row['rejected_requests_count'] ?? 0) }} pengajuan</span>
                                    <span class="sky-data-secondary">{{ number_format($row['rejected_batch_count'] ?? 0) }} batch ditolak</span>
                                </td>
                                <td>{{ number_format($row['latest_batch_unmatched_count']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="sky-empty-state py-4">
                                        <i class="bx bx-buildings"></i>
                                        <strong>Belum ada data sekolah</strong>
                                        <small>Ringkasan sekolah akan tampil otomatis saat data pengajuan tersedia.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach($submissions as $submission)
        <div class="modal fade" id="reviewModal{{ $submission->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('sk-yayasan.pengajuan.update-status', $submission) }}" class="modal-content">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title">Review Pengajuan {{ $submission->request_number }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="sky-soft-card p-3 h-100">
                                    <div class="sky-panel-label mb-1">Sekolah</div>
                                    <div class="fw-semibold">{{ $submission->madrasah?->name ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="sky-soft-card p-3 h-100">
                                    <div class="sky-panel-label mb-1">Pegawai/Guru</div>
                                    <div class="fw-semibold">{{ $submission->employee?->name ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="sky-soft-card p-3 h-100">
                                    <div class="sky-panel-label mb-1">Status Kepegawaian</div>
                                    <div class="fw-semibold">{{ $submission->employee?->statusKepegawaian?->name ?? ($submission->employee?->ketugasan ?? '-') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="sky-soft-card p-3 h-100">
                                    <div class="sky-panel-label mb-1">Nomor Surat Pengajuan</div>
                                    <div class="fw-semibold">{{ $submission->submission_letter_number ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="sky-soft-card p-3 h-100">
                                    <div class="sky-panel-label mb-1">Tanggal Surat Pengajuan</div>
                                    <div class="fw-semibold">{{ optional($submission->submission_letter_date)->translatedFormat('d F Y') ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        @if($submission->importBatch)
                            <div class="sky-inline-note sky-inline-note-info">
                                Berkas terkait:
                                <a href="{{ route('sk-yayasan.import-batches.attachments.download', [$submission->importBatch, 'excel']) }}" class="ms-2" target="_blank" rel="noopener">Excel</a>
                                <a href="{{ route('sk-yayasan.import-batches.attachments.download', [$submission->importBatch, 'fakta_integritas']) }}" class="ms-2" target="_blank" rel="noopener">Pakta Integritas</a>
                                <a href="{{ route('sk-yayasan.import-batches.attachments.download', [$submission->importBatch, 'penilaian_perilaku']) }}" class="ms-2" target="_blank" rel="noopener">Penilaian Perilaku</a>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="current_status" class="form-select" required>
                                <option value="reviewed" @selected($submission->current_status === 'reviewed')>Direview</option>
                                <option value="approved" @selected($submission->current_status === 'approved')>Setujui</option>
                                <option value="rejected" @selected($submission->current_status === 'rejected')>Tolak</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Template Rekomendasi</label>
                            <select name="template_id" class="form-select">
                                <option value="">Belum dipilih</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" @selected($submission->template_id == $template->id)>{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Catatan Review</label>
                            <textarea name="review_notes" rows="4" class="form-control">{{ $submission->review_notes }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Review</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    @foreach($importBatchModalItems as $batch)
        @php
            $batchBadge = $batch->status === 'synced'
                ? ['bg' => 'success', 'label' => 'TERSINKRON']
                : ($batch->status === 'rejected'
                    ? ['bg' => 'danger', 'label' => 'DITOLAK']
                    : ['bg' => 'warning', 'label' => 'PENDING REVIEW']);
        @endphp
        <div class="modal fade" id="importBatchModal{{ $batch->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen-xl-down modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title mb-1">Review Import Data</h5>
                            <div class="sky-file-meta">{{ $batch->original_filename }} - {{ $batch->madrasah?->name ?? '-' }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2 mb-3">
                            <div class="col-md-3 col-6">
                                <div class="sky-mini-stat">
                                    <div class="label">Uploader</div>
                                    <div class="value">{{ $batch->uploader?->name ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="sky-mini-stat">
                                    <div class="label">Upload</div>
                                    <div class="value">{{ optional($batch->uploaded_at)->format('d/m/Y') ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="sky-mini-stat">
                                    <div class="label">Valid</div>
                                    <div class="value">{{ $batch->valid_rows }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="sky-mini-stat">
                                    <div class="label">Perlu Cek</div>
                                    <div class="value">{{ $batch->invalid_rows }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            <span class="badge bg-{{ $batchBadge['bg'] }}-subtle text-{{ $batchBadge['bg'] }}">{{ $batchBadge['label'] }}</span>
                            <span class="sky-chip">{{ $batch->headings_valid ? 'Kolom sesuai template' : 'Kolom belum sesuai template' }}</span>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <div class="sky-soft-card p-3 h-100">
                                    <div class="sky-panel-label mb-2">Lampiran Excel</div>
                                    <div class="small mb-2">{{ $batch->original_filename }}</div>
                                    <a href="{{ route('sk-yayasan.import-batches.attachments.download', [$batch, 'excel']) }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">Lihat File</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="sky-soft-card p-3 h-100">
                                    <div class="sky-panel-label mb-2">Pakta Integritas</div>
                                    <div class="small mb-2">{{ $batch->fakta_integritas_filename ?? '-' }}</div>
                                    <a href="{{ route('sk-yayasan.import-batches.attachments.download', [$batch, 'fakta_integritas']) }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">Lihat File</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="sky-soft-card p-3 h-100">
                                    <div class="sky-panel-label mb-2">Penilaian Perilaku</div>
                                    <div class="small mb-2">{{ $batch->penilaian_perilaku_filename ?? '-' }}</div>
                                    <a href="{{ route('sk-yayasan.import-batches.attachments.download', [$batch, 'penilaian_perilaku']) }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">Lihat File</a>
                                </div>
                            </div>
                        </div>

                        @if(!$batch->headings_valid)
                            <div class="sky-inline-note sky-inline-note-danger">
                                Format kolom file belum sesuai template.
                                @if(!empty($batch->missing_headings))
                                    <div>Kolom kurang: {{ implode(', ', $batch->missing_headings) }}</div>
                                @endif
                                @if(!empty($batch->unexpected_headings))
                                    <div>Kolom tidak dikenali: {{ implode(', ', $batch->unexpected_headings) }}</div>
                                @endif
                            </div>
                        @endif

                        @if($batch->review_notes)
                            <div class="sky-inline-note sky-inline-note-secondary">
                                <strong>Catatan Review:</strong> {{ $batch->review_notes }}
                            </div>
                        @endif

                        @if($batch->invalid_rows > 0)
                            <div class="sky-inline-note sky-inline-note-danger mb-3">
                                Kolom dengan warna merah menandakan data itu masih perlu diperbaiki.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('sk-yayasan.import-batches.rows.update', $batch) }}" id="editImportBatchForm{{ $batch->id }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="save" data-sync-action>
                            <div class="sky-table-actions">
                                <div class="text-muted small">
                                    Pilih satu atau beberapa baris untuk dihapus dari batch ini sebelum disimpan.
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-delete-selected-rows>
                                    Hapus Baris Terpilih
                                </button>
                            </div>
                            <div class="sky-modal-table-wrap">
                                <table class="table table-sm align-middle sky-compact-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="sky-row-select-col">
                                                <input type="checkbox" class="form-check-input" data-select-all-rows>
                                            </th>
                                            @foreach($importPreviewColumns as $column)
                                                <th>{{ $column }}</th>
                                            @endforeach
                                            <th>Match User</th>
                                            <th>Status</th>
                                            <th class="wrap">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($batch->rows as $row)
                                            @php
                                                $rowErrorFields = $resolveImportErrorFields($row);
                                            @endphp
                                            <tr>
                                                <td class="sky-row-select-col">
                                                    <input type="checkbox" class="form-check-input" data-row-select>
                                                </td>
                                                <input type="hidden" name="rows[{{ $loop->index }}][row_number]" value="{{ $row->row_number }}">
                                                @foreach($importPreviewColumns as $column)
                                                    @php
                                                        $field = $importPreviewFieldMap[$column] ?? null;
                                                        $value = $field ? data_get($row, $field, '') : '';
                                                        $value = $value === '-' ? '' : $value;
                                                        $hasFieldError = $field && in_array($field, $rowErrorFields, true);
                                                    @endphp
                                                    <td class="sky-edit-cell {{ $column === 'No' ? 'sky-edit-cell-sm' : '' }} {{ $hasFieldError ? 'sky-cell-error' : '' }}">
                                                        @if($column === 'Keterangan')
                                                            <select name="rows[{{ $loop->parent->index }}][{{ $field }}]" class="form-select form-select-sm">
                                                                <option value="">Pilih</option>
                                                                @foreach($keteranganOptions as $option)
                                                                    <option value="{{ $option }}" @selected($value === $option)>{{ $option }}</option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <input type="text"
                                                                   name="rows[{{ $loop->parent->index }}][{{ $field }}]"
                                                                   value="{{ $value }}"
                                                                   class="form-control form-control-sm">
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="{{ in_array('matched_name', $rowErrorFields, true) ? 'sky-cell-error-readonly' : '' }}">{{ $row->matched_name ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $row->is_valid ? 'success' : 'danger' }}-subtle text-{{ $row->is_valid ? 'success' : 'danger' }}">
                                                        {{ $row->status_label ?? ($row->is_valid ? 'Siap sync' : 'Perlu perbaikan') }}
                                                    </span>
                                                </td>
                                                <td class="wrap">{{ !empty($row->validation_errors) ? implode(' ', $row->validation_errors) : 'Data siap disinkronkan.' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        @if($batch->status === 'pending_review')
                            <div class="w-100">
                                <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                                    <div class="text-muted small">
                                        Data pada tabel bisa diedit langsung. Simpan dulu agar status validasi dan match user diperbarui.
                                    </div>
                                    <button type="submit"
                                            form="editImportBatchForm{{ $batch->id }}"
                                            class="btn btn-outline-primary"
                                            onclick="this.form.querySelector('[data-sync-action]').value='save'">
                                        Simpan Perubahan Tabel
                                    </button>
                                </div>

                                <form method="POST" action="{{ route('sk-yayasan.import-batches.review', $batch) }}" class="w-100">
                                    @csrf
                                    @method('PATCH')
                                    <div class="mb-3">
                                        <label class="form-label">Catatan Review</label>
                                        <textarea name="review_notes" rows="3" class="form-control" placeholder="Isi catatan untuk admin sekolah bila perlu."></textarea>
                                    </div>
                                    <div class="d-flex flex-wrap justify-content-end gap-2">
                                        <button type="submit" name="action" value="reject" class="btn btn-outline-danger">Tolak Batch</button>
                                        <button type="submit" name="action" value="sync" class="btn btn-primary" @disabled(!$batch->headings_valid || $batch->invalid_rows > 0)>Sinkronkan ke Database</button>
                                    </div>
                                </form>

                                <form method="POST"
                                      action="{{ route('sk-yayasan.import-batches.destroy', $batch) }}"
                                      class="w-100 mt-2"
                                      data-sk-swal-confirm
                                      data-sk-swal-title="Hapus pengajuan ini?"
                                      data-sk-swal-text="Semua request, dokumen, dan lampiran pada batch ini akan dihapus permanen."
                                      data-sk-swal-confirm-text="Ya, hapus"
                                      data-sk-swal-icon="warning">
                                    @csrf
                                    @method('DELETE')
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-outline-danger">Hapus Pengajuan</button>
                                    </div>
                                </form>
                            </div>
                        @elseif($batch->status === 'rejected')
                            <div class="d-flex flex-wrap justify-content-between gap-2 w-100">
                                <button type="submit"
                                        form="editImportBatchForm{{ $batch->id }}"
                                        class="btn btn-outline-primary"
                                        onclick="this.form.querySelector('[data-sync-action]').value='save'">
                                    Simpan & Kembalikan ke Pending Review
                                </button>
                                <div class="d-flex flex-wrap gap-2 ms-auto">
                                    <form method="POST"
                                          action="{{ route('sk-yayasan.import-batches.destroy', $batch) }}"
                                          data-sk-swal-confirm
                                          data-sk-swal-title="Hapus pengajuan ini?"
                                          data-sk-swal-text="Semua request, dokumen, dan lampiran pada batch ini akan dihapus permanen."
                                          data-sk-swal-confirm-text="Ya, hapus"
                                          data-sk-swal-icon="warning">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Hapus Pengajuan</button>
                                    </form>
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        @elseif($batch->status === 'synced')
                            <div class="w-100">
                                <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                                    <div class="text-muted small">
                                        Data yang sudah tersinkron tetap bisa diedit. Simpan perubahan lalu lakukan sinkronisasi ulang agar perubahan diterapkan kembali ke aplikasi.
                                    </div>
                                    <button type="submit"
                                            form="editImportBatchForm{{ $batch->id }}"
                                            class="btn btn-outline-primary"
                                            onclick="this.form.querySelector('[data-sync-action]').value='save'">
                                        <i class="bx bx-save me-1"></i>Simpan Perubahan Tabel
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Catatan Sinkronisasi Ulang</label>
                                    <textarea name="review_notes"
                                              rows="3"
                                              class="form-control"
                                              form="editImportBatchForm{{ $batch->id }}"
                                              placeholder="Catatan opsional untuk sinkronisasi ulang batch ini."></textarea>
                                </div>
                                <div class="d-flex flex-wrap justify-content-end gap-2">
                                    <button type="submit"
                                            form="editImportBatchForm{{ $batch->id }}"
                                            class="btn btn-primary"
                                            onclick="this.form.querySelector('[data-sync-action]').value='sync'"
                                            @disabled(!$batch->headings_valid || $batch->invalid_rows > 0)>
                                        <i class="bx bx-refresh me-1"></i>Sinkronisasi Ulang
                                    </button>
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-end w-100">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@section('script')
<script>
    document.addEventListener('change', function (event) {
        if (event.target.matches('[data-select-all-rows]')) {
            const table = event.target.closest('table');

            if (!table) {
                return;
            }

            table.querySelectorAll('[data-row-select]').forEach(function (checkbox) {
                checkbox.checked = event.target.checked;
            });
        }

        if (event.target.matches('[data-row-select]')) {
            const table = event.target.closest('table');

            if (!table) {
                return;
            }

            const rowCheckboxes = Array.from(table.querySelectorAll('[data-row-select]'));
            const checkedCount = rowCheckboxes.filter(function (checkbox) {
                return checkbox.checked;
            }).length;
            const selectAllCheckbox = table.querySelector('[data-select-all-rows]');

            if (!selectAllCheckbox) {
                return;
            }

            selectAllCheckbox.checked = rowCheckboxes.length > 0 && checkedCount === rowCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < rowCheckboxes.length;
        }
    });

    document.addEventListener('click', function (event) {
        const deleteButton = event.target.closest('[data-delete-selected-rows]');

        if (!deleteButton) {
            return;
        }

        const modalContent = deleteButton.closest('.modal-content');
        const table = modalContent ? modalContent.querySelector('table') : null;

        if (!table) {
            return;
        }

        const selectedRows = Array.from(table.querySelectorAll('[data-row-select]:checked'));
        const totalRows = table.querySelectorAll('[data-row-select]').length;

        if (selectedRows.length === 0) {
            alert('Pilih minimal satu baris yang ingin dihapus.');
            return;
        }

        if (selectedRows.length === totalRows) {
            alert('Minimal satu baris harus tetap tersisa di dalam batch.');
            return;
        }

        if (!window.confirm('Hapus semua baris yang dipilih dari batch ini?')) {
            return;
        }

        selectedRows.forEach(function (checkbox) {
            const row = checkbox.closest('tr');

            if (row) {
                row.remove();
            }
        });

        const selectAllCheckbox = table.querySelector('[data-select-all-rows]');

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    });
</script>
@endsection
