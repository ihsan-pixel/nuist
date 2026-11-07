@extends('layouts.master')

@section('title')
    Yayasan
@endsection

@section('css')
    <link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <style>
        .yayasan-page{--yayasan-green:#08745a;--yayasan-ink:#192d37;--yayasan-muted:#6c7c86;--yayasan-border:#e5edef;padding:8px 0 24px}
        body:has(.yayasan-page){background:linear-gradient(135deg,rgba(222,227,230,.92) 0%,rgba(238,241,243,.88) 52%,rgba(215,222,225,.92) 100%)}
        body:has(.yayasan-page) .main-content{background:transparent!important}
        .yayasan-page{position:relative;overflow:visible}
        .yayasan-page:before,.yayasan-page:after{content:"";position:absolute;z-index:-1;border-radius:50%;filter:blur(2px);pointer-events:none;animation:yayasan-float 10s ease-in-out infinite}
        .yayasan-page:before{width:280px;height:280px;right:-100px;top:-90px;background:rgba(42,190,153,.15)}
        .yayasan-page:after{width:230px;height:230px;left:-100px;bottom:-100px;background:rgba(98,158,255,.11);animation-direction:reverse;animation-duration:12s}
        .yayasan-page .yayasan-shell{border:1px solid rgba(255,255,255,.86);border-radius:22px;background:rgba(255,255,255,.68);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);box-shadow:0 8px 26px rgba(32,70,78,.08);overflow:hidden;animation:yayasan-card-in .72s cubic-bezier(.22,.8,.24,1) both}
        .yayasan-page .yayasan-toolbar{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:22px 24px;border-bottom:1px solid rgba(220,231,233,.75)}
        .yayasan-page .yayasan-eyebrow{margin:0 0 4px;color:var(--yayasan-green);font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}
        .yayasan-page .yayasan-title{margin:0;color:var(--yayasan-ink);font-size:22px;font-weight:700}
        .yayasan-page .yayasan-subtitle{margin:5px 0 0;color:var(--yayasan-muted);font-size:12px}
        .yayasan-page .yayasan-toolbar .btn-primary{display:inline-flex;align-items:center;gap:6px;border:0;border-radius:12px;background:var(--yayasan-green);padding:.62rem .9rem;box-shadow:0 8px 18px rgba(8,116,90,.16)}
        .yayasan-page .yayasan-content{padding:20px 24px 24px}
        .yayasan-page .alert{border:1px solid transparent;border-radius:12px;box-shadow:none;font-size:13px}
        .yayasan-page .alert-success{border-color:#cde9dc;background:#effaf4;color:#176b4d}
        .yayasan-page .alert-danger{border-color:#f2d2d2;background:#fff5f5;color:#a33737}
        .yayasan-page .table-panel{border:1px solid var(--yayasan-border);border-radius:16px;background:rgba(255,255,255,.58);overflow:hidden}
        .yayasan-page .table-responsive{overflow-x:auto}
        .yayasan-page table.dataTable{width:100%!important;margin:0!important;border-collapse:separate;border-spacing:0;table-layout:fixed;min-width:880px}
        .yayasan-page table.dataTable thead th{border:0;border-bottom:1px solid var(--yayasan-border);background:#f1f6f6;color:var(--yayasan-ink);font-size:11px;font-weight:700;letter-spacing:.02em;padding:13px 14px;white-space:nowrap}
        .yayasan-page table.dataTable tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;vertical-align:middle;white-space:normal!important;overflow-wrap:anywhere;word-break:normal}
        .yayasan-page table.dataTable tbody tr:last-child td{border-bottom:0}
        .yayasan-page table.dataTable tbody tr:hover{background:rgba(231,246,240,.4)}
        .yayasan-page .yayasan-name{display:block;color:var(--yayasan-ink);font-weight:700;min-width:150px}
        .yayasan-page .yayasan-text{display:block;width:100%;max-width:220px;line-height:1.5;white-space:normal!important;overflow-wrap:anywhere;word-break:break-word}
        .yayasan-page .yayasan-location{display:inline-flex;align-items:flex-start;gap:5px;max-width:100%;color:var(--yayasan-green);font-size:11px;font-weight:600;white-space:normal;overflow-wrap:anywhere}
        .yayasan-page .yayasan-location.is-empty{color:var(--yayasan-muted);font-weight:400}
        .yayasan-page .yayasan-map-link{display:inline-flex;align-items:center;gap:4px;margin-top:6px;border-radius:8px!important;padding:.3rem .5rem!important;color:#08745a!important;background:#e7f6ef!important;border:0!important;font-size:11px}
        .yayasan-page .yayasan-actions{display:flex;flex-wrap:wrap;gap:6px;min-width:0;max-width:120px}
        .yayasan-page .yayasan-actions .btn{border-radius:8px;font-size:11px;padding:.34rem .55rem}
        .yayasan-page .yayasan-actions .btn-warning{color:#6d4b00;background:#fff4d6;border-color:#ffe5a0}
        .yayasan-page .yayasan-actions .btn-danger{background:#fff0f0;border-color:#ffd2d2;color:#b33b3b}
        .yayasan-page .dataTables_wrapper{padding:16px}
        .yayasan-page .dataTables_wrapper .dataTables_length,.yayasan-page .dataTables_wrapper .dataTables_filter{margin-bottom:14px;color:var(--yayasan-muted);font-size:12px}
        .yayasan-page .dataTables_wrapper select,.yayasan-page .dataTables_wrapper input{border:1px solid #dce7e9;border-radius:9px;background:#fff;color:var(--yayasan-ink);font-size:12px;min-height:34px}
        .yayasan-page .dataTables_wrapper input{margin-left:6px;padding:6px 10px;outline:0}
        .yayasan-page .dataTables_wrapper input:focus{border-color:#75c8ac;box-shadow:0 0 0 3px rgba(8,116,90,.1)}
        .yayasan-page .dataTables_wrapper .dt-buttons{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}
        .yayasan-page .dataTables_wrapper .dt-button{border:0!important;border-radius:8px!important;color:#fff!important;font-size:11px;font-weight:600;padding:.46rem .8rem;box-shadow:0 4px 10px rgba(32,70,78,.1);transition:transform .18s ease,filter .18s ease,box-shadow .18s ease}
        .yayasan-page .dataTables_wrapper .dt-button:hover{transform:translateY(-1px);filter:brightness(.96);box-shadow:0 7px 14px rgba(32,70,78,.14)}
        .yayasan-page .dataTables_wrapper .dt-buttons>.dt-button:nth-child(1){background:linear-gradient(90deg,#0f766e,#14b8a6)!important;border-color:#0f766e!important}
        .yayasan-page .dataTables_wrapper .dt-buttons>.dt-button:nth-child(2){background:linear-gradient(90deg,#2563eb,#60a5fa)!important;border-color:#2563eb!important}
        .yayasan-page .dataTables_wrapper .dt-buttons>.dt-button:nth-child(3){background:linear-gradient(90deg,#7c3aed,#a78bfa)!important;border-color:#7c3aed!important}
        .yayasan-page .dataTables_wrapper .dt-buttons>.dt-button:nth-child(4){background:linear-gradient(90deg,#d97706,#fbbf24)!important;border-color:#d97706!important}
        .yayasan-page .dataTables_wrapper .dt-buttons>.dt-button:nth-child(5){background:linear-gradient(90deg,#db2777,#f472b6)!important;border-color:#db2777!important}
        .yayasan-page .dataTables_wrapper .dt-buttons .buttons-copy{background:linear-gradient(90deg,#0f766e,#14b8a6)!important;border-color:#0f766e!important}
        .yayasan-page .dataTables_wrapper .dt-buttons .buttons-excel{background:linear-gradient(90deg,#2563eb,#60a5fa)!important;border-color:#2563eb!important}
        .yayasan-page .dataTables_wrapper .dt-buttons .buttons-pdf{background:linear-gradient(90deg,#7c3aed,#a78bfa)!important;border-color:#7c3aed!important}
        .yayasan-page .dataTables_wrapper .dt-buttons .buttons-print{background:linear-gradient(90deg,#d97706,#fbbf24)!important;border-color:#d97706!important}
        .yayasan-page .dataTables_wrapper .dt-buttons .buttons-colvis,.yayasan-page .dataTables_wrapper .dt-buttons .buttons-columnVisibility{background:linear-gradient(90deg,#db2777,#f472b6)!important;border-color:#db2777!important}
        .yayasan-page .dt-button-collection{padding:8px!important;border:1px solid #e1e9eb!important;border-radius:10px!important;background:#fff!important;box-shadow:0 12px 28px rgba(32,70,78,.16)!important}
        .yayasan-page .dt-button-collection .dt-button{display:block;width:100%;margin:0 0 3px;border:0!important;border-radius:8px!important;background:#fff!important;color:#192d37!important;text-align:left;font-size:11px;padding:.48rem .7rem}
        .yayasan-page .dt-buttons .dt-button-collection .dt-button.buttons-columnVisibility,
        .yayasan-page .dt-buttons .dt-button-collection .dt-button.buttons-colvis{background:#fff!important;background-image:none!important;border-color:transparent!important;color:#192d37!important}
        .yayasan-page .dt-button-collection .dt-button:last-child{margin-bottom:0}
        .yayasan-page .dt-button-collection .dt-button:hover{background:#eef8f4!important;color:#08745a!important;transform:none;filter:none;box-shadow:none}
        .yayasan-page .dataTables_info{color:var(--yayasan-muted);font-size:11px;padding-top:14px!important}
        .yayasan-page .dataTables_paginate{padding-top:10px!important}
        .yayasan-page .dataTables_paginate .paginate_button{border:0!important;border-radius:8px!important;color:var(--yayasan-green)!important;font-size:11px;padding:5px 9px!important}
        .yayasan-page .dataTables_paginate .paginate_button.current{background:#dff3eb!important;color:var(--yayasan-green)!important}
        .yayasan-page .modal-content{border:1px solid rgba(255,255,255,.88);border-radius:18px;box-shadow:0 18px 50px rgba(22,53,61,.18);overflow:hidden}
        body:has(.yayasan-page) .modal-backdrop{z-index:1990!important}
        body:has(.yayasan-page) .modal{z-index:2000!important;pointer-events:none}
        body:has(.yayasan-page) .modal.show{pointer-events:auto}
        body:has(.yayasan-page) .modal.show .modal-dialog{position:relative;z-index:2001;pointer-events:auto}
        .yayasan-page .modal-header{border-bottom:1px solid var(--yayasan-border);background:#f6faf9;padding:18px 22px}
        .yayasan-page .modal-title{color:var(--yayasan-ink);font-size:17px;font-weight:700}
        .yayasan-page .modal-body{padding:22px}
        .yayasan-page .modal-body label{display:block;margin-bottom:6px;color:var(--yayasan-ink);font-size:12px;font-weight:600}
        .yayasan-page .modal-body .form-control{border:1px solid #dce7e9;border-radius:9px;font-size:13px;padding:.55rem .7rem}
        .yayasan-page .modal-body .form-control:focus{border-color:#75c8ac;box-shadow:0 0 0 3px rgba(8,116,90,.1)}
        .yayasan-page .modal-footer{border-top:1px solid var(--yayasan-border);padding:14px 22px}
        .yayasan-page .modal-footer .btn{border-radius:9px;font-size:12px}
        .swal2-container{z-index:3000!important}
        .yayasan-swal-popup{border:1px solid rgba(255,255,255,.85)!important;border-radius:20px!important;background:rgba(255,255,255,.86)!important;backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);box-shadow:0 18px 50px rgba(32,70,78,.2)!important;color:#192d37!important}
        .yayasan-swal-title{color:#192d37!important;font-size:18px!important}
        .yayasan-swal-html{color:#6c7c86!important;font-size:13px!important}
        .yayasan-swal-confirm{border:0!important;border-radius:10px!important;background:#08745a!important;box-shadow:0 7px 16px rgba(8,116,90,.18)!important}
        .yayasan-swal-cancel{border:1px solid #dce7e9!important;border-radius:10px!important;background:#f5f8f9!important;color:#52656d!important}
        .yayasan-page .empty-state{padding:46px 20px!important;color:var(--yayasan-muted);background:#fbfdfd}
        .yayasan-page .empty-state .bx{display:block;margin-bottom:8px;color:#9ac9b8;font-size:38px}
        .yayasan-page .yayasan-shell:hover{box-shadow:0 16px 38px rgba(32,70,78,.12)}
        @keyframes yayasan-card-in{from{opacity:0;transform:translateY(18px) scale(.99);filter:blur(3px)}to{opacity:1;transform:translateY(0) scale(1);filter:blur(0)}}
        @keyframes yayasan-float{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(0,14px,0)}}
        @media(prefers-reduced-motion:reduce){.yayasan-page:before,.yayasan-page:after,.yayasan-page .yayasan-shell{animation:none!important}}
        @media(max-width:767.98px){.yayasan-page .yayasan-toolbar{align-items:flex-start;flex-direction:column;padding:18px}.yayasan-page .yayasan-toolbar .btn-primary{width:100%;justify-content:center}.yayasan-page .yayasan-content{padding:14px}.yayasan-page .dataTables_wrapper{padding:10px}.yayasan-page .dataTables_wrapper .dataTables_length,.yayasan-page .dataTables_wrapper .dataTables_filter{float:none!important;text-align:left!important}.yayasan-page .dataTables_wrapper .dataTables_filter input{width:calc(100% - 70px);max-width:220px}.yayasan-page .dataTables_wrapper .dt-buttons{gap:5px}.yayasan-page .dataTables_wrapper .dt-button{flex:1 1 auto;text-align:center;padding:.44rem .58rem}.yayasan-page .yayasan-text{max-width:none}.yayasan-page .yayasan-actions{max-width:none}.yayasan-page .modal-dialog{margin:.75rem}.yayasan-page .modal-body{padding:18px}}
        @media(max-width:390px){.yayasan-page{padding-top:0}.yayasan-page .yayasan-title{font-size:19px}.yayasan-page .yayasan-content{padding:10px}.yayasan-page .dataTables_wrapper .dt-buttons{display:grid;grid-template-columns:repeat(2,minmax(0,1fr))}.yayasan-page .dataTables_wrapper .dt-button{width:100%}}
    </style>
@endsection

@section('content')
@php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'pengurus']);
@endphp
@if($isAllowed)
<div class="yayasan-page">
    @component('components.breadcrumb')
        @slot('li_1') Master Data @endslot
        @slot('title') Yayasan @endslot
    @endcomponent

    <div class="yayasan-shell mb-4">
        <div class="yayasan-toolbar">
            <div>
                <p class="yayasan-eyebrow">Master Data</p>
                <h1 class="yayasan-title">Data Yayasan</h1>
                <p class="yayasan-subtitle">Kelola informasi yayasan, alamat, visi, misi, dan lokasi.</p>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahYayasan">
                    <i class="bx bx-plus"></i><span>Tambah Yayasan</span>
                </button>
            </div>
        </div>
        <div class="yayasan-content">

            <div class="table-panel">
            <div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered dt-responsive w-100">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Yayasan</th>
                            <th>Alamat</th>
                            <th>Lokasi</th>
                            <th>Visi</th>
                            <th>Misi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($yayasans as $index => $yayasan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="yayasan-name">{{ $yayasan->name }}</span></td>
                                <td><span class="yayasan-text">{{ $yayasan->alamat ?? '-' }}</span></td>
                                <td>
                                    @if($yayasan->latitude && $yayasan->longitude)
                                        <span class="yayasan-location"><i class="bx bx-map-pin"></i>{{ $yayasan->latitude }}, {{ $yayasan->longitude }}</span>
                                        @if($yayasan->map_link)
                                            <br><a href="{{ $yayasan->map_link }}" target="_blank" class="btn btn-sm yayasan-map-link"><i class="bx bx-link-external"></i>Lihat Map</a>
                                        @endif
                                    @else
                                        <span class="yayasan-location is-empty">Belum diatur</span>
                                    @endif
                                </td>
                                <td><span class="yayasan-text">{{ $yayasan->visi ? Str::limit($yayasan->visi, 50) : '-' }}</span></td>
                                <td><span class="yayasan-text">{{ $yayasan->misi ? Str::limit($yayasan->misi, 50) : '-' }}</span></td>
                                <td><div class="yayasan-actions">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditYayasan{{ $yayasan->id }}" title="Edit yayasan">
                                        <i class="bx bx-edit-alt"></i><span>Edit</span>
                                    </button>

                                    <form action="{{ route('yayasan.destroy', $yayasan->id) }}" method="POST" class="yayasan-delete-form" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus yayasan"><i class="bx bx-trash"></i><span>Hapus</span></button>
                                    </form>
                                </div></td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center empty-state">
                                    <div role="status">
                                        <i class="bx bx-buildings"></i>
                                        <strong class="d-block">Belum ada data Yayasan</strong>
                                        <small>Silakan tambahkan data yayasan terlebih dahulu.</small>
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

    @foreach($yayasans as $yayasan)
        <div class="modal fade" id="modalEditYayasan{{ $yayasan->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('yayasan.update', $yayasan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Yayasan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Nama Yayasan</label>
                                <input type="text" name="name" class="form-control" value="{{ $yayasan->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2">{{ $yayasan->alamat }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><div class="mb-3"><label>Latitude</label><input type="text" name="latitude" class="form-control" value="{{ $yayasan->latitude }}"></div></div>
                                <div class="col-md-6"><div class="mb-3"><label>Longitude</label><input type="text" name="longitude" class="form-control" value="{{ $yayasan->longitude }}"></div></div>
                            </div>
                            <div class="mb-3"><label>Link Map</label><input type="text" name="map_link" class="form-control" value="{{ $yayasan->map_link }}"></div>
                            <div class="mb-3"><label>Visi</label><textarea name="visi" class="form-control" rows="3">{{ $yayasan->visi }}</textarea></div>
                            <div class="mb-3"><label>Misi</label><textarea name="misi" class="form-control" rows="3">{{ $yayasan->misi }}</textarea></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <!-- Modal Tambah Yayasan -->
    <div class="modal fade" id="modalTambahYayasan" tabindex="-1" aria-labelledby="modalTambahYayasanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('yayasan.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahYayasanLabel">Tambah Yayasan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Yayasan</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Latitude</label>
                                    <input type="text" name="latitude" class="form-control" placeholder="Contoh: -7.7956">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Longitude</label>
                                    <input type="text" name="longitude" class="form-control" placeholder="Contoh: 110.3695">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Link Map</label>
                            <input type="text" name="map_link" class="form-control" placeholder="https://maps.app.goo.gl/xxxx">
                        </div>
                        <div class="mb-3">
                            <label>Visi</label>
                            <textarea name="visi" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Misi</label>
                            <textarea name="misi" class="form-control" rows="3"></textarea>
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
        const yayasanSwal = {
            customClass: {
                popup: 'yayasan-swal-popup',
                title: 'yayasan-swal-title',
                htmlContainer: 'yayasan-swal-html',
                confirmButton: 'yayasan-swal-confirm',
                cancelButton: 'yayasan-swal-cancel'
            },
            buttonsStyling: false,
            confirmButtonText: 'Mengerti'
        };

        @if(session('success'))
            Swal.fire({...yayasanSwal, icon: 'success', title: 'Berhasil', text: @json(session('success')), timer: 2200, showConfirmButton: false, timerProgressBar: true});
        @elseif(session('error'))
            Swal.fire({...yayasanSwal, icon: 'error', title: 'Gagal', text: @json(session('error'))});
        @elseif($errors->any())
            Swal.fire({...yayasanSwal, icon: 'error', title: 'Periksa data', html: @json(implode('<br>', $errors->all())), confirmButtonText: 'Tutup'});
        @endif

        $(document).on('submit', '.yayasan-delete-form', function (event) {
            event.preventDefault();
            const form = this;
            Swal.fire({...yayasanSwal, icon: 'warning', title: 'Hapus yayasan?', text: 'Data yang dihapus tidak dapat dikembalikan.', showCancelButton: true, confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal'}).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });

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
