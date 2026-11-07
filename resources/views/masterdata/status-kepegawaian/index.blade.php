@extends('layouts.master')

@section('title')
    Status Kepegawaian
@endsection

@section('css')
    <link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <style>
        body:has(#datatable-buttons){background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}body:has(#datatable-buttons) .card{border:1px solid rgba(255,255,255,.86);border-radius:22px;background:rgba(255,255,255,.68);backdrop-filter:blur(18px);box-shadow:0 8px 26px rgba(32,70,78,.08)}body:has(#datatable-buttons) .card-header{border-bottom:1px solid #e5edef;background:transparent;padding:22px 24px}body:has(#datatable-buttons) .card-body{padding:20px 24px 24px}body:has(#datatable-buttons) .table-responsive{border:1px solid #e5edef;border-radius:16px;overflow-x:auto}body:has(#datatable-buttons) table.dataTable{table-layout:auto;min-width:620px}body:has(#datatable-buttons) table.dataTable thead th{background:#f1f6f6;color:#192d37;border:0;border-bottom:1px solid #e5edef;font-size:11px;padding:13px 14px;white-space:nowrap}body:has(#datatable-buttons) table.dataTable tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;white-space:normal!important;overflow-wrap:anywhere}body:has(#datatable-buttons) table.dataTable th:first-child,body:has(#datatable-buttons) table.dataTable td:first-child{width:60px}body:has(#datatable-buttons) table.dataTable th:nth-child(2),body:has(#datatable-buttons) table.dataTable td:nth-child(2){min-width:240px}body:has(#datatable-buttons) table.dataTable th:last-child,body:has(#datatable-buttons) table.dataTable td:last-child{width:135px}body:has(#datatable-buttons) .dt-buttons{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}body:has(#datatable-buttons) .dt-button{border:0!important;border-radius:8px!important;color:#fff!important;font-size:11px;font-weight:600;padding:.46rem .8rem}body:has(#datatable-buttons) .buttons-copy{background:linear-gradient(90deg,#0f766e,#14b8a6)!important}body:has(#datatable-buttons) .buttons-excel{background:linear-gradient(90deg,#2563eb,#60a5fa)!important}body:has(#datatable-buttons) .buttons-pdf{background:linear-gradient(90deg,#7c3aed,#a78bfa)!important}body:has(#datatable-buttons) .buttons-print{background:linear-gradient(90deg,#d97706,#fbbf24)!important}body:has(#datatable-buttons) .buttons-colvis{background:linear-gradient(90deg,#db2777,#f472b6)!important}body:has(#datatable-buttons) div.dt-button-collection{padding:10px!important;border-radius:16px!important;background:#f8fafb!important}body:has(#datatable-buttons) div.dt-button-collection button.dt-button{background:#fff!important;background-image:none!important;color:#192d37!important;border:0!important;border-radius:14px!important;width:100%!important;margin-bottom:6px;text-align:left}body:has(#datatable-buttons) .modal{z-index:2000!important;pointer-events:none}body:has(#datatable-buttons) .modal.show{pointer-events:auto}body:has(#datatable-buttons) .modal.show .modal-dialog{position:relative;z-index:2001;pointer-events:auto}@media(max-width:767px){body:has(#datatable-buttons) .card-body{padding:14px}body:has(#datatable-buttons) .card-header{padding:18px}body:has(#datatable-buttons) .dt-buttons{gap:5px}}
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Master Data @endslot
        @slot('title') Status Kepegawaian @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="bx bx-id-card me-2"></i>Status Kepegawaian
                    </h4>
                </div>
                <div class="card-body">

                    <div class="mb-3 d-flex justify-content-end gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahStatusKepegawaian">
                            <i class="bx bx-plus"></i> Tambah Status Kepegawaian
                        </button>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportStatusKepegawaian">
                            <i class="bx bx-upload"></i> Import Data
                        </button>
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
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Status Kepegawaian</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statusKepegawaian as $index => $status)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $status->name }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditStatusKepegawaian{{ $status->id }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('status-kepegawaian.destroy', $status->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin hapus status kepegawaian ini?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit Status Kepegawaian -->
                            <div class="modal fade" id="modalEditStatusKepegawaian{{ $status->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('status-kepegawaian.update', $status->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Status Kepegawaian</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Nama Status Kepegawaian</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $status->name }}" required>
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
                                <td colspan="3" class="text-center p-4">
                                    <div class="alert alert-info d-inline-block text-center" role="alert">
                                        <i class="bx bx-info-circle bx-lg me-2"></i>
                                        <strong>Belum ada data Status Kepegawaian</strong><br>
                                        <small>Silakan tambahkan data status kepegawaian terlebih dahulu untuk melanjutkan.</small>
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

    <!-- Modal Tambah Status Kepegawaian -->
    <div class="modal fade" id="modalTambahStatusKepegawaian" tabindex="-1" aria-labelledby="modalTambahStatusKepegawaianLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('status-kepegawaian.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahStatusKepegawaianLabel">Tambah Status Kepegawaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Status Kepegawaian</label>
                            <input type="text" name="name" class="form-control" required>
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

    <!-- Modal Import Status Kepegawaian -->
<div class="modal fade" id="modalImportStatusKepegawaian" tabindex="-1" aria-labelledby="modalImportStatusKepegawaianLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('status-kepegawaian.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalImportStatusKepegawaianLabel">Import Data Status Kepegawaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Pilih File (Excel/CSV)</label>
                            <input type="file" name="file" class="form-control" accept=".xls,.xlsx,.csv" required>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">
                                Gunakan template file sesuai format data status kepegawaian.
                                <a href="{{ asset('template/status_kepegawaian_template.xlsx') }}" download>Download Template Excel</a>
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </div>
            </form>
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
            $('.modal').appendTo(document.body);
            $('#datatable-buttons tbody tr').has('td[colspan]').remove();
            let table = $("#datatable-buttons").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ["copy", "excel", "pdf", "print", "colvis"],
                language: { emptyTable: 'Belum ada data status kepegawaian.', zeroRecords: 'Data tidak ditemukan.' }
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection

