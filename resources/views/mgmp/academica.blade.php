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
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-7">
                <h5 class="mb-2">{{ $userHasUploaded ? 'Ganti Proposal' : 'Form Upload Proposal' }}</h5>
                <p class="text-muted mb-3">
                    {{ $userHasUploaded ? 'Pilih file PDF baru untuk mengganti proposal lama. File lama akan diganti.' : 'File maksimal 10 MB dan wajib berformat PDF.' }}
                </p>

                <form method="POST" action="{{ route('mgmp.academica.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="proposal" class="form-label">Pilih file PDF proposal</label>
                        <input type="file" name="proposal" id="proposal" accept="application/pdf" class="form-control" required>
                        @error('proposal') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <button class="btn btn-primary">
                        <i class="bx {{ $userHasUploaded ? 'bx-refresh' : 'bx-upload' }}"></i>
                        {{ $userHasUploaded ? 'Perbarui Proposal' : 'Upload Proposal' }}
                    </button>
                </form>
            </div>
        </div>

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

@endsection
