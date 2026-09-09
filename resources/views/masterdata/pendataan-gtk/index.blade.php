@extends('layouts.master')

@section('title', 'Pendataan GTK')

@section('css')
<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    #gtk-schools-table td { vertical-align: middle; }
    #gtk-schools-table .gtk-school-name { min-width: 200px; white-space: normal; }
    #gtk-schools-table .gtk-school-address { max-width: 340px; white-space: normal; }
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Pendataan GTK @endslot
@endcomponent

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h4 class="card-title mb-2">Pendataan GTK</h4>
                <p class="text-muted mb-0">Kelola pendataan GTK dan unduh data seluruh sekolah atau per sekolah.</p>
            </div>
            <a href="{{ route('pendataan-gtk.export') }}" class="btn btn-success">
                <i class="bx bx-download me-1"></i> Export Excel Semua GTK
            </a>
        </div>

        <div class="d-flex flex-wrap gap-3 border rounded bg-light p-3 mb-4">
            <span><i class="bx bx-buildings text-primary me-1"></i> <strong>{{ $madrasahs->count() }}</strong> sekolah</span>
            <span><i class="bx bx-group text-primary me-1"></i> <strong>{{ number_format($madrasahs->sum('tenaga_pendidik_users_count'), 0, ',', '.') }}</strong> tenaga pendidik</span>
        </div>

        <div class="table-responsive">
            <table id="gtk-schools-table" class="table table-bordered table-hover nowrap w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>SCOD</th>
                        <th>Nama Madrasah / Sekolah</th>
                        <th>Kabupaten</th>
                        <th>Alamat</th>
                        <th>Jumlah GTK</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($madrasahs as $madrasah)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $madrasah->scod ?: '-' }}</td>
                            <td class="gtk-school-name fw-semibold">{{ $madrasah->name }}</td>
                            <td>{{ $madrasah->kabupaten ?: '-' }}</td>
                            <td class="gtk-school-address text-muted">{{ $madrasah->alamat ?: '-' }}</td>
                            <td class="text-center">{{ $madrasah->tenaga_pendidik_users_count }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('pendataan-gtk.show', $madrasah->id) }}" class="btn btn-primary btn-sm" aria-label="Lihat GTK {{ $madrasah->name }}">
                                        <i class="bx bx-show me-1"></i> Detail GTK
                                    </a>
                                    <a href="{{ route('pendataan-gtk.export-school', $madrasah->id) }}" class="btn btn-success btn-sm" aria-label="Export Excel {{ $madrasah->name }}">
                                        <i class="bx bx-download me-1"></i> Export Excel
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(function () {
    $('#gtk-schools-table').DataTable({
        autoWidth: false,
        pageLength: 25,
        order: [[0, 'asc']],
        columnDefs: [{ targets: 6, orderable: false, searchable: false }],
        language: {
            search: 'Cari sekolah:',
            searchPlaceholder: 'Nama, SCOD, kabupaten...',
            lengthMenu: 'Tampilkan _MENU_ sekolah',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ sekolah',
            infoEmpty: 'Belum ada sekolah',
            infoFiltered: '(disaring dari _MAX_ sekolah)',
            zeroRecords: 'Sekolah tidak ditemukan',
            emptyTable: 'Belum ada data sekolah',
            paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' }
        }
    });
});
</script>
@endsection
