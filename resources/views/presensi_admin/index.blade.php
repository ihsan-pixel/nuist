@extends('layouts.master')

@section('title', 'Data Presensi')

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
    @slot('li_1') Presensi Admin @endslot
    @slot('title') Data Presensi @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-list-ul me-2"></i>Data Presensi
                </h4>
            </div>
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

                <div class="mb-3 d-flex justify-content-end">
                    <a href="{{ route('izin.index') }}" class="btn btn-info">
                        <i class="bx bx-mail-send"></i> Kelola Izin
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama User</th>
                                <th>Madrasah</th>
                                <th>Status Kepegawaian</th>
                                <th>Tanggal</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($presensis as $presensi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $presensi->user->name ?? '-' }}</td>
                                <td>{{ $presensi->user->madrasah->name ?? '-' }}</td>
                                <td>{{ $presensi->statusKepegawaian->name ?? '-' }}</td>
                                    <td>{{ $presensi->tanggal }}</td>
                                    <td>
                                        @if($presensi->waktu_masuk)
                                            {{ $presensi->tanggal->copy()->setTimeFromTimeString($presensi->waktu_masuk->format('H:i:s'))->format('Y-m-d H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($presensi->waktu_keluar)
                                            {{ $presensi->tanggal->copy()->setTimeFromTimeString($presensi->waktu_keluar->format('H:i:s'))->format('Y-m-d H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                <td>
                                    @if($presensi->status == 'hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif($presensi->status == 'terlambat')
                                        <span class="badge bg-warning">Terlambat</span>
                                    @elseif($presensi->status == 'izin')
                                        <span class="badge bg-info">Izin</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($presensi->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($presensi->keterangan && str_contains($presensi->keterangan, 'Terlambat'))
                                        <span class="text-danger">{{ $presensi->keterangan }}</span>
                                    @else
                                        {{ $presensi->keterangan }}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center p-4">
                                    <div class="alert alert-info d-inline-block text-center" role="alert">
                                        <i class="bx bx-info-circle bx-lg me-2"></i>
                                        <strong>Belum ada data presensi</strong><br>
                                        <small>Silakan tambahkan data presensi terlebih dahulu untuk melanjutkan.</small>
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
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    <i class="bx bx-user-x me-2"></i>Belum Melakukan Presensi pada tanggal {{ $selectedDate->format('d-m-Y') }}
                </h4>
                <form method="GET" action="{{ route('presensi_admin.index') }}" class="d-flex align-items-center">
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ $selectedDate->format('Y-m-d') }}" onchange="this.form.submit()">
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                @if($user->role === 'super_admin')
                                <th>Madrasah</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($belumPresensi as $userBelum)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $userBelum->name }}</td>
                                @if($user->role === 'super_admin')
                                <td>{{ $userBelum->madrasah->name ?? '-' }}</td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $user->role === 'super_admin' ? 3 : 2 }}" class="text-center p-4">
                                    <div class="alert alert-info d-inline-block text-center" role="alert">
                                        <i class="bx bx-info-circle bx-lg me-2"></i>
                                        <strong>Semua tenaga pendidik sudah melakukan presensi pada tanggal ini</strong><br>
                                        <small>Tidak ada data tenaga pendidik yang belum melakukan presensi.</small>
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
</div>
@endsection

@section('script')
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<link href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
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

    // Replace alert notifications with SweetAlert2
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endsection

