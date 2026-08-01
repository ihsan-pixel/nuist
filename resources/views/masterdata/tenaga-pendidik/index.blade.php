@extends('layouts.master')

@section('title') Tenaga Pendidik @endsection

@section('content')
@php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'admin', 'pengurus']);
@endphp
@if($isAllowed)
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Tenaga Pendidik @endslot
@endcomponent

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<style>
    .tp-modal-dialog {
        max-width: min(1180px, calc(100vw - 2rem));
    }

    .tp-modal-form {
        display: flex;
        flex-direction: column;
        min-height: 0;
        height: 100%;
    }

    .tp-modal-content {
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 2rem);
        overflow: hidden;
    }

    .tp-modal-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        overscroll-behavior: contain;
        padding-bottom: 1.5rem;
    }

    .tp-form-section {
        background: #f8fafc;
        border: 1px solid #e9edf4;
        border-radius: 1rem;
        padding: 1.25rem;
        height: 100%;
    }

    .tp-form-section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: 1rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 1rem;
    }

    .tp-form-hint {
        background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
        border: 1px solid #dbeafe;
        border-radius: 1rem;
        padding: 1rem 1.25rem;
    }

    .tp-form-label {
        font-weight: 600;
        margin-bottom: .4rem;
    }

    @media (min-width: 992px) {
        .tp-modal-dialog {
            width: min(1100px, calc(100vw - 4rem));
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .tp-modal-content {
            height: min(780px, calc(100vh - 3rem));
            max-height: min(780px, calc(100vh - 3rem));
        }

        .tp-modal-body {
            padding-bottom: 1rem;
        }
    }
</style>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h4 class="card-title mb-1">
                            <i class="bx bx-user me-2"></i>Tenaga Pendidik
                        </h4>
                        <p class="text-muted mb-0 small">Data dimuat bertahap dari server agar tetap cepat walau jumlah user besar.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('tenaga-pendidik.export-school-summary') }}" class="btn btn-outline-success">
                            <i class="bx bx-download"></i> Download Rekap Per Sekolah
                        </a>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTP">
                            <i class="bx bx-plus"></i> Tambah Tenaga Pendidik
                        </button>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportTP">
                            <i class="bx bx-upload"></i> Import Data TP
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-light border mb-3">
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-2">
                        <div>
                            <strong>Export rekap sekolah</strong><br>
                            <span class="text-muted small">File berisi jumlah guru `GTY/GTT`, jumlah karyawan `PTY/PTT`, dan total per sekolah.</span>
                        </div>
                        <div class="text-muted small">
                            Pencarian, urut, dan paging diproses di server.
                        </div>
                    </div>
                </div>

        <div class="table-responsive">
            <table class="table table-bordered dt-responsive nowrap w-100" id="datatable-buttons">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        {{-- <th>Foto</th> --}}
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Nuist ID</th>
                        <th>Kartanu</th>
                        <th>NUPTK</th>
                        <th>Pendidikan Terakhir</th>
                        <th>Madrasah</th>
                        <th>Status Kepegawaian</th>
                        <th>TMT</th>
                        <th>Ketugasan</th>
                        <th>Mengajar</th>
                        <th>Alamat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalViewTP" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Tenaga Pendidik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img id="view_tp_avatar" src="" alt="Foto tenaga pendidik" class="rounded-circle border-3 border-primary mb-3 d-none" width="120" height="120" style="object-fit: cover;">
                    <div id="view_tp_avatar_placeholder" class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px;">
                        <i class="bx bx-user text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="mb-1" id="view_tp_name">-</h4>
                    <p class="text-muted mb-0" id="view_tp_email">-</p>
                    <small class="text-primary fw-bold" id="view_tp_nuist_id">NUist ID: -</small>
                </div>

                <div class="card border-0 bg-light mb-3">
                    <div class="card-header bg-white border-bottom-0">
                        <h6 class="mb-0 text-primary">
                            <i class="bx bx-user-circle me-2"></i>Informasi Pribadi
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Tempat Lahir</label>
                                <p class="mb-0" id="view_tp_tempat_lahir">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Tanggal Lahir</label>
                                <p class="mb-0" id="view_tp_tanggal_lahir">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">No HP</label>
                                <p class="mb-0" id="view_tp_no_hp">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Kartu NU</label>
                                <p class="mb-0" id="view_tp_kartanu">-</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted small">Alamat</label>
                                <p class="mb-0" id="view_tp_alamat">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 bg-light mb-3">
                    <div class="card-header bg-white border-bottom-0">
                        <h6 class="mb-0 text-primary">
                            <i class="bx bx-briefcase me-2"></i>Informasi Kepegawaian
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">NIP Ma'arif</label>
                                <p class="mb-0" id="view_tp_nip">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">NUPTK</label>
                                <p class="mb-0" id="view_tp_nuptk">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">NPK</label>
                                <p class="mb-0" id="view_tp_npk">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Status Kepegawaian</label>
                                <p class="mb-0" id="view_tp_status_kepegawaian">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">TMT</label>
                                <p class="mb-0" id="view_tp_tmt">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Ketugasan</label>
                                <p class="mb-0" id="view_tp_ketugasan">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 bg-light mb-3">
                    <div class="card-header bg-white border-bottom-0">
                        <h6 class="mb-0 text-primary">
                            <i class="bx bx-graduation me-2"></i>Informasi Pendidikan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Pendidikan Terakhir</label>
                                <p class="mb-0" id="view_tp_pendidikan_terakhir">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Tahun Lulus</label>
                                <p class="mb-0" id="view_tp_tahun_lulus">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Program Studi</label>
                                <p class="mb-0" id="view_tp_program_studi">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Mengajar</label>
                                <p class="mb-0" id="view_tp_mengajar">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 bg-light mb-0">
                    <div class="card-header bg-white border-bottom-0">
                        <h6 class="mb-0 text-primary">
                            <i class="bx bx-building me-2"></i>Informasi Penugasan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Madrasah Utama</label>
                                <p class="mb-0" id="view_tp_madrasah">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Pemenuhan Beban Kerja Lain</label>
                                <p class="mb-0" id="view_tp_beban_kerja">-</p>
                            </div>
                            <div class="col-12 d-none" id="view_tp_madrasah_tambahan_wrapper">
                                <label class="form-label fw-bold text-muted small">Madrasah Tambahan</label>
                                <p class="mb-0" id="view_tp_madrasah_tambahan">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditTP" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable tp-modal-dialog">
        <div class="modal-content tp-modal-content">
            <form id="editTenagaPendidikForm" method="POST" enctype="multipart/form-data" class="tp-modal-form">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-1">Edit Tenaga Pendidik</h5>
                        <p class="text-muted mb-0 small">Perubahan disimpan ke data user yang dipilih.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body tp-modal-body">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="tp-form-section">
                                <div class="tp-form-section-title">
                                    <i class="bx bx-lock-alt text-primary"></i>
                                    <span>Akun & Penugasan</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Nama Lengkap</label>
                                        <input type="text" name="nama" id="edit_nama" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Email</label>
                                        <input type="email" name="email" id="edit_email" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Password (Kosongkan jika tidak diubah)</label>
                                        <input type="password" name="password" id="edit_password" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Madrasah</label>
                                        @if($userRole === 'super_admin')
                                            <select name="madrasah_id" id="edit_madrasah_id" class="form-control">
                                                <option value="">-- Pilih Madrasah --</option>
                                                @foreach($madrasahs as $madrasah)
                                                    <option value="{{ $madrasah->id }}">{{ $madrasah->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" id="edit_madrasah_name" class="form-control" readonly>
                                            <input type="hidden" name="madrasah_id" id="edit_madrasah_id">
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Status Kepegawaian</label>
                                        <select name="status_kepegawaian_id" id="edit_status_kepegawaian_id" class="form-control">
                                            <option value="">-- Pilih Status Kepegawaian --</option>
                                            @foreach($statusKepegawaian as $status)
                                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Ketugasan</label>
                                        <select name="ketugasan" id="edit_ketugasan" class="form-control">
                                            <option value="">-- Pilih Ketugasan --</option>
                                            <option value="tenaga pendidik">Tenaga Pendidik</option>
                                            <option value="penjaga sekolah">Penjaga Sekolah</option>
                                            <option value="kepala madrasah/sekolah">Kepala Madrasah/Sekolah</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Mengajar</label>
                                        <input type="text" name="mengajar" id="edit_mengajar" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">TMT</label>
                                        <input type="date" name="tmt" id="edit_tmt" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="tp-form-section">
                                <div class="tp-form-section-title">
                                    <i class="bx bx-user text-primary"></i>
                                    <span>Profil Pribadi</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" id="edit_tempat_lahir" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" id="edit_tanggal_lahir" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">No HP</label>
                                        <input type="text" name="no_hp" id="edit_no_hp" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Kartu NU</label>
                                        <input type="text" name="kartanu" id="edit_kartanu" class="form-control">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label tp-form-label">Alamat</label>
                                        <textarea name="alamat" id="edit_alamat" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="tp-form-section">
                                <div class="tp-form-section-title">
                                    <i class="bx bx-id-card text-primary"></i>
                                    <span>Data Kepegawaian</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">NIP Ma'arif</label>
                                        <input type="text" name="nip" id="edit_nip" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">NUPTK</label>
                                        <input type="text" name="nuptk" id="edit_nuptk" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">NPK</label>
                                        <input type="text" name="npk" id="edit_npk" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Pemenuhan Beban Kerja di Sekolah/Madrasah Lain</label>
                                        <select name="pemenuhan_beban_kerja_lain" id="edit_pemenuhan_beban_kerja_lain" class="form-control">
                                            <option value="">-- Pilih --</option>
                                            <option value="1">Iya</option>
                                            <option value="0">Tidak</option>
                                        </select>
                                    </div>
                                    <div class="col-12 d-none" id="edit_madrasah_tambahan_container">
                                        <label class="form-label tp-form-label">Madrasah Tambahan</label>
                                        <select name="madrasah_id_tambahan" id="edit_madrasah_id_tambahan" class="form-control">
                                            <option value="">-- Pilih Madrasah --</option>
                                            @foreach($madrasahs as $madrasah)
                                                <option value="{{ $madrasah->id }}">{{ $madrasah->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="tp-form-section">
                                <div class="tp-form-section-title">
                                    <i class="bx bx-book-content text-primary"></i>
                                    <span>Pendidikan & Lampiran</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Pendidikan Terakhir</label>
                                        <input type="text" name="pendidikan_terakhir" id="edit_pendidikan_terakhir" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Tahun Lulus</label>
                                        <input type="number" name="tahun_lulus" id="edit_tahun_lulus" class="form-control">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label tp-form-label">Program Studi</label>
                                        <input type="text" name="program_studi" id="edit_program_studi" class="form-control">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label tp-form-label">Foto Profile</label>
                                        <input type="file" name="avatar" id="edit_avatar" class="form-control">
                                        <small class="text-muted">Opsional, boleh dikosongkan.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahTP" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable tp-modal-dialog">
        <div class="modal-content tp-modal-content">
            <form action="{{ route('tenaga-pendidik.store') }}" method="POST" enctype="multipart/form-data" class="tp-modal-form">
                @csrf
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-1">Tambah Tenaga Pendidik</h5>
                        <p class="text-muted mb-0 small">Lengkapi data utama tenaga pendidik. Struktur field tetap sama sehingga proses simpan tidak berubah.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body tp-modal-body">
                    <div class="tp-form-hint mb-4">
                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-2">
                            <div>
                                <div class="fw-semibold text-primary mb-1">Informasi wajib</div>
                                <div class="text-muted small">Nama, email, dan password wajib diisi. Data lainnya bisa dilengkapi sekarang atau nanti.</div>
                            </div>
                            <div class="text-muted small">
                                <i class="bx bx-shield-quarter me-1"></i>
                                Pengiriman data tetap memakai endpoint dan field yang sama seperti sebelumnya.
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="tp-form-section">
                                <div class="tp-form-section-title">
                                    <i class="bx bx-lock-alt text-primary"></i>
                                    <span>Akun & Penugasan</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Nama Lengkap</label>
                                        <input type="text" name="nama" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Password</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Madrasah</label>
                                        <select name="madrasah_id" class="form-control">
                                            <option value="">-- Pilih Madrasah --</option>
                                            @foreach($madrasahs as $madrasah)
                                                <option value="{{ $madrasah->id }}">{{ $madrasah->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Status Kepegawaian</label>
                                        <select name="status_kepegawaian_id" class="form-control">
                                            <option value="">-- Pilih Status Kepegawaian --</option>
                                            @foreach($statusKepegawaian as $status)
                                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Ketugasan</label>
                                        <select name="ketugasan" class="form-control">
                                            <option value="">-- Pilih Ketugasan --</option>
                                            <option value="tenaga pendidik">Tenaga Pendidik</option>
                                            <option value="penjaga sekolah">Penjaga Sekolah</option>
                                            <option value="kepala madrasah/sekolah">Kepala Madrasah/Sekolah</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Mengajar</label>
                                        <input type="text" name="mengajar" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">TMT</label>
                                        <input type="date" name="tmt" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="tp-form-section">
                                <div class="tp-form-section-title">
                                    <i class="bx bx-user text-primary"></i>
                                    <span>Profil Pribadi</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">No HP</label>
                                        <input type="text" name="no_hp" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Kartu NU</label>
                                        <input type="text" name="kartanu" class="form-control">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label tp-form-label">Alamat</label>
                                        <textarea name="alamat" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="tp-form-section">
                                <div class="tp-form-section-title">
                                    <i class="bx bx-id-card text-primary"></i>
                                    <span>Data Kepegawaian</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">NIP Ma'arif</label>
                                        <input type="text" name="nip" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">NUPTK</label>
                                        <input type="text" name="nuptk" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">NPK</label>
                                        <input type="text" name="npk" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Pemenuhan Beban Kerja di Sekolah/Madrasah Lain</label>
                                        <select name="pemenuhan_beban_kerja_lain" id="pemenuhan_beban_kerja_lain_add" class="form-control">
                                            <option value="">-- Pilih --</option>
                                            <option value="1">Iya</option>
                                            <option value="0">Tidak</option>
                                        </select>
                                    </div>

                                    <div class="col-12" id="madrasah_tambahan_add_container" style="display: none;">
                                        <label class="form-label tp-form-label">Madrasah Tambahan</label>
                                        <select name="madrasah_id_tambahan" class="form-control">
                                            <option value="">-- Pilih Madrasah --</option>
                                            @foreach($madrasahs as $madrasah)
                                                <option value="{{ $madrasah->id }}">{{ $madrasah->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="tp-form-section">
                                <div class="tp-form-section-title">
                                    <i class="bx bx-book-content text-primary"></i>
                                    <span>Pendidikan & Lampiran</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Pendidikan Terakhir</label>
                                        <input type="text" name="pendidikan_terakhir" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label tp-form-label">Tahun Lulus</label>
                                        <input type="number" name="tahun_lulus" class="form-control">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label tp-form-label">Program Studi</label>
                                        <input type="text" name="program_studi" class="form-control">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label tp-form-label">Foto Profile</label>
                                        <input type="file" name="avatar" class="form-control">
                                        <small class="text-muted">Opsional, boleh dikosongkan.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
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
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-height: 90vh; overflow-y: auto;">
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
                                    <li><strong>PERUBAHAN BARU:</strong> Kolom 'ketugasan' menggunakan enum: 'tenaga pendidik' atau 'kepala madrasah/sekolah'</li>
                                    <li><strong>PERUBAHAN BARU:</strong> Kolom 'mengajar' wajib diisi</li>
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
                                        <a href="{{ asset('template/tenaga_pendidik_template.csv') }}"
                                           class="btn btn-outline-success btn-sm" download>
                                            <i class="bx bx-user me-1"></i>Download Template CSV
                                        </a>
                                        {{-- <a href="{{ asset('template/tenaga_pendidik_kepala_madrasah.csv') }}"
                                           class="btn btn-outline-danger btn-sm" download>
                                            <i class="bx bx-crown me-1"></i>Download Template CSV Kepala Sekolah
                                        </a> --}}
                                        <a href="{{ asset('template/tenaga_pendidik_import_structure.txt') }}"
                                           class="btn btn-outline-info btn-sm" target="_blank">
                                            <i class="bx bx-file-blank me-1"></i>Lihat Struktur Data
                                        </a>
                                        {{-- <a href="{{ asset('template/tenaga_pendidik_template.csv') }}"
                                           class="btn btn-outline-success btn-sm" download>
                                            <i class="bx bx-data me-1"></i>Download Template CSV Kosong
                                        </a>
                                        <a href="{{ asset('template/tenaga_pendidik_contoh.csv') }}"
                                           class="btn btn-outline-warning btn-sm" download>
                                            <i class="bx bx-file me-1"></i>Download Contoh Data Guru
                                        </a>
                                        <a href="{{ asset('template/tenaga_pendidik_kepala_madrasah.csv') }}"
                                           class="btn btn-outline-danger btn-sm" download>
                                            <i class="bx bx-crown me-1"></i>Download Contoh Data Kepala Sekolah
                                        </a> --}}
                                        <a href="{{ asset('template/panduan_import_tenaga_pendidik.txt') }}"
                                           class="btn btn-outline-secondary btn-sm" target="_blank">
                                            <i class="bx bx-book me-1"></i>Baca Panduan Lengkap
                                        </a>
                                    </div>

                                    <hr class="my-3">

                                    <div class="text-muted small">
                                        <strong>Kolom Wajib:</strong><br>
                                        nama, email, tempat_lahir, tanggal_lahir, no_hp, kartanu, nip, nuptk, madrasah_id, pendidikan_terakhir, tahun_lulus, program_studi, status_kepegawaian_id, tmt, ketugasan, mengajar, alamat<br>
                                        <strong>Kolom Opsional:</strong><br>
                                        npk, pemenuhan_beban_kerja_lain, madrasah_id_tambahan
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

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        $(document).ready(function () {
            const userRole = @json($userRole);
            const isSuperAdmin = @json($userRole === 'super_admin');
            const editModal = new bootstrap.Modal(document.getElementById('modalEditTP'));
            const viewModal = new bootstrap.Modal(document.getElementById('modalViewTP'));
            const rowDataMap = {};

            const toText = (value, fallback = '-') => {
                return value === null || value === undefined || value === '' ? fallback : value;
            };

            const toggleEditMadrasahTambahan = () => {
                const value = $('#edit_pemenuhan_beban_kerja_lain').val();
                $('#edit_madrasah_tambahan_container').toggleClass('d-none', value !== '1');
            };

            const fillViewModal = (data) => {
                $('#view_tp_name').text(toText(data.name));
                $('#view_tp_email').text(toText(data.email));
                $('#view_tp_nuist_id').text(`NUist ID: ${toText(data.nuist_id)}`);
                $('#view_tp_tempat_lahir').text(toText(data.tempat_lahir));
                $('#view_tp_tanggal_lahir').text(toText(data.tanggal_lahir_display));
                $('#view_tp_no_hp').text(toText(data.no_hp));
                $('#view_tp_kartanu').text(toText(data.kartanu));
                $('#view_tp_alamat').text(toText(data.alamat));
                $('#view_tp_nip').text(toText(data.nip));
                $('#view_tp_nuptk').text(toText(data.nuptk));
                $('#view_tp_npk').text(toText(data.npk));
                $('#view_tp_status_kepegawaian').text(toText(data.status_kepegawaian_name));
                $('#view_tp_tmt').text(toText(data.tmt_display));
                $('#view_tp_ketugasan').text(toText(data.ketugasan));
                $('#view_tp_pendidikan_terakhir').text(toText(data.pendidikan_terakhir));
                $('#view_tp_tahun_lulus').text(toText(data.tahun_lulus));
                $('#view_tp_program_studi').text(toText(data.program_studi));
                $('#view_tp_mengajar').text(toText(data.mengajar));
                $('#view_tp_madrasah').text(toText(data.madrasah_name));
                $('#view_tp_beban_kerja').text(toText(data.pemenuhan_beban_kerja_lain_label));

                const hasMadrasahTambahan = data.pemenuhan_beban_kerja_lain_label === 'Ya' && data.madrasah_tambahan_name;
                $('#view_tp_madrasah_tambahan_wrapper').toggleClass('d-none', !hasMadrasahTambahan);
                $('#view_tp_madrasah_tambahan').text(toText(data.madrasah_tambahan_name));

                if (data.avatar_url) {
                    $('#view_tp_avatar').attr('src', data.avatar_url).removeClass('d-none');
                    $('#view_tp_avatar_placeholder').addClass('d-none');
                } else {
                    $('#view_tp_avatar').attr('src', '').addClass('d-none');
                    $('#view_tp_avatar_placeholder').removeClass('d-none');
                }
            };

            const fillEditModal = (data) => {
                const updateUrl = @json(route('tenaga-pendidik.update', '__ID__')).replace('__ID__', data.id);

                $('#editTenagaPendidikForm').attr('action', updateUrl);
                $('#edit_nama').val(data.name || '');
                $('#edit_email').val(data.email || '');
                $('#edit_password').val('');
                $('#edit_tempat_lahir').val(data.tempat_lahir || '');
                $('#edit_tanggal_lahir').val(data.tanggal_lahir_form || '');
                $('#edit_no_hp').val(data.no_hp || '');
                $('#edit_kartanu').val(data.kartanu || '');
                $('#edit_nip').val(data.nip || '');
                $('#edit_nuptk').val(data.nuptk || '');
                $('#edit_npk').val(data.npk || '');
                $('#edit_status_kepegawaian_id').val(data.status_kepegawaian_id || '');
                $('#edit_tmt').val(data.tmt_form || '');
                $('#edit_pendidikan_terakhir').val(data.pendidikan_terakhir || '');
                $('#edit_tahun_lulus').val(data.tahun_lulus || '');
                $('#edit_program_studi').val(data.program_studi || '');
                $('#edit_ketugasan').val(data.ketugasan || '');
                $('#edit_mengajar').val(data.mengajar || '');
                $('#edit_alamat').val(data.alamat || '');
                $('#edit_pemenuhan_beban_kerja_lain').val(
                    data.pemenuhan_beban_kerja_lain === 1 || data.pemenuhan_beban_kerja_lain === '1' ? '1' : '0'
                );
                $('#edit_madrasah_id_tambahan').val(data.madrasah_id_tambahan || '');

                if (isSuperAdmin) {
                    $('#edit_madrasah_id').val(data.madrasah_id || '');
                } else {
                    $('#edit_madrasah_name').val(data.madrasah_name || '-');
                    $('#edit_madrasah_id').val(data.madrasah_id || '');
                }

                toggleEditMadrasahTambahan();
            };

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    confirmButtonColor: '#0e8549'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: @json(session('error')),
                    confirmButtonColor: '#d33'
                });
            @endif

            let table = $("#datatable-buttons").DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                deferRender: true,
                pageLength: 25,
                ajax: "{{ route('tenaga-pendidik.data') }}",
                order: [[7, 'asc'], [1, 'asc']],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false },
                    { data: 'name', name: 'users.name' },
                    { data: 'email', name: 'users.email' },
                    { data: 'nuist_id', name: 'users.nuist_id' },
                    { data: 'kartanu', name: 'users.kartanu' },
                    { data: 'nuptk', name: 'users.nuptk' },
                    { data: 'pendidikan_terakhir', name: 'users.pendidikan_terakhir' },
                    { data: 'madrasah_name', name: 'madrasahs.name' },
                    { data: 'status_kepegawaian_name', name: 'status_kepegawaian.name' },
                    { data: 'tmt_display', name: 'users.tmt' },
                    { data: 'ketugasan', name: 'users.ketugasan' },
                    { data: 'mengajar', name: 'users.mengajar' },
                    { data: 'alamat', name: 'users.alamat' },
                    { data: 'action', name: 'action', searchable: false, orderable: false }
                ],
                buttons: ["copy", "excel", "pdf", "print", "colvis"],
                language: {
                    emptyTable: 'Belum ada data tenaga pendidik.',
                    processing: 'Memuat data...'
                }
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

            table.on('xhr.dt', function (e, settings, json) {
                Object.keys(rowDataMap).forEach((key) => delete rowDataMap[key]);

                (json.data || []).forEach((item) => {
                    rowDataMap[item.id] = item;
                });
            });

            $(document).on('click', '.view-tenaga-pendidik-btn', function () {
                const data = rowDataMap[$(this).data('id')];
                if (!data) {
                    return;
                }

                fillViewModal(data);
                viewModal.show();
            });

            $(document).on('click', '.edit-tenaga-pendidik-btn', function () {
                const data = rowDataMap[$(this).data('id')];
                if (!data) {
                    return;
                }

                fillEditModal(data);
                editModal.show();
            });

            $(document).on('click', '.delete-tenaga-pendidik-btn', function () {
                const teacherId = $(this).data('id');
                const teacherName = $(this).data('name') || 'data ini';
                const deleteUrl = @json(route('tenaga-pendidik.destroy', '__ID__')).replace('__ID__', teacherId);

                Swal.fire({
                    title: 'Hapus tenaga pendidik?',
                    text: `Data ${teacherName} akan dihapus permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                });
            });

            $('#pemenuhan_beban_kerja_lain_add').change(function() {
                if ($(this).val() == '1') {
                    $('#madrasah_tambahan_add_container').show();
                } else {
                    $('#madrasah_tambahan_add_container').hide();
                }
            });
            $('#edit_pemenuhan_beban_kerja_lain').change(toggleEditMadrasahTambahan);
        });
    </script>
@endsection
