@extends('layouts.master')

@section('title')Perpanjangan SK Yayasan @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Perpanjangan SK @endslot
@endcomponent

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row">
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Ajukan Perpanjangan SK</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sk-yayasan.sekolah.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Guru/Pegawai</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">Pilih pegawai</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>
                                    {{ $employee->name }} - {{ $employee->statusKepegawaian?->name ?? ($employee->ketugasan ?? '-') }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
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

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Ringkasan Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Diajukan</span>
                    <strong>{{ $statusCounts['submitted'] ?? 0 }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Direview</span>
                    <strong>{{ $statusCounts['reviewed'] ?? 0 }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Disetujui</span>
                    <strong>{{ $statusCounts['approved'] ?? 0 }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Terbit</span>
                    <strong>{{ $statusCounts['published'] ?? 0 }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Riwayat Pengajuan</h5>
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
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
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
                            @forelse($submissions as $submission)
                                <tr>
                                    <td>{{ $submission->request_number }}</td>
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
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada pengajuan perpanjangan SK.</td>
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

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">SK Yayasan Terbit Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @forelse($publishedDocuments as $document)
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="fw-semibold">{{ $document->document_number }}</div>
                                <div class="text-muted small mb-2">{{ $document->request?->employee?->name ?? '-' }}</div>
                                <div class="small mb-3">
                                    Terbit:
                                    {{ optional($document->published_at)->format('d/m/Y H:i') ?? optional($document->issued_date)->format('d/m/Y') }}
                                </div>
                                <a href="{{ route('sk-yayasan.documents.download', $document) }}" class="btn btn-sm btn-primary" target="_blank">Lihat PDF</a>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-light border mb-0">Belum ada SK Yayasan terbit untuk sekolah ini.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
