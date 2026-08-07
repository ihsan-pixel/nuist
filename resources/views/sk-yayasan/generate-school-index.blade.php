@extends('layouts.master')

@section('title')Generate SK Yayasan - {{ $madrasah->name }} @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('li_2') Generate SK Yayasan @endslot
    @slot('title') {{ $madrasah->name }} @endslot
@endcomponent

@include('sk-yayasan.partials.ui-styles')
@include('sk-yayasan.partials.sweet-alert')

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

    $importBatchModalItems = $requests
        ->pluck('importBatch')
        ->filter()
        ->unique('id')
        ->values();
    $generatedDocumentsCount = $requests->filter(fn ($submission) => filled($submission->document?->document_number))->count();
    $lockedDocumentsCount = $requests->filter(fn ($submission) => $submission->document?->number_locked_at !== null)->count();

    $shouldIgnorePerformanceScoreError = function ($row) {
        $keterangan = \Illuminate\Support\Str::lower(trim((string) ($row->source_keterangan ?? '')));

        return str_contains($keterangan, 'gtt') || str_contains($keterangan, 'ptt');
    };

    $resolveImportValidationMessages = function ($row) use ($shouldIgnorePerformanceScoreError) {
        return collect($row->validation_errors ?? [])
            ->map(fn ($error) => (string) $error)
            ->reject(fn ($error) => $shouldIgnorePerformanceScoreError($row) && str_contains($error, 'Penilaian Kinerja'));
    };

    $resolveImportErrorFields = function ($row) use ($resolveImportValidationMessages) {
        $errors = $resolveImportValidationMessages($row);
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

        if ($errors->contains(fn ($error) => str_contains($error, 'Penilaian Kinerja wajib diisi'))) {
            $fields[] = 'source_penilaian_kinerja';
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'Keterangan wajib diisi'))) {
            $fields[] = 'source_keterangan';
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'NIPM wajib diisi') || str_contains($error, 'belum memiliki NIPM'))) {
            $fields[] = 'source_nip_maarif';
        }

        return array_values(array_unique($fields));
    };

    $resolveNipmImportWarning = function ($row) {
        $errors = collect($row->validation_errors ?? [])->map(fn ($error) => (string) $error);
        $keterangan = \Illuminate\Support\Str::lower(trim((string) ($row->source_keterangan ?? '')));
        $nipmValue = trim((string) ($row->source_nip_maarif ?? ''));
        $tmtValue = trim((string) ($row->source_tmt_pertama ?? ''));

        $existingWarning = $errors->first(fn ($error) => str_contains($error, 'NIPM wajib diisi') || str_contains($error, 'belum memiliki NIPM'));
        if ($existingWarning) {
            return $existingWarning;
        }

        $betweenTwoAndThreeYears = false;
        if ($tmtValue !== '') {
            try {
                $normalizedTmt = str_ireplace(
                    ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    $tmtValue
                );
                $tmtDate = \Illuminate\Support\Carbon::parse($normalizedTmt)->startOfDay();
                $today = now()->startOfDay();
                $betweenTwoAndThreeYears = $tmtDate->copy()->addYears(2)->lessThanOrEqualTo($today)
                    && $tmtDate->copy()->addYears(3)->greaterThan($today);
            } catch (\Throwable $exception) {
            }
        }

        if ($betweenTwoAndThreeYears) {
            return null;
        }

        if (in_array($keterangan, ['perpanjangan gty', 'perpanjangan pty'], true) && $nipmValue === '') {
            return 'NIPM wajib diisi untuk pengajuan Perpanjangan GTY/PTY.';
        }

        return null;
    };
@endphp

