@extends('layouts.master')

@section('title')
    Data Broadcast
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
<style>
body:has(#datatable-buttons){background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}body:has(#datatable-buttons) .card{border:1px solid rgba(255,255,255,.86);border-radius:22px;background:rgba(255,255,255,.68);backdrop-filter:blur(18px);box-shadow:0 8px 26px rgba(32,70,78,.08)}body:has(#datatable-buttons) .card-header{border-bottom:1px solid #e5edef;background:transparent;padding:22px 24px}body:has(#datatable-buttons) .card-body{padding:20px 24px 24px}body:has(#datatable-buttons) .table-responsive{border:1px solid #e5edef;border-radius:16px;overflow-x:auto}body:has(#datatable-buttons) table.dataTable{table-layout:auto;min-width:900px}body:has(#datatable-buttons) table.dataTable thead th{background:#f1f6f6;color:#192d37;border:0;border-bottom:1px solid #e5edef;font-size:11px;padding:13px 14px;white-space:nowrap}body:has(#datatable-buttons) table.dataTable tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;white-space:normal!important;overflow-wrap:anywhere}body:has(#datatable-buttons) table.dataTable th:first-child,body:has(#datatable-buttons) table.dataTable td:first-child{width:60px}body:has(#datatable-buttons) table.dataTable th:nth-child(2),body:has(#datatable-buttons) table.dataTable td:nth-child(2){min-width:220px}body:has(#datatable-buttons) table.dataTable th:nth-child(3),body:has(#datatable-buttons) table.dataTable td:nth-child(3){min-width:170px}body:has(#datatable-buttons) table.dataTable th:nth-child(4),body:has(#datatable-buttons) table.dataTable td:nth-child(4){min-width:220px}body:has(#datatable-buttons) table.dataTable th:last-child,body:has(#datatable-buttons) table.dataTable td:last-child{width:135px}body:has(#datatable-buttons) .dt-buttons{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}body:has(#datatable-buttons) .dt-button{border:0!important;border-radius:8px!important;color:#fff!important;font-size:11px;font-weight:600;padding:.46rem .8rem}body:has(#datatable-buttons) .buttons-copy{background:linear-gradient(90deg,#0f766e,#14b8a6)!important}body:has(#datatable-buttons) .buttons-excel{background:linear-gradient(90deg,#2563eb,#60a5fa)!important}body:has(#datatable-buttons) .buttons-pdf{background:linear-gradient(90deg,#7c3aed,#a78bfa)!important}body:has(#datatable-buttons) .buttons-print{background:linear-gradient(90deg,#d97706,#fbbf24)!important}body:has(#datatable-buttons) .buttons-colvis{background:linear-gradient(90deg,#db2777,#f472b6)!important}body:has(#datatable-buttons) div.dt-button-collection{padding:10px!important;border-radius:16px!important;background:#f8fafb!important}body:has(#datatable-buttons) div.dt-button-collection button.dt-button{background:#fff!important;background-image:none!important;color:#192d37!important;border:0!important;border-radius:14px!important;width:100%!important;margin-bottom:6px;text-align:left}body:has(#datatable-buttons) .modal{z-index:2000!important;pointer-events:none}body:has(#datatable-buttons) .modal.show{pointer-events:auto}body:has(#datatable-buttons) .modal.show .modal-dialog{position:relative;z-index:2001;pointer-events:auto}@media(max-width:767px){body:has(#datatable-buttons) .card-body{padding:14px}body:has(#datatable-buttons) .card-header{padding:18px}}
</style>
@endsection

@section('content')
@php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'pengurus']);
@endphp
@if($isAllowed)
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Data Broadcast @endslot
@endcomponent

