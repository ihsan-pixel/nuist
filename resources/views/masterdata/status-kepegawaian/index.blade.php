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
            let table = $("#datatable-buttons").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ["copy", "excel", "pdf", "print", "colvis"]
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection

