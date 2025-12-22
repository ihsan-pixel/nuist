@extends('layouts.mobile')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan Aktivitas')

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

// Calculate progress color from red to bright green based on percentage
$red = 255 - (int)($kinerjaPercent * 2.55);
$green = (int)($kinerjaPercent * 2.55);
$progressColor = "rgb($red, $green, 0)";

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
            <div class="text-start flex-grow-1">
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
<div class="container py-3" style="max-width: 520px; margin: auto;">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            background-color: #f8f9fb;
            position: relative;
            min-height: 100vh; /* 🔥 minimal tinggi layar */
            overflow-x: hidden;
        }

        body::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: linear-gradient(to bottom, rgba(248,249,251,0), #f8f9fb);
            z-index: -1;
        }

        @media (max-width: 768px) {
            body {
                background-size: 100% auto;
                background-attachment: scroll; /* 🔥 hindari bug mobile */
            }
        }

        .dashboard-header {
            background: #f8f9fb url('{{ asset("images/qwe1.png") }}') no-repeat center center;
            background-size: cover;
            border-radius: 14px;
            padding: 12px;
            color: #004b4c;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176);
        }

        .id-card {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .id-card-photo {
            width: 56px;
            height: 76px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            /* border: 2px solid rgba(255,255,255,0.4); */
        }

        .id-card-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .id-card-details {
            flex: 1;
        }

        .row-item {
            display: grid;
            grid-template-columns: 70px 10px 1fr;
            align-items: center;
            font-size: 10px;
            line-height: 1.4;
            margin-bottom: 2px;
        }

        .row-item:last-child {
            margin-bottom: 0;
        }

        .label_text {
            color: #004b4c;
            text-align: left;
            font-size: 10px;
        }

        .label {
            color: #004b4c;
            text-align: left;
        }

        .colon {
            color: #004b4c;
            text-align: center;
        }

        .value {
            font-weight: 600;
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #004b4c;
        }

        .badge-status {
            background: rgba(0,75,76,0.1);
            padding: 2px 6px;
            border-radius: 6px;
            font-size: 10px;
            color: #004b4c;
            border: 1px solid rgba(0,75,76,0.2);
        }

        .id-card-title {
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #004b4c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(0,75,76,0.3);
            padding-bottom: 4px;
        }

        .mobile-header,
        .mobile-header .container-fluid {
            background: transparent !important;
        }

        .mobile-header {
            box-shadow: none !important;
            border: none !important;
        }

        body {
            background-color: transparent !important;
        }

        .name-form {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176);
            margin-bottom: 0px;
            display: inline-block;
        }

        .stats-form {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176);
            margin-bottom: 12px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }

        .stat-item {
            text-align: center;
            padding: 6px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .stat-item .icon-container {
            margin-bottom: 4px;
        }

        .stat-item i {
            font-size: 18px;
        }

        .stat-item h6 {
            font-size: 12px;
            margin-bottom: 0;
            font-weight: 600;
        }

        .stat-item small {
            font-size: 9px;
            color: #6c757d;
        }

        .services-form {
            /* background: #fff; */
            border-radius: 12px;
            padding: 12px;
            /* box-shadow: 0 2px 8px rgba(0,0,0,0.05); */
            margin-bottom: 12px;
            min-height: 50px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            text-align: center;
        }

        .service-wrapper {
            text-align: center;
        }

        .service-item {
            position: relative;
            border-radius: 8px;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease-in-out;
            height: 64px;
            width: 100%;
            box-sizing: border-box;
            border: 0px solid rgba(0,75,76,0.2);
            box-shadow: #000000
        }

        .extra-service {
            visibility: hidden;
            height: 0;
            overflow: hidden;
        }

        .extra-service.show {
            visibility: visible;
            height: auto;
        }

        .service-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        .service-item i {
            font-size: 30px;
            color: #003d3d;
        }

        .service-label {
            font-size: 10px;
            font-weight: 600;
            margin-top: 6px;
            color: #333;
        }

        .service-item i {
            font-size: 28px;
            color: #003d3d;
        }

        .service-item h6 {
            font-size: 10px;
            margin-bottom: 0;
            font-weight: 600;
            color: #ffffff;
        }

        .info-section {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 12px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .info-item {
            padding: 6px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .info-item small {
            color: #6c757d;
            font-size: 10px;
        }

        .info-item strong {
            font-size: 11px;
            color: #333;
        }

        .schedule-section {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            /* box-shadow: 0 2px 8px rgba(0,0,0,0.05); */
            margin-bottom: 12px;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .schedule-item {
            padding: 6px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .schedule-item strong {
            font-size: 11px;
            color: #333;
        }

        .schedule-item small {
            color: #6c757d;
            font-size: 10px;
        }

        /* Schedule Carousel Styles */
        .schedule-carousel {
            display: flex;
            overflow-x: auto;
            gap: 12px;
            padding: 0 12px;
            scrollbar-width: none; /* hide scrollbar for better mobile look */
            -ms-overflow-style: none; /* IE and Edge */
        }

        .schedule-carousel::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }

        .schedule-card {
            flex: 0 0 60vw; /* 60% of viewport width */
            padding: 16px;
            background: linear-gradient(to bottom, #fdbd57, #f89a3c);
            border-radius: 8px;
            text-align: left;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .attendance-indicator {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .schedule-header strong {
            font-size: 14px;
            color: white;
            margin-bottom: 4px;
        }

        .schedule-header small {
            font-size: 12px;
            color: white;
        }

        .schedule-time {
            margin: 8px 0;
        }

        .schedule-time small {
            font-size: 11px;
            color: white;
        }

        .schedule-status .badge {
            font-size: 10px;
            padding: 4px 8px;
        }

        .quick-actions {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 60px;
        }

        .quick-actions-header {
            background: #f8f9fa;
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .quick-actions-header h6 {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin: 0;
        }

        .quick-actions-content {
            padding: 12px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .action-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            border-radius: 8px;
            padding: 12px 8px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 500;
            text-align: center;
            transition: all 0.2s;
        }

        .action-button:hover {
            background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
            color: white;
            transform: translateY(-1px);
        }

        .action-button i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .section-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            color: #333;
        }

        .no-schedule {
            text-align: center;
            padding: 16px;
            color: #999;
        }

        .no-schedule i {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .no-schedule p {
            font-size: 12px;
            margin: 0;
        }

        /* Calendar Styles */
        .calendar-section {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 60px;
        }

        .calendar-container {
            width: 100%;
        }

        .calendar-header {
            text-align: center;
            margin-bottom: 12px;
        }

        .calendar-title {
            font-weight: 600;
            font-size: 12px;
            color: #333;
            margin: 0;
        }

        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            margin-bottom: 8px;
        }

        .weekday-label {
            text-align: center;
            font-size: 9px;
            font-weight: 600;
            color: #666;
            padding: 4px 0;
            text-transform: uppercase;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }

        .calendar-day {
            aspect-ratio: 1;
            border-radius: 6px;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
            min-height: 40px;
        }

        .calendar-day.empty {
            background: transparent;
            border: none;
        }

        .calendar-day.today {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            border-color: #004b4c;
        }

        .calendar-day.has-presensi {
            border-width: 2px;
        }

        .calendar-day.status-hadir {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }

        .calendar-day.status-izin {
            background: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }

        .calendar-day.status-alpha {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }

        .calendar-day.today.status-hadir,
        .calendar-day.today.status-izin,
        .calendar-day.today.status-alpha {
            color: white;
        }

        .day-number {
            font-size: 11px;
            font-weight: 600;
            line-height: 1;
            margin-bottom: 1px;
        }

        .day-name {
            font-size: 8px;
            text-transform: uppercase;
            opacity: 0.8;
            line-height: 1;
        }

        .presensi-indicator {
            position: absolute;
            bottom: 2px;
            left: 2px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 7px;
        }

        .calendar-day.status-hadir .presensi-indicator {
            background: #28a745;
            color: white;
        }

        .calendar-day.status-izin .presensi-indicator {
            background: #ffc107;
            color: #856404;
        }

        .calendar-day.status-alpha .presensi-indicator {
            background: #dc3545;
            color: white;
        }

        .calendar-day.today .presensi-indicator {
            background: rgba(255, 255, 255, 0.9);
            color: #004b4c;
        }

        .calendar-day.holiday {
            background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
            color: #d63031;
            border-color: #fdcb6e;
        }

        .calendar-day.holiday.today {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
        }

        .holiday-indicator {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 10px;
            height: 10px;
            background: #e17055;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 7px;
            color: white;
            border: 1px solid #d63031;
        }

        .calendar-day.holiday .holiday-indicator {
            background: #e17055;
        }

        .calendar-day.holiday.today .holiday-indicator {
            background: rgba(255, 255, 255, 0.9);
            color: #004b4c;
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* Month Navigation Button Styles */
        .month-nav-btn {
            background: transparent;
            border: none;
            color: #004b4c;
            font-size: 18px;
            padding: 5px;
            cursor: pointer;
            border-radius: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .month-nav-btn:hover {
            color: #0e8549;
        }

        .month-nav-btn:focus {
            outline: none;
        }

        /* Banner Modal Styles */
        .modal-content {
            border-radius: 15px;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.8);
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
            object-fit: cover;      /* 🔥 kunci anti gepeng */
            object-position: center;
            display: block;
        }

        .performance-card {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border-radius: 14px;
            padding: 14px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,.15);
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .performance-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,.2);
        }

        /* LEFT */
        .performance-left {
            flex: 1;
        }

        .performance-level {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
        }

        .level-badge {
            font-size: 9px;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 2px 6px;
            border-radius: 6px;
            font-weight: 600;
        }

        .performance-level strong {
            font-size: 10px;
            color: white;
        }

        /* TIMELINE */
        .timeline {
            display: none;
        }

        .timeline-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            color: #adb5bd;
            font-size: 9px;
            flex: 1;
        }

        .timeline-item i {
            font-size: 16px;
            margin-bottom: 2px;
        }

        .timeline-item .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #dee2e6;
            margin-bottom: 4px;
        }

        .timeline-item.done {
            color: #0e8549;
        }

        .timeline-item.done .dot {
            background: #0e8549;
        }

        /* RIGHT */
        .performance-right {
            width: 460px;
            display: flex;
            justify-content: center;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: white;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: {{ $kinerjaPercent }}%;
            background: #0e8549;
            border-radius: 999px;
            transition: width .4s ease;
        }

        .progress-text {
            text-align: center;
        }

        .progress-text strong {
            font-size: 14px;
            color: #fcffff;
        }

        .progress-text small {
            font-size: 9px;
            color: #ffffff;
        }

        .performance-progress {
            width: 100%;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 8px;
        }

        .progress-bar {
            flex: 1;
        }

        .timeline-accordion {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding-left: 20px;
        }

        .timeline-accordion::before {
            content: '';
            position: absolute;
            left: 16px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, rgba(255,255,255,0.8), rgba(255,255,255,0.4));
            border-radius: 1px;
        }

        .timeline-item-accordion {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 6px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .timeline-item-accordion:hover {
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .timeline-item-accordion.done {
            background: #d4edda;
            border-color: #c3e6cb;
            border-left: 4px solid #28a745;
        }

        .timeline-item-accordion .timeline-icon {
            position: absolute;
            left: -20px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1;
            font-size: 12px;
        }

        .timeline-item-accordion.done .timeline-icon {
            background: #28a745;
            color: white;
        }

        .timeline-item-accordion .timeline-content {
            flex: 1;
        }

        .timeline-item-accordion .timeline-content strong {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 4px;
        }

        .timeline-item-accordion .timeline-content small {
            font-size: 11px;
            color: #6c757d;
        }

        .timeline-item-accordion.done .timeline-content strong {
            color: #155724;
        }

        .timeline-item-accordion.done .timeline-content small {
            color: #155724;
        }

    </style>

    <!-- Show banner modal on page load -->
    @if($showBanner)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var welcomeModal = new bootstrap.Modal(document.getElementById('welcomeBannerModal'), {
                backdrop: 'static',
                keyboard: false
            });
            welcomeModal.show();

            // Auto hide after 3 seconds
            setTimeout(function() {
                welcomeModal.hide();
            }, 3000);
        });
    </script>
    @endif

    <!-- Header -->
    {{-- <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Halo, {{ Auth::user()->name }} 👋</h6>
                <h5 class="fw-bold mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah belum diatur' }}</h5>
            </div>
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div> --}}

    <!-- Banner Modal -->
    @if($bannerImage)
    <div class="modal fade" id="welcomeBannerModal" tabindex="-1" aria-labelledby="welcomeBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="background: transparent;">
                <div class="modal-body p-0">
                    <div class="text-center">
                        <img src="{{ $bannerImage }}" alt="Welcome Banner" class="img-fluid rounded" style="max-height: 60vh; width: auto;">
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 bg-transparent">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- <!-- Stats Form -->
    <div class="dashboard-header mb-4">
        <div class="id-card-title">Kartu Identitas Digital</div>
        <div class="id-card">
            <!-- Foto -->
            <div class="id-card-photo">
                <img
                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                    alt="User"
                >
            </div>

            <!-- Detail -->
            <div class="id-card-details">
                <div class="row-item">
                    <span class="label">Nama</span>
                    <span class="colon">:</span>
                    <span class="value">{{ Auth::user()->name }}</span>
                </div>
                <div class="row-item">
                    <span class="label">TTL</span>
                    <span class="colon">:</span>
                    <span class="value">{{ $userInfo['tempat_lahir'] }}, {{ $userInfo['tanggal_lahir'] }}</span>
                </div>
                <div class="row-item">
                    <span class="label">NIPM</span>
                    <span class="colon">:</span>
                    <span class="value">{{ $userInfo['nip'] }}</span>
                </div>
                <div class="row-item">
                    <span class="label">NUIST ID</span>
                    <span class="colon">:</span>
                    <span class="value">{{ $userInfo['nuist_id'] }}</span>
                </div>
                <div class="row-item">
                    <span class="label">Asal Sekolah</span>
                    <span class="colon">:</span>
                    <span class="value text-truncate">
                        {{ Auth::user()->madrasah?->name ?? 'Belum diatur' }}
                    </span>
                </div>
                <div class="row-item">
                    <span class="label">Status</span>
                    <span class="colon">:</span>
                    <span class="value badge-status">
                        {{ $userInfo['status_kepegawaian'] }}
                    </span>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="performance-card">
        <div class="performance-level">
            <span class="level-badge">LEVEL HARI INI</span>
            <strong>{{ $kinerjaPercent >= 100 ? 'Teladan' : ($kinerjaPercent >= 50 ? 'Baik' : ($kinerjaPercent >= 20 ? 'Cukup' : 'Belum Ada Progress')) }}</strong>
        </div>

        <div class="performance-progress">
            <div class="progress-bar">
                <div class="progress-fill" style="background: {{ $progressColor }}"></div>
            </div>
            <div class="progress-text">
                <strong>{{ $kinerjaPercent }}%</strong>
            </div>
        </div>

        <div class="text-center mt-2" style="font-size: 10px">
            <a href="#" class="text-light text-decoration-none" data-bs-toggle="collapse" data-bs-target="#performanceAccordion" aria-expanded="false" aria-controls="performanceAccordion" style="font-size: 10px; font-weight: 500;">
                Lihat Detail <i class="bx bx-chevron-down" id="detailArrow"></i>
            </a>
        </div>
        <div class="collapse" id="performanceAccordion">
            <div class="accordion-content" style="background: rgba(255, 255, 255, 0); border-radius: 0 0 14px 14px; padding: 16px 14px 12px 14px; margin-top: 8px; border-top: 1px solid rgba(255,255,255,0.2);">
                <h6 class="accordion-title" style="font-size: 10px; font-weight: 600; color: white; margin-bottom: 12px; text-align: center;">Detail Aktivitas Hari Ini</h6>
                <div class="timeline-accordion">
                    <!-- Presensi Masuk -->
                    <div class="timeline-item-accordion {{ $presensiMasukStatus === 'sudah' ? 'done' : '' }}">
                        <div class="timeline-icon">
                            <i class="bx bx-log-in"></i>
                        </div>
                        <div class="timeline-content">
                            <strong>Presensi Masuk</strong>
                            <small>{{ $presensiMasukStatus === 'sudah' ? 'Sudah dilakukan' : 'Belum dilakukan' }}</small>
                        </div>
                    </div>

                    <!-- Presensi Mengajar - tampilkan per jadwal -->
                    @if(count($teachingSteps) > 0)
                        @foreach($teachingSteps as $step)
                        <div class="timeline-item-accordion {{ $step['status'] === 'completed' ? 'done' : '' }}">
                            <div class="timeline-icon">
                                <i class="bx bx-chalkboard"></i>
                            </div>
                            <div class="timeline-content">
                                <strong>{{ $step['label'] }}</strong>
                                <small>{{ $step['status'] === 'completed' ? 'Sudah dilakukan' : 'Belum dilakukan' }}</small>
                            </div>
                        </div>
                        @endforeach
                    @endif

                    <!-- Presensi Keluar -->
                    <div class="timeline-item-accordion {{ $presensiKeluarStatus === 'sudah' ? 'done' : '' }}">
                        <div class="timeline-icon">
                            <i class="bx bx-log-out"></i>
                        </div>
                        <div class="timeline-content">
                            <strong>Presensi Keluar</strong>
                            <small>{{ $presensiKeluarStatus === 'sudah' ? 'Sudah dilakukan' : 'Belum dilakukan' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <small class="name-form" style="font-style: italic">Aktivitas Presensi Bulan {{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->locale('id')->monthName }} {{ $currentYear }}</small>

    <div class="stats-form">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-check-circle text-success"></i>
                </div>
                <h6>{{ $kehadiranPercent }}%</h6>
                <small>Kehadiran</small>
            </div>
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-calendar text-primary"></i>
                </div>
                <h6>{{ $hadir }}</h6>
                <small>Presensi</small>
            </div>
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-time text-warning"></i>
                </div>
                <h6>{{ $izin }}</h6>
                <small>Izin</small>
            </div>
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-x text-danger"></i>
                </div>
                <h6>{{ $alpha }}</h6>
                <small>
                    <a href="{{ route('mobile.riwayat-presensi-alpha') }}" class="text-decoration-none text-danger" style="font-size: 9px;">Tidak Hadir</a>
                </small>
            </div>
        </div>
    </div>

    <small>Layanan</small>

    <!-- Services Form -->
    <div class="services-form">
        <div class="services-grid" id="servicesGrid">
            <div class="service-wrapper">
                <a href="{{ route('mobile.presensi') }}" class="service-item">
                    <img src="{{ asset('images/menu_icon/1.png') }}" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                </a>
                <div class="service-label">Presensi</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('mobile.teaching-attendances') }}" class="service-item">
                    <img src="{{ asset('images/menu_icon/2.png') }}" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                    {{-- <i class="bx bx-chalkboard" style="position: relative; z-index: 1;"></i> --}}
                </a>
                <div class="service-label">Presensi Mengajar</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('mobile.izin', ['type' => 'cuti']) }}" class="service-item">
                    <img src="{{ asset('images/menu_icon/3.png') }}" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                    {{-- <i class="bx bx-calendar-star" style="position: relative; z-index: 1;"></i> --}}
                </a>
                <div class="service-label">Izin Cuti</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('mobile.izin', ['type' => 'terlambat']) }}" class="service-item">
                    <img src="{{ asset('images/menu_icon/4.png') }}" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                    {{-- <i class="bx bx-time-five" style="position: relative; z-index: 1;"></i> --}}
                </a>
                <div class="service-label">Izin Terlambat</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('mobile.izin', ['type' => 'sakit']) }}" class="service-item">
                    <img src="{{ asset('images/menu_icon/5.png') }}" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                    {{-- <i class="bx bx-plus-medical" style="position: relative; z-index: 1;"></i> --}}
                </a>
                <div class="service-label">Izin Sakit</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('mobile.izin', ['type' => 'tugas_luar']) }}" class="service-item">
                    <img src="{{ asset('images/menu_icon/6.png') }}" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                    {{-- <i class="bx bx-briefcase"></i> --}}
                </a>
                <div class="service-label">Izin Dinas Luar</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('mobile.jadwal') }}" class="service-item">
                    <img src="{{ asset('images/menu_icon/7.png') }}" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                    {{-- <i class="bx bx-calendar" style="position: relative; z-index: 1;"></i> --}}
                </a>
                <div class="service-label">Jadwal Mengajar</div>
            </div>
            <div id="viewAllBtn" class="service-wrapper">
                <a href="#" class="service-item" onclick="return toggleServices(event)">
                    <img src="{{ asset('images/menu_icon/12.png') }}" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                    {{-- <i class="bx bx-plus" style="position: relative; z-index: 1;"></i> --}}
                </a>
                <div class="service-label" id="serviceLabel">Lihat Semua</div>
            </div>
            <div class="extra-service service-wrapper">
                <a href="{{ route('mobile.profile') }}" class="service-item">
                    <img src="{{ asset('images/menu_icon/8.png') }}" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                    {{-- <i class="bx bx-user" style="position: relative; z-index: 1;"></i> --}}
                </a>
                <div class="service-label">Profile</div>
            </div>
            <div class="extra-service service-wrapper">
                <a href="{{ route('mobile.laporan') }}" class="service-item">
                    <img src="{{ asset('images/menu_icon/11.png') }}" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                    {{-- <i class="bx bx-file" style="position: relative; z-index: 1;"></i> --}}
                </a>
                <div class="service-label">Laporan</div>
            </div>
            <div class="extra-service service-wrapper">
                <a href="{{ route('mobile.ubah-akun') }}" class="service-item">
                    <img src="{{ asset('images/menu_icon/9.png') }}" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                    {{-- <i class="bx bx-cog" style="position: relative; z-index: 1;"></i> --}}
                </a>
                <div class="service-label">Pengaturan</div>
            </div>

            @if(Auth::user()->role === 'tenaga_pendidik' && Auth::user()->ketugasan === 'kepala madrasah/sekolah')
            <div class="extra-service service-wrapper">
                <a href="{{ route('mobile.kelola-izin') }}" class="service-item">
                    <img src="{{ asset('images/menu_icon/10.png') }}" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                    {{-- <i class="bx bx-edit" style="position: relative; z-index: 1;"></i> --}}
                </a>
                <div class="service-label">Kelola Izin</div>
            </div>
            <div class="extra-service service-wrapper">
                <a href="{{ route('mobile.monitor-presensi') }}" class="service-item">
                    <img src="{{ asset('images/menu_icon/13.png') }}" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                    {{-- <i class="bx bx-calendar-check" style="position: relative; z-index: 1;"></i> --}}
                </a>
                <div class="service-label">Data Presensi</div>
            </div>

            @endif
        </div>
    </div>

    <script>
        function toggleServices(event) {
            event.preventDefault();

            const extraServices = document.querySelectorAll('.extra-service');
            const viewAllBtn = document.getElementById('viewAllBtn');
            const servicesGrid = document.getElementById('servicesGrid');
            const label = document.getElementById('serviceLabel');

            const isHidden = !extraServices[0].classList.contains('show');

            extraServices.forEach(service => {
                service.classList.toggle('show', isHidden);
            });

            if (isHidden) {
                // Move button to the end
                servicesGrid.appendChild(viewAllBtn);
                label.textContent = 'Tutup';
            } else {
                // Move button back to original position (before first extra-service)
                const firstExtra = extraServices[0];
                servicesGrid.insertBefore(viewAllBtn, firstExtra);
                label.textContent = 'Lihat Semua';
            }
        }

        function togglePerformanceDetails() {
            const modal = new bootstrap.Modal(document.getElementById('performanceModal'));
            modal.show();
        }

        // Handle accordion toggle text change and icon rotation
        document.addEventListener('DOMContentLoaded', function() {
            const accordion = document.getElementById('performanceAccordion');
            const detailLink = document.querySelector('.performance-card a[data-bs-toggle="collapse"]');
            const detailIcon = document.getElementById('detailArrow');

            if (accordion && detailLink && detailIcon) {
                accordion.addEventListener('show.bs.collapse', function() {
                    detailLink.innerHTML = 'Tutup Detail <i class="bx bx-chevron-up" id="detailArrow"></i>';
                });

                accordion.addEventListener('hide.bs.collapse', function() {
                    detailLink.innerHTML = 'Lihat Detail <i class="bx bx-chevron-down" id="detailArrow"></i>';
                });
            }
        });

        // Add click event listener to performance card
        document.addEventListener('DOMContentLoaded', function() {
            const performanceCard = document.querySelector('.performance-card');
            if (performanceCard) {
                performanceCard.addEventListener('click', togglePerformanceDetails);
            }
        });
    </script>

    <!-- Teacher Info -->
    {{-- <div class="info-section">
        <h6 class="section-title">Informasi Tenaga Pendidik</h6>
        <div class="info-grid">
            <div class="info-item">
                <small class="d-block">NUIST ID</small>
                <strong>{{ $userInfo['nuist_id'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">Status Kepegawaian</small>
                <strong>{{ $userInfo['status_kepegawaian'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">Ketugasan</small>
                <strong>{{ $userInfo['ketugasan'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">Tempat Lahir</small>
                <strong>{{ $userInfo['tempat_lahir'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">Tanggal Lahir</small>
                <strong>{{ $userInfo['tanggal_lahir'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">TMT</small>
                <strong>{{ $userInfo['tmt'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">NUPTK</small>
                <strong>{{ $userInfo['nuptk'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">NPK</small>
                <strong>{{ $userInfo['npk'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">Kartanu</small>
                <strong>{{ $userInfo['kartanu'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">NIP Ma'arif</small>
                <strong>{{ $userInfo['nip'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">Pendidikan Terakhir</small>
                <strong>{{ $userInfo['pendidikan_terakhir'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">Program Studi</small>
                <strong>{{ $userInfo['program_studi'] }}</strong>
            </div>
        </div>
    </div> --}}

    <small>Jadwal Hari Ini</small>

    <!-- Schedule Section -->
    <div class="schedule-section">
        {{-- <h6 class="section-title">Jadwal Hari Ini</h6> --}}
        @if($todaySchedulesWithAttendance->count() > 0)
            <div class="schedule-carousel">
                @foreach($todaySchedulesWithAttendance as $schedule)
                    <div class="schedule-card">
                        <div class="attendance-indicator {{ $schedule->attendance_status == 'sudah' ? 'bg-success' : 'bg-danger' }}"></div>
                        <div class="schedule-header">
                            <strong class="d-block">{{ $schedule->subject }}</strong>
                            <small class="d-block text-dark">{{ $schedule->class_name }}</small>
                        </div>
                        <div class="schedule-time">
                            <small class="d-block text-muted"><i class="bx bx-time-five"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }}</small>
                        </div>
                        <div class="schedule-status">
                            <span class="badge {{ $schedule->attendance_status == 'sudah' ? 'bg-success' : 'bg-warning' }}">
                                <i class="bx {{ $schedule->attendance_status == 'sudah' ? 'bx-check-circle' : 'bx-time' }}"></i>
                                Presensi {{ $schedule->attendance_status == 'sudah' ? 'Sudah' : 'Belum' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-schedule">
                <i class="bx bx-calendar-x"></i>
                <p>Tidak ada jadwal mengajar hari ini</p>
            </div>
        @endif
    </div>

    <small>Kalender Presensi Bulan Ini</small>

    <!-- Calendar Section -->
    <div class="calendar-section">
        <div class="calendar-container">
        <div class="calendar-header">
            <div class="calendar-title">
                <button class="month-nav-btn" onclick="navigateMonth({{ $prevYear }}, {{ $prevMonth }})">
                    <i class="bx bx-chevron-left"></i>
                </button>
                <span>{{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->locale('id')->monthName }} {{ $currentYear }}</span>
                <button class="month-nav-btn" onclick="navigateMonth({{ $nextYear }}, {{ $nextMonth }})">
                    <i class="bx bx-chevron-right"></i>
                </button>
            </div>
        </div>
        <div class="calendar-weekdays">
            <div class="weekday-label">Min</div>
            <div class="weekday-label">Sen</div>
            <div class="weekday-label">Sel</div>
            <div class="weekday-label">Rab</div>
            <div class="weekday-label">Kam</div>
            <div class="weekday-label">Jum</div>
            <div class="weekday-label">Sab</div>
        </div>
        <div class="calendar-grid">
                @php
                    $daysInMonth = \Carbon\Carbon::create($currentYear, $currentMonth, 1)->daysInMonth;
                    $firstDayOfMonth = \Carbon\Carbon::create($currentYear, $currentMonth, 1)->dayOfWeek; // 0=Sunday, 6=Saturday
                    $today = \Carbon\Carbon::now()->day;
                    $currentMonthCheck = \Carbon\Carbon::now()->month;
                    $currentYearCheck = \Carbon\Carbon::now()->year;
                @endphp

                @for ($i = 0; $i < $firstDayOfMonth; $i++)
                    <div class="calendar-day empty"></div>
                @endfor

                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $dateKey = \Carbon\Carbon::create($currentYear, $currentMonth, $day)->toDateString();
                        $presensiStatus = $monthlyPresensi[$dateKey] ?? null;
                        $isToday = ($currentMonth == $currentMonthCheck && $currentYear == $currentYearCheck && $day == $today);
                        $isPastDay = \Carbon\Carbon::create($currentYear, $currentMonth, $day)->isBefore(\Carbon\Carbon::now()->startOfDay());
                        $dayName = \Carbon\Carbon::create($currentYear, $currentMonth, $day)->locale('id')->dayName;
                        $shortDayName = substr($dayName, 0, 3);
                        $dayOfWeek = \Carbon\Carbon::create($currentYear, $currentMonth, $day)->dayOfWeek; // 0 = Sunday, 6 = Saturday
                        $isHoliday = isset($monthlyHolidays[$dateKey]);

                        // Cek apakah hari ini adalah hari kerja berdasarkan hari KBM madrasah
                        $isWorkingDay = true;
                        if ($hariKbm == 5 && ($dayOfWeek == 6 || $dayOfWeek == 0)) { // Jika KBM 5 hari, exclude Sabtu (6) dan Minggu (0)
                            $isWorkingDay = false;
                        } elseif ($hariKbm == 6 && $dayOfWeek == 0) { // Jika KBM 6 hari, exclude hanya Minggu (0)
                            $isWorkingDay = false;
                        }

                        // Jika presensi status adalah 'alpha' tapi bukan hari kerja, jangan tampilkan sebagai alpha
                        if ($presensiStatus === 'alpha' && !$isWorkingDay) {
                            $presensiStatus = null;
                        }

                        // Jika hari sebelum hari ini, hari kerja, dan bukan hari libur tapi tidak ada presensi, tandai sebagai alpha
                        if ($isPastDay && $isWorkingDay && !$isHoliday && !$presensiStatus) {
                            $presensiStatus = 'alpha';
                        }

                        // Treat 'sakit' as 'izin' for display consistency
                        if ($presensiStatus === 'sakit') {
                            $presensiStatus = 'izin';
                        }
                    @endphp

                    <div class="calendar-day {{ $isToday ? 'today' : '' }} {{ $presensiStatus ? 'status-' . $presensiStatus : '' }} {{ $presensiStatus ? 'has-presensi' : '' }} {{ $isHoliday ? 'holiday' : '' }}">
                        <div class="day-number">{{ $day }}</div>
                        @if($isHoliday)
                            <div class="holiday-indicator">
                                <i class="bx bx-star"></i>
                            </div>
                        @elseif($presensiStatus)
                            <div class="presensi-indicator">
                                @if($presensiStatus == 'hadir')
                                    <i class="bx bx-check"></i>
                                @elseif($presensiStatus == 'izin')
                                    <i class="bx bx-time-five"></i>
                                @elseif($presensiStatus == 'alpha')
                                    <i class="bx bx-x"></i>
                                @endif
                            </div>
                        @endif
                    </div>
                @endfor

                @php
                    $totalCells = $firstDayOfMonth + $daysInMonth;
                    $emptyAtEnd = (7 - ($totalCells % 7)) % 7;
                @endphp

                @for ($i = 0; $i < $emptyAtEnd; $i++)
                    <div class="calendar-day empty"></div>
                @endfor
            </div>

            @if(count($monthlyHolidays) > 0)
            <div class="holiday-list">
                <small style="color: #666; font-weight: 500;">Hari Libur Nasional:</small>
                <div style="margin-top: 4px;">
                    @foreach($monthlyHolidays as $date => $name)
                        <small style="display: block; color: #d63031; margin-bottom: 2px;">
                            • {{ \Carbon\Carbon::parse($date)->locale('id')->format('d F Y') }} - {{ $name }}
                        </small>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Performance Details Modal -->
    {{-- <div class="modal fade" id="performanceModal" tabindex="-1" aria-labelledby="performanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 14px; border: none;">
                <div class="modal-header" style="border-bottom: 1px solid #e9ecef;">
                    <h5 class="modal-title" id="performanceModalLabel" style="font-weight: 600; color: #004b4c;">Detail Aktivitas Kinerja Hari Ini</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    <div class="performance-level mb-4">
                        <span class="level-badge">LEVEL</span>
                        <strong>{{ $kinerjaPercent >= 100 ? 'Teladan' : ($kinerjaPercent >= 80 ? 'Baik' : ($kinerjaPercent >= 66 ? 'Cukup' : 'Belum Ada Progres')) }}</strong>
                    </div>

                    <div class="progress-bar mb-3">
                        <div class="progress-fill" style="background: {{ $progressColor }}"></div>
                    </div>
                    <div class="progress-text mb-4">
                        <strong>{{ $kinerjaPercent }}%</strong>
                        <small>Hari ini</small>
                    </div>

                    <div class="timeline-modal">
                        <!-- Presensi Masuk -->
                        <div class="timeline-item-modal {{ $presensiMasukStatus === 'sudah' ? 'done' : '' }}">
                            <div class="timeline-icon">
                                <i class="bx bx-log-in"></i>
                            </div>
                            <div class="timeline-content">
                                <strong>Presensi Masuk</strong>
                                <small>{{ $presensiMasukStatus === 'sudah' ? 'Sudah dilakukan' : 'Belum dilakukan' }}</small>
                            </div>
                        </div>

                        <!-- Presensi Mengajar - tampilkan per jadwal -->
                        @if(count($teachingSteps) > 0)
                            @foreach($teachingSteps as $step)
                            <div class="timeline-item-modal {{ $step['status'] === 'completed' ? 'done' : '' }}">
                                <div class="timeline-icon">
                                    <i class="bx bx-chalkboard"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong>{{ $step['label'] }}</strong>
                                    <small>{{ $step['status'] === 'completed' ? 'Sudah dilakukan' : 'Belum dilakukan' }}</small>
                                </div>
                            </div>
                            @endforeach
                        @endif

                        <!-- Presensi Keluar -->
                        <div class="timeline-item-modal {{ $presensiKeluarStatus === 'sudah' ? 'done' : '' }}">
                            <div class="timeline-icon">
                                <i class="bx bx-log-out"></i>
                            </div>
                            <div class="timeline-content">
                                <strong>Presensi Keluar</strong>
                                <small>{{ $presensiKeluarStatus === 'sudah' ? 'Sudah dilakukan' : 'Belum dilakukan' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <style>
        .timeline-modal {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .timeline-item-modal {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 8px;
            background: #f8f9fa;
            transition: all 0.2s ease;
        }

        .timeline-item-modal.done {
            background: #d4edda;
            border-left: 4px solid #28a745;
        }

        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }

        .timeline-item-modal.done .timeline-icon {
            background: #28a745;
            color: white;
        }

        .timeline-content strong {
            display: block;
            font-size: 14px;
            color: #333;
            margin-bottom: 2px;
        }

        .timeline-content small {
            font-size: 12px;
            color: #6c757d;
        }

        .timeline-item-modal.done .timeline-content strong,
        .timeline-item-modal.done .timeline-content small {
            color: #155724;
        }
    </style>
</div>
@endsection

<script>
function navigateMonth(year, month) {
    // Show loading state
    const calendarContainer = document.querySelector('.calendar-container');
    const originalContent = calendarContainer.innerHTML;
    calendarContainer.innerHTML = '<div style="text-align: center; padding: 20px;"><i class="bx bx-loader-alt bx-spin" style="font-size: 24px;"></i><br><small>Loading...</small></div>';

    // Fetch new calendar data via AJAX
    fetch(`{{ route('mobile.dashboard.calendar-data') }}?year=${year}&month=${month}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update URL without reload
        const url = new URL(window.location);
        url.searchParams.set('year', year);
        url.searchParams.set('month', month);
        window.history.pushState({}, '', url);

        // Re-render calendar with new data
        renderCalendar(data);

        // Update stats data for the new month
        updateStatsData(year, month);

        // Update month name in the stats header
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        document.querySelector('.name-form').textContent = `Aktivitas Presensi Bulan ${monthNames[month - 1]} ${year}`;
    })
    .catch(error => {
        console.error('Error loading calendar data:', error);
        // Restore original content on error
        calendarContainer.innerHTML = originalContent;
        alert('Gagal memuat data kalender. Silakan coba lagi.');
    });
}

function updateStatsData(year, month) {
    // Fetch new stats data via AJAX
    fetch(`{{ route('mobile.dashboard.stats-data') }}?year=${year}&month=${month}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update stats display
        document.querySelector('.stat-item:nth-child(1) h6').textContent = data.kehadiranPercent + '%';
        document.querySelector('.stat-item:nth-child(2) h6').textContent = data.hadir;
        document.querySelector('.stat-item:nth-child(3) h6').textContent = data.izin;
        document.querySelector('.stat-item:nth-child(4) h6').textContent = data.alpha;
    })
    .catch(error => {
        console.error('Error loading stats data:', error);
    });
}

function renderCalendar(data) {
    const calendarContainer = document.querySelector('.calendar-container');

    // Calculate calendar grid
    const daysInMonth = new Date(data.currentYear, data.currentMonth, 0).getDate();
    const firstDayOfMonth = new Date(data.currentYear, data.currentMonth - 1, 1).getDay();
    const today = new Date();
    const isCurrentMonth = today.getMonth() + 1 === data.currentMonth && today.getFullYear() === data.currentYear;
    const currentDay = today.getDate();

    // Indonesian month names
    const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    // Day names
    const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

    let html = `
        <div class="calendar-header">
            <div class="calendar-title">
                <button class="month-nav-btn" onclick="navigateMonth(${data.prevYear}, ${data.prevMonth})">
                    <i class="bx bx-chevron-left"></i>
                </button>
                <span>${monthNames[data.currentMonth - 1]} ${data.currentYear}</span>
                <button class="month-nav-btn" onclick="navigateMonth(${data.nextYear}, ${data.nextMonth})">
                    <i class="bx bx-chevron-right"></i>
                </button>
            </div>
        </div>
        <div class="calendar-weekdays">
    `;

    // Weekday headers
    dayNames.forEach(day => {
        html += `<div class="weekday-label">${day}</div>`;
    });

    html += `</div><div class="calendar-grid">`;

    // Empty cells for days before first day of month
    for (let i = 0; i < firstDayOfMonth; i++) {
        html += `<div class="calendar-day empty"></div>`;
    }

    // Calendar days
    for (let day = 1; day <= daysInMonth; day++) {
        const dateKey = `${data.currentYear}-${String(data.currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        let presensiStatus = data.monthlyPresensi[dateKey] || null;
        const isHoliday = data.monthlyHolidays[dateKey] !== undefined;
        const isToday = isCurrentMonth && day === currentDay;

        // Check if it's a working day
        const dayOfWeek = new Date(data.currentYear, data.currentMonth - 1, day).getDay();
        const isSunday = dayOfWeek === 0;
        const isSaturday = dayOfWeek === 6;
        const isWorkingDay = !isSunday && !(isSaturday && data.hariKbm == 5);

        // If presensi status is 'alpha' but not a working day, don't display as alpha
        if (presensiStatus === 'alpha' && !isWorkingDay) {
            presensiStatus = null;
        }

        // If no presensi data and it's a past working day and not holiday, mark as alpha
        if (!presensiStatus && !isHoliday && isWorkingDay) {
            const currentDate = new Date();
            const checkDate = new Date(data.currentYear, data.currentMonth - 1, day);
            if (checkDate < currentDate) {
                presensiStatus = 'alpha';
            }
        }

        let statusClass = '';
        if (presensiStatus) {
            statusClass = `status-${presensiStatus}`;
        } else if (isHoliday) {
            statusClass = 'holiday';
        }

        const hasPresensi = presensiStatus ? 'has-presensi' : '';

        html += `
            <div class="calendar-day ${isToday ? 'today' : ''} ${statusClass} ${hasPresensi} ${isHoliday ? 'holiday' : ''}">
                <div class="day-number">${day}</div>
        `;

        if (isHoliday) {
            html += `<div class="holiday-indicator"><i class="bx bx-star"></i></div>`;
        } else if (presensiStatus) {
            let icon = '';
            if (presensiStatus === 'hadir') icon = 'bx-check';
            else if (presensiStatus === 'izin') icon = 'bx-time-five';
            else if (presensiStatus === 'alpha') icon = 'bx-x';

            html += `<div class="presensi-indicator"><i class="bx ${icon}"></i></div>`;
        }

        html += `</div>`;
    }

    html += `</div>`;

    // Holiday list
    if (Object.keys(data.monthlyHolidays).length > 0) {
        html += `<div class="holiday-list"><small style="color: #666; font-weight: 500;">Hari Libur Nasional:</small><div style="margin-top: 4px;">`;

        Object.entries(data.monthlyHolidays).forEach(([date, name]) => {
            const dateObj = new Date(date);
            const formattedDate = dateObj.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            html += `<small style="display: block; color: #d63031; margin-bottom: 2px;">• ${formattedDate} - ${name}</small>`;
        });

        html += `</div></div>`;
    }

    html += `</div>`;

    calendarContainer.innerHTML = html;
}

// Handle accordion arrow toggle
document.addEventListener('DOMContentLoaded', function() {
    const accordion = document.getElementById('performanceAccordion');
    const arrowIcon = document.getElementById('detailArrow');

    if (accordion && arrowIcon) {
        // Listen to Bootstrap collapse events
        accordion.addEventListener('show.bs.collapse', function() {
            arrowIcon.className = 'bx bx-chevron-up';
        });

        accordion.addEventListener('hide.bs.collapse', function() {
            arrowIcon.className = 'bx bx-chevron-down';
        });
    }
});
</script>
