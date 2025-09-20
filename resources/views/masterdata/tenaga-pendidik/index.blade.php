@extends('layouts.master')

@section('title') Tenaga Pendidik @endsection

@section('content')
@php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'admin']);
@endphp
@if($isAllowed)
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Tenaga Pendidik @endslot
@endcomponent

@section('css')
<link href="{{ URL::asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('build/css/app.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-user me-2"></i>Tenaga Pendidik
                </h4>
            </div>
            <div class="card-body">

                <div class="mb-3 d-flex justify-content-end gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTP"><i class="bx bx-plus"></i> Tambah Tenaga Pendidik</button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportTP"><i class="bx bx-upload"></i> Import Data TP</button>
                </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered dt-responsive nowrap w-100" id="datatable-buttons">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Madrasah</th>
                        <th>Status Kepegawaian</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenagaPendidiks as $index => $tp)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
@if($tp->avatar)
    <img src="{{ asset('storage/' . $tp->avatar) }}"
        alt="Avatar {{ $tp->name }}"
        class="rounded-circle"
        width="50" height="50">
@else
    <span class="text-muted">-</span>
@endif
                            </td>
                            <td>{{ $tp->name }}</td>
                            <td>{{ $tp->email }}</td>
                            <td>{{ $tp->madrasah?->name ?? '-' }}</td>
                            <td>{{ $tp->statusKepegawaian->name ?? '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditTP{{ $tp->id }}">Edit</button>
                                <form action="{{ route('tenaga-pendidik.destroy', $tp->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger @if(strtolower(auth()->user()->role) == 'admin') d-none @endif" onclick="return confirm('Yakin hapus data ini?')">Delete</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit Tenaga Pendidik -->
                        <div class="modal fade" id="modalEditTP{{ $tp->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                <form action="{{ route('tenaga-pendidik.update', $tp->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Tenaga Pendidik</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body row g-3">
                                            <div class="col-md-6">
                                                <label>Nama Lengkap</label>
                                                <input type="text" name="nama" class="form-control" value="{{ $tp->name }}" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $tp->email }}" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Password (Kosongkan jika tidak diubah)</label>
                                                <input type="password" name="password" class="form-control">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Tempat Lahir</label>
                                                <input type="text" name="tempat_lahir" class="form-control" value="{{ $tp->tempat_lahir }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Tanggal Lahir</label>
                                                <input type="date" name="tanggal_lahir" class="form-control" value="{{ $tp->tanggal_lahir }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label>No HP</label>
                                                <input type="text" name="no_hp" class="form-control" value="{{ $tp->no_hp }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Kartu NU</label>
                                                <input type="text" name="kartanu" class="form-control" value="{{ $tp->kartanu }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label>NIP</label>
                                                <input type="text" name="nip" class="form-control" value="{{ $tp->nip }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label>NUPTK</label>
                                                <input type="text" name="nuptk" class="form-control" value="{{ $tp->nuptk }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label>NPK</label>
                                                <input type="text" name="npk" class="form-control" value="{{ $tp->npk }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Madrasah</label>
                                                <input type="text" class="form-control" value="{{ $tp->madrasah ? $tp->madrasah->name : '-' }}" readonly>
                                                <input type="hidden" name="madrasah_id" value="{{ $tp->madrasah_id }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Status Kepegawaian</label>
                                                <select name="status_kepegawaian_id" class="form-control">
                                                    <option value="">-- Pilih Status Kepegawaian --</option>
                                                    @foreach($statusKepegawaian as $status)
                                                        <option value="{{ $status->id }}" {{ $tp->status_kepegawaian_id == $status->id ? 'selected' : '' }}>
                                                            {{ $status->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Pendidikan Terakhir</label>
                                                <input type="text" name="pendidikan_terakhir" class="form-control" value="{{ $tp->pendidikan_terakhir }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Tahun Lulus</label>
                                                <input type="number" name="tahun_lulus" class="form-control" value="{{ $tp->tahun_lulus }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Program Studi</label>
                                                <input type="text" name="program_studi" class="form-control" value="{{ $tp->program_studi }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Foto Profile</label>
                                                <input type="file" name="avatar" class="form-control">
                                                <small class="text-muted">Opsional, boleh dikosongkan</small>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Ketugasan</label>
                                                <input type="text" name="ketugasan" class="form-control" value="{{ old('ketugasan', $tp->ketugasan) }}">
                                            </div>
                                            <div class="col-12">
                                                <label>Alamat</label>
                                                <textarea name="alamat" class="form-control" rows="2">{{ $tp->alamat }}</textarea>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-4">
                                <div class="alert alert-info d-inline-block text-center" role="alert">
                                    <i class="bx bx-info-circle bx-lg me-2"></i>
                                    <strong>Belum ada data Tenaga Pendidik</strong><br>
                                    <small>Silakan tambahkan data tenaga pendidik terlebih dahulu untuk melanjutkan.</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahTP" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form action="{{ route('tenaga-pendidik.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tenaga Pendidik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">

                    <div class="col-md-6">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>No HP</label>
                        <input type="text" name="no_hp" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Kartu NU</label>
                        <input type="text" name="kartanu" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>NIP</label>
                        <input type="text" name="nip" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>NUPTK</label>
                        <input type="text" name="nuptk" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>NPK</label>
                        <input type="text" name="npk" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Madrasah</label>
                        <select name="madrasah_id" class="form-control">
                            <option value="">-- Pilih Madrasah --</option>
                            @foreach($madrasahs as $madrasah)
                                <option value="{{ $madrasah->id }}">{{ $madrasah->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Status Kepegawaian</label>
                        <select name="status_kepegawaian_id" class="form-control">
                            <option value="">-- Pilih Status Kepegawaian --</option>
                            @foreach($statusKepegawaian as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan_terakhir" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Tahun Lulus</label>
                        <input type="number" name="tahun_lulus" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Program Studi</label>
                        <input type="text" name="program_studi" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Foto Profile</label>
                        <input type="file" name="avatar" class="form-control">
                        <small class="text-muted">Opsional, boleh dikosongkan</small>
                    </div>

                    <div class="col-md-6">
                        <label>Ketugasan</label>
                        <input type="text" name="ketugasan" class="form-control">
                    </div>
                    <div class="col-12">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@else
<div class="alert alert-danger text-center">
    <h4>Akses Ditolak</h4>
    <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
</div>
@endif

<!-- Modal Import -->
<div class="modal fade" id="modalImportTP" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('tenaga-pendidik.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-upload me-2"></i>Import Data Tenaga Pendidik
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="file" class="form-label">
                                    <i class="bx bx-file me-1"></i>Pilih File Excel (.xlsx, .xls, .csv)
                                </label>
                                <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                            </div>

                            <div class="alert alert-info">
                                <strong><i class="bx bx-info-circle me-1"></i>Catatan Penting:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>File Excel HARUS memiliki baris header dengan nama kolom yang sesuai</li>
                                    <li>Password akan dibuat otomatis menggunakan NIP (jika ada) atau default 'nuist123'</li>
                                    <li>Email harus unik dan belum terdaftar</li>
                                    <li>Gunakan ID numerik untuk madrasah_id dan status_kepegawaian_id</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="bx bx-download me-1"></i>Template & Panduan</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="d-grid gap-2">
                                        <a href="{{ asset('template/tenaga_pendidik_template.xlsx') }}"
                                           class="btn btn-outline-primary btn-sm" download>
                                            <i class="bx bx-download me-1"></i>Download Template Excel
                                        </a>
                                        <a href="{{ asset('template/tenaga_pendidik_import_structure.txt') }}"
                                           class="btn btn-outline-info btn-sm" target="_blank">
                                            <i class="bx bx-file-blank me-1"></i>Lihat Struktur Data
                                        </a>
                                        <a href="{{ asset('template/test_import_tenaga_pendidik_updated.csv') }}"
                                           class="btn btn-outline-success btn-sm" download>
                                            <i class="bx bx-data me-1"></i>Download Contoh CSV
                                        </a>
                                    </div>

                                    <hr class="my-3">

                                    <div class="text-muted small">
                                        <strong>Kolom Wajib:</strong><br>
                                        nama, email, tempat_lahir, tanggal_lahir, no_hp, kartanu, nip, nuptk, npk, madrasah_id, pendidikan_terakhir, tahun_lulus, program_studi, status_kepegawaian_id, tmt, ketugasan, alamat
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="bx bx-list-ul me-1"></i>Referensi ID</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>ID Madrasah (Contoh):</strong>
                                            <div class="small text-muted mt-1">
                                                10 - SMA Ma'arif 1 Sleman<br>
                                                16 - SMK Ma'arif 1 Sleman<br>
                                                23 - SMK Ma'arif 1 Yogyakarta<br>
                                                <em>...dan lainnya (lihat struktur data lengkap)</em>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>ID Status Kepegawaian:</strong>
                                            <div class="small text-muted mt-1">
                                                1 - PNS Sertifikasi<br>
                                                3 - GTY Sertifikasi<br>
                                                5 - GTY Non Sertifikasi<br>
                                                6 - GTT<br>
                                                <em>...dan lainnya (lihat struktur data lengkap)</em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-upload me-1"></i>Import Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
