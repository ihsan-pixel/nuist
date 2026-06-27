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
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Antrean Generate</div>
                            <h6 class="mb-0">Data pengajuan SK Yayasan per sekolah</h6>
                        </div>
                        <span class="sky-chip">{{ $requests->total() }} data</span>
                    </div>

                    @if($requests->count() > 0)
                        <div class="accordion" id="generateAccordion">
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
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="generateHeading{{ $submission->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#generateCollapse{{ $submission->id }}">
                                            <div class="w-100 me-3 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="fw-semibold">{{ $submission->request_number }} - {{ $submission->employee?->name ?? '-' }}</div>
                                                    <small class="text-muted">{{ $submission->submission_type_label }}</small>
                                                </div>
                                                <span class="badge bg-{{ $badgeConfig['bg'] }}-subtle text-{{ $badgeConfig['text'] }}">
                                                    {{ $badgeConfig['label'] }}
                                                </span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="generateCollapse{{ $submission->id }}" class="accordion-collapse collapse" data-bs-parent="#generateAccordion">
                                        <div class="accordion-body">
                                            @if($isSyncedBatch)
                                                <div class="sky-inline-note mb-3">
                                                    Data pengajuan ini berasal dari batch yang sudah berhasil tersinkronisasi pada {{ optional($submission->importBatch?->synced_at)->format('d/m/Y H:i') ?? '-' }}.
                                                </div>
                                            @endif

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
                                                        <div class="sky-panel-label mb-1">Jenis Pengajuan</div>
                                                        <div class="fw-semibold">{{ $submission->submission_type_label }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <form method="POST" action="{{ route('sk-yayasan.generate.store') }}">
                                                @csrf
                                                <input type="hidden" name="request_id" value="{{ $submission->id }}">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Template</label>
                                                        @if($submission->resolved_template)
                                                            <input type="text" class="form-control" value="{{ $submission->resolved_template->name }}" readonly>
                                                            <input type="hidden" name="template_id" value="{{ $submission->resolved_template->id }}">
                                                            <small class="text-muted">Template otomatis mengikuti jenis pengajuan ini.</small>
                                                        @else
                                                            <select name="template_id" class="form-select" required>
                                                                <option value="">Pilih template</option>
                                                                @foreach($templates as $template)
                                                                    <option value="{{ $template->id }}" @selected($submission->template_id == $template->id)>{{ $template->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <small class="text-muted">Template belum bisa dipetakan otomatis, silakan pilih manual.</small>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Tanggal Terbit</label>
                                                        <input type="date" name="issued_date" class="form-control" value="{{ optional($submission->document?->issued_date)->format('Y-m-d') ?? now()->format('Y-m-d') }}" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nomor SK</label>
                                                        <input type="text" name="document_number" class="form-control" value="{{ $submission->document?->document_number }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Penandatangan</label>
                                                        <input type="text" name="signer_name" class="form-control" value="{{ $submission->document?->signer_name ?? 'Ketua Yayasan' }}" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Jabatan Penandatangan</label>
                                                        <input type="text" name="signer_position" class="form-control" value="{{ $submission->document?->signer_position ?? 'Ketua Yayasan' }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Catatan Penerbitan</label>
                                                        <input type="text" name="publication_notes" class="form-control" value="{{ $submission->document?->publication_notes }}">
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <button type="submit" class="btn btn-primary">Generate Draft</button>
                                                    @if($submission->document)
                                                        <a href="{{ route('sk-yayasan.documents.download', $submission->document) }}" class="btn btn-outline-primary" target="_blank">Preview PDF</a>
                                                    @endif
                                                </div>
                                            </form>

                                            @if($submission->document && $submission->document->status !== 'published')
                                                <form method="POST" action="{{ route('sk-yayasan.generate.publish', $submission->document) }}" class="mt-3" data-sk-swal-confirm data-sk-swal-title="Terbitkan dokumen?" data-sk-swal-text="Dokumen akan dipublikasikan sebagai SK Yayasan." data-sk-swal-confirm-text="Ya, terbitkan" data-sk-swal-icon="question">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success">Terbitkan SK Yayasan</button>
                                                </form>
                                            @elseif($submission->document && $submission->document->status === 'published')
                                                <div class="sky-inline-note sky-inline-note-success mt-3 mb-0">
                                                    Dokumen ini sudah diterbitkan pada {{ optional($submission->document->published_at)->format('d/m/Y H:i') }}.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="sky-panel-label mb-1">Dokumen Terbit</div>
                    <h6 class="mb-3">Publikasi terbaru sekolah ini</h6>

                    @forelse($publishedDocuments as $document)
                        <div class="sky-document-card mb-3">
                            <div class="fw-semibold">{{ $document->document_number }}</div>
                            <div class="sky-document-meta">{{ $document->request?->employee?->name ?? '-' }} - {{ $document->request?->madrasah?->name ?? '-' }}</div>
                            <div class="small mb-3 mt-2">Terbit {{ optional($document->published_at)->format('d/m/Y H:i') }}</div>
                            <a href="{{ route('sk-yayasan.documents.download', $document) }}" class="btn btn-sm btn-outline-primary" target="_blank">Lihat PDF</a>
                        </div>
                    @empty
                        <div class="sky-empty-state">
                            <i class="bx bx-printer"></i>
                            <strong>Belum ada dokumen terbit</strong>
                            <small>Dokumen yang berhasil dipublish untuk sekolah ini akan tampil di panel ini.</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
