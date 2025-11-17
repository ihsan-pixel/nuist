@extends('layouts.mobile')

@section('title', 'Jadwal Mengajar')
@section('subtitle', 'Jadwal Mengajar Saya')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }

        .schedule-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 12px;
            padding: 12px 10px;
            box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
            margin-bottom: 10px;
        }

        .schedule-header h6 {
            font-weight: 600;
            font-size: 12px;
        }

        .schedule-header h5 {
            font-size: 14px;
        }

        .day-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 8px;
            overflow: hidden;
        }

        .day-header {
            background: #f8f9fa;
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .day-header h6 {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin: 0;
        }

        .day-content {
            padding: 12px;
        }

        .schedule-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 6px;
        }

        .schedule-icon {
            width: 32px;
            height: 32px;
            background: rgba(14, 133, 73, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .schedule-icon i {
            color: #0e8549;
            font-size: 14px;
        }

        .schedule-info h6 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
            color: #333;
        }

        .schedule-info p {
            font-size: 12px;
            margin-bottom: 2px;
            color: #666;
        }

        .schedule-info small {
            font-size: 11px;
            color: #999;
        }

        .school-badge {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
            padding: 2px 6px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
        }

        .no-schedule {
            text-align: center;
            padding: 20px;
            color: #999;
        }

        .no-schedule i {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .no-schedule p {
            font-size: 12px;
            margin: 0;
        }
    </style>

    <!-- Header -->
    <div class="schedule-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Jadwal Mengajar</h6>
                <h5 class="fw-bold mb-0">{{ Auth::user()->name }}</h5>
            </div>
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/' . Auth::user()->avatar) : asset('build/images/avatar-1.jpg') }}"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 rounded-3 mb-3" style="background: rgba(25, 135, 84, 0.1); color: #198754; border-radius: 12px; padding: 10px;">
        <i class="bx bx-check-circle me-1"></i>{{ session('success') }}
    </div>
    @endif

    @php
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    @endphp

    @foreach($days as $day)
        <div class="day-section">
            <div class="day-header">
                <h6><i class="bx bx-calendar me-2"></i>{{ $day }}</h6>
            </div>
            <div class="day-content">
                @if(isset($schedules[$day]) && $schedules[$day]->count() > 0)
                    @foreach($schedules[$day] as $schedule)
                        <div class="schedule-item">
                            <div class="schedule-icon">
                                <i class="bx bx-book"></i>
                            </div>
                            <div class="schedule-info flex-grow-1">
                                <h6>{{ $schedule->subject }}</h6>
                                <p>{{ $schedule->class_name }}</p>
                                <small><i class="bx bx-time-five"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }}</small>
                            </div>
                            <div class="school-badge">
                                {{ $schedule->school->name ?? 'N/A' }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-schedule">
                        <i class="bx bx-calendar-x"></i>
                        <p>Tidak ada jadwal mengajar</p>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
