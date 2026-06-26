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

.student-modal .modal-dialog {
    margin: 0.75rem auto;
    height: calc(100vh - 1.5rem);
}

.student-modal .modal-content {
    height: 100%;
    border: 0;
    border-radius: 1rem;
    overflow: hidden;
}

.student-modal form {
    display: flex;
    flex-direction: column;
    min-height: 0;
    height: 100%;
}

.student-modal .modal-body {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}

.student-modal .modal-header,
.student-modal .modal-footer {
    flex: 0 0 auto;
    background: #fff;
}

@media (max-width: 991.98px) {
    .student-modal .modal-dialog {
        margin: 0;
        height: 100vh;
        max-width: 100%;
    }

    .student-modal .modal-content {
        border-radius: 0;
    }
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
                        <p class="mb-0 text-white-50">Kelola data siswa administrasi dengan input manual modal atau import template sekolah.</p>
                    </div>
                    @if($userRole !== 'admin_spp')
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('data-sekolah.data-siswa.template') }}" class="btn btn-light">
                                <i class="bx bx-download me-1"></i>Template
                            </a>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="bx bx-upload me-1"></i>Import
                            </button>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                                <i class="bx bx-plus me-1"></i>Tambah Siswa
                            </button>
                        </div>
                    @endif
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
                        <p class="text-muted mb-1">Siswa Aktif</p>
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
                    <div class="stats-icon bg-dark"><i class="bx bx-bar-chart"></i></div>
                    <div class="ms-3">
                        <p class="text-muted mb-1">Memiliki NISN</p>
                        <h4 class="mb-0">{{ number_format($stats['nisn']) }}</h4>
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
                @if(!in_array($userRole, ['admin', 'admin_spp']))
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
                <div class="col-md-2">
                    <label for="kelas" class="form-label">Kelas</label>
                    <input type="text" name="kelas" id="kelas" class="form-control" value="{{ request('kelas') }}" placeholder="Contoh: X IPA 1">
                </div>
                <div class="col-md-2">
                    <label for="jurusan" class="form-label">Jurusan</label>
                    <input type="text" name="jurusan" id="jurusan" class="form-control" value="{{ request('jurusan') }}" placeholder="Contoh: IPA">
                </div>
                <div class="col-md-3">
                    <label for="q" class="form-label">Pencarian</label>
                    <input type="text" name="q" id="q" class="form-control" value="{{ request('q') }}" placeholder="Cari SCOD, sekolah, NIS, NISN, NIK, nama">
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
            Halaman ini tidak lagi membuat akun login siswa. Kolom email hanya disimpan sebagai data kontak, bukan kredensial autentikasi.
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>SCOD</th>
                        <th>Asal Sekolah/Madrasah</th>
                        <th>NIS / NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas / Jurusan</th>
                        <th>Kontak Siswa</th>
                        <th>Orang Tua</th>
                        <th style="min-width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $index => $siswa)
                        <tr>
                            <td>{{ $siswas->firstItem() + $index }}</td>
                            <td>{{ $siswa->scod ?: ($siswa->madrasah->scod ?? '-') }}</td>
                            <td>{{ $siswa->nama_madrasah ?: ($siswa->madrasah->name ?? '-') }}</td>
                            <td>
                                <div class="fw-semibold">{{ $siswa->nis }}</div>
                                <small class="text-muted">{{ $siswa->nisn ?: '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $siswa->nama_lengkap }}</div>
                                <small class="text-muted">
                                    {{ $siswa->jenis_kelamin ?: '-' }}
                                    @if($siswa->tempat_lahir || $siswa->tanggal_lahir)
                                        | {{ $siswa->tempat_lahir ?: '-' }}{{ $siswa->tanggal_lahir ? ', ' . $siswa->tanggal_lahir->format('d-m-Y') : '' }}
                                    @endif
                                </small>
                            </td>
                            <td>
                                <div>{{ $siswa->kelas }}</div>
                                <small class="text-muted">{{ $siswa->jurusan ?: '-' }}</small>
                            </td>
                            <td>
                                <div>{{ $siswa->no_hp ?: '-' }}</div>
                                <small class="text-muted">{{ $siswa->email ?: '-' }}</small>
                            </td>
                            <td>
                                <div>{{ $siswa->nama_orang_tua_wali ?: ($siswa->nama_ayah ?: ($siswa->nama_ibu ?: '-')) }}</div>
                                <small class="text-muted">{{ $siswa->no_hp_orang_tua_wali ?: '-' }}</small>
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
                                        data-scod="{{ $siswa->scod ?: ($siswa->madrasah->scod ?? '') }}"
                                        data-asal_sekolah_madrasah="{{ $siswa->nama_madrasah ?: ($siswa->madrasah->name ?? '') }}"
                                        data-nis="{{ $siswa->nis }}"
                                        data-nisn="{{ $siswa->nisn }}"
                                        data-nik="{{ $siswa->nik }}"
                                        data-no_kk="{{ $siswa->no_kk }}"
                                        data-nama_lengkap="{{ $siswa->nama_lengkap }}"
                                        data-jenis_kelamin="{{ $siswa->jenis_kelamin }}"
                                        data-tempat_lahir="{{ $siswa->tempat_lahir }}"
                                        data-tanggal_lahir="{{ optional($siswa->tanggal_lahir)->format('Y-m-d') }}"
                                        data-agama="{{ $siswa->agama }}"
                                        data-kelas="{{ $siswa->kelas }}"
                                        data-jurusan="{{ $siswa->jurusan }}"
                                        data-alamat="{{ $siswa->alamat }}"
                                        data-dusun="{{ $siswa->dusun }}"
                                        data-kelurahan="{{ $siswa->kelurahan }}"
                                        data-kecamatan="{{ $siswa->kecamatan }}"
                                        data-no_hp="{{ $siswa->no_hp }}"
                                        data-email="{{ $siswa->email }}"
                                        data-nama_ayah="{{ $siswa->nama_ayah }}"
                                        data-nama_ibu="{{ $siswa->nama_ibu }}"
                                        data-no_hp_orang_tua_wali="{{ $siswa->no_hp_orang_tua_wali }}"
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

@if($userRole !== 'admin_spp')
    <div class="modal fade student-modal" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-lg-down modal-xl modal-dialog-scrollable">
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
@endif

<div class="modal fade student-modal" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down modal-xl modal-dialog-scrollable">
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

@if($userRole !== 'admin_spp')
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
                            Gunakan template resmi. Kolom data siswa boleh kosong. Jika kolom <strong>SCOD</strong> dan <strong>ASAL SEKOLAH/MADRASAH</strong> di file kosong, pilih madrasah pada form import ini sebagai tujuan penyimpanan.
                        </div>
                        @if(!in_array($userRole, ['admin', 'admin_spp']))
                            <div class="mb-3">
                                <label for="import_madrasah_id" class="form-label">Madrasah Tujuan Import</label>
                                <select class="form-select" id="import_madrasah_id" name="madrasah_id">
                                    <option value="">Gunakan SCOD/Asal Sekolah dari file</option>
                                    @foreach($madrasahOptions as $madrasah)
                                        <option value="{{ $madrasah->id }}" {{ (string) old('madrasah_id', $selectedMadrasahId) === (string) $madrasah->id ? 'selected' : '' }}>
                                            {{ $madrasah->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="madrasah_id" value="{{ $selectedMadrasahId }}">
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
@endif
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const syncSchoolMetadata = (form) => {
        if (!form) {
            return;
        }

        const select = form.querySelector('.js-student-school-select');
        const scodField = form.querySelector('.js-student-scod');
        const schoolNameField = form.querySelector('.js-student-school-name');

        if (!select || !scodField || !schoolNameField) {
            return;
        }

        const selectedOption = select.options[select.selectedIndex];
        scodField.value = selectedOption?.getAttribute('data-scod') ?? '';
        schoolNameField.value = selectedOption?.getAttribute('data-name') ?? '';
    };

    document.querySelectorAll('.student-modal form').forEach(function (form) {
        syncSchoolMetadata(form);

        const select = form.querySelector('.js-student-school-select');
        if (select) {
            select.addEventListener('change', function () {
                syncSchoolMetadata(form);
            });
        }
    });

    const editModal = document.getElementById('editModal');
    if (!editModal) {
        return;
    }

    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const form = document.getElementById('editForm');
        const siswaId = button.getAttribute('data-id');

        const setValue = (name, attribute) => {
            const field = form.querySelector(`[name="${name}"]`);
            if (!field) {
                return;
            }

            field.value = button.getAttribute(attribute) ?? '';
        };

        form.action = `{{ route('data-sekolah.data-siswa.index') }}/${siswaId}`;
        setValue('madrasah_id', 'data-madrasah_id');
        syncSchoolMetadata(form);
        setValue('scod', 'data-scod');
        setValue('asal_sekolah_madrasah', 'data-asal_sekolah_madrasah');
        setValue('nis', 'data-nis');
        setValue('nisn', 'data-nisn');
        setValue('nik', 'data-nik');
        setValue('no_kk', 'data-no_kk');
        setValue('nama_lengkap', 'data-nama_lengkap');
        setValue('jenis_kelamin', 'data-jenis_kelamin');
        setValue('tempat_lahir', 'data-tempat_lahir');
        setValue('tanggal_lahir', 'data-tanggal_lahir');
        setValue('agama', 'data-agama');
        setValue('kelas', 'data-kelas');
        setValue('jurusan', 'data-jurusan');
        setValue('alamat', 'data-alamat');
        setValue('dusun', 'data-dusun');
        setValue('kelurahan', 'data-kelurahan');
        setValue('kecamatan', 'data-kecamatan');
        setValue('no_hp', 'data-no_hp');
        setValue('email', 'data-email');
        setValue('nama_ayah', 'data-nama_ayah');
        setValue('nama_ibu', 'data-nama_ibu');
        setValue('no_hp_orang_tua_wali', 'data-no_hp_orang_tua_wali');
    });
});
</script>
@endsection
