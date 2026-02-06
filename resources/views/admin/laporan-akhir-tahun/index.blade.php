@extends('layouts.master')

@section('title')
    Data Laporan Akhir Tahun Kepala Sekolah
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
    $isAllowed = in_array($userRole, ['super_admin']);
@endphp
@if($isAllowed)
@component('components.breadcrumb')
    @slot('li_1') Admin @endslot
    @slot('title') Data Laporan Akhir Tahun Kepala Sekolah @endslot
@endcomponent

<div class="card mb-4">
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

        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Kepala Sekolah</th>
                        <th>Gelar</th>
                        <th>Nama Satpen</th>
                        <th>Tahun Pelaporan</th>
                        <th>Status</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporans as $index => $laporan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $laporan->nama_kepala_sekolah_madrasah }}</td>
                        <td>{{ $laporan->gelar }}</td>
                        <td>{{ $laporan->nama_satpen }}</td>
                        <td>{{ $laporan->tahun_pelaporan }}</td>
                        <td>
                            @if($laporan->status == 'published')
                                <span class="badge bg-success">Published</span>
                            @elseif($laporan->status == 'draft')
                                <span class="badge bg-warning">Draft</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($laporan->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $laporan->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.laporan-akhir-tahun.pdf', $laporan->id) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-primary"
                                   title="Lihat PDF">
                                    <i class="bx bx-file-pdf me-1"></i> PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center p-4">
                            <div class="alert alert-info d-inline-block text-center" role="alert">
                                <i class="bx bx-info-circle bx-lg me-2"></i>
                                <strong>Belum ada data Laporan Akhir Tahun</strong><br>
                                <small>Silakan tunggu kepala sekolah mengisi laporan akhir tahun.</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Laporan Completion Report -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Laporan Pengisian Laporan Akhir Tahun per Madrasah</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-striped">
            <thead class="bg-light">
                <tr>
                    <th rowspan="2" class="text-center align-middle" style="position: sticky; left: 0; background: #f8f9fa; z-index: 10;">SCOD</th>
                    <th rowspan="2" class="text-center align-middle" style="position: sticky; left: 60px; background: #f8f9fa; z-index: 10;">Nama Sekolah / Madrasah</th>
                    <th colspan="3" class="text-center">Status Laporan</th>
                    <th rowspan="2" class="text-center align-middle">Persentase Pengisian (%)</th>
                </tr>
                <tr>
                    <th class="text-center">Sudah Isi</th>
                    <th class="text-center">Belum Isi</th>
                    <th class="text-center">Total Kepala Sekolah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporanData as $kabupaten)
                <tr class="bg-info">
                    <td colspan="6" class="font-weight-bold text-center">{{ $kabupaten['kabupaten'] }}</td>
                </tr>
                @foreach(collect($kabupaten['madrasahs'])->sortBy(function($madrasah) { return (int)$madrasah['scod']; }) as $madrasah)
                <tr>
                    <td class="text-center" style="position: sticky; left: 0; background: white;">{{ $madrasah['scod'] }}</td>
                    <td style="position: sticky; left: 60px; background: white;">{{ $madrasah['nama'] }}</td>
                    <td class="text-center">{{ $madrasah['sudah'] }}</td>
                    <td class="text-center">{{ $madrasah['belum'] }}</td>
                    <td class="text-center">{{ $madrasah['total'] }}</td>
                    <td class="text-center font-weight-bold">{{ number_format($madrasah['persentase'], 2) }}%</td>
                </tr>
                @endforeach
                <tr class="bg-warning font-weight-bold">
                    <td colspan="2" class="text-center" style="position: sticky; left: 0; background: #fff3cd;">TOTAL {{ $kabupaten['kabupaten'] }}</td>
                    <td class="text-center">{{ $kabupaten['total_sudah'] }}</td>
                    <td class="text-center">{{ $kabupaten['total_belum'] }}</td>
                    <td class="text-center">{{ $kabupaten['total_kepala_sekolah'] }}</td>
                    <td class="text-center">{{ number_format($kabupaten['persentase'], 2) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@else
<div class="alert alert-danger text-center">
    <h4>Akses Ditolak</h4>
    <p>Hanya Super Admin yang dapat mengakses halaman ini.</p>
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
    let table = $("#datatable-buttons").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ session('error') }}',
            timer: 5000,
            timerProgressBar: true,
            showConfirmButton: true
        });
    @endif
});
</script>
@endsection
