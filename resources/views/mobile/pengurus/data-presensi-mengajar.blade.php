@extends('layouts.mobile')

@section('title', 'Data Presensi Mengajar')

@section('content')
<?php

date_default_timezone_set('Asia/Jakarta');

$b = time();
$hour = date('G', $b);

if ($hour >= 0 && $hour <= 11) {
    $congrat = 'Selamat Pagi';
} elseif ($hour >= 12 && $hour <= 14) {
    $congrat = 'Selamat Siang ';
} elseif ($hour >= 15 && $hour <= 17) {
    $congrat = 'Selamat Sore ';
} elseif ($hour >= 17 && $hour <= 18) {
    $congrat = 'Selamat Petang ';
} elseif ($hour >= 19 && $hour <= 23) {
    $congrat = 'Selamat Malam ';
}

?>
<header class="mobile-header d-md-none" style="position: sticky; top: 0; z-index: 1050;">
    <div class="container-fluid px-0 py-0" style="background: transparent;">
        <div class="d-flex align-items-center justify-content-between">
            <!-- User Avatar (Left) -->
            <div class="avatar-sm me-3 ms-3">
                <img
                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                    class="avatar-img rounded-circle"
                    alt="User"
                >
            </div>

            <!-- Welcome Text (Right-aligned) -->
            <div class="text-start grow">
                <small class="text-dark fw-medium" style="font-size: 11px;">{{ $congrat }}</small>
                <h6 class="mb-0 fw-semibold text-dark" style="font-size: 14px;">{{ Auth::user()->name }}</h6>
            </div>

            <!-- Notification and Menu Buttons (Right) -->
            <div class="d-flex align-items-center">
                <!-- Notification Bell -->
                <a href="{{ route('mobile.notifications') }}" class="btn btn-link text-decoration-none p-0 me-2 position-relative">
                    <i class="bx bx-bell" style="font-size: 22px; color: #db3434;"></i>
                    <span id="notificationBadge" class="badge bg-danger rounded-pill position-absolute" style="font-size: 9px; padding: 2px 5px; top: -4px; right: -4px; display: none;">0</span>
                </a>

                <!-- Dropdown Menu -->
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded" style="font-size: 22px; color: #000000;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item py-2" href="{{ route('mobile.notifications') }}"><i class="bx bx-bell me-2"></i>Notifikasi</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item py-2" href="{{ route('dashboard') }}"><i class="bx bx-home me-2"></i>Dashboard</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bx bx-log-out me-2"></i>Logout
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        background-color: #f8f9fb;
    }

    .mobile-header,
    .mobile-header .container-fluid {
        background: transparent !important;
    }

    .mobile-header {
        box-shadow: none !important;
        border: none !important;
    }

    .avatar-sm {
        width: 40px;
        height: 40px;
        overflow: hidden;
        border-radius: 50%;
    }

    .avatar-sm .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    .card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .card-header {
        border-radius: 12px 12px 0 0 !important;
        padding: 16px;
    }

    .table {
        font-size: 12px;
    }

    .table th {
        font-weight: 600;
        color: #004b4c;
        background: #f8f9fa;
    }

    .table td {
        vertical-align: middle;
    }

    .badge {
        font-size: 10px;
        padding: 4px 8px;
    }
</style>

<div class="container py-3" style="max-width: 520px; margin: auto;">
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body py-2">
                    <h4 class="page-title mb-0" style="font-size: 16px; font-weight: 600; color: #004b4c;">
                        <i class="bx bx-bar-chart-alt-2 me-2"></i>Data Presensi Mengajar
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Guru</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Jam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachingAttendances as $attendance)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($attendance->tanggal)->format('d M Y') }}</td>
                                    <td>{{ $attendance->teachingSchedule->teacher->name ?? '-' }}</td>
                                    <td>{{ $attendance->teachingSchedule->subject ?? '-' }}</td>
                                    <td>{{ $attendance->teachingSchedule->start_time ?? '-' }} - {{ $attendance->teachingSchedule->end_time ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-success">Hadir</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data presensi mengajar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $teachingAttendances->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
<div style="height: 80px;"></div> <!-- Spacer for bottom nav -->
@endsection
