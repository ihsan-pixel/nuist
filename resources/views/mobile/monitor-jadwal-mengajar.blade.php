@extends('layouts.mobile')

@section('title', 'Monitoring Jadwal Mengajar')
@section('subtitle', 'Data Jadwal Mengajar Madrasah')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }

        .monitor-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 12px;
            padding: 12px 10px;
            box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
            margin-bottom: 10px;
        }

        .back-btn {
            background: none;
            border: none;
            color: #004b4c;
            font-size: 16px;
            padding: 8px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            background: #f0f8f0;
        }

        .monitor-header h6 {
            font-weight: 600;
            font-size: 12px;
        }

        .monitor-header h5 {
            font-size: 14px;
        }

        .date-nav {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .date-nav button {
            background: none;
            border: none;
            color: #0e8549;
            font-size: 16px;
            padding: 5px;
        }

        .date-nav .current-date {
            font-weight: 600;
            font-size: 14px;
            color: #333;
        }

        .jadwal-section {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .section-title {
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 8px;
            color: #333;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 5px;
        }

        .jadwal-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .jadwal-item {
            padding: 8px 0;
            border-bottom: 1px solid #f1f1f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .jadwal-item:last-child {
            border-bottom: none;
        }

        .jadwal-info {
            flex: 1;
        }

        .jadwal-info .class-subject {
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 2px;
        }

        .jadwal-info .teacher {
            font-size: 10px;
            color: #6c757d;
        }

        .jadwal-time {
            text-align: right;
        }

        .jadwal-time .time {
            font-weight: 600;
            font-size: 12px;
            color: #0e8549;
        }

        .jadwal-time .sub-time {
            font-size: 10px;
            color: #6c757d;
        }

        .status-badge {
            background: rgba(255, 193, 7, 0.1);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: 600;
            margin-left: 4px;
        }

        .status-hadir {
            background: rgba(40, 167, 69, 0.1);
            color: #155724;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 24px;
            margin-bottom: 8px;
        }
    </style>

    <!-- Back Button -->
    <button onclick="history.back()" class="back-btn">
        <i class="bx bx-arrow-back"></i>
        <span>Kembali</span>
    </button>

    <!-- Header -->
    <div class="monitor-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Monitoring Jadwal Mengajar</h6>
                <h5 class="fw-bold mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah' }}</h5>
            </div>
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div>

    <!-- Date Navigation -->
    <div class="date-nav">
        <button type="button" onclick="changeDate('{{ $selectedDate->copy()->subDay()->toDateString() }}')">
            <i class="bx bx-chevron-left"></i>
        </button>
        <div class="current-date">{{ $selectedDate->format('d M Y') }}</div>
        <button type="button" onclick="changeDate('{{ $selectedDate->copy()->addDay()->toDateString() }}')">
            <i class="bx bx-chevron-right"></i>
        </button>
    </div>

    <!-- Jadwal Mengajar -->
    <div class="jadwal-section">
        <h6 class="section-title">
            <i class="bx bx-calendar-event"></i>
            Jadwal Mengajar ({{ $schedules->count() }})
        </h6>
        @if($schedules->isEmpty())
            <div class="empty-state">
                <i class="bx bx-calendar-x"></i>
                <p>Tidak ada jadwal mengajar pada tanggal ini.</p>
            </div>
        @else
            <ul class="jadwal-list">
                @foreach($schedules as $schedule)
                    <li class="jadwal-item">
                        <div class="jadwal-info">
                            <div class="class-subject">
                                {{ $schedule->class_name }} - {{ $schedule->subject }}
                                @if($schedule->attendance_status === 'hadir')
                                    <span class="status-badge status-hadir">HADIR</span>
                                @else
                                    <span class="status-badge">BELUM</span>
                                @endif
                            </div>
                            <div class="teacher">{{ $schedule->teacher->name ?? '-' }}</div>
                        </div>
                        <div class="jadwal-time">
                            <div class="time">{{ $schedule->start_time }} - {{ $schedule->end_time }}</div>
                            @if($schedule->attendance_status === 'hadir' && $schedule->attendance_time)
                                <div class="sub-time">Presensi {{ $schedule->attendance_time->format('H:i') }}</div>
                            @else
                                <div class="sub-time">Belum presensi</div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection

@section('script')
<script>
function changeDate(date) {
    window.location.href = '{{ route("mobile.monitor-jadwal-mengajar") }}?date=' + date;
}
</script>
@endsection
