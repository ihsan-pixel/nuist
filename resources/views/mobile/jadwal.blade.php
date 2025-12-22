@extends('layouts.mobile')

@section('title', 'Jadwal Mengajar')
@section('subtitle', 'Jadwal Mengajar Saya')

@section('content')
<div class="container py-3" style="max-width: 600px; margin: auto;">
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

        .day-indicator {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 16px;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .day-indicator::-webkit-scrollbar {
            display: none;
        }

        .day-indicator-container {
            display: flex;
            justify-content: center;
            min-width: max-content;
        }

        .day-indicator-item {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 3px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            flex-shrink: 0;
        }

        @media (max-width: 576px) {
            .day-indicator-item {
                width: 32px;
                height: 32px;
                margin: 0 2px;
            }
        }

        .day-indicator-item::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #fdbd57, #f89a3c);
            border-radius: 1px;
            transition: width 0.3s ease;
        }

        .day-indicator-item.active::after {
            width: 20px;
        }

        .day-indicator-item.active {
            background: linear-gradient(135deg, #fdbd57, #f89a3c);
            color: white;
            border-color: rgba(253, 189, 87, 0.5);
            box-shadow: 0 2px 8px rgba(253, 189, 87, 0.4);
        }

        .day-indicator-item span {
            font-size: 12px;
            font-weight: 600;
            color: #666;
        }

        .day-indicator-item.active span {
            color: white;
        }

        .day-card {
            max-width: 520px;
            margin: 0 auto;
            background: linear-gradient(135deg, #fdbd57 0%, #f89a3c 50%, #e67e22 100%);
            border-radius: 0;
            min-height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
            position: relative;
            padding: 16px;
            box-shadow: none;
            border: none;
            display: none;
        }

        .day-card.active {
            display: flex;
        }

        @media (max-width: 576px) {
            .day-card {
                min-height: 200px;
                padding: 12px;
            }
        }

        .day-header {
            text-align: center;
            margin-bottom: 12px;
        }

        .day-header strong {
            font-size: 18px;
            color: white;
            margin-bottom: 4px;
            display: block;
            font-weight: 700;
        }

        .day-header small {
            font-size: 12px;
            color: white;
            opacity: 0.9;
        }

        @media (max-width: 576px) {
            .day-header strong {
                font-size: 16px;
            }
        }

        .schedule-list {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }

        .schedule-item {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.2s ease;
            max-width: 460px;
            margin: 0 auto;
        }

        .schedule-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .schedule-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #0e8549, #0f9d58);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(14, 133, 73, 0.3);
        }

        .schedule-icon i {
            color: #fff;
            font-size: 14px;
        }

        .schedule-info {
            flex: 1;
            min-width: 0;
        }

        .schedule-info strong {
            font-size: 14px;
            color: #2d3748;
            display: block;
            margin-bottom: 3px;
            font-weight: 600;
            line-height: 1.2;
        }

        .schedule-info small {
            font-size: 12px;
            color: #718096;
            display: block;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .schedule-time {
            font-size: 11px;
            color: #a0aec0;
            margin-top: 0;
            font-weight: 500;
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

    <!-- Day Indicator -->
    <div class="day-indicator">
        <div class="day-indicator-container">
            @php
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            @endphp
            @foreach($days as $index => $day)
                <div class="day-indicator-item {{ $index === 0 ? 'active' : '' }}" data-day="{{ $day }}">
                    <span>{{ substr($day, 0, 6) }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Schedule Cards -->
    @foreach($days as $index => $day)
        <div class="day-card {{ $index === 0 ? 'active' : '' }}" data-day="{{ $day }}">
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
                                {{ Str::limit($schedule->school->name ?? 'N/A', 8) }}
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const indicatorItems = document.querySelectorAll('.day-indicator-item');
            const dayCards = document.querySelectorAll('.day-card');

            indicatorItems.forEach(item => {
                item.addEventListener('click', function() {
                    const day = this.getAttribute('data-day');

                    // Remove active class from all items
                    indicatorItems.forEach(i => i.classList.remove('active'));
                    dayCards.forEach(c => c.classList.remove('active'));

                    // Add active class to clicked item and corresponding card
                    this.classList.add('active');
                    document.querySelector(`.day-card[data-day="${day}"]`).classList.add('active');
                });
            });
        });
    </script>
</div>
@endsection
