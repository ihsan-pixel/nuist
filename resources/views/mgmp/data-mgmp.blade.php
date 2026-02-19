{{-- resources/views/mgmp/data-mgmp.blade.php --}}
@extends('layouts.master')

@section('title')
    Data MGMP
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
    @slot('title') Data MGMP @endslot
@endcomponent

<div class="card mb-4">
    <div class="card-body">

        @if(auth()->user()->role === 'mgmp' && !$canAdd)
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bx bx-info-circle me-2"></i>Anda sudah memiliki data MGMP. Hanya satu data MGMP yang diperbolehkan per pengguna dengan role MGMP.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Tombol aksi -->
        <div class="mb-3 d-flex justify-content-end gap-2">
            @if($canAdd)
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahMGMP">
                <i class="bx bx-plus"></i> Tambah MGMP
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportMGMP">
                <i class="bx bx-upload"></i> Import Data MGMP
            </button>
            @endif
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive">
            <table id="datatable-mgmp" class="table table-bordered dt-responsive nowrap w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama MGMP</th>
                        <th>Jumlah Anggota</th>
                        <th>Logo</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mgmpGroups as $index => $mgmp)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $mgmp->name }}</td>
                        <td>{{ $mgmp->member_count }}</td>
                        <td>
                            @if($mgmp->logo)
                                <img src="{{ asset('uploads/' . $mgmp->logo) }}" alt="Logo" width="50">
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if(auth()->user()->role === 'mgmp' && $mgmp->user_id === auth()->id() || in_array(auth()->user()->role, ['super_admin', 'admin', 'pengurus']))
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditMGMP{{ $mgmp->id }}">Edit</button>
                            {{-- <form action="{{ route('mgmp.destroy', $mgmp->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus MGMP ini?')">Delete</button>
                            </form> --}}
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Edit MGMP -->
                    <div class="modal fade" id="modalEditMGMP{{ $mgmp->id }}" tabindex="-1" aria-labelledby="modalEditMGMPLabel{{ $mgmp->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('mgmp.update', $mgmp->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditMGMPLabel{{ $mgmp->id }}">Edit MGMP</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Nama MGMP</label>
                                            <input type="text" name="name" class="form-control" value="{{ $mgmp->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Jumlah Anggota</label>
                                            <input type="number" name="member_count" class="form-control" value="{{ $mgmp->member_count }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Logo</label>
                                            <input type="file" name="logo" class="form-control" accept="image/*">
                                            <small class="text-muted">Opsional, boleh dikosongkan</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center p-4">
                            <div class="alert alert-info d-inline-block text-center" role="alert">
                                <i class="bx bx-info-circle bx-lg me-2"></i>
                                <strong>Belum ada data MGMP</strong><br>
                                <small>Silakan tambahkan data MGMP terlebih dahulu untuk melanjutkan.</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah MGMP -->
<div class="modal fade" id="modalTambahMGMP" tabindex="-1" aria-labelledby="modalTambahMGMPLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('mgmp.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahMGMPLabel">Tambah MGMP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama MGMP</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah Anggota</label>
                        <input type="number" name="member_count" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small class="text-muted">Opsional, boleh dikosongkan</small>
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

<!-- Modal Import MGMP -->
<div class="modal fade" id="modalImportMGMP" tabindex="-1" aria-labelledby="modalImportMGMPLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('mgmp.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportMGMPLabel">Import Data MGMP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Pilih File (Excel/CSV)</label>
                        <input type="file" name="file" class="form-control" accept=".xls,.xlsx,.csv" required>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">
                            Gunakan template file sesuai format data MGMP. Urutan kolom harus: nama, member_count, logo.
                            <a href="{{ url('public/template/mgmp_template.xlsx') }}" download>Download Template Excel</a>
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
    let table = $("#datatable-mgmp").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-mgmp_wrapper .col-md-6:eq(0)');

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
