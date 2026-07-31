@extends('layouts.master')

@section('title')Nomor SK Yayasan @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Nomor SK Yayasan @endslot
@endcomponent

@include('sk-yayasan.partials.ui-styles')
@include('sk-yayasan.partials.sweet-alert')

<div class="sky-page">
    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="sky-kicker mb-2">SK Yayasan</div>
                <h4 class="mb-1">Nomor SK Yayasan terpakai</h4>
                <p class="mb-0 text-white-50">
                    Halaman ini hanya untuk super admin. Semua nomor SK yang sudah terpakai ditampilkan dari nomor terkecil ke terbesar dan bisa diedit langsung dari sini.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ number_format($numberStats['total_documents'] ?? 0) }} nomor terpakai</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ number_format($numberStats['locked_documents'] ?? 0) }} terkunci</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $numberStats['range_label'] ?? '-' }}</span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-3 col-md-6">
            <div class="card sky-stat-card h-100">
                <div class="card-body">
                    <div class="sky-panel-label mb-2">Total Nomor</div>
                    <h4 class="mb-1">{{ number_format($numberStats['total_documents'] ?? 0) }}</h4>
                    <div class="text-muted small">Semua nomor SK yang sudah tersimpan.</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card sky-stat-card h-100">
                <div class="card-body">
                    <div class="sky-panel-label mb-2">Rentang</div>
                    <h4 class="mb-1">{{ $numberStats['range_label'] ?? '-' }}</h4>
                    <div class="text-muted small">Urutan global nomor yang saat ini terpakai.</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card sky-stat-card h-100">
                <div class="card-body">
                    <div class="sky-panel-label mb-2">Nomor Terkunci</div>
                    <h4 class="mb-1">{{ number_format($numberStats['locked_documents'] ?? 0) }}</h4>
                    <div class="text-muted small">Nomor yang tetap bisa diedit oleh super admin.</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card sky-stat-card h-100">
                <div class="card-body">
                    <div class="sky-panel-label mb-2">Duplikat</div>
                    <h4 class="mb-1">{{ number_format($numberStats['duplicate_number_count'] ?? 0) }}</h4>
                    <div class="text-muted small">{{ number_format($numberStats['duplicate_row_count'] ?? 0) }} baris terdeteksi memakai nomor yang sama.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('sk-yayasan.numbers.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-6">
                        <label class="form-label">Cari nomor / guru / sekolah</label>
                        <input type="text" name="q" class="form-control" value="{{ $filters['q'] ?? '' }}" placeholder="Contoh: 7095, Agung, SMAPDA">
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label">Filter sekolah</label>
                        <select name="madrasah_id" class="form-select">
                            <option value="">Semua sekolah</option>
                            @foreach($schoolOptions as $schoolOption)
                                <option value="{{ $schoolOption->id }}" @selected((int) ($filters['madrasah_id'] ?? 0) === (int) $schoolOption->id)>
                                    {{ $schoolOption->scod ? $schoolOption->scod . ' - ' : '' }}{{ $schoolOption->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Terapkan</button>
                            <a href="{{ route('sk-yayasan.numbers.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Daftar Nomor</div>
                    <h6 class="mb-0">Urut berdasarkan angka nomor SK terkecil</h6>
                </div>
                <span class="sky-chip">{{ $documents->total() }} data</span>
            </div>

            @if($documents->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 72px;">No</th>
                                <th style="width: 110px;">Urutan</th>
                                <th>Nomor SK</th>
                                <th>Sekolah</th>
                                <th>Guru/Pegawai</th>
                                <th>Status</th>
                                <th>Terkunci</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                                @php($requestData = $document->request)
                                @php($school = $requestData?->madrasah)
                                @php($employee = $requestData?->employee)
                                <tr>
                                    <td>{{ $documents->firstItem() + $loop->index }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ $document->sequence_number ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $document->document_number }}</div>
                                        @if(($document->duplicate_total ?? 0) > 1)
                                            <span class="badge bg-danger-subtle text-danger mt-1">Duplikat {{ $document->duplicate_total }} data</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $school?->name ?? '-' }}</div>
                                        <small class="text-muted">{{ $school?->scod ? 'SCOD ' . $school->scod : 'SCOD belum diisi' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $employee?->name ?? '-' }}</div>
                                        <small class="text-muted">{{ $requestData?->request_number ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $document->status === 'published' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                                            {{ $document->status === 'published' ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($document->number_locked_at)
                                            <div class="fw-semibold text-success">Terkunci</div>
                                            <small class="text-muted">{{ $document->number_locked_at->format('d/m/Y H:i') }}</small>
                                        @else
                                            <span class="text-muted">Belum</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editDocumentNumberModal{{ $document->id }}">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $documents->links() }}
                </div>
            @else
                <div class="sky-empty-state py-5">
                    <i class="bx bx-hash"></i>
                    <strong>Belum ada nomor SK yang cocok dengan filter</strong>
                    <small>Ubah pencarian atau filter sekolah untuk melihat data nomor SK yang lain.</small>
                </div>
            @endif
        </div>
    </div>

    @foreach($documents as $document)
        @php($requestData = $document->request)
        @php($school = $requestData?->madrasah)
        @php($employee = $requestData?->employee)
        <div class="modal fade" id="editDocumentNumberModal{{ $document->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title mb-1">Edit Nomor SK Yayasan</h5>
                            <small class="text-muted">{{ $employee?->name ?? '-' }} - {{ $school?->name ?? '-' }}</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST"
                          action="{{ route('sk-yayasan.numbers.update', $document) }}"
                          data-sk-swal-confirm
                          data-sk-swal-title="Perbarui nomor SK ini?"
                          data-sk-swal-text="Nomor lama akan diganti dan status validasi sekolah akan dihitung ulang dari data tersimpan."
                          data-sk-swal-confirm-text="Ya, simpan">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nomor SK</label>
                                <input type="text"
                                       name="document_number"
                                       class="form-control"
                                       value="{{ old('document_number', $document->document_number) }}"
                                       required>
                                <small class="text-muted">Harus diawali angka lalu `/`, contoh: `7095/SK.02/LPM.DIY/VII/2026`.</small>
                            </div>
                            <div class="sky-summary-stack">
                                <div class="sky-summary-row">
                                    <span class="text-muted">Urutan sekarang</span>
                                    <span class="fw-semibold">{{ $document->sequence_number ?? '-' }}</span>
                                </div>
                                <div class="sky-summary-row">
                                    <span class="text-muted">Status lock</span>
                                    <span class="fw-semibold">{{ $document->number_locked_at ? 'Terkunci' : 'Belum terkunci' }}</span>
                                </div>
                                <div class="sky-summary-row">
                                    <span class="text-muted">Status duplikat</span>
                                    <span class="fw-semibold {{ ($document->duplicate_total ?? 0) > 1 ? 'text-danger' : 'text-success' }}">
                                        {{ ($document->duplicate_total ?? 0) > 1 ? 'Duplikat ' . $document->duplicate_total . ' data' : 'Tidak ada' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
