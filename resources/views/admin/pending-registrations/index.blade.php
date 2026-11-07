@extends('layouts.master')

@section('title')
    Pending Registrations
@endsection

@section('css')
    <link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'pengurus']);
@endphp

@if($isAllowed)
    @component('components.breadcrumb')
        @slot('li_1') Admin @endslot
        @slot('title') Pending Registrations @endslot
    @endcomponent

    @include('mgmp.partials.ui-styles')
    <style>
        .mgmp-page .avatar-title{border:1px solid rgba(255,255,255,.9)!important;background:linear-gradient(135deg,rgba(224,246,238,.96),rgba(190,232,218,.72))!important;color:#08745a!important;box-shadow:0 7px 16px rgba(8,116,90,.12),inset 0 1px 0 rgba(255,255,255,.95);backdrop-filter:blur(10px)}.mgmp-page .avatar-title.text-warning{color:#b77908!important;background:linear-gradient(135deg,rgba(255,247,218,.98),rgba(255,225,144,.7))!important}.mgmp-page .avatar-title.text-primary{color:#2563eb!important;background:linear-gradient(135deg,rgba(224,236,255,.98),rgba(172,202,255,.72))!important}.mgmp-page .avatar-title.text-info{color:#087d91!important;background:linear-gradient(135deg,rgba(222,248,250,.98),rgba(164,224,231,.72))!important}.mgmp-page .registration-stat-label{color:#6c7c86}.mgmp-page .registration-stat-number{background:linear-gradient(90deg,#005b58,#0e8549);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent}.mgmp-page .badge.bg-warning-subtle,.mgmp-page .badge.bg-primary-subtle,.mgmp-page .badge.bg-success-subtle,.mgmp-page .badge.bg-light{border:1px solid rgba(255,255,255,.8);border-radius:999px;box-shadow:0 3px 8px rgba(32,70,78,.08);font-weight:700}.mgmp-page .badge.bg-warning-subtle{background:linear-gradient(90deg,#fff4d6,#ffe8a3)!important;color:#856404!important}.mgmp-page .badge.bg-primary-subtle{background:linear-gradient(90deg,#e3edff,#c7dbff)!important;color:#2457a6!important}.mgmp-page .badge.bg-success-subtle{background:linear-gradient(90deg,#e1f6ec,#bde8d1)!important;color:#08745a!important}.mgmp-page .badge.bg-light{background:rgba(255,255,255,.74)!important;color:#52656d!important}.mgmp-page .registration-approved-avatar{border:1px solid rgba(255,255,255,.8);box-shadow:0 8px 18px rgba(8,116,90,.18),inset 0 1px 0 rgba(255,255,255,.45)}
        .mgmp-page .mgmp-hero-strip{background:linear-gradient(135deg,rgba(0,91,88,.96),rgba(8,116,90,.9));box-shadow:0 18px 42px rgba(8,116,90,.22),inset 0 1px 0 rgba(255,255,255,.42);isolation:isolate}.mgmp-page .mgmp-hero-strip:before{content:"";position:absolute;inset:-80% -30%;z-index:0;background:linear-gradient(110deg,transparent 38%,rgba(255,255,255,.16) 48%,transparent 58%);transform:translateX(-50%) rotate(12deg);animation:pending-shine 7s ease-in-out infinite}.mgmp-page .mgmp-hero-strip>*{position:relative;z-index:1}.mgmp-page .mgmp-stat-card,.mgmp-page>.card{background:linear-gradient(135deg,rgba(255,255,255,.82),rgba(245,250,249,.55))!important;border-color:rgba(255,255,255,.96)!important;box-shadow:0 12px 34px rgba(32,70,78,.1),inset 0 1px 0 rgba(255,255,255,.9)!important}.mgmp-page .mgmp-stat-card,.mgmp-page>.card{position:relative;overflow:hidden}.mgmp-page .registration-approved-card{background:linear-gradient(135deg,rgba(255,255,255,.82),rgba(245,250,249,.58));box-shadow:0 10px 26px rgba(32,70,78,.08),inset 0 1px 0 rgba(255,255,255,.9)}@keyframes pending-shine{0%,35%{transform:translateX(-55%) rotate(12deg)}70%,100%{transform:translateX(55%) rotate(12deg)}}
    </style>
    <style>
        .mgmp-page{--dash-green:#08745a;--dash-ink:#192d37;--dash-muted:#6c7c86;position:relative;isolation:isolate;padding:8px 0 24px}.mgmp-page:before,.mgmp-page:after{content:"";position:absolute;z-index:-1;border-radius:50%;filter:blur(2px);pointer-events:none;animation:pending-orb 11s ease-in-out infinite}.mgmp-page:before{width:280px;height:280px;right:-90px;top:-80px;background:rgba(42,190,153,.15)}.mgmp-page:after{width:230px;height:230px;left:-100px;bottom:-100px;background:rgba(98,158,255,.12);animation-direction:reverse;animation-duration:14s}body:has(.mgmp-page){background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}body:has(.mgmp-page) .main-content{background:transparent!important}.mgmp-page .mgmp-hero-strip{background:linear-gradient(135deg,#005b58,#08745a);border:1px solid rgba(255,255,255,.35);border-radius:22px;box-shadow:0 14px 34px rgba(32,70,78,.14);animation:pending-card-in .7s both}.mgmp-page .mgmp-stat-card,.mgmp-page>.card{border:1px solid rgba(255,255,255,.86)!important;border-radius:20px!important;background:rgba(255,255,255,.7)!important;backdrop-filter:blur(16px);box-shadow:0 8px 26px rgba(32,70,78,.08)!important}.mgmp-page .mgmp-stat-card:hover{transform:translateY(-4px);box-shadow:0 14px 32px rgba(32,70,78,.13)!important}.mgmp-page .card-body{padding:20px 24px}.mgmp-page .registration-section-header{border-bottom:1px solid #e5edef;padding-bottom:16px}.mgmp-page .registration-table{min-width:1050px}.mgmp-page .table-responsive{border:1px solid #e5edef;border-radius:16px;overflow-x:auto}.mgmp-page .registration-table thead th{background:#f1f6f6!important;border:0!important;border-bottom:1px solid #e5edef!important;color:var(--dash-ink);font-size:11px;padding:13px 14px;white-space:nowrap}.mgmp-page .registration-table tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;vertical-align:middle;white-space:normal;overflow-wrap:anywhere}.mgmp-page .registration-table tbody tr{transition:background .18s ease,transform .18s ease}.mgmp-page .registration-table tbody tr:hover{background:rgba(231,246,240,.45);transform:translateX(2px)}.mgmp-page .btn{border-radius:10px;transition:transform .18s ease,box-shadow .18s ease}.mgmp-page .btn:hover{transform:translateY(-2px);box-shadow:0 7px 14px rgba(32,70,78,.14)}.mgmp-page .btn-success,.mgmp-page .btn-primary{background:linear-gradient(90deg,#0f766e,#14b8a6);border:0}.mgmp-page .btn-outline-danger{border-color:#ffd2d2;color:#b33b3b;background:#fff0f0}.mgmp-page .registration-approved-card{border:1px solid #e5edef;border-radius:16px;background:rgba(255,255,255,.68);box-shadow:0 6px 18px rgba(32,70,78,.06);transition:transform .18s ease,box-shadow .18s ease}.mgmp-page .registration-approved-card:hover{transform:translateY(-3px);box-shadow:0 12px 24px rgba(32,70,78,.11)}.mgmp-page .mgmp-empty-state{min-height:180px;justify-content:center}.mgmp-page .pagination .page-link{border:0;border-radius:8px;color:var(--dash-green);margin-left:4px}.mgmp-page .pagination .active .page-link{background:#dff3eb;color:var(--dash-green)}.mgmp-page .mgmp-chip{background:#e7f6ef;color:var(--dash-green)}.mgmp-page .swal2-popup{border:1px solid rgba(255,255,255,.9)!important;border-radius:22px!important;background:rgba(255,255,255,.92)!important;backdrop-filter:blur(18px);box-shadow:0 18px 50px rgba(32,70,78,.2)!important}.mgmp-page .swal2-confirm{border-radius:10px!important;background:#08745a!important}.mgmp-page .swal2-cancel{border-radius:10px!important;background:#f5f8f9!important;color:#52656d!important}@keyframes pending-orb{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(-12px,18px,0)}}@keyframes pending-card-in{from{opacity:0;transform:translateY(18px) scale(.99);filter:blur(3px)}to{opacity:1;transform:none;filter:none}}@media(max-width:767.98px){.mgmp-page .mgmp-hero-strip{padding:18px}.mgmp-page .card-body{padding:14px}.mgmp-page .registration-table{min-width:1050px}.mgmp-page .registration-section-header{gap:10px}}
    </style>

    <div class="mgmp-page">
        <div class="mgmp-hero-strip mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <div class="mgmp-kicker mb-2">Admin</div>
                    <h4 class="mb-1">Persetujuan Registrasi Pengguna</h4>
                    <p class="mb-0 text-white-50">Kelola antrean registrasi yang menunggu review dan pantau user yang sudah aktif setelah disetujui.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="mgmp-chip bg-white text-success">{{ $stats['pending_total'] ?? 0 }} pending</span>
                    <span class="mgmp-chip bg-white text-success">{{ $stats['approved_total'] ?? 0 }} approved</span>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card mgmp-stat-card registration-stat-card p-3 h-100">
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-md">
                            <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                <i class="bx bx-time-five fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="registration-stat-label">Antrean Pending</div>
                            <div class="registration-stat-number">{{ $stats['pending_total'] ?? 0 }}</div>
                            <small class="text-muted">Registrasi menunggu persetujuan</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mgmp-stat-card registration-stat-card p-3 h-100">
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-md">
                            <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                <i class="bx bx-check-shield fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="registration-stat-label">User Approved</div>
                            <div class="registration-stat-number">{{ $stats['approved_total'] ?? 0 }}</div>
                            <small class="text-muted">Akun aktif hasil persetujuan</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mgmp-stat-card registration-stat-card p-3 h-100">
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-md">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="bx bx-briefcase-alt-2 fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="registration-stat-label">Pending Pengurus</div>
                            <div class="registration-stat-number">{{ $stats['pending_pengurus'] ?? 0 }}</div>
                            <small class="text-muted">Calon pengurus belum diproses</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mgmp-stat-card registration-stat-card p-3 h-100">
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-md">
                            <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                <i class="bx bx-book-reader fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="registration-stat-label">Pending Tenaga Pendidik</div>
                            <div class="registration-stat-number">{{ $stats['pending_tenaga_pendidik'] ?? 0 }}</div>
                            <small class="text-muted">Calon guru belum diproses</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
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

                <div class="registration-section-header mb-4">
                    <div>
                        <div class="mgmp-kicker text-success mb-2">Review Queue</div>
                        <h5 class="mb-1">Registrasi Menunggu Persetujuan</h5>
                        <p class="text-muted mb-0">Periksa identitas pendaftar, peran, dan asal sekolah sebelum menyetujui atau menolak.</p>
                    </div>
                    <span class="mgmp-chip">{{ $pendingRegistrations->total() }} total antrean</span>
                </div>

                @if($pendingRegistrations->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle registration-table mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Peran</th>
                                    <th>Jabatan</th>
                                    <th>Asal Sekolah</th>
                                    <th>Diajukan</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingRegistrations as $registration)
                                    <tr>
                                        <td>{{ $pendingRegistrations->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $registration->name }}</div>
                                        </td>
                                        <td>{{ $registration->email }}</td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">{{ ucfirst(str_replace('_', ' ', $registration->role)) }}</span>
                                        </td>
                                        <td>{{ $registration->jabatan ?: '-' }}</td>
                                        <td>{{ optional($registration->madrasah)->name ?? '-' }}</td>
                                        <td>
                                            @if($registration->submitted_at)
                                                <div class="fw-semibold">{{ $registration->submitted_at->format('d M Y') }}</div>
                                                <small class="text-muted">{{ $registration->submitted_at->format('H:i') }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                                <button type="button"
                                                    class="btn btn-sm btn-success approve-btn"
                                                    data-id="{{ $registration->id }}"
                                                    data-name="{{ $registration->name }}">
                                                    <i class="bx bx-check me-1"></i> Approve
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger reject-btn"
                                                    data-id="{{ $registration->id }}"
                                                    data-name="{{ $registration->name }}">
                                                    <i class="bx bx-x me-1"></i> Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $pendingRegistrations->links() }}
                    </div>
                @else
                    <div class="mgmp-empty-state py-5">
                        <i class="bx bx-check-shield"></i>
                        <strong>Tidak ada registrasi pending</strong>
                        <small>Semua pendaftaran sudah diproses.</small>
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="registration-section-header mb-4">
                    <div>
                        <div class="mgmp-kicker text-success mb-2">Approved Users</div>
                        <h5 class="mb-1">Data Nama User Yang Sudah Di-Approve</h5>
                        <p class="text-muted mb-0">Menampilkan akun aktif terbaru dengan peran pengurus atau tenaga pendidik.</p>
                    </div>
                    <span class="mgmp-chip">{{ $approvedUsers->count() }} ditampilkan</span>
                </div>

                @if($approvedUsers->isNotEmpty())
                    <div class="row g-3 mb-4">
                        @foreach($approvedUsers->take(6) as $approvedUser)
                            <div class="col-xl-4 col-md-6">
                                <div class="registration-approved-card h-100">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="registration-approved-avatar">
                                            {{ strtoupper(\Illuminate\Support\Str::substr($approvedUser->name, 0, 1)) }}
                                        </div>
                                        <div class="grow">
                                            <div class="fw-semibold text-dark mb-1">{{ $approvedUser->name }}</div>
                                            <div class="small text-muted mb-2">{{ $approvedUser->email }}</div>
                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                <span class="badge bg-success-subtle text-success">{{ ucfirst(str_replace('_', ' ', $approvedUser->role)) }}</span>
                                                @if($approvedUser->madrasah)
                                                    <span class="badge bg-light text-dark">{{ $approvedUser->madrasah->name }}</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">
                                                Aktif sejak {{ optional($approvedUser->created_at)->format('d M Y H:i') ?? '-' }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle registration-table mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama User</th>
                                    <th>Email</th>
                                    <th>Peran</th>
                                    <th>Sekolah / Jabatan</th>
                                    <th>Aktif Sejak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($approvedUsers as $approvedUser)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $approvedUser->name }}</div>
                                        </td>
                                        <td>{{ $approvedUser->email }}</td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success">{{ ucfirst(str_replace('_', ' ', $approvedUser->role)) }}</span>
                                        </td>
                                        <td>
                                            @if($approvedUser->role === 'pengurus')
                                                {{ $approvedUser->ketugasan ?: $approvedUser->jabatan ?: '-' }}
                                            @else
                                                {{ optional($approvedUser->madrasah)->name ?? '-' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($approvedUser->created_at)
                                                <div class="fw-semibold">{{ $approvedUser->created_at->format('d M Y') }}</div>
                                                <small class="text-muted">{{ $approvedUser->created_at->format('H:i') }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="mgmp-empty-state py-5">
                        <i class="bx bx-user-check"></i>
                        <strong>Belum ada user approved</strong>
                        <small>Data user yang disetujui akan tampil di sini setelah proses approve dilakukan.</small>
                    </div>
                @endif
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
    <script>
        $(document).ready(function () {
            $('.approve-btn').on('click', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');

                Swal.fire({
                    title: 'Approve Registration?',
                    text: `Approve registration for ${name}? Sistem akan membuat akun dan mengirim email kredensial login.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0e8549',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Approve',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $('<form>', {
                            'method': 'POST',
                            'action': '{{ url("admin/pending-registrations") }}/' + id + '/approve'
                        });

                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': '_token',
                            'value': '{{ csrf_token() }}'
                        }));

                        $('body').append(form);
                        form.submit();
                    }
                });
            });

            $('.reject-btn').on('click', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');

                Swal.fire({
                    title: 'Reject Registration?',
                    text: `Reject registration for ${name}? Tindakan ini akan menghapus antrean pendaftaran ini.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Reject',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $('<form>', {
                            'method': 'POST',
                            'action': '{{ url("admin/pending-registrations") }}/' + id + '/reject'
                        });

                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': '_token',
                            'value': '{{ csrf_token() }}'
                        }));

                        $('body').append(form);
                        form.submit();
                    }
                });
            });
        });
    </script>

    <style>
        .registration-stat-card {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbf9 100%);
        }

        .registration-stat-label {
            color: #6b7b75;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .registration-stat-number {
            color: #004b4c;
            font-size: 28px;
            font-weight: 800;
            line-height: 1.1;
            margin: 4px 0;
        }

        .registration-section-header {
            align-items: flex-start;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: space-between;
        }

        .registration-table thead th {
            white-space: nowrap;
        }

        .registration-approved-card {
            background: linear-gradient(180deg, #ffffff 0%, #f7fbf8 100%);
            border: 1px solid #e5eee9;
            border-radius: 18px;
            padding: 18px;
        }

        .registration-approved-avatar {
            align-items: center;
            background: linear-gradient(135deg, #004b4c, #0e8549);
            border-radius: 16px;
            color: #fff;
            display: inline-flex;
            font-size: 18px;
            font-weight: 800;
            height: 48px;
            justify-content: center;
            min-width: 48px;
        }
    </style>
@endsection