<div class="card mb-4">
    <div class="card-body">

        <!-- Tombol aksi -->
        <div class="mb-3 d-flex justify-content-end gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBroadcast">
                <i class="bx bx-plus"></i> Tambah Nomor Broadcast
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

        <!-- Broadcast Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-broadcast me-2"></i>Kirim Broadcast WhatsApp
                </h5>
            </div>
            <div class="card-body">
                <form id="broadcastForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Pilih Madrasah/Sekolah</label>
                            <select name="school_id" class="form-control" required>
                                <option value="">-- Pilih Madrasah/Sekolah --</option>
                                @foreach($madrasahs as $madrasah)
                                    <option value="{{ $madrasah->id }}">{{ $madrasah->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pesan Broadcast</label>
                            <textarea name="message" class="form-control" rows="3" placeholder="Ketik pesan yang akan dikirim..." required maxlength="1000"></textarea>
                            <small class="text-muted">Maksimal 1000 karakter</small>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success" id="sendBroadcastBtn">
                                    <i class="bx bx-send"></i> Kirim Broadcast
                                </button>
                                <button type="button" class="btn btn-info btn-sm" id="testConnectionBtn">
                                    <i class="bx bx-wifi"></i> Test Koneksi
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div id="broadcastResult" class="mt-3" style="display: none;"></div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Madrasah/Sekolah</th>
                        <th>Nomor WhatsApp</th>
                        <th>Deskripsi</th>
                        <th>Dibuat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($broadcastNumbers as $index => $broadcast)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $broadcast->madrasah->name }}</td>
                        <td>{{ $broadcast->whatsapp_number }}</td>
                        <td>{{ $broadcast->description ?? '-' }}</td>
                        <td>{{ $broadcast->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditBroadcast{{ $broadcast->id }}">
                                Edit
                            </button>

                            <!-- Tombol Delete -->
                            <form action="{{ route('admin_masterdata.broadcast-numbers.destroy', $broadcast->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus nomor broadcast ini?')">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit Broadcast -->
                    <div class="modal fade" id="modalEditBroadcast{{ $broadcast->id }}" tabindex="-1" aria-labelledby="modalEditBroadcastLabel{{ $broadcast->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('admin_masterdata.broadcast-numbers.update', $broadcast->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditBroadcastLabel{{ $broadcast->id }}">Edit Nomor Broadcast</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Madrasah/Sekolah</label>
                                            <select name="madrasah_id" class="form-control" required>
                                                @foreach($madrasahs as $madrasah)
                                                    <option value="{{ $madrasah->id }}" {{ $broadcast->madrasah_id == $madrasah->id ? 'selected' : '' }}>
                                                        {{ $madrasah->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Nomor WhatsApp</label>
                                            <input type="text" name="whatsapp_number" class="form-control" value="{{ $broadcast->whatsapp_number }}" required placeholder="Contoh: 6281234567890">
                                            <small class="text-muted">Format: 628xxxxxxxxx (tanpa spasi atau karakter lain)</small>
                                        </div>
                                        <div class="mb-3">
                                            <label>Deskripsi</label>
                                            <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi penggunaan nomor broadcast">{{ $broadcast->description }}</textarea>
                                            <small class="text-muted">Opsional, untuk menjelaskan penggunaan nomor ini</small>
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
                        <td colspan="6" class="text-center p-4">
                            <div class="alert alert-info d-inline-block text-center" role="alert">
                                <i class="bx bx-info-circle bx-lg me-2"></i>
                                <strong>Belum ada data broadcast</strong><br>
                                <small>Silakan tambahkan nomor broadcast terlebih dahulu untuk setiap madrasah/sekolah.</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Broadcast -->
<div class="modal fade" id="modalTambahBroadcast" tabindex="-1" aria-labelledby="modalTambahBroadcastLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin_masterdata.broadcast-numbers.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahBroadcastLabel">Tambah Nomor Broadcast</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Madrasah/Sekolah</label>
                        <select name="madrasah_id" class="form-control" required>
                            <option value="">-- Pilih Madrasah/Sekolah --</option>
                            @foreach($madrasahs as $madrasah)
                                <option value="{{ $madrasah->id }}">{{ $madrasah->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Nomor WhatsApp</label>
                        <input type="text" name="whatsapp_number" class="form-control" required placeholder="Contoh: 6281234567890">
                        <small class="text-muted">Format: 628xxxxxxxxx (tanpa spasi atau karakter lain)</small>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi penggunaan nomor broadcast"></textarea>
                        <small class="text-muted">Opsional, untuk menjelaskan penggunaan nomor ini</small>
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
            $('.modal').appendTo(document.body);
            $('#datatable-buttons tbody tr').has('td[colspan]').remove();
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
