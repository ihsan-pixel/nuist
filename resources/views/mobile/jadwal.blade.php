@extends('layouts.mobile')

@section('title', 'Jadwal Mengajar')
@section('subtitle', 'Jadwal Mengajar Saya')

@section('content')
<div class="container py-3" style="max-width: 520px; margin: auto;">
    <div class="text-center mb-4">
        <h5 class="fw-bold text-dark mb-1" style="font-size: 18px;">Jadwal Mengajar</h5>
        <small class="text-muted" style="font-size: 12px;">Minggu Ini</small>
    </div>
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
