@extends('layouts.master')

@section('title')Generate SK Yayasan @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Generate SK Yayasan @endslot
@endcomponent

@include('sk-yayasan.partials.ui-styles')

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
                <div class="sky-kicker mb-2">Generate SK Yayasan</div>
                <h4 class="mb-1">Susun draft, preview, lalu terbitkan dokumen</h4>
                <p class="mb-0 text-white-50">
                    Pilih template, isi metadata penerbitan, lalu generate SK berdasarkan pengajuan yang sudah disetujui.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $requests->total() }} antrean</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $publishedDocuments->count() }} dokumen terbaru</span>
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
                            <h6 class="mb-0">Pengajuan siap diproses menjadi SK</h6>
                        </div>
                        <span class="sky-chip">{{ $requests->total() }} data</span>
                    </div>

                    @if($requests->count() > 0)
                        <div class="accordion" id="generateAccordion">
                            @foreach($requests as $submission)
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="generateHeading{{ $submission->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#generateCollapse{{ $submission->id }}">
                                            <div class="w-100 me-3 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="fw-semibold">{{ $submission->request_number }} - {{ $submission->employee?->name ?? '-' }}</div>
                                                    <small class="text-muted">{{ $submission->madrasah?->name ?? '-' }}</small>
                                                </div>
                                                <span class="badge bg-{{ $submission->current_status === 'published' ? 'success' : 'warning' }}-subtle text-{{ $submission->current_status === 'published' ? 'success' : 'warning' }}">
                                                    {{ strtoupper($submission->current_status) }}
                                                </span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="generateCollapse{{ $submission->id }}" class="accordion-collapse collapse" data-bs-parent="#generateAccordion">
                                        <div class="accordion-body">
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

                                            <form method="POST" action="{{ route('sk-yayasan.generate.store') }}">
                                                @csrf
                                                <input type="hidden" name="request_id" value="{{ $submission->id }}">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Template</label>
                                                        <select name="template_id" class="form-select" required>
                                                            <option value="">Pilih template</option>
                                                            @foreach($templates as $template)
                                                                <option value="{{ $template->id }}" @selected($submission->template_id == $template->id)>{{ $template->name }}</option>
                                                            @endforeach
                                                        </select>
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
                                                <form method="POST" action="{{ route('sk-yayasan.generate.publish', $submission->document) }}" class="mt-3">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success">Terbitkan SK Yayasan</button>
                                                </form>
                                            @elseif($submission->document && $submission->document->status === 'published')
                                                <div class="alert alert-success mt-3 mb-0">
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
                            <small>Setelah pengajuan disetujui, antreannya akan muncul di halaman ini.</small>
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
                    <h6 class="mb-3">Publikasi terbaru</h6>

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
                            <small>Dokumen yang berhasil dipublish akan tampil di panel ini.</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
