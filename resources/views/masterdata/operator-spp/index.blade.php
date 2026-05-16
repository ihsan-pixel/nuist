@extends('layouts.master')

@section('title')
Data Operator SPP
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Data Operator SPP @endslot
@endcomponent

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Pending</div>
                <div class="display-6 fw-bold">{{ $stats['pending'] }}</div>
                <div class="text-muted small">Menunggu approval Super Admin</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Approved</div>
                <div class="display-6 fw-bold">{{ $stats['approved'] }}</div>
                <div class="text-muted small">Permohonan berhasil dibuatkan akun</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Rejected</div>
                <div class="display-6 fw-bold">{{ $stats['rejected'] }}</div>
                <div class="text-muted small">Permohonan yang ditolak</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Akun Aktif</div>
                <div class="display-6 fw-bold">{{ $stats['active_accounts'] }}</div>
                <div class="text-muted small">Operator SPP yang bisa login</div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <div>
            <h5 class="mb-1">Permohonan Operator SPP</h5>
            <div class="text-muted small">Approval dan penolakan pendaftaran akun Admin SPP dari sekolah.</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('operator-spp.index') }}" class="btn btn-sm {{ $status === '' ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
            <a href="{{ route('operator-spp.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">Pending</a>
            <a href="{{ route('operator-spp.index', ['status' => 'approved']) }}" class="btn btn-sm {{ $status === 'approved' ? 'btn-primary' : 'btn-outline-primary' }}">Approved</a>
            <a href="{{ route('operator-spp.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status === 'rejected' ? 'btn-primary' : 'btn-outline-primary' }}">Rejected</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Pengaju</th>
                        <th>Sekolah</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $registration->name }}</div>
                                <div class="text-muted small">{{ $registration->jabatan }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $registration->madrasah->name ?? '-' }}</div>
                                <div class="text-muted small">{{ $registration->madrasah->kabupaten ?? 'Kabupaten belum diisi' }}</div>
                            </td>
                            <td>
                                <div>{{ $registration->email }}</div>
                                <div class="text-muted small">{{ $registration->no_hp ?: 'No. HP belum diisi' }}</div>
                            </td>
                            <td>
                                @if($registration->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning">Pending</span>
                                @elseif($registration->status === 'approved')
                                    <span class="badge bg-success-subtle text-success">Approved</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                @endif
                                @if($registration->review_notes)
                                    <div class="text-muted small mt-2">{{ $registration->review_notes }}</div>
                                @endif
                            </td>
                            <td>
                                <div>{{ optional($registration->submitted_at)->format('d M Y') }}</div>
                                <div class="text-muted small">{{ optional($registration->submitted_at)->format('H:i') }}</div>
                            </td>
                            <td class="text-nowrap">
                                @if($registration->status === 'pending')
                                    <form action="{{ route('operator-spp.approve', $registration) }}" method="POST" class="d-inline" onsubmit="return confirm('Setujui pendaftaran dan buat akun operator SPP?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $registration->id }}">Reject</button>
                                @else
                                    <span class="text-muted small">Sudah diproses</span>
                                @endif
                            </td>
                        </tr>

                        <div class="modal fade" id="rejectModal{{ $registration->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('operator-spp.reject', $registration) }}" method="POST" class="modal-content">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tolak Pendaftaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-3">Pendaftaran untuk <strong>{{ $registration->name }}</strong> akan ditolak.</p>
                                        <label class="form-label" for="review_notes_{{ $registration->id }}">Catatan Penolakan</label>
                                        <textarea id="review_notes_{{ $registration->id }}" name="review_notes" class="form-control" rows="4" placeholder="Opsional"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Tolak Pendaftaran</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data pendaftaran operator SPP.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($registrations->hasPages())
            <div class="mt-3">
                {{ $registrations->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h5 class="mb-1">Akun Admin SPP</h5>
        <div class="text-muted small">Kelola akun operator SPP yang sudah aktif pada masing-masing sekolah.</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Sekolah</th>
                        <th>Email</th>
                        <th>Status Akun</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvedOperators as $operator)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $operator->name }}</div>
                                <div class="text-muted small">{{ $operator->jabatan ?: 'Operator SPP' }}</div>
                            </td>
                            <td>{{ $operator->madrasah->name ?? '-' }}</td>
                            <td>
                                <div>{{ $operator->email }}</div>
                                <div class="text-muted small">{{ $operator->no_hp ?: 'No. HP belum diisi' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $operator->is_active ? 'success' : 'secondary' }}">
                                    {{ $operator->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>{{ $operator->created_at?->format('d M Y H:i') }}</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editOperatorModal{{ $operator->id }}">Edit</button>
                                <form action="{{ route('operator-spp.accounts.status', $operator) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_active" value="{{ $operator->is_active ? 0 : 1 }}">
                                    <button type="submit" class="btn btn-sm btn-outline-{{ $operator->is_active ? 'secondary' : 'success' }}">
                                        {{ $operator->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="editOperatorModal{{ $operator->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('operator-spp.accounts.update', $operator) }}" method="POST" class="modal-content">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Akun Operator SPP</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Sekolah</label>
                                            <input type="text" class="form-control" value="{{ $operator->madrasah->name ?? '-' }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="name" class="form-control" value="{{ $operator->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jabatan</label>
                                            <input type="text" name="jabatan" class="form-control" value="{{ $operator->jabatan ?: 'Operator SPP' }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ $operator->email }}" required>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label">No. HP</label>
                                            <input type="text" name="no_hp" class="form-control" value="{{ $operator->no_hp }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada akun Admin SPP yang aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
