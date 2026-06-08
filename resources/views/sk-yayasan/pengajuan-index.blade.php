@extends('layouts.master')

@section('title')Pengajuan Perpanjangan SK @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Pengajuan Perpanjangan SK @endslot
@endcomponent

@include('sk-yayasan.partials.ui-styles')

@php
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
@endphp

<div class="sky-page">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="sky-kicker mb-2">Verifikasi Yayasan</div>
                <h4 class="mb-1">Review pengajuan perpanjangan dari sekolah</h4>
                <p class="mb-0 text-white-50">
                    Telaah pengajuan, beri catatan review, pilih template, lalu lanjutkan ke proses generate SK Yayasan.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('sk-yayasan.template.index') }}" class="btn btn-light">
                    <i class="mdi mdi-text-box-edit-outline me-1"></i> Template
                </a>
                <a href="{{ route('sk-yayasan.generate.index') }}" class="btn btn-light">
                    <i class="mdi mdi-file-document-multiple-outline me-1"></i> Generate
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Filter Pengajuan</div>
                    <h6 class="mb-0">Saring antrean berdasarkan sekolah, status, atau kata kunci</h6>
                </div>
                <span class="sky-chip">{{ $submissions->total() }} total pengajuan</span>
            </div>

            <form method="GET" class="row g-2 align-items-end mb-3">
                <div class="col-md-4">
                    <label class="form-label">Cari</label>
                    <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Nomor pengajuan / nama pegawai / sekolah">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sekolah</label>
                    <select name="madrasah_id" class="form-select">
                        <option value="">Semua sekolah</option>
                        @foreach($madrasahs as $madrasah)
                            <option value="{{ $madrasah->id }}" @selected((string) request('madrasah_id') === (string) $madrasah->id)>{{ $madrasah->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua status</option>
                        @foreach(['submitted', 'reviewed', 'approved', 'rejected', 'published'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ strtoupper($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>

            @if($submissions->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>No Pengajuan</th>
                                <th>Sekolah</th>
                                <th>Pegawai/Guru</th>
                                <th>Status</th>
                                <th>Dokumen</th>
                                <th>Aksi Review</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $submission)
                                <tr>
                                    <td class="fw-semibold">{{ $submission->request_number }}</td>
                                    <td>{{ $submission->madrasah?->name ?? '-' }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $submission->employee?->name ?? '-' }}</div>
                                        <small class="text-muted">{{ $submission->employee?->statusKepegawaian?->name ?? ($submission->employee?->ketugasan ?? '-') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary text-uppercase">{{ $submission->current_status }}</span>
                                    </td>
                                    <td>
                                        @if($submission->document)
                                            <a href="{{ route('sk-yayasan.documents.download', $submission->document) }}" class="btn btn-sm btn-outline-primary" target="_blank">Lihat PDF</a>
                                        @else
                                            <span class="text-muted small">Belum ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $submission->id }}">
                                            Review
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
                    <strong>Belum ada pengajuan dari sekolah</strong>
                    <small>Pengajuan baru akan muncul di sini untuk direview oleh Yayasan.</small>
                </div>
            @endif
        </div>

        @if($submissions->hasPages())
            <div class="card-footer bg-white">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Review Import Data</div>
                    <h6 class="mb-0">Tinjau file Excel dari admin sekolah sebelum sinkronisasi ke database</h6>
                </div>
                <span class="sky-chip">{{ $importBatches->total() }} total batch</span>
            </div>

            <form method="GET" class="row g-2 align-items-end mb-3">
                <input type="hidden" name="q" value="{{ request('q') }}">
                <input type="hidden" name="madrasah_id" value="{{ request('madrasah_id') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="col-md-4">
                    <label class="form-label">Status Batch Import</label>
                    <select name="import_status" class="form-select">
                        <option value="">Semua status batch</option>
                        @foreach(['pending_review' => 'Pending Review', 'rejected' => 'Ditolak', 'synced' => 'Tersinkron'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('import_status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-primary">Filter Batch</button>
                </div>
            </form>

            @if($importBatches->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Batch</th>
                                <th>Sekolah</th>
                                <th>Upload</th>
                                <th>Validasi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($importBatches as $batch)
                                @php
                                    $batchBadge = $batch->status === 'synced'
                                        ? ['bg' => 'success', 'label' => 'TERSINKRON']
                                        : ($batch->status === 'rejected'
                                            ? ['bg' => 'danger', 'label' => 'DITOLAK']
                                            : ['bg' => 'warning', 'label' => 'PENDING REVIEW']);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold">#{{ $batch->id }}</div>
                                        <small class="text-muted">{{ $batch->original_filename }}</small>
                                    </td>
                                    <td>{{ $batch->madrasah?->name ?? '-' }}</td>
                                    <td>
                                        <div>{{ $batch->uploader?->name ?? '-' }}</div>
                                        <small class="text-muted">{{ optional($batch->uploaded_at)->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $batch->valid_rows }} valid / {{ $batch->invalid_rows }} salah</div>
                                        <small class="text-muted">
                                            {{ $batch->headings_valid ? 'Kolom sesuai template' : 'Kolom belum sesuai template' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $batchBadge['bg'] }}-subtle text-{{ $batchBadge['bg'] }}">{{ $batchBadge['label'] }}</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#importBatchModal{{ $batch->id }}">
                                            Lihat Review
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="sky-empty-state py-5">
                    <i class="bx bx-spreadsheet"></i>
                    <strong>Belum ada batch import dari sekolah</strong>
                    <small>File Excel yang diupload admin sekolah akan muncul di sini untuk direview sebelum sinkronisasi.</small>
                </div>
            @endif
        </div>

        @if($importBatches->hasPages())
            <div class="card-footer bg-white">
                {{ $importBatches->links() }}
            </div>
        @endif
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
                        </div>
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

    @foreach($importBatches as $batch)
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
                            <h5 class="modal-title mb-1">Review Batch Import #{{ $batch->id }}</h5>
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

                        @if(!$batch->headings_valid)
                            <div class="alert alert-danger">
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
                            <div class="alert alert-secondary">
                                <strong>Catatan Review:</strong> {{ $batch->review_notes }}
                            </div>
                        @endif

                        <div class="sky-modal-table-wrap">
                            <table class="table table-sm align-middle sky-compact-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Baris</th>
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
                                        <tr>
                                            <td>{{ $row->row_number ?? '-' }}</td>
                                            @foreach($importPreviewColumns as $column)
                                                <td>{{ data_get($row, $importPreviewFieldMap[$column] ?? '', '-') ?: '-' }}</td>
                                            @endforeach
                                            <td>{{ $row->matched_name ?? '-' }}</td>
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
                    </div>
                    <div class="modal-footer">
                        @if($batch->status === 'pending_review')
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
                        @else
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
