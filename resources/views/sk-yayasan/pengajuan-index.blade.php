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
</style>

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

    $importBatchModalItems = $pendingImportBatches->getCollection()
        ->merge($rejectedImportBatches->getCollection())
        ->unique('id');
@endphp

<div class="sky-page">
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

    <div class="row g-3">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Review Import Data</div>
                            <h6 class="mb-0">Batch dengan status pending review</h6>
                        </div>
                        <span class="sky-chip">{{ $pendingImportBatches->total() }} pending review</span>
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
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingImportBatches as $batch)
                                        <tr>
                                            <td><div class="fw-semibold">{{ $batch->original_filename }}</div></td>
                                            <td>{{ $batch->madrasah?->name ?? '-' }}</td>
                                            <td>
                                                <div>{{ $batch->uploader?->name ?? '-' }}</div>
                                                <small class="text-muted">{{ optional($batch->uploaded_at)->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $batch->valid_rows }} valid / {{ $batch->invalid_rows }} salah</div>
                                                <small class="text-muted">{{ $batch->headings_valid ? 'Kolom sesuai template' : 'Kolom belum sesuai template' }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#importBatchModal{{ $batch->id }}">
                                                        Lihat Review
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
                    <div class="card-footer bg-white">
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
                            <div class="sky-panel-label mb-1">Batch Ditolak</div>
                            <h6 class="mb-0">Riwayat batch yang dikembalikan ke sekolah</h6>
                        </div>
                        <span class="sky-chip">{{ $rejectedImportBatches->total() }} ditolak</span>
                    </div>

                    @if($rejectedImportBatches->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>File Import</th>
                                        <th>Sekolah</th>
                                        <th>Direview</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rejectedImportBatches as $batch)
                                        <tr>
                                            <td><div class="fw-semibold">{{ $batch->original_filename }}</div></td>
                                            <td>{{ $batch->madrasah?->name ?? '-' }}</td>
                                            <td>
                                                <div>{{ $batch->reviewer?->name ?? '-' }}</div>
                                                <small class="text-muted">{{ optional($batch->reviewed_at)->format('d/m/Y H:i') ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <div class="small">{{ \Illuminate\Support\Str::limit($batch->review_notes ?: 'Tidak ada catatan.', 90) }}</div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#importBatchModal{{ $batch->id }}">
                                                        Lihat Review
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
                            <i class="bx bx-check-shield"></i>
                            <strong>Tidak ada batch ditolak</strong>
                            <small>Batch yang pernah ditolak oleh Yayasan akan tampil terpisah di sini.</small>
                        </div>
                    @endif
                </div>

                @if($rejectedImportBatches->hasPages())
                    <div class="card-footer bg-white">
                        <div class="sky-pagination-wrap">
                            {{ $rejectedImportBatches->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
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
                        @else
                            <div class="d-flex flex-wrap justify-content-between gap-2 w-100">
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
