@extends('layouts.master')

@section('title')Data Siswa @endsection

@section('css')
<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<style>
.data-siswa-page {
    --ds-ink: #102d28;
    --ds-muted: #6b7b75;
    --ds-line: #e5eee9;
    --ds-soft: #f5faf7;
    --ds-green: #0e8549;
    --ds-teal: #004b4c;
    --ds-gold: #efaa0c;
}

.data-siswa-page .card {
    border: 0;
    border-radius: 18px;
    box-shadow: 0 12px 34px rgba(16, 45, 40, 0.08);
}

.data-siswa-page .alert {
    border: 0;
    border-radius: 14px;
}

.data-siswa-page .btn {
    border-radius: 10px;
    font-weight: 700;
}

.data-siswa-page .btn-success,
.data-siswa-page .btn-primary {
    background: linear-gradient(135deg, var(--ds-teal), var(--ds-green));
    border: 0;
}

.data-siswa-page .btn-secondary {
    background: #eef4f1;
    border-color: #eef4f1;
    color: var(--ds-ink);
}

.data-siswa-page .btn-light {
    border-color: rgba(255, 255, 255, 0.28);
}

.data-siswa-page .form-control,
.data-siswa-page .form-select {
    border-color: #dce7e2;
    border-radius: 12px;
    min-height: 44px;
}

.data-siswa-page .form-control:focus,
.data-siswa-page .form-select:focus {
    border-color: rgba(14, 133, 73, .45);
    box-shadow: 0 0 0 0.2rem rgba(14, 133, 73, 0.12);
}

.data-siswa-hero {
    background:
        radial-gradient(circle at top right, rgba(239, 170, 12, 0.28), transparent 28%),
        linear-gradient(135deg, var(--ds-teal), var(--ds-green));
    border-radius: 22px;
    box-shadow: 0 18px 42px rgba(0, 75, 76, 0.20);
    color: #fff;
    overflow: hidden;
    padding: 24px;
    position: relative;
}

.data-siswa-hero::after {
    background: rgba(255, 255, 255, 0.12);
    border-radius: 999px;
    content: "";
    height: 180px;
    position: absolute;
    right: -70px;
    top: -70px;
    width: 180px;
}

.data-siswa-hero > * {
    position: relative;
    z-index: 1;
}

.data-siswa-kicker {
    color: rgba(255, 255, 255, 0.75);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.data-siswa-subtitle {
    color: rgba(255, 255, 255, 0.78);
    max-width: 640px;
}

.data-siswa-stat {
    border: 1px solid var(--ds-line);
    transition: transform .2s ease, box-shadow .2s ease;
}

.data-siswa-stat:hover {
    transform: translateY(-3px);
}

.stats-icon {
    align-items: center;
    border-radius: 16px;
    display: inline-flex;
    font-size: 1.15rem;
    height: 52px;
    justify-content: center;
    width: 52px;
}

.stats-icon-soft-primary {
    background: rgba(13, 110, 253, 0.12);
    color: #0d6efd;
}

.stats-icon-soft-success {
    background: rgba(25, 135, 84, 0.12);
    color: #198754;
}

.stats-icon-soft-info {
    background: rgba(13, 202, 240, 0.14);
    color: #0aa2c0;
}

.stats-icon-soft-warning {
    background: rgba(255, 193, 7, 0.16);
    color: #c58a00;
}

.stats-icon-soft-dark {
    background: rgba(33, 37, 41, 0.10);
    color: #212529;
}

.data-siswa-panel-head {
    align-items: center;
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
}

.data-siswa-panel-kicker {
    color: var(--ds-muted);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.data-siswa-chip {
    background: rgba(14, 133, 73, .10);
    border: 1px solid rgba(14, 133, 73, .16);
    border-radius: 999px;
    color: var(--ds-green);
    display: inline-flex;
    font-size: 12px;
    font-weight: 700;
    padding: 5px 10px;
}

.data-siswa-filter {
    background: linear-gradient(180deg, #ffffff 0%, #fbfdfc 100%);
}

.data-siswa-filter-grid {
    background: var(--ds-soft);
    border: 1px solid var(--ds-line);
    border-radius: 16px;
    padding: 1rem;
}

.completion-bar {
    background-color: #e9ecef;
    border-radius: 999px;
    height: 8px;
    overflow: hidden;
}

.completion-bar-value {
    border-radius: 999px;
    height: 100%;
    transition: width .25s ease;
}

.bulk-edit-modal .modal-dialog {
    margin: 1rem auto;
    max-width: calc(100vw - 2rem);
}

.bulk-edit-modal .modal-body {
    overflow: auto;
    padding: 1rem;
}

.bulk-edit-table {
    min-width: 3400px;
}

.bulk-edit-table th {
    background: #f8f9fa;
    font-size: .75rem;
    position: sticky;
    text-transform: uppercase;
    top: 0;
    white-space: nowrap;
    z-index: 2;
}

.bulk-edit-table td {
    min-width: 140px;
    padding: .35rem;
    vertical-align: top;
}

.bulk-grid-input {
    font-size: .8125rem;
    min-width: 120px;
}

.bulk-grid-textarea {
    min-height: 68px;
    min-width: 220px;
}

#editModal .field-empty-highlight {
    background-color: #fff5f5;
    border-color: #dc3545;
}

#editModal .field-empty-highlight:focus {
    background-color: #fff5f5;
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
}

#bulkEditModal .field-empty-highlight {
    background-color: #fff5f5;
    border-color: #dc3545;
}

