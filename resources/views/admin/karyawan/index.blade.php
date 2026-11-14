@extends('layouts.master')

@section('title', 'Karyawan Yayasan')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Karyawan Yayasan</h4>
            <p class="text-muted mb-0">Kelola akun karyawan untuk presensi mobile.</p>
        </div>
        <a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i>Tambah Karyawan</a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="card">
        <div class="card-body">
            <form class="row g-2 mb-3" method="GET">
                <div class="col-12 col-md-6"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama, email, atau NIP"></div>
                <div class="col-auto"><button class="btn btn-outline-secondary">Cari</button></div>
            </form>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Nama</th><th>Email</th><th>Jabatan</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                    <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td><strong>{{ $employee->name }}</strong><small class="d-block text-muted">{{ $employee->nip ?: 'NIP belum diisi' }}</small></td>
                            <td>{{ $employee->email }}</td><td>{{ $employee->jabatan ?: '-' }}</td>
                            <td><span class="badge {{ $employee->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">{{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td class="text-end">@if($employee->is_active)<form method="POST" action="{{ route('admin.karyawan.destroy', $employee) }}" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" onclick="return confirm('Nonaktifkan akun ini?')">Nonaktifkan</button></form>@endif</td>
                        </tr>
                    @empty <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data karyawan.</td></tr> @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $employees->links() }}</div>
        </div>
    </div>
</div>
@endsection
