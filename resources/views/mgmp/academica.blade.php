@extends('layouts.master')

@section('title')
    Academica - Proposal
@endsection

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />

<!-- DataTables -->
<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />

@endsection

@section('content')
@php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'admin', 'pengurus', 'mgmp']) && auth()->user()->password_changed;
@endphp

@if($isAllowed)
@php
    $showResetUpdateModal = $errors->has('title')
        || $errors->has('progress_percent')
        || $errors->has('progress_note')
        || $errors->has('attachments')
        || $errors->has('attachments.*');
    $showArticleModal = $errors->has('article_file');
@endphp
@component('components.breadcrumb')
    @slot('li_1') MGMP @endslot
    @slot('title') Academica @endslot
@endcomponent

@include('mgmp.partials.ui-styles')

<div class="mgmp-page">
<div class="mgmp-hero-strip mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <div class="mgmp-kicker mb-2">Academica</div>
            <h4 class="mb-1">Proposal Akademik MGMP</h4>
            <p class="mb-0 text-white-50">Unggah dan pantau proposal akademik anggota MGMP.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <span class="mgmp-chip bg-white text-success">{{ $proposals->count() }} proposal</span>
            <span class="mgmp-chip bg-white text-success">{{ $academicaSummary['total_updates'] ?? 0 }} laporan</span>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card mgmp-stat-card academica-metric-card p-3 h-100">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div class="mgmp-icon-bubble">
                    <i class="bx {{ $academicaSummary['proposal_uploaded'] ? 'bx-file' : 'bx-file-blank' }}"></i>
                </div>
                <div>
                    <div class="academica-metric-label">Proposal Utama</div>
                    <div class="academica-metric-value">{{ $academicaSummary['proposal_uploaded'] ? 'Tersedia' : 'Kosong' }}</div>
                    <small class="text-muted">{{ $userProposal ? 'Dokumen utama aktif' : 'Belum ada proposal' }}</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mgmp-stat-card academica-metric-card p-3 h-100">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div class="mgmp-icon-bubble">
                    <i class="bx bx-task"></i>
                </div>
                <div>
                    <div class="academica-metric-label">Laporan Terkumpul</div>
                    <div class="academica-metric-number">{{ $academicaSummary['total_updates'] ?? 0 }}</div>
                    <small class="text-muted">Riwayat progres tersimpan</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mgmp-stat-card academica-metric-card p-3 h-100">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div class="mgmp-icon-bubble">
                    <i class="bx bx-paperclip"></i>
                </div>
                <div>
                    <div class="academica-metric-label">Lampiran Terkumpul</div>
                    <div class="academica-metric-number">{{ $academicaSummary['total_attachments'] ?? 0 }}</div>
                    <small class="text-muted">Dokumen pendukung terarsip</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mgmp-stat-card academica-metric-card p-3 h-100">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div class="mgmp-icon-bubble">
                    <i class="bx bx-file-find"></i>
                </div>
                <div>
                    <div class="academica-metric-label">Artikel & Progres</div>
                    <div class="academica-metric-value">
                        {{ $academicaSummary['article_uploaded'] ? 'Artikel siap' : 'Artikel belum ada' }}
                    </div>
                    <small class="text-muted">
                        {{ isset($academicaSummary['latest_progress']) ? $academicaSummary['latest_progress'] . '% progres terbaru' : 'Belum ada progres' }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mgmp-panel mb-4">
    <div class="card-body">
        <div class="academica-section-header">
            <div>
                <div class="mgmp-kicker text-success mb-2">Informasi</div>
                <h5 class="mb-1">Data Yang Terkumpul</h5>
                <p class="text-muted mb-0">
                    {{ ($academicaSummary['total_updates'] ?? 0) > 0
                        ? 'Pantau status proposal, jumlah laporan, lampiran, dan kesiapan artikel PDF dari satu tempat.'
                        : 'Belum banyak data terkumpul. Mulai dari upload proposal lalu lanjutkan laporan update riset.' }}
                </p>
            </div>
            <div class="academica-summary-badge">
                <strong>{{ $proposals->count() }}</strong>
                <span>proposal pada sistem</span>
            </div>
        </div>
        <div class="row g-3 mt-3">
            <div class="col-lg-4">
                <div class="academica-summary-card">
                    <span class="academica-summary-label">Proposal aktif</span>
                    <strong>{{ $userProposal?->filename ?? 'Belum ada proposal utama' }}</strong>
                    <small>{{ $userProposal ? 'Update terakhir ' . $userProposal->updated_at->format('d M Y H:i') : 'Upload proposal utama untuk memulai alur Academica.' }}</small>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="academica-summary-card">
                    <span class="academica-summary-label">Laporan update riset</span>
                    <strong>{{ $academicaSummary['total_updates'] ?? 0 }} laporan tersimpan</strong>
                    <small>{{ $academicaSummary['latest_update_at'] ? 'Aktivitas terakhir ' . $academicaSummary['latest_update_at']->format('d M Y H:i') : 'Belum ada laporan update riset yang dikirim.' }}</small>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="academica-summary-card">
                    <span class="academica-summary-label">Artikel PDF</span>
                    <strong>{{ $academicaSummary['article_uploaded'] ? ($userProposal->article_filename ?? 'Artikel tersedia') : 'Belum ada artikel PDF' }}</strong>
                    <small>{{ $canUploadArticle ? 'Artikel PDF dapat dikelola lewat modal upload.' : 'Artikel aktif setelah minimal satu laporan update riset tersimpan.' }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mgmp-panel mb-4">
    <div class="card-body">
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

        <div class="academica-section-header mb-4">
            <div>
                <div class="mgmp-kicker text-success mb-2">Proposal</div>
                <h5 class="mb-1">Dokumen Proposal Utama</h5>
                <p class="text-muted mb-0">Kelola file proposal utama sebagai basis seluruh aktivitas Academica.</p>
            </div>
        </div>

        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                @if($userHasUploaded && $userProposal)
                    <div class="academica-resource-card">
                        <div class="d-flex align-items-start gap-3">
                            <div class="mgmp-icon-bubble">
                                <i class="bx bx-file"></i>
                            </div>
                            <div class="grow">
                                <div class="academica-resource-title">Proposal aktif</div>
                                <div class="fw-semibold text-dark mb-1">{{ $userProposal->filename }}</div>
                                <small class="text-muted d-block">Terakhir diperbarui {{ $userProposal->updated_at->format('d M Y H:i') }}</small>
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <a href="{{ url('/uploads/' . $userProposal->path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-show"></i> Lihat File
                                    </a>
                                    <button type="button" class="btn btn-sm btn-primary ms-2" id="toggleReplaceProposal">
                                        <i class="bx bx-edit-alt"></i> Edit / Ganti File
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="academica-resource-card">
                        <div class="d-flex align-items-start gap-3">
                            <div class="mgmp-icon-bubble">
                                <i class="bx bx-upload"></i>
                            </div>
                            <div class="grow">
                                <div class="academica-resource-title">Proposal aktif</div>
                                <div class="fw-semibold text-dark">Belum ada file proposal</div>
                                <small class="text-muted">Silakan upload proposal pertama Anda dalam format PDF.</small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-7">
                <div class="academica-form-panel {{ $userHasUploaded ? 'is-collapsed' : '' }}" id="academicaReplacePanel">
                    <h5 class="mb-2">{{ $userHasUploaded ? 'Edit / Ganti Proposal' : 'Form Upload Proposal' }}</h5>
                    <p class="text-muted mb-3">
                        {{ $userHasUploaded ? 'Pilih file PDF baru untuk mengganti file proposal lama. File lama akan otomatis diperbarui.' : 'File maksimal 10 MB dan wajib berformat PDF.' }}
                    </p>

                    @if($userHasUploaded && $userProposal)
                        <div class="alert alert-info border-0">
                            <div class="fw-semibold mb-1">File saat ini</div>
                            <div>{{ $userProposal->filename }}</div>
                            <small class="text-muted">Saat Anda simpan file baru, file lama akan digantikan.</small>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('mgmp.academica.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="proposal" class="form-label">Pilih file PDF proposal</label>
                            <input type="file" name="proposal" id="proposal" accept="application/pdf" class="form-control" required>
                            @error('proposal') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-primary">
                                <i class="bx {{ $userHasUploaded ? 'bx-refresh' : 'bx-upload' }}"></i>
                                {{ $userHasUploaded ? 'Simpan File Baru' : 'Upload Proposal' }}
                            </button>
                            @if($userHasUploaded)
                                <button type="button" class="btn btn-outline-secondary" id="cancelReplaceProposal">
                                    <i class="bx bx-x"></i> Batal
                                </button>
                            @endif
                        </div>
                    </form>
                </div>

                @if($userHasUploaded)
                    <div class="academica-placeholder-panel" id="academicaReplacePlaceholder">
                        <div class="academica-action-card h-100 d-flex flex-column justify-content-center">
                            <span class="academica-summary-label mb-2">Aksi Cepat</span>
                            <h5 class="mb-2">Perbarui Proposal</h5>
                            <p class="text-muted mb-3">Klik tombol edit untuk mengganti file proposal tanpa mengubah susunan data lain di halaman ini.</p>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-primary" id="openReplaceProposalFromPlaceholder">
                                    <i class="bx bx-refresh"></i> Ganti File Sekarang
                                </button>
                                <span class="mgmp-chip">PDF maksimal 10 MB</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<div class="card mgmp-panel mb-4">
    <div class="card-body">
        <div class="academica-section-header mb-4">
            <div>
                <div class="mgmp-kicker text-success mb-2">Riset</div>
                <h5 class="mb-1">Laporan Update Riset</h5>
                <p class="text-muted mb-0">Riwayat progres riset dan dokumen pendukung tersusun dalam satu alur kerja.</p>
            </div>
            <span class="mgmp-chip">{{ isset($resetUpdates) ? $resetUpdates->count() : 0 }} update</span>
        </div>

        @if($userHasUploaded && $userProposal)
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="academica-action-card h-100">
                        <div class="d-flex align-items-start gap-3">
                            <div class="mgmp-icon-bubble">
                                <i class="bx bx-task"></i>
                            </div>
                            <div class="grow">
                                <span class="academica-summary-label mb-2 d-inline-block">Aksi Utama</span>
                                <h6 class="mb-2">Tambah Laporan Update</h6>
                                <p class="text-muted mb-3">Gunakan modal untuk menambah progres baru tanpa membuat halaman utama terasa padat.</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#resetUpdateModal">
                                        <i class="bx bx-plus-circle"></i> Tambah Laporan Update
                                    </button>
                                    <span class="mgmp-chip">{{ $academicaSummary['total_updates'] ?? 0 }} laporan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0">Riwayat Progres Riset</h6>
                        @if(isset($resetUpdates) && $resetUpdates->isNotEmpty())
                            <small class="text-muted">Terbaru: {{ $resetUpdates->first()->progress_percent }}%</small>
                        @endif
                    </div>

                    @if(isset($resetUpdates) && $resetUpdates->isNotEmpty())
                        <div class="academica-reset-list">
                            @foreach($resetUpdates as $update)
                                <div class="academica-reset-card">
                                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
                                        <div>
                                            <span class="academica-summary-label mb-2 d-inline-block">Laporan Progres</span>
                                            <h6 class="mb-1">{{ $update->title }}</h6>
                                            <small class="text-muted d-block">
                                                {{ $update->created_at->format('d M Y H:i') }}
                                                @if($update->updated_at && $update->updated_at->ne($update->created_at))
                                                    • diperbarui {{ $update->updated_at->format('d M Y H:i') }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="academica-progress-pill">
                                            <strong>{{ $update->progress_percent }}%</strong>
                                            <span>progres</span>
                                        </div>
                                    </div>

                                    <div class="academica-progress-track mb-3">
                                        <div class="academica-progress-bar" style="width: {{ max(0, min(100, (int) $update->progress_percent)) }}%;"></div>
                                    </div>

                                    <p class="text-muted mb-3">{{ $update->progress_note }}</p>

                                    @if($update->files->isNotEmpty())
                                        <div class="academica-attachment-wrap">
                                            <div class="academica-attachment-label">Lampiran</div>
                                            <div class="d-flex flex-wrap gap-2">
                                            @foreach($update->files as $file)
                                                <a href="{{ url('/uploads/' . $file->path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bx bx-paperclip me-1"></i>{{ \Illuminate\Support\Str::limit($file->original_name, 28) }}
                                                </a>
                                            @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <small class="text-muted">Tidak ada lampiran pada update ini.</small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="mgmp-empty-state py-5">
                            <i class="bx bx-timer"></i>
                            <strong>Belum ada update riset</strong>
                            <small>Tambahkan progres riset pertama Anda agar riwayat pengerjaan mulai tercatat.</small>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="alert alert-warning mb-0">
                <i class="bx bx-info-circle me-2"></i>Upload proposal utama terlebih dahulu. Setelah itu barulah Anda bisa menambahkan update riset dan lampiran progres.
            </div>
        @endif
    </div>
</div>

<div class="card mgmp-panel mb-4">
    <div class="card-body">
        <div class="academica-section-header mb-4">
            <div>
                <div class="mgmp-kicker text-success mb-2">Artikel</div>
                <h5 class="mb-1">Upload Artikel PDF</h5>
                <p class="text-muted mb-0">Artikel PDF dikelola terpisah setelah alur laporan update riset mulai berjalan.</p>
            </div>
            <span class="mgmp-chip">{{ $canUploadArticle ? 'Aktif' : 'Menunggu update riset' }}</span>
        </div>

        @if($userHasUploaded && $userProposal)
            @if($canUploadArticle)
                <div class="row g-4 align-items-start">
                    <div class="col-lg-5">
                        <div class="academica-article-box h-100">
                            <div class="d-flex align-items-start gap-3">
                                <div class="mgmp-icon-bubble">
                                    <i class="bx bx-file"></i>
                                </div>
                                <div class="grow">
                                    <span class="academica-summary-label mb-2 d-inline-block">Status Artikel</span>
                                    <div class="fw-semibold text-dark mb-1">
                                        {{ $userProposal->article_filename ? 'Artikel saat ini' : 'Belum ada artikel PDF' }}
                                    </div>
                                    @if($userProposal->article_path)
                                        <small class="text-muted d-block mb-3">
                                            {{ $userProposal->article_filename }}
                                        </small>
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="{{ url('/uploads/' . $userProposal->article_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bx bx-show me-1"></i> Lihat Artikel
                                            </a>
                                        </div>
                                    @else
                                        <small class="text-muted d-block">
                                            Artikel PDF belum diupload. Anda sekarang sudah bisa menambahkan file artikel secara terpisah.
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="academica-action-card h-100">
                            <h6 class="mb-2">{{ $userProposal->article_path ? 'Kelola Artikel PDF' : 'Upload Artikel Dalam Modal' }}</h6>
                            <p class="text-muted mb-3">Upload artikel PDF sekarang ditampilkan melalui modal agar area konten utama tetap rapi.</p>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#articleUploadModal">
                                    <i class="bx bx-upload"></i>
                                    {{ $userProposal->article_path ? 'Perbarui Artikel PDF' : 'Upload Artikel PDF' }}
                                </button>
                                @if($userProposal->article_path)
                                    <a href="{{ url('/uploads/' . $userProposal->article_path) }}" target="_blank" class="btn btn-outline-primary">
                                        <i class="bx bx-show"></i> Lihat Artikel
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="bx bx-info-circle me-2"></i>Fitur upload artikel PDF akan aktif setelah Anda mengirim minimal satu laporan update riset.
                </div>
            @endif
        @else
            <div class="alert alert-warning mb-0">
                <i class="bx bx-info-circle me-2"></i>Upload proposal utama terlebih dahulu. Setelah itu kirim update riset, lalu panel upload artikel PDF akan aktif.
            </div>
        @endif
    </div>
</div>

{{-- <div class="card mgmp-panel">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h5 class="mb-1">Daftar Proposal</h5>
                <p class="text-muted mb-0">Proposal yang sudah diunggah oleh anggota.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table id="datatable-academica" class="table table-bordered dt-responsive nowrap w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Pengupload</th>
                        <th>File</th>
                        <th>Diunggah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposals as $index => $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->user->name ?? 'User ID ' . $p->user_id }}</td>
                        <td>{{ $p->filename }}</td>
                        <td>{{ $p->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ url('/uploads/' . $p->path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="mgmp-empty-state">
                                <i class="bx bx-file-blank"></i>
                                <strong>Belum ada proposal</strong>
                                <small>Proposal yang diunggah akan tampil di sini.</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div> --}}
</div>

<div class="modal fade" id="resetUpdateModal" tabindex="-1" aria-labelledby="resetUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1" id="resetUpdateModalLabel">Upload Laporan Update Riset</h5>
                    <small class="text-muted">Isi progres terbaru dan lampiran pendukung dalam satu modal.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('mgmp.academica.reset-update.store') }}" enctype="multipart/form-data" id="resetUpdateFormModal">
                    @csrf
                    <div class="mb-3">
                        <label for="reset_title_modal" class="form-label">Judul Progres</label>
                        <input type="text" name="title" id="reset_title_modal" class="form-control" value="{{ old('title') }}" placeholder="Contoh: Progress penyusunan" required>
                        @error('title') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="progress_percent_modal" class="form-label">Progres pengerjaan (%)</label>
                        <input type="number" min="0" max="100" name="progress_percent" id="progress_percent_modal" class="form-control" value="{{ old('progress_percent', 0) }}" required>
                        @error('progress_percent') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="progress_note_modal" class="form-label">Keterangan progres</label>
                        <textarea name="progress_note" id="progress_note_modal" rows="4" class="form-control" placeholder="Jelaskan sudah sampai tahap mana reset dikerjakan, kendala, atau target berikutnya." required>{{ old('progress_note') }}</textarea>
                        @error('progress_note') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reset_attachments_modal" class="form-label">Lampiran pendukung</label>
                        <input type="file" name="attachments[]" id="reset_attachments_modal" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip">
                        <small class="text-muted d-block mt-1">Boleh upload lebih dari satu file. Format umum dokumen/gambar, maksimal 10 MB per file.</small>
                        <small class="text-muted d-block mt-1" id="resetAttachmentInfo"></small>
                        @error('attachments') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        @error('attachments.*') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="resetUpdateFormModal" class="btn btn-primary">
                    <i class="bx bx-save"></i> Simpan Update Riset
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="articleUploadModal" tabindex="-1" aria-labelledby="articleUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1" id="articleUploadModalLabel">Upload Artikel PDF</h5>
                    <small class="text-muted">Kelola artikel PDF secara terpisah tanpa memenuhi area halaman utama.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('mgmp.academica.article-upload') }}" enctype="multipart/form-data" id="articleUploadFormModal">
                    @csrf
                    <div class="mb-3">
                        <label for="article_file_modal" class="form-label">Pilih file artikel PDF</label>
                        <input type="file" name="article_file" id="article_file_modal" class="form-control" accept=".pdf,application/pdf" required>
                        <small class="text-muted d-block mt-1">Gunakan file PDF artikel final atau draft terbaru. Maksimal 10 MB.</small>
                        <small class="text-muted d-block mt-1" id="articleFileInfo"></small>
                        @error('article_file') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="articleUploadFormModal" class="btn btn-primary">
                    <i class="bx bx-upload"></i>
                    {{ $userProposal?->article_path ? 'Perbarui Artikel PDF' : 'Upload Artikel PDF' }}
                </button>
            </div>
        </div>
    </div>
</div>

@else
<div class="alert alert-danger text-center">
    <h4>Akses Ditolak</h4>
    <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
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
<script src="{{ asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    $('#article_file_modal').on('change', function () {
        const fileName = this.files && this.files[0] ? this.files[0].name : '';
        $('#articleFileInfo').text(fileName ? 'File artikel dipilih: ' + fileName : '');
    });

    $('#reset_attachments_modal').on('change', function () {
        const count = this.files ? this.files.length : 0;
        $('#resetAttachmentInfo').text(count > 0 ? count + ' file dipilih.' : '');
    });

    function openReplacePanel() {
        $('#academicaReplacePanel').removeClass('is-collapsed');
        $('#academicaReplacePlaceholder').hide();
        $('#proposal').trigger('focus');
    }

    function closeReplacePanel() {
        $('#academicaReplacePanel').addClass('is-collapsed');
        $('#academicaReplacePlaceholder').show();
        $('#proposal').val('');
    }

    $('#toggleReplaceProposal, #openReplaceProposalFromPlaceholder').on('click', function () {
        openReplacePanel();
    });

    $('#cancelReplaceProposal').on('click', function () {
        closeReplacePanel();
    });

    if ($.fn.DataTable.isDataTable('#datatable-academica')) {
        $('#datatable-academica').DataTable().destroy();
    }

    let table = $("#datatable-academica").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        destroy: true,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-academica_wrapper .col-md-6:eq(0)');

    const shouldOpenResetModal = @json($showResetUpdateModal);
    const shouldOpenArticleModal = @json($showArticleModal);

    if (shouldOpenResetModal) {
        const resetModalEl = document.getElementById('resetUpdateModal');
        if (resetModalEl) {
            bootstrap.Modal.getOrCreateInstance(resetModalEl).show();
        }
    }

    if (shouldOpenArticleModal) {
        const articleModalEl = document.getElementById('articleUploadModal');
        if (articleModalEl) {
            bootstrap.Modal.getOrCreateInstance(articleModalEl).show();
        }
    }
});
</script>

<style>
    .academica-section-header {
        align-items: flex-start;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: space-between;
    }

    .academica-metric-card {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbf9 100%);
    }

    .academica-metric-label {
        color: var(--mgmp-muted);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
    }

    .academica-metric-number {
        color: var(--mgmp-teal);
        font-size: 28px;
        font-weight: 800;
        line-height: 1.1;
        margin: 4px 0;
    }

    .academica-metric-value {
        color: var(--mgmp-ink);
        font-size: 18px;
        font-weight: 700;
        line-height: 1.2;
        margin: 4px 0;
    }

    .academica-summary-badge {
        align-items: flex-end;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .academica-summary-badge strong {
        color: var(--mgmp-teal);
        font-size: 28px;
        line-height: 1;
    }

    .academica-summary-badge span {
        color: var(--mgmp-muted);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .academica-summary-card {
        background: linear-gradient(180deg, #ffffff 0%, #f7fbf8 100%);
        border: 1px solid #e5eee9;
        border-radius: 16px;
        display: grid;
        gap: 6px;
        height: 100%;
        padding: 16px;
    }

    .academica-summary-label {
        color: var(--mgmp-green);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
    }

    .academica-summary-card strong {
        color: var(--mgmp-ink);
        font-size: 16px;
    }

    .academica-summary-card small {
        color: var(--mgmp-muted);
        line-height: 1.5;
    }

    .academica-resource-card,
    .academica-action-card {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbf9 100%);
        border: 1px solid #e5eee9;
        border-radius: 18px;
        padding: 18px;
    }

    .academica-resource-title {
        color: var(--mgmp-green);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .05em;
        margin-bottom: 6px;
        text-transform: uppercase;
    }

    .academica-form-panel.is-collapsed {
        display: none;
    }

    .academica-placeholder-panel {
        display: block;
    }

    .academica-reset-list {
        display: grid;
        gap: 14px;
    }

    .academica-reset-card {
        background: linear-gradient(180deg, #ffffff 0%, #f7fbf8 100%);
        border: 1px solid #e5eee9;
        border-radius: 16px;
        box-shadow: inset 3px 0 0 rgba(14, 133, 73, 0.14);
        padding: 16px;
    }

    .academica-article-box {
        background: linear-gradient(180deg, #fefcf4 0%, #f8fbf7 100%);
        border: 1px solid #e6ebd7;
        border-radius: 14px;
        padding: 16px;
    }

    .academica-progress-pill {
        align-items: flex-end;
        background: rgba(14, 133, 73, 0.08);
        border: 1px solid rgba(14, 133, 73, 0.14);
        border-radius: 14px;
        display: inline-flex;
        flex-direction: column;
        min-width: 86px;
        padding: 10px 12px;
    }

    .academica-progress-pill strong {
        color: var(--mgmp-teal);
        font-size: 20px;
        line-height: 1;
    }

    .academica-progress-pill span {
        color: var(--mgmp-muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        margin-top: 4px;
        text-transform: uppercase;
    }

    .academica-attachment-wrap {
        border-top: 1px dashed #d9e6df;
        padding-top: 12px;
    }

    .academica-attachment-label {
        color: var(--mgmp-muted);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .05em;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .academica-progress-track {
        background: #e8f1ec;
        border-radius: 999px;
        height: 10px;
        overflow: hidden;
    }

    .academica-progress-bar {
        background: linear-gradient(90deg, #004b4c, #0e8549);
        border-radius: 999px;
        height: 100%;
    }
</style>

@endsection
