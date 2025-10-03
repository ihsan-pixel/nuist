@extends('layouts.master')

@section('title', 'Detail Profile Madrasah')

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
    @slot('li_1') Master Data @endslot
    @slot('li_2') Profile Madrasah/Sekolah @endslot
    @slot('title') Detail {{ $madrasah->name }} @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-school me-2"></i>Detail Profile Madrasah
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

                <!-- Data Madrasah -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        @if($madrasah->logo)
                        <img src="{{ asset('storage/app/public/' . $madrasah->logo) }}" class="rounded mx-auto d-block mb-3" alt="{{ $madrasah->name }}" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                        <div class="rounded mx-auto d-block mb-3 bg-light d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                            <i class="bx bx-school bx-lg text-muted"></i>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h3 class="fw-bold">{{ $madrasah->name }}</h3>
                        <p class="text-muted">{{ $madrasah->alamat ?? 'Alamat tidak tersedia' }}</p>
                        @if($madrasah->map_link)
                        <a href="{{ $madrasah->map_link }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-map me-1"></i> Lihat di Peta
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Kepala Sekolah -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="fw-semibold mb-2">Kepala Sekolah/Madrasah</h5>
                        @if($kepalaSekolah)
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                @if($kepalaSekolah->avatar)
                                <img src="{{ asset('storage/' . $kepalaSekolah->avatar) }}" class="rounded-circle" alt="{{ $kepalaSekolah->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    {{ substr($kepalaSekolah->name, 0, 1) }}
                                </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $kepalaSekolah->name }}</h6>
                                <small class="text-muted">{{ $kepalaSekolah->email }}</small>
                            </div>
                        </div>
                        @else
                        <p class="text-muted">Kepala sekolah belum ditetapkan.</p>
                        @endif
                    </div>
                </div>

                <!-- Jumlah TP berdasarkan Status Kepegawaian -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="fw-semibold mb-3">Jumlah Tenaga Pendidik berdasarkan Status Kepegawaian</h5>
                        <div class="row">
                            @forelse($tpByStatus as $status => $count)
                            <div class="col-md-3 mb-2">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-muted mb-1">{{ $status ?? 'Tidak Diketahui' }}</h6>
                                        <h4 class="fw-bold text-primary">{{ $count }}</h4>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <p class="text-muted">Belum ada data tenaga pendidik.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Data Table Tenaga Pendidik -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="fw-semibold mb-3">Data Tenaga Pendidik</h5>
                        <div class="table-responsive">
                            <table id="tenaga-pendidik-table" class="table table-bordered dt-responsive nowrap w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Status Kepegawaian</th>
                                        <th>No HP</th>
                                        <th>NIP/NUPTK</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($madrasah->tenagaPendidik as $tp)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tp->nama }}</td>
                                        <td>{{ $tp->email }}</td>
                                        <td>{{ $tp->statusKepegawaian->name ?? '-' }}</td>
                                        <td>{{ $tp->no_hp ?? '-' }}</td>
                                        <td>{{ $tp->nip ?? $tp->nuptk ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center p-4">
                                            <div class="alert alert-info d-inline-block text-center" role="alert">
                                                <i class="bx bx-info-circle bx-lg me-2"></i>
                                                <strong>Belum ada data tenaga pendidik</strong>
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
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
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
    let table = $("#tenaga-pendidik-table").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#tenaga-pendidik-table_wrapper .col-md-6:eq(0)');

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
