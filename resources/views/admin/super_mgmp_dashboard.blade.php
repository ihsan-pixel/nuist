@extends('layouts.master')

@section('title')
    Dashboard MGMP - Super Admin
@endsection

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />

<!-- DataTables -->
<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Admin @endslot
    @slot('title') Dashboard MGMP @endslot
@endcomponent

<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="mb-3">Data MGMP</h5>
                <div class="table-responsive">
                    <table id="datatable-mgmp" class="table table-bordered dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Grup</th>
                                <th>Owner (User ID)</th>
                                <th>Member Count</th>
                                <th>Logo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mgmpGroups as $group)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $group->name }}</td>
                                <td>{{ $group->user_id }}</td>
                                <td>{{ $group->members->count() }}</td>
                                <td>
                                    @if($group->logo)
                                    <a href="{{ url('/uploads/' . $group->logo) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center">Belum ada data MGMP.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="mb-3">Data Anggota MGMP</h5>
                <div class="table-responsive">
                    <table id="datatable-anggota" class="table table-bordered dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Grup MGMP</th>
                                <th>Sekolah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $m)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $m->name }}</td>
                                <td>{{ $m->email }}</td>
                                <td>{{ $m->mgmpGroup->name ?? '-' }}</td>
                                <td>{{ $m->sekolah ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center">Belum ada anggota MGMP.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Daftar Proposal Academica</h5>
                <div class="table-responsive">
                    <table id="datatable-academica-admin" class="table table-bordered dt-responsive nowrap w-100">
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
                            @forelse($proposals as $p)
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
                            <tr><td colspan="5" class="text-center">Belum ada proposal.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

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
$(document).ready(function () {
    ["#datatable-mgmp","#datatable-anggota","#datatable-academica-admin"].forEach(function(sel){
        if ($.fn.DataTable.isDataTable(sel)) { $(sel).DataTable().destroy(); }
        let table = $(sel).DataTable({ responsive: true, lengthChange: true, autoWidth: false, destroy: true, buttons: ["copy","excel","pdf","print","colvis"] });
        table.buttons().container().appendTo(sel + '_wrapper .col-md-6:eq(0)');
    });
});
</script>

@endsection
