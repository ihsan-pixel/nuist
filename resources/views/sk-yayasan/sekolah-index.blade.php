@extends('layouts.master')

@section('title')Perpanjangan SK Yayasan @endsection

@section('css')
<link href="{{ asset('build/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Perpanjangan SK @endslot
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
                <div class="sky-kicker mb-2">Perpanjangan SK</div>
                <h4 class="mb-1">Ajukan dan pantau SK guru atau pegawai</h4>
                <p class="mb-0 text-white-50">
                    Kirim pengajuan perpanjangan SK ke Yayasan dan pantau status review sampai dokumen resmi terbit.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $submissions->total() }} total pengajuan</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $publishedDocuments->count() }} SK terbaru</span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3 col-6">
            <div class="card sky-stat-card p-3 h-100">
                <div class="text-muted small">Diajukan</div>
                <div class="h4 mb-0">{{ $statusCounts['submitted'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card sky-stat-card p-3 h-100">
                <div class="text-muted small">Direview</div>
                <div class="h4 mb-0">{{ $statusCounts['reviewed'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card sky-stat-card p-3 h-100">
                <div class="text-muted small">Disetujui</div>
                <div class="h4 mb-0">{{ $statusCounts['approved'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card sky-stat-card p-3 h-100">
                <div class="text-muted small">Terbit</div>
                <div class="h4 mb-0">{{ $statusCounts['published'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="sky-panel-label mb-1">Form Pengajuan</div>
                    <h6 class="mb-3">Ajukan Perpanjangan SK</h6>

                    <form action="{{ route('sk-yayasan.sekolah.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Guru/Pegawai</label>
                            <select name="employee_ids[]" class="form-select select2-pegawai" multiple required data-placeholder="Pilih satu atau lebih pegawai">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" @selected(in_array($employee->id, old('employee_ids', [])))>
                                        {{ $employee->name }} - {{ $employee->statusKepegawaian?->name ?? ($employee->ketugasan ?? '-') }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Bisa pilih lebih dari satu pegawai sekaligus.</small>
                            @error('employee_ids')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                            @error('employee_ids.*')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mulai Berlaku</label>
                                <input type="date" name="effective_start_date" class="form-control" value="{{ old('effective_start_date') }}" required>
                                @error('effective_start_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Selesai Berlaku</label>
                                <input type="date" name="effective_end_date" class="form-control" value="{{ old('effective_end_date') }}" required>
                                @error('effective_end_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan Pengajuan</label>
                            <textarea name="submission_notes" rows="4" class="form-control" placeholder="Contoh: perpanjangan SK guru mapel IPS semester ganjil">{{ old('submission_notes') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kirim Pengajuan</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="sky-panel-label mb-1">Dokumen Terbit</div>
                    <h6 class="mb-3">SK Yayasan terbaru</h6>

                    @forelse($publishedDocuments as $document)
                        <div class="sky-document-card mb-3">
                            <div class="fw-semibold">{{ $document->document_number }}</div>
                            <div class="sky-document-meta mb-2">{{ $document->request?->employee?->name ?? '-' }}</div>
                            <div class="small mb-3">
                                Terbit:
                                {{ optional($document->published_at)->format('d/m/Y H:i') ?? optional($document->issued_date)->format('d/m/Y') }}
                            </div>
                            <a href="{{ route('sk-yayasan.documents.download', $document) }}" class="btn btn-sm btn-outline-primary" target="_blank">Lihat PDF</a>
                        </div>
                    @empty
                        <div class="sky-empty-state">
                            <i class="bx bx-file-blank"></i>
                            <strong>Belum ada SK terbit</strong>
                            <small>Dokumen terbit akan muncul setelah Yayasan mempublikasikan SK.</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Riwayat Pengajuan</div>
                            <h6 class="mb-0">Status dan perkembangan pengajuan</h6>
                        </div>
                        <form method="GET" class="d-flex gap-2">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Semua status</option>
                                @foreach(['submitted' => 'Diajukan', 'reviewed' => 'Direview', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'published' => 'Terbit'] as $value => $label)
                                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                        </form>
                    </div>

                    @if($submissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>No Pengajuan</th>
                                        <th>Nama</th>
                                        <th>Masa Berlaku</th>
                                        <th>Status</th>
                                        <th>Catatan Review</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions as $submission)
                                        <tr>
                                            <td class="fw-semibold">{{ $submission->request_number }}</td>
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
                                            <td>{{ $submission->review_notes ?? '-' }}</td>
                                            <td>
                                                @if($submission->document)
                                                    <a href="{{ route('sk-yayasan.documents.download', $submission->document) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        Unduh SK
                                                    </a>
                                                @else
                                                    <span class="text-muted small">Menunggu proses Yayasan</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-task-x"></i>
                            <strong>Belum ada pengajuan perpanjangan SK</strong>
                            <small>Silakan kirim pengajuan pertama Anda melalui form di sebelah kiri.</small>
                        </div>
                    @endif
                </div>

                @if($submissions->hasPages())
                    <div class="card-footer bg-white">
                        {{ $submissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('build/libs/select2/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.select2-pegawai').select2({
            width: '100%',
            placeholder: $('.select2-pegawai').data('placeholder'),
            closeOnSelect: false
        });
    });
</script>
@endsection
