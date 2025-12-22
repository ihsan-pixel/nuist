@extends('layouts.mobile')

@section('title', 'Jadwal Mengajar')
@section('subtitle', 'Jadwal Mengajar Saya')

@section('content')
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
                <small class="text-dark fw-medium" style="font-size: 11px;">Jadwal Mengajar</small>
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
            min-height: 100vh;
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

        .schedule-section {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 60px;
        }

        .schedule-carousel {
            display: flex;
            overflow-x: auto;
            gap: 12px;
            padding: 0 12px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .schedule-carousel::-webkit-scrollbar {
            display: none;
        }

        .day-card {
            flex: 0 0 70vw;
            background: linear-gradient(to bottom, #fdbd57, #f89a3c);
            border-radius: 8px;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            position: relative;
            padding: 16px;
        }

        .day-header {
            text-align: center;
            margin-bottom: 12px;
        }

        .day-header strong {
            font-size: 16px;
            color: white;
            margin-bottom: 4px;
            display: block;
        }

        .day-header small {
            font-size: 12px;
            color: white;
            opacity: 0.9;
        }

        .schedule-list {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .schedule-item {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 6px;
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .schedule-icon {
            width: 28px;
            height: 28px;
            background: rgba(14, 133, 73, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .schedule-icon i {
            color: #0e8549;
            font-size: 12px;
        }

        .schedule-info {
            flex: 1;
            min-width: 0;
        }

        .schedule-info strong {
            font-size: 13px;
            color: #333;
            display: block;
            margin-bottom: 2px;
            font-weight: 600;
        }

        .schedule-info small {
            font-size: 11px;
            color: #666;
            display: block;
        }

        .schedule-time {
            font-size: 10px;
            color: #999;
            margin-top: 2px;
        }

        .school-badge {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .no-schedule {
            text-align: center;
            padding: 20px;
            color: #999;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .no-schedule i {
            font-size: 32px;
            margin-bottom: 8px;
            opacity: 0.7;
        }

        .no-schedule p {
            font-size: 12px;
            margin: 0;
        }

        .week-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 0 12px;
        }

        .week-nav-btn {
            background: transparent;
            border: none;
            color: #004b4c;
            font-size: 18px;
            padding: 5px;
            cursor: pointer;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .week-nav-btn:hover {
            background: rgba(0, 75, 76, 0.1);
        }

        .week-nav-btn:focus {
            outline: none;
        }

        .week-title {
            font-weight: 600;
            font-size: 14px;
            color: #333;
        }
    </style>

    @if(session('success'))
    <div class="alert alert-success border-0 rounded-3 mb-3" style="background: rgba(25, 135, 84, 0.1); color: #198754; border-radius: 12px; padding: 10px;">
        <i class="bx bx-check-circle me-1"></i>{{ session('success') }}
    </div>
    @endif

    <small>Jadwal Mengajar Minggu Ini</small>

    <!-- Schedule Section -->
    <div class="schedule-section">
        <div class="schedule-carousel">
            @php
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            @endphp

            @foreach($days as $day)
                <div class="day-card">
                    <div class="day-header">
                        <strong>{{ $day }}</strong>
                    </div>
                    <div class="schedule-list">
                        @if(isset($schedules[$day]) && $schedules[$day]->count() > 0)
                            @foreach($schedules[$day] as $schedule)
                                <div class="schedule-item">
                                    <div class="schedule-icon">
                                        <i class="bx bx-book"></i>
                                    </div>
                                    <div class="schedule-info">
                                        <strong>{{ $schedule->subject }}</strong>
                                        <small>{{ $schedule->class_name }}</small>
                                        <div class="schedule-time">
                                            <i class="bx bx-time-five"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                        </div>
                                    </div>
                                    <div class="school-badge">
                                        {{ $schedule->school->name ?? 'N/A' }}
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="no-schedule">
                                <i class="bx bx-calendar-x"></i>
                                <p>Tidak ada jadwal</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
