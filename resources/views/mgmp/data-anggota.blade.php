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
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAnggota">
                <i class="bx bx-plus"></i> Tambah Anggota
            </button>
        </div>

        <div class="table-responsive">
            <table id="datatable-anggota" class="table table-bordered dt-responsive nowrap w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Asal Sekolah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->sekolah }}</td>
                        <td>
                            @if(auth()->user()->role === 'mgmp' && $member->mgmpGroup->user_id === auth()->id() || in_array(auth()->user()->role, ['super_admin', 'admin', 'pengurus']))
                            <form action="#" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus anggota ini?')">Hapus</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center p-5">
                            <div class="mb-3">
                                <i class="bx bx-info-circle bx-lg text-info"></i>
                            </div>
                            <h5 class="mb-1">Belum ada data anggota</h5>
                            <p class="text-muted">Klik tombol "Tambah Anggota" untuk menambahkan anggota MGMP.</p>
                        </td>
                    </tr>
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
    <div class="modal-dialog">
        <form action="{{ route('mgmp.store-member') }}" method="POST" id="formTambahAnggota">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahAnggotaLabel">Tambah Anggota MGMP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama</label>
                        <select name="user_ids[]" class="form-control" multiple required>
                            @foreach($tenagaPendidik as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->madrasah_id ?? 'Tidak ada sekolah' }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih satu atau lebih tenaga pendidik</small>
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
    let table = $("#datatable-anggota").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-anggota_wrapper .col-md-6:eq(0)');

    // Initialize Select2 for multi-select
    $('select[name="user_ids[]"]').select2({
        placeholder: "Pilih tenaga pendidik...",
        allowClear: true,
        width: '100%',
        templateResult: function (data) {
            if (!data.id) { return data.text; }
            var $data = $(data.element);
            var text = $data.text();
            return text;
        },
        templateSelection: function (data) {
            if (!data.id) { return data.text; }
            var $data = $(data.element);
            var text = $data.text();
            return text;
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
                $('#modalTambahAnggota').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseJSON?.message || 'Unknown error');
            }
        });
    });
});
</script>
@endsection