<div class="sky-page">
    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="sky-kicker mb-2">Generate SK Yayasan</div>
                <h4 class="mb-1">{{ $madrasah->name }}</h4>
                <p class="mb-0 text-white-50">
                    Daftar pengajuan pada sekolah ini yang siap digenerate menjadi draft PDF. Template akan mengikuti jenis pengajuan dan kategori pegawai.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('sk-yayasan.generate.index') }}" class="btn btn-light">
                    <i class="bx bx-arrow-back me-1"></i>Kembali ke Antrean
                </a>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $requests->count() }} pengajuan</span>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Surat Pengajuan SK</div>
                            <h6 class="mb-1">Nomor dan tanggal surat pengajuan sekolah</h6>
                            <p class="text-muted mb-0">Perubahan di form ini akan diterapkan ke semua pengajuan tersinkron yang tampil pada halaman generate sekolah ini.</p>
                        </div>
                        @if($submissionLetterIsMixed)
                            <span class="badge bg-warning-subtle text-warning">Data saat ini berbeda-beda antar pengajuan</span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('sk-yayasan.generate.school.submission-letter.update', $madrasah) }}">
                        @csrf
                        @method('PATCH')
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-7">
                                <label class="form-label fw-semibold">Nomor Surat Pengajuan</label>
                                <input type="text"
                                       name="submission_letter_number"
                                       class="form-control @error('submission_letter_number') is-invalid @enderror"
                                       value="{{ old('submission_letter_number', $submissionLetterReference?->submission_letter_number) }}"
                                       placeholder="Contoh: 421.5/SMK-PD/VI/2026"
                                       required>
                                @error('submission_letter_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label fw-semibold">Tanggal Surat</label>
                                <input type="date"
                                       name="submission_letter_date"
                                       class="form-control @error('submission_letter_date') is-invalid @enderror"
                                       value="{{ old('submission_letter_date', optional($submissionLetterReference?->submission_letter_date)->format('Y-m-d')) }}"
                                       required>
                                @error('submission_letter_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-2 d-grid">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bx bx-save me-1"></i>Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Antrean Generate</div>
                            <h6 class="mb-0">Generate otomatis satu sekolah atau tetap per guru</h6>
                        </div>
                        <div class="sky-action-cluster">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="sky-chip">{{ $requests->count() }} data</span>
                                @if($generatedDocumentsCount > 0)
                                    <span class="sky-chip">{{ $generatedDocumentsCount }} nomor tergenerate</span>
                                @endif
                                @if($lockedDocumentsCount > 0)
                                    <span class="sky-chip">{{ $lockedDocumentsCount }} nomor terkunci</span>
                                @endif
                            </div>
                            @if($requests->count() > 0)
                                <div class="dropdown">
                                    <button class="btn action-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-grid-alt me-1"></i>Aksi Generate
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-modern">
                                        <li>
                                            <form method="POST"
                                                  action="{{ route('sk-yayasan.generate.school.pdf', $madrasah) }}"
                                                  data-sk-pdf-post>
                                                @csrf
                                                <input type="hidden" name="issued_date" value="{{ $coreData['issued_date'] }}">
                                                <input type="hidden" name="school_year" value="{{ $coreData['school_year'] }}">
                                                <input type="hidden" name="document_number_start" value="{{ $coreData['document_number_start'] }}">
                                                <input type="hidden" name="number_format_suffix" value="{{ $coreData['number_format_suffix'] }}">
                                                <input type="hidden" name="signer_name" value="{{ $coreData['signer_name'] }}">
                                                <input type="hidden" name="signer_position" value="{{ $coreData['signer_position'] }}">
                                                <input type="hidden" name="established_at" value="{{ $coreData['established_at'] }}">
                                                <input type="hidden" name="copy_recipient_1" value="{{ $coreData['copy_recipient_1'] }}">
                                                <input type="hidden" name="copy_recipient_2" value="{{ $coreData['copy_recipient_2'] }}">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bx bx-printer me-2 text-primary"></i>{{ $schoolHasLockedNumbers ? 'Generate Semua Guru Sekolah Ini' : 'Preview Semua Guru Sekolah Ini' }}
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <a href="{{ route('sk-yayasan.numbers.index', ['madrasah_id' => $madrasah->id]) }}#document-list" class="dropdown-item">
                                                <i class="bx bx-hash me-2 text-warning"></i>Kelola Nomor SK Sekolah Ini
                                            </a>
                                        </li>
                                        @foreach($importBatchModalItems as $batch)
                                            <li>
                                                <button type="button"
                                                        class="dropdown-item"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#generateImportBatchModal{{ $batch->id }}">
                                                    <i class="bx bx-detail me-2 text-info"></i>Lihat Data Detail{{ $importBatchModalItems->count() > 1 ? ' Batch ' . $loop->iteration : '' }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if(!$schoolHasLockedNumbers)
                        <div class="alert alert-warning border-0 mb-3">
                            Sekolah ini belum memiliki nomor SK yang terkunci. Generate dari halaman ini hanya membuka preview PDF, dan draft baru akan tersimpan setelah nomor SK diisi dari halaman <strong>Nomor SK Yayasan</strong>.
                        </div>
                    @endif

                    @if($requests->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Request</th>
                                        <th>Guru/Pegawai</th>
                                        <th>Jenis Pengajuan</th>
                                        <th>Template</th>
                                        <th>Status</th>
                                        <th>Nomor SK</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $submission)
                                        @php
                                            $isSyncedBatch = in_array($submission->current_status, ['submitted', 'reviewed'], true)
                                                && $submission->importBatch?->status === 'synced';
                                            $badgeConfig = $submission->current_status === 'published'
                                                ? ['bg' => 'success', 'text' => 'success', 'label' => 'PUBLISHED']
                                                : ($submission->current_status === 'approved'
                                                    ? ['bg' => 'primary', 'text' => 'primary', 'label' => 'APPROVED']
                                                    : ($isSyncedBatch
                                                        ? ['bg' => 'info', 'text' => 'info', 'label' => 'TERSINKRON']
                                                        : ['bg' => 'warning', 'text' => 'warning', 'label' => strtoupper($submission->current_status)]));
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $submission->request_number }}</div>
                                                @if($submission->importBatch)
                                                    <small class="text-muted">{{ $submission->importBatch->original_filename }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $submission->employee?->name ?? '-' }}</td>
                                            <td>{{ $submission->submission_type_label }}</td>
                                            <td>
                                                @if($submission->resolved_template)
                                                    <div class="fw-semibold">{{ $submission->resolved_template->name }}</div>
                                                    @if($submission->template_mismatch && $submission->stored_template)
                                                        <small class="text-warning d-block mt-1">
                                                            Draft tersimpan sebelumnya memakai template <strong>{{ $submission->stored_template->name }}</strong>.
                                                            Generate ulang untuk menyamakan dengan keterangan pengajuan saat ini.
                                                        </small>
                                                    @elseif($submission->stored_template && $submission->document?->number_locked_at)
                                                        <small class="text-muted d-block mt-1">
                                                            Template dokumen terkunci: <strong>{{ $submission->stored_template->name }}</strong>
                                                        </small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Pilih manual saat generate</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $badgeConfig['bg'] }}-subtle text-{{ $badgeConfig['text'] }}">
                                                    {{ $badgeConfig['label'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>{{ $submission->document?->document_number ?? '-' }}</div>
                                                @if($submission->document?->number_locked_at)
                                                    <small class="text-success fw-semibold">Terkunci</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <form method="POST" action="{{ route('sk-yayasan.generate.store') }}" data-sk-pdf-post>
                                                        @csrf
                                                        <input type="hidden" name="request_id" value="{{ $submission->id }}">
                                                        <input type="hidden" name="preview_pdf" value="1">
                                                        <input type="hidden" name="issued_date" value="{{ $coreData['issued_date'] }}">
                                                        <input type="hidden" name="school_year" value="{{ $coreData['school_year'] }}">
                                                        <input type="hidden" name="document_number_start" value="{{ $coreData['document_number_start'] }}">
                                                        <input type="hidden" name="number_format_suffix" value="{{ $coreData['number_format_suffix'] }}">
                                                        <input type="hidden" name="signer_name" value="{{ $coreData['signer_name'] }}">
                                                        <input type="hidden" name="signer_position" value="{{ $coreData['signer_position'] }}">
                                                        <input type="hidden" name="established_at" value="{{ $coreData['established_at'] }}">
                                                        <input type="hidden" name="copy_recipient_1" value="{{ $coreData['copy_recipient_1'] }}">
                                                        <input type="hidden" name="copy_recipient_2" value="{{ $coreData['copy_recipient_2'] }}">
                                                        <input type="hidden" name="publication_notes" value="{{ $submission->document?->publication_notes }}">
                                                        @if($submission->resolved_template)
                                                            <input type="hidden" name="template_id" value="{{ $submission->resolved_template->id }}">
                                                        @else
                                                            <input type="hidden" name="template_id" value="{{ $submission->template_id }}">
                                                        @endif
                                                        <button type="submit" class="btn btn-sm btn-outline-primary" @disabled(!$submission->resolved_template && !$submission->template_id)>
                                                            {{ $schoolHasLockedNumbers ? 'Generate 1 Guru' : 'Preview 1 Guru' }}
                                                        </button>
                                                    </form>
                                                    @if($submission->document)
                                                        <a href="{{ route('sk-yayasan.documents.download', $submission->document) }}" class="btn btn-sm btn-outline-primary" target="_blank">Preview PDF</a>
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
                            <strong>Belum ada pengajuan yang siap digenerate</strong>
                            <small>Pengajuan yang sudah disetujui atau batch yang sudah tersinkronisasi akan tampil di halaman ini.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($importBatchModalItems as $batch)
    @php
        $batchBadge = $batch->status === 'synced'
            ? ['bg' => 'success', 'label' => 'TERSINKRON']
            : ($batch->status === 'rejected'
                ? ['bg' => 'danger', 'label' => 'DITOLAK']
                : ['bg' => 'warning', 'label' => 'PENDING REVIEW']);
    @endphp
    <div class="modal fade" id="generateImportBatchModal{{ $batch->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-xl-down modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-1">Detail Data Sinkronisasi</h5>
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

                    <form method="POST" action="{{ route('sk-yayasan.import-batches.rows.update', $batch) }}" id="generateEditImportBatchForm{{ $batch->id }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="action" value="save" data-sync-action>
                        <div class="sky-modal-table-wrap">
                            <table class="table table-sm align-middle sky-compact-table mb-0">
                                <thead>
                                    <tr>
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
                                            $nipmWarning = $resolveNipmImportWarning($row);
                                        @endphp
                                        <tr>
                                            <input type="hidden" name="rows[{{ $loop->index }}][row_number]" value="{{ $row->row_number }}">
                                            @foreach($importPreviewColumns as $column)
                                                @php
                                                    $field = $importPreviewFieldMap[$column] ?? null;
                                                    $value = $field ? data_get($row, $field, '') : '';
                                                    $value = $value === '-' ? '' : $value;
                                                    $hasFieldError = $field && in_array($field, $rowErrorFields, true);
                                                    $hasNipmWarning = $field === 'source_nip_maarif' && $nipmWarning;
                                                @endphp
                                                <td class="sky-edit-cell {{ $column === 'No' ? 'sky-edit-cell-sm' : '' }} {{ ($hasFieldError || $hasNipmWarning) ? 'sky-cell-error' : '' }}">
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
                                                               class="form-control form-control-sm {{ $hasNipmWarning ? 'is-invalid' : '' }}">
                                                        @if($hasNipmWarning)
                                                            <small class="text-danger d-block mt-1">{{ $nipmWarning }}</small>
                                                        @endif
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="{{ in_array('matched_name', $rowErrorFields, true) ? 'sky-cell-error-readonly' : '' }}">{{ $row->matched_name ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $row->is_valid ? 'success' : 'danger' }}-subtle text-{{ $row->is_valid ? 'success' : 'danger' }}">
                                                    {{ $row->status_label ?? ($row->is_valid ? 'Siap sync' : 'Perlu perbaikan') }}
                                                </span>
                                            </td>
                                            <td class="wrap">
                                                @php
                                                    $rowMessages = $resolveImportValidationMessages($row);
                                                    if ($nipmWarning && !$rowMessages->contains($nipmWarning)) {
                                                        $rowMessages->prepend($nipmWarning);
                                                    }
                                                @endphp
                                                {{ $rowMessages->isNotEmpty() ? $rowMessages->implode(' ') : 'Data siap disinkronkan.' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                            <div class="text-muted small">
                                Data di sini tetap bisa diedit. Setelah diperbarui, lakukan sinkronisasi ulang agar hasilnya dipakai kembali saat generate SK.
                            </div>
                            <button type="submit"
                                    form="generateEditImportBatchForm{{ $batch->id }}"
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
                                      form="generateEditImportBatchForm{{ $batch->id }}"
                                      placeholder="Catatan opsional untuk sinkronisasi ulang batch ini."></textarea>
                        </div>
                        <div class="d-flex flex-wrap justify-content-end gap-2">
                            <button type="submit"
                                    form="generateEditImportBatchForm{{ $batch->id }}"
                                    class="btn btn-primary"
                                    onclick="this.form.querySelector('[data-sync-action]').value='sync'"
                                    @disabled(!$batch->headings_valid || $batch->invalid_rows > 0)>
                                <i class="bx bx-refresh me-1"></i>Sinkronisasi Ulang
                            </button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form[data-sk-pdf-post]').forEach((form) => {
                if (form.dataset.skPdfPostBound === '1') {
                    return;
                }

                form.dataset.skPdfPostBound = '1';

                form.addEventListener('submit', async function (event) {
                    event.preventDefault();

                    const popup = window.open('', '_blank');
                    const submitter = event.submitter || null;
                    const formData = new FormData(form);

                    if (submitter && submitter.name) {
                        formData.append(submitter.name, submitter.value);
                    }

                    if (popup) {
                        popup.document.write('<title>Menyiapkan PDF...</title><p style="font-family:Arial,sans-serif;padding:16px;">Menyiapkan PDF...</p>');
                    }

                    try {
                        const response = await fetch(form.action, {
                            method: form.method || 'POST',
                            body: formData,
                            credentials: 'same-origin',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/pdf, text/html, application/xhtml+xml',
                            },
                        });

                        const contentType = (response.headers.get('content-type') || '').toLowerCase();

                        if (!response.ok || !contentType.includes('application/pdf')) {
                            let message = 'PDF gagal dibuka. Silakan coba lagi.';

                            if (response.status === 404) {
                                message = 'Endpoint generate PDF tidak ditemukan. Kemungkinan request tab baru berubah menjadi request biasa pada browser ini.';
                            } else if (response.status === 419) {
                                message = 'Sesi login sudah habis. Muat ulang halaman lalu coba lagi.';
                            } else if (response.status === 403) {
                                message = 'Akses ke generate PDF ditolak.';
                            }

                            throw new Error(message);
                        }

                        const pdfBlob = await response.blob();
                        const pdfUrl = URL.createObjectURL(pdfBlob);

                        if (popup) {
                            popup.location.replace(pdfUrl);
                        } else {
                            window.open(pdfUrl, '_blank');
                        }

                        window.setTimeout(() => URL.revokeObjectURL(pdfUrl), 60000);
                    } catch (error) {
                        if (popup && !popup.closed) {
                            popup.close();
                        }

                        const message = error instanceof Error
                            ? error.message
                            : 'PDF gagal dibuka. Silakan coba lagi.';

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal membuka PDF',
                                text: message,
                                confirmButtonColor: '#0e8549',
                            });
                            return;
                        }

                        window.alert(message);
                    }
                });
            });
        });
    </script>
@endpush
@endsection
