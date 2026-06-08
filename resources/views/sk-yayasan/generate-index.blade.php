@extends('layouts.master')

@section('title')Generate SK Yayasan @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Generate SK Yayasan @endslot
@endcomponent

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Pengajuan Siap Digenerate</h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="generateAccordion">
                    @forelse($requests as $submission)
                        <div class="accordion-item">
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
                                        <div class="d-flex gap-2">
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
                    @empty
                        <div class="alert alert-light border mb-0">Belum ada pengajuan yang siap digenerate.</div>
                    @endforelse
                </div>
            </div>
            @if($requests->hasPages())
                <div class="card-footer bg-white">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Dokumen Terbit Terbaru</h5>
            </div>
            <div class="card-body">
                @forelse($publishedDocuments as $document)
                    <div class="border rounded p-3 mb-3">
                        <div class="fw-semibold">{{ $document->document_number }}</div>
                        <div class="small text-muted">{{ $document->request?->employee?->name ?? '-' }} - {{ $document->request?->madrasah?->name ?? '-' }}</div>
                        <div class="small mb-2">Terbit {{ optional($document->published_at)->format('d/m/Y H:i') }}</div>
                        <a href="{{ route('sk-yayasan.documents.download', $document) }}" class="btn btn-sm btn-outline-primary" target="_blank">Lihat PDF</a>
                    </div>
                @empty
                    <div class="alert alert-light border mb-0">Belum ada dokumen yang diterbitkan.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
