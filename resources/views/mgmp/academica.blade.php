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

<div class="card mb-4">
    <div class="card-body">

        <div class="row mb-3">
            <div class="col text-end">
                @if($userHasUploaded)
                    <div class="text-muted">Anda sudah mengupload proposal. Hanya satu upload per user.</div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-3">Upload Proposal PDF</h5>
                @if($userHasUploaded)
                    <div class="mb-2">
                        @if($userProposal)
                        <a href="{{ url('/uploads/' . $userProposal->path) }}" target="_blank" class="btn btn-outline-primary">
                            <i class="bx bx-file"></i> {{ $userProposal->filename }}
                        </a>
                        @endif
                    </div>
                @else
                    <form method="POST" action="{{ route('mgmp.academica.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            <label for="proposal" class="form-label">Pilih file PDF proposal</label>
                            <input type="file" name="proposal" id="proposal" accept="application/pdf" class="form-control" required>
                            @error('proposal') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <button class="btn btn-primary">Upload Proposal</button>
                    </form>
                @endif
            </div>
        </div>

    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="mb-3">Daftar Proposal</h5>
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
                        <td colspan="5" class="text-center">Belum ada proposal.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
