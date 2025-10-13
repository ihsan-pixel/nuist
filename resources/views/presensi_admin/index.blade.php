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

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Presensi Admin @endslot
    @slot('title') Data Presensi @endslot
@endcomponent

@if($user->role === 'super_admin')
    <!-- Super Admin View: 5 tables per madrasah -->
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="bx bx-calendar me-2"></i>Data Presensi per Tanggal: {{ $selectedDate->format('d-m-Y') }}
                    </h4>
                    <div class="d-flex align-items-center gap-2">
                        <form method="GET" action="{{ route('presensi_admin.index') }}" class="d-flex align-items-center" id="date-form">
                            <input type="date" id="date-picker" name="date" class="form-control form-control-sm" value="{{ $selectedDate->format('Y-m-d') }}">
                        </form>
                        <a href="{{ route('presensi_admin.export', ['date' => $selectedDate->format('Y-m-d')]) }}" class="btn btn-success btn-sm">
                            <i class="bx bx-download"></i> Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center py-2">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bx bx-user-check bx-sm text-primary me-2"></i>
                        <span class="h5 mb-0 text-primary fw-bold">{{ $summary['users_presensi'] }}</span>
                    </div>
                    <small class="text-muted d-block mt-1">Users Presensi</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center py-2">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bx bx-building bx-sm text-success me-2"></i>
                        <span class="h5 mb-0 text-success fw-bold">{{ $summary['sekolah_presensi'] }}</span>
                    </div>
                    <small class="text-muted d-block mt-1">Sekolah Presensi</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body text-center py-2">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bx bx-user-x bx-sm text-danger me-2"></i>
                        <span class="h5 mb-0 text-danger fw-bold">{{ $summary['guru_tidak_presensi'] }}</span>
                    </div>
                    <small class="text-muted d-block mt-1">Guru Belum Presensi</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($madrasahData as $data)
        <div class="col-12 col-sm-6 col-md-4 col-lg-2 col-xl-2 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0 small">
                        <i class="bx bx-building me-1"></i>
                        <span class="madrasah-detail-link" style="cursor: pointer; text-decoration: underline;" data-madrasah-id="{{ $data['madrasah']->id }}" data-madrasah-name="{{ $data['madrasah']->name }}">
                            {{ $data['madrasah']->name }}
                        </span>
                    </h6>
                    <small class="text-muted">{{ count($data['presensi']) }} guru</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table id="madrasah-table-{{ $loop->index }}" class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['presensi'] as $presensi)
                                <tr>
                                    <td class="small">
                                        <span class="user-detail-link" style="cursor: pointer; text-decoration: underline;" data-user-id="{{ $presensi['user_id'] ?? '' }}" data-user-name="{{ $presensi['nama'] }}">
                                            {{ $presensi['nama'] }}
                                        </span>
                                    </td>
                                    <td class="small">
                                        @if($presensi['status'] == 'hadir')
                                            <span class="badge bg-success">Hadir</span>
                                        @elseif($presensi['status'] == 'terlambat')
                                            <span class="badge bg-warning">Terlambat</span>
                                        @elseif($presensi['status'] == 'izin')
                                            <span class="badge bg-info">Izin</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Hadir</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted small">
                                        <small>Tidak ada tenaga pendidik</small>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- User Detail Modal -->
    <div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailModalLabel">Detail Presensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="userDetailTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Informasi Pengguna</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">Riwayat Presensi</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="userDetailTabContent">
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="mb-2"><strong>Nama:</strong> <span id="detail-name"></span></div>
                                    <div class="mb-2"><strong>Email:</strong> <span id="detail-email" class="text-muted"></span></div>
                                    <div class="mb-2"><strong>Madrasah:</strong> <span id="detail-madrasah"></span></div>
                                    <div class="mb-2"><strong>Status Kepegawaian:</strong> <span id="detail-status"></span></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2"><strong>NIP:</strong> <span id="detail-nip" class="text-muted"></span></div>
                                    <div class="mb-2"><strong>NUPTK:</strong> <span id="detail-nuptk" class="text-muted"></span></div>
                                    <div class="mb-2"><strong>No HP:</strong> <span id="detail-phone"></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="history" role="tabpanel">
                            <div class="table-responsive mt-3" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 100px;">Tanggal</th>
                                            <th style="width: 80px;">Masuk</th>
                                            <th style="width: 80px;">Keluar</th>
                                            <th style="width: 80px;">Status</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail-history-body">
                                        <!-- Data will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Madrasah Detail Modal -->
    <div class="modal fade" id="madrasahDetailModal" tabindex="-1" aria-labelledby="madrasahDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="madrasahDetailModalLabel">Detail Madrasah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-2"><strong>Nama Madrasah:</strong> <span id="madrasah-detail-name"></span></div>
                            <div class="mb-2"><strong>SCOD:</strong> <span id="madrasah-detail-scod"></span></div>
                            <div class="mb-2"><strong>Kabupaten:</strong> <span id="madrasah-detail-kabupaten"></span></div>
                            <div class="mb-2"><strong>Alamat:</strong> <span id="madrasah-detail-alamat"></span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2"><strong>Hari KBM:</strong> <span id="madrasah-detail-hari-kbm"></span></div>
                            <div class="mb-2"><strong>Latitude:</strong> <span id="madrasah-detail-latitude"></span></div>
                            <div class="mb-2"><strong>Longitude:</strong> <span id="madrasah-detail-longitude"></span></div>
                            <div class="mb-2"><strong>Map Link:</strong> <a id="madrasah-detail-map-link" href="#" target="_blank">Lihat Peta</a></div>
                            <div class="mb-2"><strong>Polygon Koordinat:</strong> <span id="madrasah-detail-polygon">-</span></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Area Poligon Presensi</label>
                        <div id="madrasah-detail-map" style="height: 250px; width: 100%; margin-top: 15px; border: 1px solid #ddd; border-radius: 4px;"></div>
                        <small class="text-muted">Area poligon presensi madrasah ini.</small>
                    </div>

                    <h6>Daftar Tenaga Pendidik:</h6>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Status Kepegawaian</th>
                                    <th>Status Presensi</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Keluar</th>
                                </tr>
                            </thead>
                            <tbody id="madrasah-detail-guru-body">
                                <!-- Data will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Admin and other roles: Original view -->
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center py-2">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bx bx-user-check bx-sm text-primary me-2"></i>
                        <span class="h5 mb-0 text-primary fw-bold">{{ $summary['users_presensi'] }}</span>
                    </div>
                    <small class="text-muted d-block mt-1">Users Presensi</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center py-2">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bx bx-building bx-sm text-success me-2"></i>
                        <span class="h5 mb-0 text-success fw-bold">{{ $summary['sekolah_presensi'] }}</span>
                    </div>
                    <small class="text-muted d-block mt-1">Sekolah Presensi</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body text-center py-2">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bx bx-user-x bx-sm text-danger me-2"></i>
                        <span class="h5 mb-0 text-danger fw-bold">{{ $summary['guru_tidak_presensi'] }}</span>
                    </div>
                    <small class="text-muted d-block mt-1">Guru Belum Presensi</small>
                </div>
            </div>
        </div>
    </div>

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
@endif
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

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<script>
$(document).ready(function () {
    @if($user->role !== 'super_admin')
    let table = $("#datatable-buttons").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
    @endif

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



    // Real-time update for super admin
    let currentDate = '{{ $selectedDate->format('Y-m-d') }}';
    let updateInterval;

    function updatePresensiData() {
        $.ajax({
            url: '{{ route('presensi_admin.data') }}',
            type: 'GET',
            data: { date: currentDate },
            success: function(data) {
                updateTables(data);
                // Update summary cards
                updateSummaryCards();
            },
            error: function(xhr, status, error) {
                console.log('Error updating data:', error);
            }
        });
    }

    function updateSummaryCards() {
        $.ajax({
            url: '{{ route('presensi_admin.summary') }}',
            type: 'GET',
            data: { date: currentDate },
            success: function(data) {
                // Update summary cards with JSON data
                $('.row.mb-4 .card .h5').eq(0).text(data.users_presensi);
                $('.row.mb-4 .card .h5').eq(1).text(data.sekolah_presensi);
                $('.row.mb-4 .card .h5').eq(2).text(data.guru_tidak_presensi);
            },
            error: function(xhr, status, error) {
                console.log('Error updating summary:', error);
            }
        });
    }

    function updateTables(data) {
        data.forEach(function(madrasahData, index) {
            let tableBody = $('#madrasah-table-' + index + ' tbody');
            tableBody.empty();

            if (madrasahData.presensi.length > 0) {
                madrasahData.presensi.forEach(function(presensi) {
                    let statusBadge = '';
                    if (presensi.status === 'hadir') {
                        statusBadge = '<span class="badge bg-success">Hadir</span>';
                    } else if (presensi.status === 'terlambat') {
                        statusBadge = '<span class="badge bg-warning">Terlambat</span>';
                    } else if (presensi.status === 'izin') {
                        statusBadge = '<span class="badge bg-info">Izin</span>';
                    } else {
                        statusBadge = '<span class="badge bg-secondary">Tidak Hadir</span>';
                    }

                    let row = '<tr>' +
                        '<td class="small">' +
                        '<span class="user-detail-link" style="cursor: pointer; text-decoration: underline;" data-user-id="' + presensi.user_id + '" data-user-name="' + presensi.nama + '">' +
                        presensi.nama +
                        '</span>' +
                        '</td>' +
                        '<td class="small">' + statusBadge + '</td>' +
                        '</tr>';
                    tableBody.append(row);
                });
            } else {
                let emptyRow = '<tr>' +
                    '<td colspan="2" class="text-center text-muted small">' +
                    '<small>Tidak ada tenaga pendidik</small>' +
                    '</td>' +
                    '</tr>';
                tableBody.append(emptyRow);
            }
        });
    }

    @if($user->role === 'super_admin')
    // Handle user detail modal
    $(document).on('click', '.user-detail-link', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let userId = $(this).data('user-id');
        let userName = $(this).data('user-name');
        $('#userDetailModalLabel').text('Detail Presensi: ' + userName);

        $.ajax({
            url: '{{ url('/presensi-admin/detail') }}/' + userId,
            type: 'GET',
            success: function(data) {
                // Populate user info tab
                $('#detail-name').text(data.user.name);
                $('#detail-email').text(data.user.email);
                $('#detail-madrasah').text(data.user.madrasah);
                $('#detail-status').text(data.user.status_kepegawaian);
                $('#detail-nip').text(data.user.nip || '-');
                $('#detail-nuptk').text(data.user.nuptk || '-');
                $('#detail-phone').text(data.user.no_hp || '-');

                // Populate history tab
                let presensiRows = '';
                data.presensi_history.forEach(function(presensi) {
                    let statusBadge = '';
                    if (presensi.status === 'hadir') {
                        statusBadge = '<span class="badge bg-success">Hadir</span>';
                    } else if (presensi.status === 'terlambat') {
                        statusBadge = '<span class="badge bg-warning">Terlambat</span>';
                    } else if (presensi.status === 'izin') {
                        statusBadge = '<span class="badge bg-info">Izin</span>';
                    } else {
                        statusBadge = '<span class="badge bg-secondary">' + presensi.status + '</span>';
                    }

                    presensiRows += '<tr>' +
                        '<td>' + presensi.tanggal + '</td>' +
                        '<td>' + (presensi.waktu_masuk || '-') + '</td>' +
                        '<td>' + (presensi.waktu_keluar || '-') + '</td>' +
                        '<td>' + statusBadge + '</td>' +
                        '<td>' + (presensi.keterangan || '-') + '</td>' +
                        '</tr>';
                });
                $('#detail-history-body').html(presensiRows);

                // Show modal
                $('#userDetailModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.log('Error loading user detail:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal memuat detail pengguna'
                });
            }
        });
        return false;
    });

    // Handle madrasah detail modal
    $(document).on('click', '.madrasah-detail-link', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let madrasahId = $(this).data('madrasah-id');
        let madrasahName = $(this).data('madrasah-name');
        $('#madrasahDetailModalLabel').text('Detail Madrasah: ' + madrasahName);

        $.ajax({
            url: '{{ url('/presensi-admin/madrasah-detail') }}/' + madrasahId,
            type: 'GET',
            data: { date: currentDate },
            success: function(data) {
                // Populate madrasah info
                $('#madrasah-detail-name').text(data.madrasah.name);
                $('#madrasah-detail-scod').text(data.madrasah.scod || '-');
                $('#madrasah-detail-kabupaten').text(data.madrasah.kabupaten || '-');
                $('#madrasah-detail-alamat').text(data.madrasah.alamat || '-');
                $('#madrasah-detail-hari-kbm').text(data.madrasah.hari_kbm || '-');
                $('#madrasah-detail-latitude').text(data.madrasah.latitude || '-');
                $('#madrasah-detail-longitude').text(data.madrasah.longitude || '-');
                if (data.madrasah.map_link) {
                    $('#madrasah-detail-map-link').attr('href', data.madrasah.map_link).show();
                } else {
                    $('#madrasah-detail-map-link').hide();
                }
                if (data.madrasah.polygon_koordinat) {
                    $('#madrasah-detail-polygon').text('Ada (Tersimpan)');
                } else {
                    $('#madrasah-detail-polygon').text('Tidak Ada');
                }



                // Initialize map for polygon display
                initializeMadrasahMap(data.madrasah);

                // Populate guru list
                let guruRows = '';
                data.tenaga_pendidik.forEach(function(guru) {
                    let statusBadge = '';
                    if (guru.status === 'hadir') {
                        statusBadge = '<span class="badge bg-success">Hadir</span>';
                    } else if (guru.status === 'terlambat') {
                        statusBadge = '<span class="badge bg-warning">Terlambat</span>';
                    } else if (guru.status === 'izin') {
                        statusBadge = '<span class="badge bg-info">Izin</span>';
                    } else {
                        statusBadge = '<span class="badge bg-secondary">Tidak Hadir</span>';
                    }

                    guruRows += '<tr>' +
                        '<td>' + guru.nama + '</td>' +
                        '<td>' + (guru.status_kepegawaian || '-') + '</td>' +
                        '<td>' + statusBadge + '</td>' +
                        '<td>' + (guru.waktu_masuk || '-') + '</td>' +
                        '<td>' + (guru.waktu_keluar || '-') + '</td>' +
                        '</tr>';
                });
                $('#madrasah-detail-guru-body').html(guruRows);

                // Show modal
                $('#madrasahDetailModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.log('Error loading madrasah detail:', error);
                console.log('Response:', xhr.responseText);
                let errorMessage = 'Gagal memuat detail madrasah';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            }
        });
        return false;
    });

    // Update data every 30 seconds
    updateInterval = setInterval(updatePresensiData, 30000);

    // Handle date change
    $('#date-picker').on('change', function(e) {
        e.preventDefault();
        currentDate = $(this).val();
        // Update export link
        let exportLink = '{{ route('presensi_admin.export', ['date' => 'PLACEHOLDER']) }}'.replace('PLACEHOLDER', currentDate);
        $('a[href*="presensi_admin.export"]').attr('href', exportLink);
        updatePresensiData();
        return false;
    });

    // Initial update
    updatePresensiData();


    // Function to initialize map for madrasah detail
    function initializeMadrasahMap(madrasah) {
        // Clear any existing map
        if (window.madrasahMap) {
            window.madrasahMap.remove();
        }

        // Initialize Leaflet map with default center
        let defaultLat = -7.7956;
        let defaultLon = 110.3695;
        let lat = madrasah.latitude || defaultLat;
        let lon = madrasah.longitude || defaultLon;
        window.madrasahMap = L.map('madrasah-detail-map').setView([lat, lon], 16);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(window.madrasahMap);

        let drawnItems = new L.FeatureGroup();
        window.madrasahMap.addLayer(drawnItems);

        // Add marker if coordinates exist
        if (madrasah.latitude && madrasah.longitude) {
            L.marker([lat, lon])
                .addTo(window.madrasahMap)
                .bindPopup('<b>' + madrasah.name + '</b><br/>' + (madrasah.alamat || ''));
        }

        // Load existing polygon like in edit modal (read-only)
        if (madrasah.polygon_koordinat) {
            try {
                let geometry = JSON.parse(madrasah.polygon_koordinat);
                let layer = L.geoJSON(geometry);
                layer.eachLayer(function(l) {
                    drawnItems.addLayer(l);
                });
                if (drawnItems.getLayers().length > 0) {
                    window.madrasahMap.fitBounds(drawnItems.getBounds());
                }
            } catch (e) {
                console.error("Invalid GeoJSON data for polygon:", e);
            }
        }

        // Fit map to show all elements and adjust zoom for better view
        setTimeout(() => {
            window.madrasahMap.invalidateSize();

            // Force a resize to ensure proper rendering
            window.madrasahMap._onResize();

            // If we have both marker and polygon, fit bounds to show everything
            if (drawnItems.getLayers().length > 0 && madrasah.latitude && madrasah.longitude) {
                // Create a group with both marker and polygon layers
                let allLayers = new L.FeatureGroup();
                drawnItems.eachLayer(layer => allLayers.addLayer(layer));

                // Add a temporary marker to the group for bounds calculation
                let tempMarker = L.marker([madrasah.latitude, madrasah.longitude]);
                allLayers.addLayer(tempMarker);

                // Fit bounds to show all elements
                window.madrasahMap.fitBounds(allLayers.getBounds(), { padding: [20, 20] });

                // Remove temporary marker
                allLayers.removeLayer(tempMarker);
            } else if (drawnItems.getLayers().length > 0) {
                // Only polygon exists, fit to polygon bounds
                window.madrasahMap.fitBounds(drawnItems.getBounds(), { padding: [20, 20] });
            } else if (madrasah.latitude && madrasah.longitude) {
                // Only marker exists, center on marker with appropriate zoom
                window.madrasahMap.setView([madrasah.latitude, madrasah.longitude], 18);
            } else {
                // No specific location, use default view
                window.madrasahMap.setView([defaultLat, defaultLon], 13);
            }

            // Additional invalidateSize after bounds fitting to ensure polygon renders
            setTimeout(() => {
                window.madrasahMap.invalidateSize();
            }, 200);
        }, 100);
    }
    @endif
});
</script>
@endsection

