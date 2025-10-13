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

@if($user->role === 'super_admin')
    <!-- Super Admin View: 5 tables per madrasah -->
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="bx bx-calendar me-2"></i>Data Presensi per Tanggal: {{ $selectedDate->format('d-m-Y') }}
                    </h4>
                    <form method="GET" action="{{ route('presensi_admin.index') }}" class="d-flex align-items-center">
                        <input type="date" id="date-picker" name="date" class="form-control form-control-sm" value="{{ $selectedDate->format('Y-m-d') }}">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($madrasahData as $data)
        <div class="col-12 col-sm-6 col-md-4 col-lg-2 col-xl-2 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bx bx-building me-1"></i>{{ $data['madrasah']->name }}
                    </h6>
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
                                        <a href="#" class="text-decoration-none user-detail-link" data-user-id="{{ $presensi['user_id'] ?? '' }}" data-user-name="{{ $presensi['nama'] }}">
                                            {{ $presensi['nama'] }}
                                        </a>
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
@else
    <!-- Admin and other roles: Original view -->
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

    @if($user->role === 'super_admin')
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
            },
            error: function(xhr, status, error) {
                console.log('Error updating data:', error);
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
                        '<a href="#" class="text-decoration-none user-detail-link" data-user-id="' + presensi.user_id + '" data-user-name="' + presensi.nama + '">' +
                        presensi.nama +
                        '</a>' +
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

    // Handle user detail popup
    $(document).on('click', '.user-detail-link', function(e) {
        e.preventDefault();
        let userId = $(this).data('user-id');
        let userName = $(this).data('user-name');

        $.ajax({
            url: '{{ route('presensi_admin.detail', ':userId') }}'.replace(':userId', userId),
            type: 'GET',
            success: function(data) {
                showUserDetailPopup(data, userName);
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
    });

    function showUserDetailPopup(data, userName) {
        let presensiRows = '';
        data.presensi_history.forEach(function(presensi) {
            let statusBadge = '';
            let statusClass = '';
            if (presensi.status === 'hadir') {
                statusBadge = '<i class="bx bx-check-circle text-success me-1"></i>Hadir';
                statusClass = 'table-success';
            } else if (presensi.status === 'terlambat') {
                statusBadge = '<i class="bx bx-time-five text-warning me-1"></i>Terlambat';
                statusClass = 'table-warning';
            } else if (presensi.status === 'izin') {
                statusBadge = '<i class="bx bx-calendar text-info me-1"></i>Izin';
                statusClass = 'table-info';
            } else {
                statusBadge = '<i class="bx bx-x-circle text-secondary me-1"></i>' + presensi.status;
                statusClass = 'table-secondary';
            }

            presensiRows += '<tr class="' + statusClass + '">' +
                '<td class="text-center fw-bold">' + presensi.tanggal + '</td>' +
                '<td class="text-center">' + (presensi.waktu_masuk || '<span class="text-muted">-</span>') + '</td>' +
                '<td class="text-center">' + (presensi.waktu_keluar || '<span class="text-muted">-</span>') + '</td>' +
                '<td class="text-center">' + statusBadge + '</td>' +
                '<td class="small">' + (presensi.keterangan || '<span class="text-muted">-</span>') + '</td>' +
                '<td class="small text-truncate" style="max-width: 120px;" title="' + (presensi.lokasi || '-') + '">' + (presensi.lokasi || '<span class="text-muted">-</span>') + '</td>' +
                '</tr>';
        });

        let content = '<div class="container-fluid">' +
            '<div class="row g-2">' +
            '<div class="col-lg-5">' +
            '<div class="card">' +
            '<div class="card-header">' +
            '<small class="text-muted mb-0"><i class="bx bx-user me-1"></i>Informasi Pengguna</small>' +
            '</div>' +
            '<div class="card-body p-2">' +
            '<div class="row g-1">' +
            '<div class="col-4"><small><strong>Nama:</strong></small></div>' +
            '<div class="col-8"><small>' + data.user.name + '</small></div>' +
            '<div class="col-4"><small><strong>Email:</strong></small></div>' +
            '<div class="col-8"><small class="text-muted">' + data.user.email + '</small></div>' +
            '<div class="col-4"><small><strong>Madrasah:</strong></small></div>' +
            '<div class="col-8"><small>' + data.user.madrasah + '</small></div>' +
            '<div class="col-4"><small><strong>Status:</strong></small></div>' +
            '<div class="col-8"><small>' + data.user.status_kepegawaian + '</small></div>' +
            '<div class="col-4"><small><strong>NIP:</strong></small></div>' +
            '<div class="col-8"><small><code>' + (data.user.nip || '-') + '</code></small></div>' +
            '<div class="col-4"><small><strong>NUPTK:</strong></small></div>' +
            '<div class="col-8"><small><code>' + (data.user.nuptk || '-') + '</code></small></div>' +
            '<div class="col-4"><small><strong>No HP:</strong></small></div>' +
            '<div class="col-8"><small>' + (data.user.no_hp || '-') + '</small></div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-lg-7">' +
            '<div class="card">' +
            '<div class="card-header d-flex justify-content-between align-items-center">' +
            '<small class="text-muted mb-0"><i class="bx bx-history me-1"></i>Riwayat Presensi</small>' +
            '<small class="text-muted">10 data terakhir</small>' +
            '</div>' +
            '<div class="card-body p-0">' +
            '<div style="max-height: 300px; overflow-y: auto;">' +
            '<table class="table table-sm mb-0">' +
            '<thead class="table-light">' +
            '<tr>' +
            '<th class="text-center" style="width: 80px;"><small>Tanggal</small></th>' +
            '<th class="text-center" style="width: 60px;"><small>Masuk</small></th>' +
            '<th class="text-center" style="width: 60px;"><small>Keluar</small></th>' +
            '<th class="text-center" style="width: 80px;"><small>Status</small></th>' +
            '<th class="text-center"><small>Keterangan</small></th>' +
            '<th class="text-center" style="width: 100px;"><small>Lokasi</small></th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' + presensiRows + '</tbody>' +
            '</table>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';

        Swal.fire({
            title: '<i class="bx bx-detail me-2"></i>Detail Presensi: ' + userName,
            html: content,
            width: '95%',
            maxWidth: '1200px',
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: 'swal-wide'
            }
        });
    }

    // Update data every 30 seconds
    updateInterval = setInterval(updatePresensiData, 30000);

    // Handle date change
    $('#date-picker').on('change', function() {
        currentDate = $(this).val();
        updatePresensiData();
    });

    // Initial update
    updatePresensiData();
    @endif
});
</script>
@endsection