#bulkEditModal .field-empty-highlight:focus {
    background-color: #fff5f5;
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
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

<div class="data-siswa-page">
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

        <div class="data-siswa-hero mb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <div class="data-siswa-kicker mb-2">Data Sekolah</div>
                    <h4 class="mb-1">Data Siswa</h4>
                    <p class="mb-0 data-siswa-subtitle">Kelola data siswa lebih rapi melalui input manual, import template, dan pemutakhiran massal tanpa mengubah struktur tabel utama.</p>
                </div>
                @if($userRole !== 'admin_spp')
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('data-sekolah.data-siswa.template') }}" class="btn btn-light">
                            <i class="bx bx-download me-1"></i>Template
                        </a>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bx bx-upload me-1"></i>Import
                        </button>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="bx bx-plus me-1"></i>Tambah Siswa
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6 col-xl">
        <div class="card data-siswa-stat h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="stats-icon stats-icon-soft-primary"><i class="bx bx-user"></i></div>
                    <div class="ms-3">
                        <p class="text-muted mb-1">Total Siswa</p>
                        <h4 class="mb-0">{{ number_format($stats['total']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl">
        <div class="card data-siswa-stat h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="stats-icon stats-icon-soft-success"><i class="bx bx-check-circle"></i></div>
                    <div class="ms-3">
                        <p class="text-muted mb-1">Siswa Aktif</p>
                        <h4 class="mb-0">{{ number_format($stats['aktif']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl">
        <div class="card data-siswa-stat h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="stats-icon stats-icon-soft-info"><i class="bx bx-task"></i></div>
                    <div class="ms-3">
                        <p class="text-muted mb-1">Data Lengkap</p>
                        <h4 class="mb-0">{{ $stats['rata_rata_kelengkapan'] }}%</h4>
                        <small class="text-muted">Rata-rata dari {{ number_format($stats['total']) }} data siswa</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl">
        <div class="card data-siswa-stat h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="stats-icon stats-icon-soft-warning"><i class="bx bx-buildings"></i></div>
                    <div class="ms-3">
                        <p class="text-muted mb-1">Sekolah Upload</p>
                        <h4 class="mb-0">{{ number_format($stats['sekolah_upload']) }}</h4>
                        <small class="text-muted">Sekolah sudah memiliki data siswa</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl">
        <div class="card data-siswa-stat h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="stats-icon stats-icon-soft-dark"><i class="bx bx-bar-chart"></i></div>
                    <div class="ms-3">
                        <p class="text-muted mb-1">Memiliki NISN</p>
                        <h4 class="mb-0">{{ number_format($stats['nisn']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card data-siswa-filter mb-4">
    <div class="card-body">
        <div class="data-siswa-panel-head">
            <div>
                <div class="data-siswa-panel-kicker mb-1">Filter Data</div>
                <h6 class="mb-0">Pencarian dan penyaringan siswa</h6>
            </div>
            <span class="data-siswa-chip">Filter aktif sesuai kebutuhan</span>
        </div>
        <form method="GET" action="{{ route('data-sekolah.data-siswa.index') }}">
            <div class="row g-3 align-items-end data-siswa-filter-grid">
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
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div>
                <div class="text-uppercase text-muted small fw-semibold mb-1">Master Data</div>
                <h6 class="mb-0">Daftar siswa tersimpan</h6>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge bg-light text-dark border">{{ $siswas->count() }} data</span>
                @if($userRole !== 'admin_spp')
                    <button
                        type="button"
                        class="btn btn-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#bulkEditModal"
                        {{ $siswas->isEmpty() ? 'disabled' : '' }}
                    >
                        <i class="bx bx-table me-1"></i>Edit Data Keseluruhan
                    </button>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>SCOD</th>
                        <th>Asal Sekolah/Madrasah</th>
                        <th>NIS / NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas / Jurusan</th>
                        <th>Kontak Siswa</th>
                        <th>Kelengkapan</th>
                        <th style="min-width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $index => $siswa)
                        @php
                            $completionPercentage = (int) $siswa->completion_percentage;
                            $completionBarClass = $completionPercentage >= 80
                                ? 'bg-success'
                                : ($completionPercentage >= 50 ? 'bg-warning' : 'bg-danger');
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
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
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold">{{ $completionPercentage }}%</span>
                                    <small class="text-muted">{{ $siswa->completion_filled }}/{{ $siswa->completion_total }} kolom</small>
                                </div>
                                <div class="completion-bar">
                                    <div class="completion-bar-value {{ $completionBarClass }}" style="width: {{ $completionPercentage }}%;"></div>
                                </div>
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
                    @endforeach
                </tbody>
            </table>
        </div>
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

@if($userRole !== 'admin_spp')
    <div class="modal fade bulk-edit-modal" id="bulkEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-xl-down">
            <div class="modal-content">
                <form method="POST" action="{{ route('data-sekolah.data-siswa.bulk-update') }}" id="bulkEditForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="bulk_edit_submit" value="1">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data Siswa Keseluruhan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle bulk-edit-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        @if(!in_array($userRole, ['admin', 'admin_spp']))
                                            <th>Madrasah / Sekolah</th>
                                        @endif
                                        <th>NIS</th>
                                        <th>NISN</th>
                                        <th>NIK</th>
                                        <th>NO_KK</th>
                                        <th>Nama Peserta Didik</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tempat Lahir</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Agama</th>
                                        <th>Kelas</th>
                                        <th>Jurusan</th>
                                        <th>Alamat</th>
                                        <th>Dusun</th>
                                        <th>Kelurahan</th>
                                        <th>Kecamatan</th>
                                        <th>No HP Siswa</th>
                                        <th>Email Siswa</th>
                                        <th>Nama Ayah</th>
                                        <th>Nama Ibu</th>
                                        <th>No HP Orang Tua / Wali</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($siswas as $index => $siswa)
                                        <tr class="js-bulk-edit-row">
                                            <td class="text-center fw-semibold">{{ $index + 1 }}</td>
                                            @if(!in_array($userRole, ['admin', 'admin_spp']))
                                                <td>
                                                    <select
                                                        name="rows[{{ $siswa->id }}][madrasah_id]"
                                                        class="form-select form-select-sm bulk-grid-input js-bulk-school-select"
                                                    >
                                                        <option value="">Pilih Madrasah</option>
                                                        @foreach($madrasahOptions as $madrasah)
                                                            <option
                                                                value="{{ $madrasah->id }}"
                                                                data-scod="{{ $madrasah->scod }}"
                                                                data-name="{{ $madrasah->name }}"
                                                                {{ (string) old("rows.{$siswa->id}.madrasah_id", $siswa->madrasah_id) === (string) $madrasah->id ? 'selected' : '' }}
                                                            >
                                                                {{ $madrasah->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            @endif
                                            <td>
                                                @if(in_array($userRole, ['admin', 'admin_spp']))
                                                    <input type="hidden" name="rows[{{ $siswa->id }}][madrasah_id]" value="{{ old("rows.{$siswa->id}.madrasah_id", $siswa->madrasah_id) }}">
                                                @endif
                                                <input
                                                    type="hidden"
                                                    name="rows[{{ $siswa->id }}][scod]"
                                                    class="js-bulk-scod"
                                                    value="{{ old("rows.{$siswa->id}.scod", $siswa->scod ?: ($siswa->madrasah->scod ?? '')) }}"
                                                >
                                                <input
                                                    type="hidden"
                                                    name="rows[{{ $siswa->id }}][asal_sekolah_madrasah]"
                                                    class="js-bulk-school-name"
                                                    value="{{ old("rows.{$siswa->id}.asal_sekolah_madrasah", $siswa->nama_madrasah ?: ($siswa->madrasah->name ?? '')) }}"
                                                >
                                                <input type="text" name="rows[{{ $siswa->id }}][nis]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.nis", $siswa->nis) }}">
                                            </td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][nisn]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.nisn", $siswa->nisn) }}"></td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][nik]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.nik", $siswa->nik) }}"></td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][no_kk]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.no_kk", $siswa->no_kk) }}"></td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][nama_lengkap]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.nama_lengkap", $siswa->nama_lengkap) }}"></td>
                                            <td>
                                                <select name="rows[{{ $siswa->id }}][jenis_kelamin]" class="form-select form-select-sm bulk-grid-input">
                                                    <option value="">Pilih</option>
                                                    <option value="L" {{ old("rows.{$siswa->id}.jenis_kelamin", $siswa->jenis_kelamin) === 'L' ? 'selected' : '' }}>L</option>
                                                    <option value="P" {{ old("rows.{$siswa->id}.jenis_kelamin", $siswa->jenis_kelamin) === 'P' ? 'selected' : '' }}>P</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][tempat_lahir]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.tempat_lahir", $siswa->tempat_lahir) }}"></td>
                                            <td><input type="date" name="rows[{{ $siswa->id }}][tanggal_lahir]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.tanggal_lahir", optional($siswa->tanggal_lahir)->format('Y-m-d')) }}"></td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][agama]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.agama", $siswa->agama) }}"></td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][kelas]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.kelas", $siswa->kelas) }}"></td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][jurusan]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.jurusan", $siswa->jurusan) }}"></td>
                                            <td>
                                                <textarea name="rows[{{ $siswa->id }}][alamat]" class="form-control form-control-sm bulk-grid-input bulk-grid-textarea">{{ old("rows.{$siswa->id}.alamat", $siswa->alamat) }}</textarea>
                                            </td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][dusun]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.dusun", $siswa->dusun) }}"></td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][kelurahan]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.kelurahan", $siswa->kelurahan) }}"></td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][kecamatan]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.kecamatan", $siswa->kecamatan) }}"></td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][no_hp]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.no_hp", $siswa->no_hp) }}"></td>
                                            <td><input type="email" name="rows[{{ $siswa->id }}][email]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.email", $siswa->email) }}"></td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][nama_ayah]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.nama_ayah", $siswa->nama_ayah) }}"></td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][nama_ibu]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.nama_ibu", $siswa->nama_ibu) }}"></td>
                                            <td><input type="text" name="rows[{{ $siswa->id }}][no_hp_orang_tua_wali]" class="form-control form-control-sm bulk-grid-input" value="{{ old("rows.{$siswa->id}.no_hp_orang_tua_wali", $siswa->no_hp_orang_tua_wali) }}"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Semua Perubahan</button>
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
                            Gunakan template resmi. Kolom data siswa boleh kosong.
                            @if($userRole === 'super_admin')
                                Pastikan kolom <strong>SCOD</strong> pada file terisi agar sekolah tujuan dikenali otomatis.
                            @else
                                Jika kolom <strong>SCOD</strong> dan <strong>ASAL SEKOLAH/MADRASAH</strong> di file kosong, pilih madrasah pada form import ini sebagai tujuan penyimpanan.
                            @endif
                        </div>
                        @if(!in_array($userRole, ['admin', 'admin_spp', 'super_admin']))
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
                        @elseif(in_array($userRole, ['admin', 'admin_spp']))
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
<script src="{{ asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dataTableElement = $('#datatable-buttons');
    if (dataTableElement.length) {
        const table = dataTableElement.DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'],
            language: {
                emptyTable: 'Belum ada data siswa.'
            }
        });

        table.buttons().container()
            .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
    }

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

    const updateFieldHighlights = (container) => {
        if (!container) {
            return;
        }

        container.querySelectorAll('input, select, textarea').forEach(function (field) {
            if (['hidden', 'button', 'submit', 'file'].includes(field.type)) {
                return;
            }

            const isEmpty = (field.value ?? '').trim() === '';
            field.classList.toggle('field-empty-highlight', isEmpty);
        });
    };

    const bindFieldHighlightListeners = (container) => {
        if (!container) {
            return;
        }

        container.querySelectorAll('input, select, textarea').forEach(function (field) {
            if (field.dataset.highlightBound === '1' || ['hidden', 'button', 'submit', 'file'].includes(field.type)) {
                return;
            }

            const eventName = field.tagName === 'SELECT' ? 'change' : 'input';
            field.addEventListener(eventName, function () {
                updateFieldHighlights(container);
            });
            field.dataset.highlightBound = '1';
        });
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

    const bulkEditForm = document.getElementById('bulkEditForm');
    if (bulkEditForm) {
        bindFieldHighlightListeners(bulkEditForm);

        bulkEditForm.querySelectorAll('.js-bulk-edit-row').forEach(function (row) {
            const select = row.querySelector('.js-bulk-school-select');
            const scodField = row.querySelector('.js-bulk-scod');
            const schoolNameField = row.querySelector('.js-bulk-school-name');

            const syncBulkSchoolMetadata = () => {
                if (!select || !scodField || !schoolNameField) {
                    return;
                }

                const selectedOption = select.options[select.selectedIndex];
                scodField.value = selectedOption?.getAttribute('data-scod') ?? '';
                schoolNameField.value = selectedOption?.getAttribute('data-name') ?? '';
            };

            syncBulkSchoolMetadata();

            if (select && select.dataset.syncBound !== '1') {
                select.addEventListener('change', function () {
                    syncBulkSchoolMetadata();
                    updateFieldHighlights(row);
                });
                select.dataset.syncBound = '1';
            }
        });

        updateFieldHighlights(bulkEditForm);
    }

    const editForm = document.getElementById('editForm');
    if (editForm) {
        bindFieldHighlightListeners(editForm);
    }

    const editModal = document.getElementById('editModal');
    if (editModal) {
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
            updateFieldHighlights(form);
        });
    }

    const shouldReopenBulkModal = @json((bool) old('bulk_edit_submit'));
    if (shouldReopenBulkModal) {
        const bulkEditModal = document.getElementById('bulkEditModal');
        if (bulkEditModal && window.bootstrap?.Modal) {
            window.bootstrap.Modal.getOrCreateInstance(bulkEditModal).show();
        }
    }
});
</script>
@endsection
