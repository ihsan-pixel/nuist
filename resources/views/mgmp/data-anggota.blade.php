{{-- resources/views/mgmp/data-anggota.blade.php --}}
@extends('layouts.master')

@section('title')
    Data Anggota MGMP
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

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
@php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'admin', 'pengurus', 'mgmp']) && auth()->user()->password_changed;
@endphp
@if($isAllowed)
@component('components.breadcrumb')
    @slot('li_1') MGMP @endslot
    @slot('title') Data Anggota @endslot
@endcomponent

<div class="card mb-4">
    <div class="card-body">

        <!-- Tombol tambah anggota -->
        <div class="mb-3 d-flex justify-content-end">
            @if(isset($canAddMember) && $canAddMember)
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAnggota">
                <i class="bx bx-plus"></i> Tambah Anggota
            </button>
            @else
            <div class="alert alert-info mb-0">
                <i class="bx bx-info-circle me-1"></i>
                Jika Anda belum membuat data MGMP, silakan buat data MGMP terlebih dahulu untuk dapat menambahkan anggota.
            </div>
            @endif
        </div>

        <div class="table-responsive">
            <table id="datatable-anggota" class="table table-bordered dt-responsive nowrap w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Asal Sekolah</th>
                        <th>Grup MGMP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->sekolah }}</td>
                        <td>{{ $member->mgmpGroup->name ?? '-' }}</td>
                        <td>
                            @if(auth()->user()->role === 'mgmp' && $member->mgmpGroup->user_id === auth()->id() || in_array(auth()->user()->role, ['super_admin', 'admin', 'pengurus']))
                            <form action="#" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus anggota ini?')">Hapus</button>
                            </form>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    {{-- <tr>
                        <td colspan="5" class="text-center p-5">
                            <div class="mb-3">
                                <i class="bx bx-info-circle bx-lg text-info"></i>
                            </div>
                            <h5 class="mb-1">Belum ada data anggota</h5>
                            <p class="text-muted">Klik tombol "Tambah Anggota" untuk menambahkan anggota MGMP.</p>
                        </td>
                    </tr> --}}
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
<!-- Modal Tambah Anggota -->
<div class="modal fade" id="modalTambahAnggota" tabindex="-1" aria-labelledby="modalTambahAnggotaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
                <form action="{{ route('mgmp.store-member') }}" method="POST" id="formTambahAnggota">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahAnggotaLabel">Tambah Anggota MGMP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Tenaga Pendidik</label>
                        @if(isset($canAddMember) && $canAddMember)
                        <select name="user_ids[]" class="form-select select2-anggota" multiple="multiple" required data-placeholder="Ketik nama atau sekolah...">
                            @forelse($tenagaPendidik as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->madrasah->name ?? 'Tidak ada sekolah' }}</option>
                            @empty
                            <option value="">Tidak ada tenaga pendidik yang tersedia</option>
                            @endforelse
                        </select>
                        @else
                        <select class="form-select" disabled>
                            <option>Anda harus membuat data MGMP terlebih dahulu</option>
                        </select>
                        @endif
                        <div class="form-text">Pilih satu atau lebih tenaga pendidik yang akan ditambahkan sebagai anggota MGMP</div>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="bx bx-info-circle me-1"></i>
                        <strong>Catatan:</strong> Pengguna yang sudah menjadi anggota di grup MGMP lain tidak akan ditampilkan dalam daftar ini.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Simpan
                    </button>
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    // Destroy existing DataTable instance if exists
    if ($.fn.DataTable.isDataTable('#datatable-anggota')) {
        $('#datatable-anggota').DataTable().destroy();
    }

    let table = $("#datatable-anggota").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        destroy: true,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-anggota_wrapper .col-md-6:eq(0)');

    // Initialize Select2 for multi-select
    $('.select2-anggota').select2({
        placeholder: "Ketik nama atau sekolah...",
        allowClear: true,
        width: '100%',
        dropdownParent: $('#modalTambahAnggota'),
        language: {
            noResults: function() {
                return "Tidak ada hasil ditemukan";
            },
            searching: function() {
                return "Mencari...";
            }
        },
        templateResult: function (data) {
            if (!data.id) { return data.text; }
            var $data = $(data.element);
            var text = $data.text();
            return $('<span>').text(text);
        },
        templateSelection: function (data) {
            if (!data.id) { return data.text; }
            var $data = $(data.element);
            var text = $data.text();
            return $('<span>').text(text);
        }
    });

    // Handle form submission
    $('#formTambahAnggota').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#modalTambahAnggota').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: errorMessage
                });
            }
        });
    });
});
</script>
@endsection
