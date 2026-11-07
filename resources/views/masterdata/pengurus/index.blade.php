@extends('layouts.master')

@section('title')
    Pengurus
@endsection

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />

<!-- DataTables -->
<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
<style>
    .pengurus-page{--p-green:#08745a;--p-ink:#192d37;--p-muted:#6c7c86;--p-border:#e5edef;position:relative;isolation:isolate;padding:8px 0 24px}
    body:has(.pengurus-page){background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}body:has(.pengurus-page) .main-content{background:transparent!important}
    .pengurus-page:before,.pengurus-page:after{content:"";position:absolute;z-index:-1;border-radius:50%;filter:blur(2px);pointer-events:none;animation:pengurus-float 10s ease-in-out infinite}.pengurus-page:before{width:280px;height:280px;right:-100px;top:-90px;background:rgba(42,190,153,.15)}.pengurus-page:after{width:230px;height:230px;left:-100px;bottom:-100px;background:rgba(98,158,255,.11);animation-direction:reverse;animation-duration:12s}
    .pengurus-page .pengurus-shell{border:1px solid rgba(255,255,255,.86);border-radius:22px;background:rgba(255,255,255,.68);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);box-shadow:0 8px 26px rgba(32,70,78,.08);overflow:hidden;animation:pengurus-in .72s cubic-bezier(.22,.8,.24,1) both}
    .pengurus-page .pengurus-toolbar{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:22px 24px;border-bottom:1px solid rgba(220,231,233,.75)}.pengurus-page .eyebrow{margin:0 0 4px;color:var(--p-green);font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.pengurus-page h1{margin:0;color:var(--p-ink);font-size:22px;font-weight:700}.pengurus-page .subtitle{margin:5px 0 0;color:var(--p-muted);font-size:12px}.pengurus-page .pengurus-toolbar .btn-primary{border:0;border-radius:12px;background:var(--p-green);padding:.62rem .9rem;box-shadow:0 8px 18px rgba(8,116,90,.16)}.pengurus-page .pengurus-content{padding:20px 24px 24px}
    .pengurus-page .table-panel{border:1px solid var(--p-border);border-radius:16px;background:rgba(255,255,255,.58);overflow:hidden}.pengurus-page table.dataTable{width:100%!important;margin:0!important;table-layout:fixed;min-width:620px}.pengurus-page table.dataTable thead th{border:0;border-bottom:1px solid var(--p-border);background:#f1f6f6;color:var(--p-ink);font-size:11px;padding:13px 14px;white-space:nowrap}.pengurus-page table.dataTable tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;vertical-align:middle;white-space:normal!important;overflow-wrap:anywhere}.pengurus-page table.dataTable tbody tr:last-child td{border-bottom:0}.pengurus-page table.dataTable tbody tr:hover{background:rgba(231,246,240,.4)}.pengurus-page .person-name{display:block;color:var(--p-ink);font-weight:700;overflow-wrap:anywhere}.pengurus-page .person-email{color:var(--p-muted);overflow-wrap:anywhere}.pengurus-page .actions{display:flex;flex-wrap:wrap;gap:6px}.pengurus-page .actions .btn{border-radius:8px;font-size:11px;padding:.34rem .55rem}.pengurus-page .btn-warning{color:#6d4b00;background:#fff4d6;border-color:#ffe5a0}.pengurus-page .btn-danger{color:#b33b3b;background:#fff0f0;border-color:#ffd2d2}
    .pengurus-page .dataTables_wrapper{padding:16px}.pengurus-page .dataTables_wrapper select,.pengurus-page .dataTables_wrapper input{border:1px solid #dce7e9;border-radius:9px;background:#fff;color:var(--p-ink);font-size:12px;min-height:34px}.pengurus-page .dataTables_wrapper input{margin-left:6px;padding:6px 10px}.pengurus-page .dataTables_wrapper .dt-buttons{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}.pengurus-page .dataTables_wrapper .dt-button{border:0!important;border-radius:8px!important;color:#fff!important;font-size:11px;font-weight:600;padding:.46rem .8rem;box-shadow:0 4px 10px rgba(32,70,78,.1)}.pengurus-page .dataTables_wrapper .dt-button:nth-child(1){background:linear-gradient(90deg,#0f766e,#14b8a6)!important}.pengurus-page .dataTables_wrapper .dt-button:nth-child(2){background:linear-gradient(90deg,#2563eb,#60a5fa)!important}.pengurus-page .dataTables_wrapper .dt-button:nth-child(3){background:linear-gradient(90deg,#7c3aed,#a78bfa)!important}.pengurus-page .dataTables_wrapper .dt-button:nth-child(4){background:linear-gradient(90deg,#d97706,#fbbf24)!important}.pengurus-page .dataTables_wrapper .dt-button:nth-child(5){background:linear-gradient(90deg,#db2777,#f472b6)!important}.pengurus-page .dt-button-collection{padding:8px!important;border:1px solid #e1e9eb!important;border-radius:10px!important;background:#fff!important;box-shadow:0 12px 28px rgba(32,70,78,.16)!important}.pengurus-page .dt-button-collection .dt-button{display:block;width:100%;margin:0 0 3px;background:#fff!important;color:var(--p-ink)!important;box-shadow:none!important;text-align:left}.pengurus-page .dataTables_info{color:var(--p-muted);font-size:11px}.pengurus-page .dataTables_paginate .paginate_button{border:0!important;border-radius:8px!important;color:var(--p-green)!important;font-size:11px;padding:5px 9px!important}.pengurus-page .dataTables_paginate .paginate_button.current{background:#dff3eb!important}
    .pengurus-page .empty-state{padding:46px 20px!important;color:var(--p-muted);background:#fbfdfd}.pengurus-page .empty-state .bx{display:block;margin-bottom:8px;color:#9ac9b8;font-size:38px}.pengurus-page .modal-content{border:1px solid rgba(255,255,255,.88);border-radius:18px;box-shadow:0 18px 50px rgba(22,53,61,.18);overflow:hidden}.pengurus-page .modal-header{border-bottom:1px solid var(--p-border);background:#f6faf9;padding:18px 22px}.pengurus-page .modal-title{color:var(--p-ink);font-size:17px;font-weight:700}.pengurus-page .modal-body{padding:22px}.pengurus-page .modal-body label{display:block;margin-bottom:6px;color:var(--p-ink);font-size:12px;font-weight:600}.pengurus-page .modal-body .form-control{border:1px solid #dce7e9;border-radius:9px;font-size:13px;padding:.55rem .7rem}.pengurus-page .modal-footer{border-top:1px solid var(--p-border);padding:14px 22px}.pengurus-page .modal-footer .btn{border-radius:9px;font-size:12px}
    .pengurus-swal{border:1px solid rgba(255,255,255,.9)!important;border-radius:22px!important;background:rgba(255,255,255,.9)!important;backdrop-filter:blur(18px);box-shadow:0 18px 50px rgba(32,70,78,.2)!important}.pengurus-swal-title{color:var(--p-ink)!important}.pengurus-swal-confirm{border:0!important;border-radius:10px!important;background:var(--p-green)!important;color:#fff!important;padding:10px 16px!important}.pengurus-swal-cancel{border:1px solid #dce7e9!important;border-radius:10px!important;background:#f5f8f9!important;color:#52656d!important;padding:10px 16px!important}
    @keyframes pengurus-in{from{opacity:0;transform:translateY(18px) scale(.99);filter:blur(3px)}to{opacity:1;transform:none;filter:none}}@keyframes pengurus-float{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(0,14px,0)}}
    @media(max-width:767.98px){.pengurus-page .pengurus-toolbar{align-items:flex-start;flex-direction:column;padding:18px}.pengurus-page .pengurus-toolbar .btn-primary{width:100%}.pengurus-page .pengurus-content{padding:14px}.pengurus-page .dataTables_wrapper{padding:10px}.pengurus-page .dataTables_wrapper .dataTables_length,.pengurus-page .dataTables_wrapper .dataTables_filter{float:none!important;text-align:left!important}.pengurus-page .dataTables_wrapper .dataTables_filter input{width:calc(100% - 70px);max-width:220px}.pengurus-page .dataTables_wrapper .dt-button{flex:1 1 auto;text-align:center}.pengurus-page .modal-dialog{margin:.75rem}}
    .pengurus-page .dataTables_wrapper .buttons-copy{background:linear-gradient(90deg,#0f766e,#14b8a6)!important}.pengurus-page .dataTables_wrapper .buttons-excel{background:linear-gradient(90deg,#2563eb,#60a5fa)!important}.pengurus-page .dataTables_wrapper .buttons-pdf{background:linear-gradient(90deg,#7c3aed,#a78bfa)!important}.pengurus-page .dataTables_wrapper .buttons-print{background:linear-gradient(90deg,#d97706,#fbbf24)!important}.pengurus-page .dataTables_wrapper .buttons-colvis,.pengurus-page .dataTables_wrapper .buttons-columnVisibility{background:linear-gradient(90deg,#db2777,#f472b6)!important}
    .pengurus-page .dt-button-collection .dt-button{background:#fff!important;background-image:none!important;color:#192d37!important;border-color:transparent!important}.pengurus-page .dt-button-collection .dt-button:hover{background:#eef8f4!important;color:#08745a!important}
    body:has(.pengurus-page) .modal-content{border:1px solid rgba(255,255,255,.9)!important;border-radius:18px!important;box-shadow:0 18px 50px rgba(22,53,61,.18)!important;overflow:hidden}body:has(.pengurus-page) .modal-header{border-bottom:1px solid #e5edef!important;background:#f6faf9!important;padding:18px 22px!important}body:has(.pengurus-page) .modal-body{padding:22px!important}body:has(.pengurus-page) .modal-body label{display:block;margin-bottom:6px;color:#192d37;font-size:12px;font-weight:600}body:has(.pengurus-page) .modal-body .form-control{border:1px solid #dce7e9;border-radius:9px;font-size:13px;padding:.55rem .7rem}body:has(.pengurus-page) .modal-footer{border-top:1px solid #e5edef!important;padding:14px 22px!important}body:has(.pengurus-page) .modal-backdrop{z-index:1990!important}body:has(.pengurus-page) .modal{z-index:2000!important}
    .pengurus-swal{width:min(420px,calc(100% - 32px))!important;padding:26px 28px 24px!important;border-radius:22px!important;background:rgba(255,255,255,.9)!important;backdrop-filter:blur(18px);box-shadow:0 18px 50px rgba(32,70,78,.2)!important}.pengurus-swal-title{margin:0!important;color:#192d37!important;font-size:22px!important}.pengurus-swal-confirm{border:0!important;border-radius:10px!important;background:#08745a!important;color:#fff!important;padding:10px 16px!important}.pengurus-swal-cancel{border:1px solid #dce7e9!important;border-radius:10px!important;background:#f5f8f9!important;color:#52656d!important;padding:10px 16px!important}
    body:has(.pengurus-page) .dt-button-collection{z-index:2100!important;padding:8px!important;border:1px solid #e1e9eb!important;border-radius:10px!important;background:#fff!important;box-shadow:0 12px 28px rgba(32,70,78,.16)!important}
    body:has(.pengurus-page) .dt-button-collection .dt-button,body:has(.pengurus-page) .dt-button-collection .buttons-columnVisibility{display:block!important;width:100%!important;margin:0 0 3px!important;border:0!important;border-radius:8px!important;background:#fff!important;background-image:none!important;color:#192d37!important;box-shadow:none!important;text-align:left!important}
    body:has(.pengurus-page) .dt-button-collection .dt-button:hover{background:#eef8f4!important;color:#08745a!important}
    div.dt-button-collection{width:245px!important;padding:10px!important;border:1px solid #e3eaec!important;border-radius:16px!important;background:#f8fafb!important;background-image:none!important;box-shadow:0 12px 28px rgba(32,70,78,.14)!important}
    div.dt-button-collection>div,div.dt-button-collection button.dt-button,div.dt-button-collection button.buttons-columnVisibility{display:block!important;width:100%!important;min-height:48px!important;margin:0 0 6px!important;padding:13px 18px!important;border:0!important;border-radius:14px!important;background:#fff!important;background-color:#fff!important;background-image:none!important;color:#192d37!important;box-shadow:0 4px 10px rgba(32,70,78,.07)!important;text-shadow:none!important;text-align:left!important;font-size:16px!important;font-weight:600!important;line-height:1.2!important}
    div.dt-button-collection button.dt-button:last-child,div.dt-button-collection button.buttons-columnVisibility:last-child{margin-bottom:0!important}
    div.dt-button-collection button.dt-button:hover,div.dt-button-collection button.buttons-columnVisibility:hover{background:#f1faf6!important;background-color:#f1faf6!important;color:#08745a!important;transform:none!important;filter:none!important;box-shadow:0 5px 12px rgba(8,116,90,.1)!important}
    body:has(.pengurus-page) .modal{position:fixed!important;inset:0!important;display:none;overflow-x:hidden;overflow-y:auto;pointer-events:none!important;z-index:2200!important}body:has(.pengurus-page) .modal.show{display:block!important;pointer-events:auto!important}body:has(.pengurus-page) .modal.show .modal-dialog{pointer-events:auto!important;z-index:2201!important}
    body:has(.pengurus-page) div.dt-button-collection button,body:has(.pengurus-page) div.dt-button-collection .dt-button,body:has(.pengurus-page) div.dt-button-collection .buttons-columnVisibility{background:#fff!important;background-color:#fff!important;background-image:none!important;color:#192d37!important;border:0!important;box-shadow:0 4px 10px rgba(32,70,78,.07)!important}
    body:has(.pengurus-page) div.dt-button-collection button:hover,body:has(.pengurus-page) div.dt-button-collection .dt-button:hover{background:#f1faf6!important;background-color:#f1faf6!important;color:#08745a!important}
</style>
@endsection

@section('content')
@php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'admin', 'pengurus']);
@endphp
@if($isAllowed)
<div class="pengurus-page">
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Pengurus @endslot
@endcomponent

<div class="pengurus-shell mb-4">
    <div class="pengurus-toolbar">
        <div><p class="eyebrow">Master Data</p><h1>Data Pengurus</h1><p class="subtitle">Kelola akun, email, dan jabatan pengurus NUIST.</p></div>
        @if($userRole != 'pengurus')
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPengurus"><i class="bx bx-plus"></i> Tambah Pengurus</button>
        @endif
    </div>
    <div class="pengurus-content">

        <!-- Tombol aksi -->
        <div class="table-panel"><div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered dt-responsive w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penguruses as $index => $pengurus)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="person-name">{{ $pengurus->name }}</span></td>
                        <td><span class="person-email">{{ $pengurus->email }}</span></td>
                        <td>{{ $pengurus->ketugasan ?? '-' }}</td>
                        <td><div class="actions">
                            <!-- Tombol Edit -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditPengurus{{ $pengurus->id }}">
                                Edit
                            </button>

                            <!-- Tombol Delete -->
                            <form action="{{ route('pengurus.destroy', $pengurus->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center empty-state">
                            <div role="status"><i class="bx bx-group"></i><strong class="d-block">Belum ada data Pengurus</strong><small>Silakan tambahkan data pengurus terlebih dahulu untuk melanjutkan.</small></div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div></div>
    </div>
</div>

@foreach($penguruses as $pengurus)
<div class="modal fade" id="modalEditPengurus{{ $pengurus->id }}" tabindex="-1" aria-labelledby="modalEditPengurusLabel{{ $pengurus->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('pengurus.update', $pengurus->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="modalEditPengurusLabel{{ $pengurus->id }}">Edit Pengurus</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
                <div class="modal-body"><div class="mb-3"><label>Nama</label><input type="text" name="name" class="form-control" value="{{ $pengurus->name }}" required></div><div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="{{ $pengurus->email }}" required></div><div class="mb-3"><label>Jabatan</label><input type="text" name="jabatan" class="form-control" value="{{ $pengurus->ketugasan ?? '' }}"></div><div class="mb-3"><label>Password (Kosongkan jika tidak ingin diubah)</label><input type="password" name="password" class="form-control"></div></div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan Perubahan</button></div>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Modal Tambah Pengurus -->
<div class="modal fade" id="modalTambahPengurus" tabindex="-1" aria-labelledby="modalTambahPengurusLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('pengurus.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahPengurusLabel">Tambah Pengurus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
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

<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
const pengurusSwal = {customClass:{popup:'pengurus-swal',title:'pengurus-swal-title',confirmButton:'pengurus-swal-confirm',cancelButton:'pengurus-swal-cancel'},buttonsStyling:false};

$(document).ready(function () {
    // Keep Bootstrap modals outside page stacking contexts so the backdrop stays behind them.
    $('.modal').appendTo(document.body);

    let table = $("#datatable-buttons").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    @if(session('success'))
        Swal.fire({...pengurusSwal,icon:'success',title:'Berhasil',text:@json(session('success')),timer:2200,showConfirmButton:false});
    @endif

    @if(session('error'))
        Swal.fire({...pengurusSwal,icon:'error',title:'Gagal',text:@json(session('error'))});
    @endif
});

$(document).on('submit','form[action*="/pengurus/"]',function(event){
    if (!this.querySelector('input[name="_method"][value="DELETE"]')) return;
    event.preventDefault(); const form=this;
    Swal.fire({...pengurusSwal,icon:'warning',title:'Hapus pengurus?',text:'Data yang dihapus tidak dapat dikembalikan.',showCancelButton:true,confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(result=>{if(result.isConfirmed) form.submit();});
});
</script>
@endsection
