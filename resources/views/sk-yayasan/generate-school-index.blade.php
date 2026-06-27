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

    $importBatchModalItems = $requests->getCollection()
        ->pluck('importBatch')
        ->filter()
        ->unique('id')
        ->values();

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

        if ($errors->contains(fn ($error) => str_contains($error, 'Penilaian Kinerja wajib diisi'))) {
            $fields[] = 'source_penilaian_kinerja';
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'Keterangan wajib diisi'))) {
            $fields[] = 'source_keterangan';
        }

        return array_values(array_unique($fields));
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
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $requests->total() }} pengajuan</span>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Antrean Generate</div>
                            <h6 class="mb-0">Generate otomatis satu sekolah atau tetap per guru</h6>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="sky-chip">{{ $requests->total() }} data</span>
                            @if($requests->count() > 0)
                                <form method="POST"
                                      action="{{ route('sk-yayasan.generate.school.pdf', $madrasah) }}"
                                      target="_blank">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-printer me-1"></i>Generate Semua Guru Sekolah Ini
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

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
                                                @else
                                                    <span class="text-muted">Pilih manual saat generate</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $badgeConfig['bg'] }}-subtle text-{{ $badgeConfig['text'] }}">
                                                    {{ $badgeConfig['label'] }}
                                                </span>
                                            </td>
                                            <td>{{ $submission->document?->document_number ?? '-' }}</td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <form method="POST" action="{{ route('sk-yayasan.generate.store') }}" target="_blank">
                                                        @csrf
                                                        <input type="hidden" name="request_id" value="{{ $submission->id }}">
                                                        <input type="hidden" name="preview_pdf" value="1">
                                                        <input type="hidden" name="issued_date" value="{{ $coreData['issued_date'] }}">
                                                        <input type="hidden" name="school_year" value="{{ $coreData['school_year'] }}">
                                                        <input type="hidden" name="document_number_start" value="{{ $coreData['document_number_start'] }}">
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
                                                            Generate 1 Guru
                                                        </button>
                                                    </form>
                                                    @if($submission->document)
                                                        <a href="{{ route('sk-yayasan.documents.download', $submission->document) }}" class="btn btn-sm btn-outline-primary" target="_blank">Preview PDF</a>
                                                    @endif
                                                    @if($submission->importBatch)
                                                        <button type="button"
                                                                class="btn btn-sm btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#generateImportBatchModal{{ $submission->importBatch->id }}">
                                                            Lihat Data Detail
                                                        </button>
                                                    @endif
                                                </div>
                                                @if($submission->document && $submission->document->status !== 'published')
                                                    <form method="POST" action="{{ route('sk-yayasan.generate.publish', $submission->document) }}" class="mt-2 d-inline-block" data-sk-swal-confirm data-sk-swal-title="Terbitkan dokumen?" data-sk-swal-text="Dokumen akan dipublikasikan sebagai SK Yayasan." data-sk-swal-confirm-text="Ya, terbitkan" data-sk-swal-icon="question">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success">Terbitkan</button>
                                                    </form>
                                                @endif
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

                @if($requests->hasPages())
                    <div class="card-footer bg-white">
                        {{ $requests->links() }}
                    </div>
                @endif
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
                                        @endphp
                                        <tr>
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
@endsection
