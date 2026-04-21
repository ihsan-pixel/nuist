@extends('layouts.master')

@section('title')
    DPS (Dewan Pengawas Sekolah)
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
    @slot('title') DPS (Dewan Pengawas Sekolah) @endslot
@endcomponent

<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="text-muted small">Jumlah Akun DPS</div>
                        <div class="fw-bold" style="font-size: 22px;">{{ $stats['akun_dps'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="text-muted small">Jumlah Anggota DPS (Record)</div>
                        <div class="fw-bold" style="font-size: 22px;">{{ $stats['anggota_dps'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="text-muted small">Jumlah Sekolah Terkait</div>
                        <div class="fw-bold" style="font-size: 22px;">{{ $stats['sekolah_terkait'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3 d-flex justify-content-end gap-2">
            <a href="{{ route('dps.accounts.export') }}" class="btn btn-outline-dark">
                <i class="bx bx-download"></i> Unduh Akun DPS (Excel)
            </a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportDps">
                <i class="bx bx-upload"></i> Import Excel
            </button>
            <a href="{{ route('dps.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Tambah DPS
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('dps_credentials_token'))
            <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2" role="alert">
                <div>
                    <div class="fw-semibold">File akun DPS hasil import tersedia.</div>
                    <div class="small text-muted">Berisi email dan password untuk baris yang berhasil membuat akun baru.</div>
                </div>
                <a class="btn btn-sm btn-outline-dark" href="{{ route('dps.credentials.download', session('dps_credentials_token')) }}">
                    <i class="bx bx-download"></i> Download CSV Akun
                </a>
            </div>
        @endif

        @if(session('dps_import_errors'))
            <div class="alert alert-danger">
                <div class="fw-semibold mb-2">Sebagian baris gagal diproses (maks 30 ditampilkan):</div>
                <ul class="mb-0">
                    @foreach(session('dps_import_errors') as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th style="width: 110px;">SCOD</th>
                        <th>Nama Sekolah</th>
                        <th>Daftar DPS</th>
                        <th>Unsur DPS</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($madrasahs as $index => $madrasah)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $madrasah->scod ?? '-' }}</span></td>
                            <td class="fw-semibold">{{ $madrasah->name ?? '-' }}</td>
                            <td>
                                <ul class="list-unstyled mb-0" style="min-width:260px;">
                                    @foreach($madrasah->dpsMembers as $m)
                                        <li class="mb-2">
                                            <div>{{ $m->nama }}</div>
                                            <div class="text-muted small">Periode: {{ $m->periode }}</div>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($madrasah->dpsMembers as $m)
                                        <li class="mb-2">{{ $m->unsur }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <a href="{{ route('dps.show', $madrasah->id) }}" class="btn btn-sm btn-outline-primary">
                                    Kelola
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data DPS.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Import DPS -->
<div class="modal fade" id="modalImportDps" tabindex="-1" aria-labelledby="modalImportDpsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('dps.import') }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalImportDpsLabel">Import DPS (Excel)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">File Excel (.xlsx / .xls / .csv)</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="alert alert-info mb-0">
                    <div class="fw-semibold mb-1">Format kolom (heading):</div>
                    <div class="small">
                        <code>scod</code>, <code>nama_dps</code>, <code>unsur_dps</code>, <code>periode</code>
                    </div>
                    <div class="small mt-2">
                        Nilai <code>unsur_dps</code> boleh baru (akan tersimpan sesuai Excel dan otomatis muncul sebagai pilihan unsur di form).
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">
                    <i class="bx bx-upload"></i> Import
                </button>
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
