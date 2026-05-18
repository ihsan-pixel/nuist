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
        <span class="mgmp-chip bg-white text-success">{{ $proposals->count() }} proposal</span>
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

        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <h5 class="mb-2">{{ $userHasUploaded ? 'Proposal Anda' : 'Upload Proposal PDF' }}</h5>
                <p class="text-muted mb-3">
                    {{ $userHasUploaded ? 'File yang sudah diunggah masih bisa diperbarui dengan file PDF baru.' : 'Unggah proposal akademik dalam format PDF.' }}
                </p>

                @if($userHasUploaded && $userProposal)
                    <div class="p-3 rounded-3 border bg-light">
                        <div class="d-flex align-items-start gap-3">
                            <div class="mgmp-icon-bubble">
                                <i class="bx bx-file"></i>
                            </div>
                            <div class="grow">
                                <div class="fw-semibold text-dark">{{ $userProposal->filename }}</div>
                                <small class="text-muted">Terakhir diperbarui {{ $userProposal->updated_at->format('d M Y H:i') }}</small>
                                <div class="mt-2">
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
                    <div class="p-3 rounded-3 border bg-light">
                        <div class="d-flex align-items-start gap-3">
                            <div class="mgmp-icon-bubble">
                                <i class="bx bx-upload"></i>
                            </div>
                            <div class="grow">
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
                        <div class="p-4 rounded-3 border bg-light h-100 d-flex flex-column justify-content-center">
                            <h5 class="mb-2">Edit / Ganti Proposal</h5>
                            <p class="text-muted mb-3">Klik tombol <strong>Edit / Ganti File</strong> di sebelah kiri untuk mengganti file proposal yang sudah diupload.</p>
                            <div>
                                <button type="button" class="btn btn-primary" id="openReplaceProposalFromPlaceholder">
                                    <i class="bx bx-refresh"></i> Ganti File Sekarang
                                </button>
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
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h5 class="mb-1">Laporan Update Reset</h5>
                <p class="text-muted mb-0">Catat progres pengerjaan reset dan unggah beberapa file pendukung dalam satu laporan.</p>
            </div>
            <span class="mgmp-chip">{{ isset($resetUpdates) ? $resetUpdates->count() : 0 }} update</span>
        </div>

        @if($userHasUploaded && $userProposal)
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="p-3 rounded-3 border bg-light h-100">
                        <h6 class="mb-3">Form Laporan Update Reset</h6>
                        <form method="POST" action="{{ route('mgmp.academica.reset-update.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="reset_title" class="form-label">Judul Progres</label>
                                <input
                                    type="text"
                                    name="title"
                                    id="reset_title"
                                    class="form-control"
                                    value="{{ old('title') }}"
                                    placeholder="Contoh: Progress penyusunan"
                                    required
                                >
                                @error('title') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="progress_percent" class="form-label">Progres pengerjaan (%)</label>
                                <input
                                    type="number"
                                    min="0"
                                    max="100"
                                    name="progress_percent"
                                    id="progress_percent"
                                    class="form-control"
                                    value="{{ old('progress_percent', 0) }}"
                                    required
                                >
                                @error('progress_percent') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="progress_note" class="form-label">Keterangan progres</label>
                                <textarea
                                    name="progress_note"
                                    id="progress_note"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Jelaskan sudah sampai tahap mana reset dikerjakan, kendala, atau target berikutnya."
                                    required
                                >{{ old('progress_note') }}</textarea>
                                @error('progress_note') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="reset_attachments" class="form-label">Lampiran pendukung</label>
                                <input
                                    type="file"
                                    name="attachments[]"
                                    id="reset_attachments"
                                    class="form-control"
                                    multiple
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip"
                                >
                                <small class="text-muted d-block mt-1">
                                    Boleh upload lebih dari satu file. Format umum dokumen/gambar, maksimal 10 MB per file.
                                </small>
                                <small class="text-muted d-block mt-1" id="resetAttachmentInfo"></small>
                                @error('attachments') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                @error('attachments.*') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <button class="btn btn-primary">
                                <i class="bx bx-save"></i> Simpan Update Reset
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0">Riwayat Progres Reset</h6>
                        @if(isset($resetUpdates) && $resetUpdates->isNotEmpty())
                            <small class="text-muted">Terbaru: {{ $resetUpdates->first()->progress_percent }}%</small>
                        @endif
                    </div>

                    @if(isset($resetUpdates) && $resetUpdates->isNotEmpty())
                        <div class="academica-reset-list">
                            @foreach($resetUpdates as $update)
                                <div class="academica-reset-card">
                                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-2">
                                        <div>
                                            <h6 class="mb-1">{{ $update->title }}</h6>
                                            <small class="text-muted">
                                                {{ $update->created_at->format('d M Y H:i') }}
                                                @if($update->updated_at && $update->updated_at->ne($update->created_at))
                                                    • diperbarui {{ $update->updated_at->format('d M Y H:i') }}
                                                @endif
                                            </small>
                                        </div>
                                        <span class="badge bg-primary-subtle text-primary">{{ $update->progress_percent }}%</span>
                                    </div>

                                    <div class="academica-progress-track mb-3">
                                        <div class="academica-progress-bar" style="width: {{ max(0, min(100, (int) $update->progress_percent)) }}%;"></div>
                                    </div>

                                    <p class="text-muted mb-3">{{ $update->progress_note }}</p>

                                    @if($update->files->isNotEmpty())
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($update->files as $file)
                                                <a href="{{ url('/uploads/' . $file->path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bx bx-paperclip me-1"></i>{{ \Illuminate\Support\Str::limit($file->original_name, 28) }}
                                                </a>
                                            @endforeach
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
                            <strong>Belum ada update reset</strong>
                            <small>Tambahkan progres reset pertama Anda agar riwayat pengerjaan mulai tercatat.</small>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="alert alert-warning mb-0">
                <i class="bx bx-info-circle me-2"></i>Upload proposal utama terlebih dahulu. Setelah itu barulah Anda bisa menambahkan update reset dan lampiran progres.
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    $('#reset_attachments').on('change', function () {
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
});
</script>

<style>
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
        padding: 16px;
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
