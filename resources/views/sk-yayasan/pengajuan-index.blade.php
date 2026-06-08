@extends('layouts.master')

@section('title')Pengajuan Perpanjangan SK @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Pengajuan Perpanjangan SK @endslot
@endcomponent

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent">
        <form method="GET" class="row g-2 align-items-end">
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
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No Pengajuan</th>
                        <th>Sekolah</th>
                        <th>Pegawai/Guru</th>
                        <th>Masa Berlaku</th>
                        <th>Status</th>
                        <th>Dokumen</th>
                        <th>Aksi Review</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                        <tr>
                            <td>{{ $submission->request_number }}</td>
                            <td>{{ $submission->madrasah?->name ?? '-' }}</td>
                            <td>
                                <div class="fw-semibold">{{ $submission->employee?->name ?? '-' }}</div>
                                <small class="text-muted">{{ $submission->employee?->statusKepegawaian?->name ?? ($submission->employee?->ketugasan ?? '-') }}</small>
                            </td>
                            <td>
                                {{ optional($submission->effective_start_date)->format('d/m/Y') }}
                                -
                                {{ optional($submission->effective_end_date)->format('d/m/Y') }}
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

                        <div class="modal fade" id="reviewModal{{ $submission->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('sk-yayasan.pengajuan.update-status', $submission) }}" class="modal-content">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Review Pengajuan {{ $submission->request_number }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
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
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada pengajuan perpanjangan SK dari sekolah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($submissions->hasPages())
        <div class="card-footer bg-white">
            {{ $submissions->links() }}
        </div>
    @endif
</div>
@endsection
