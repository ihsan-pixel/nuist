@extends('layouts.master')

@section('title')Data Siswa @endsection

@section('css')
<style>
.hero-card {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    color: #fff;
    border: none;
    border-radius: 18px;
    box-shadow: 0 12px 32px rgba(0, 75, 76, 0.18);
}

.stats-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
}

.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.2rem;
}

.table thead th {
    white-space: nowrap;
    vertical-align: middle;
}

.badge-soft-success {
    background: rgba(14, 133, 73, 0.12);
    color: #0e8549;
}

.badge-soft-secondary {
    background: rgba(100, 116, 139, 0.14);
    color: #475569;
}

.modal .form-label {
    font-weight: 600;
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('li_2') Data Sekolah @endslot
    @slot('title') Data Siswa @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terjadi kesalahan.</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card hero-card mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h4 class="text-white mb-2"><i class="bx bx-id-card me-2"></i>Data Siswa</h4>
                        <p class="mb-0 text-white-50">
                            Kelola dan import data siswa per madrasah.
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('data-sekolah.data-siswa.template') }}" class="btn btn-light">
                            <i class="bx bx-download me-1"></i>Template Import
                        </a>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bx bx-upload me-1"></i>Import
                        </button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="bx bx-plus me-1"></i>Tambah Siswa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary"><i class="bx bx-user"></i></div>
                    <div class="ms-3">
                        <p class="text-muted mb-1">Total Siswa</p>
                        <h4 class="mb-0">{{ number_format($stats['total']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success"><i class="bx bx-check-circle"></i></div>
                    <div class="ms-3">
                        <p class="text-muted mb-1">Akun Aktif</p>
                        <h4 class="mb-0">{{ number_format($stats['aktif']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info"><i class="bx bx-buildings"></i></div>
                    <div class="ms-3">
                        <p class="text-muted mb-1">Madrasah</p>
                        <h4 class="mb-0">{{ number_format($stats['madrasah']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-dark"><i class="bx bx-grid-alt"></i></div>
                    <div class="ms-3">
                        <p class="text-muted mb-1">Jumlah Kelas</p>
                        <h4 class="mb-0">{{ number_format($stats['kelas']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('data-sekolah.data-siswa.index') }}">
            <div class="row g-3 align-items-end">
                @if($userRole !== 'admin')
                <div class="col-md-3">
                    <label for="madrasah_id" class="form-label">Madrasah</label>
                    <select name="madrasah_id" id="madrasah_id" class="form-select">
                        <option value="">Semua Madrasah</option>
                        @foreach($madrasahOptions as $madrasah)
                            <option value="{{ $madrasah->id }}" {{ (string) request('madrasah_id') === (string) $madrasah->id ? 'selected' : '' }}>
                                {{ $madrasah->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-3">
                    <label for="kelas" class="form-label">Kelas</label>
                    <input type="text" name="kelas" id="kelas" class="form-control" value="{{ request('kelas') }}" placeholder="Contoh: X IPA 1">
                </div>
                <div class="col-md-4">
                    <label for="q" class="form-label">Pencarian</label>
                    <input type="text" name="q" id="q" class="form-control" value="{{ request('q') }}" placeholder="Cari NIS, nama, email, wali">
                </div>
                <div class="col-md-2">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success"><i class="bx bx-search me-1"></i>Filter</button>
                        <a href="{{ route('data-sekolah.data-siswa.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-info">
            Password default akun siswa saat tambah/import adalah <strong>NIS</strong>.
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Madrasah</th>
                        <th>Email Siswa</th>
                        <th>Orang Tua/Wali</th>
                        <th>Status</th>
                        <th style="min-width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $index => $siswa)
                        <tr>
                            <td>{{ $siswas->firstItem() + $index }}</td>
                            <td>{{ $siswa->nis }}</td>
                            <td>
                                <div class="fw-semibold">{{ $siswa->nama_lengkap }}</div>
                                <small class="text-muted">{{ $siswa->no_hp }}</small>
                            </td>
                            <td>{{ $siswa->kelas }}</td>
                            <td>{{ $siswa->nama_madrasah }}</td>
                            <td>
                                <div>{{ $siswa->email }}</div>
                                <small class="text-muted">{{ $siswa->email_orang_tua_wali }}</small>
                            </td>
                            <td>
                                <div>{{ $siswa->nama_orang_tua_wali }}</div>
                                <small class="text-muted">{{ $siswa->no_hp_orang_tua_wali }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $siswa->is_active ? 'badge-soft-success' : 'badge-soft-secondary' }}">
                                    {{ $siswa->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-id="{{ $siswa->id }}"
                                        data-madrasah_id="{{ $siswa->madrasah_id }}"
                                        data-nis="{{ $siswa->nis }}"
                                        data-nama_lengkap="{{ $siswa->nama_lengkap }}"
                                        data-nama_orang_tua_wali="{{ $siswa->nama_orang_tua_wali }}"
                                        data-email="{{ $siswa->email }}"
                                        data-email_orang_tua_wali="{{ $siswa->email_orang_tua_wali }}"
                                        data-no_hp="{{ $siswa->no_hp }}"
                                        data-no_hp_orang_tua_wali="{{ $siswa->no_hp_orang_tua_wali }}"
                                        data-kelas="{{ $siswa->kelas }}"
                                        data-alamat="{{ $siswa->alamat }}"
                                        data-is_active="{{ $siswa->is_active ? 1 : 0 }}"
                                    >
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('data-sekolah.data-siswa.destroy', $siswa) }}" onsubmit="return confirm('Hapus data siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $siswas->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="{{ route('data-sekolah.data-siswa.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('data-sekolah.partials.siswa-form', ['formId' => 'create', 'siswa' => null, 'madrasahOptions' => $madrasahOptions, 'selectedMadrasahId' => $selectedMadrasahId, 'userRole' => $userRole])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('data-sekolah.partials.siswa-form', ['formId' => 'edit', 'siswa' => null, 'madrasahOptions' => $madrasahOptions, 'selectedMadrasahId' => $selectedMadrasahId, 'userRole' => $userRole])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('data-sekolah.data-siswa.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-3">
                        Gunakan template resmi agar nama kolom sesuai. Kolom nama madrasah tidak perlu ada di file import. Jika NIS atau email sudah ada, data akan diperbarui.
                    </div>
                    @if($userRole !== 'admin')
                        <div class="mb-3">
                            <label for="import_madrasah_id" class="form-label">Madrasah/Sekolah</label>
                            <select class="form-select" id="import_madrasah_id" name="madrasah_id" required>
                                <option value="">Pilih Madrasah</option>
                                @foreach($madrasahOptions as $madrasah)
                                    <option value="{{ $madrasah->id }}" {{ (string) old('madrasah_id', $selectedMadrasahId) === (string) $madrasah->id ? 'selected' : '' }}>
                                        {{ $madrasah->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="madrasah_id" value="{{ $selectedMadrasahId }}">
                        <div class="mb-3">
                            <label class="form-label">Madrasah/Sekolah</label>
                            <input type="text" class="form-control" value="{{ optional($madrasahOptions->first())->name }}" readonly>
                        </div>
                    @endif
                    <label for="file" class="form-label">File Excel/CSV</label>
                    <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Import Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editModal');
    if (!editModal) {
        return;
    }

    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const form = document.getElementById('editForm');
        const siswaId = button.getAttribute('data-id');

        form.action = `{{ route('data-sekolah.data-siswa.index') }}/${siswaId}`;
        form.querySelector('[name="madrasah_id"]').value = button.getAttribute('data-madrasah_id');
        form.querySelector('[name="nis"]').value = button.getAttribute('data-nis');
        form.querySelector('[name="nama_lengkap"]').value = button.getAttribute('data-nama_lengkap');
        form.querySelector('[name="nama_orang_tua_wali"]').value = button.getAttribute('data-nama_orang_tua_wali');
        form.querySelector('[name="email"]').value = button.getAttribute('data-email');
        form.querySelector('[name="email_orang_tua_wali"]').value = button.getAttribute('data-email_orang_tua_wali');
        form.querySelector('[name="no_hp"]').value = button.getAttribute('data-no_hp');
        form.querySelector('[name="no_hp_orang_tua_wali"]').value = button.getAttribute('data-no_hp_orang_tua_wali');
        form.querySelector('[name="kelas"]').value = button.getAttribute('data-kelas');
        form.querySelector('[name="alamat"]').value = button.getAttribute('data-alamat');
        form.querySelector('[name="is_active"]').checked = button.getAttribute('data-is_active') === '1';
    });
});
</script>
@endsection
