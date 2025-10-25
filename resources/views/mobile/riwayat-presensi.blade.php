@extends('layouts.mobile')

@section('title', 'Riwayat Presensi')
@section('subtitle', 'Riwayat Presensi Bulan Ini')

@section('content')
<!-- Back Button -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #004b4c; font-size: 14px;">Kembali</span>
</div>

<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }

        .history-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 12px;
            padding: 12px 10px;
            box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
            margin-bottom: 10px;
        }

        .history-header h6 {
            font-weight: 600;
            font-size: 12px;
        }

        .history-header h5 {
            font-size: 14px;
        }

        .history-item {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 8px;
        }

        .history-date {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 6px;
        }

        .history-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .time-info {
            font-size: 12px;
            color: #666;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-hadir {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .status-izin {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .status-sakit {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .status-alpha {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .no-history {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .no-history i {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .no-history p {
            font-size: 14px;
            margin: 0;
        }
    </style>

    <!-- Header -->
    <div class="history-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Riwayat Presensi</h6>
                <h5 class="fw-bold mb-0">{{ now()->format('F Y') }}</h5>
            </div>
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div>

    <!-- History List -->
    @if($presensiHistory->count() > 0)
        @foreach($presensiHistory as $presensi)
            <div class="history-item">
                <div class="history-date">
                    {{ \Carbon\Carbon::parse($presensi->tanggal)->format('d M Y') }}
                    <span class="text-muted">({{ \Carbon\Carbon::parse($presensi->tanggal)->locale('id')->dayName }})</span>
                </div>
                <div class="history-details">
                    <div class="time-info">
                        @if($presensi->waktu_masuk)
                            <div>Masuk: {{ \Carbon\Carbon::parse($presensi->waktu_masuk)->format('H:i') }}</div>
                        @endif
                        @if($presensi->waktu_keluar)
                            <div>Keluar: {{ \Carbon\Carbon::parse($presensi->waktu_keluar)->format('H:i') }}</div>
                        @endif
                        @if($presensi->keterangan)
                            <div class="text-muted small">{{ $presensi->keterangan }}</div>
                        @endif
                    </div>
                    <div class="status-badge status-{{ $presensi->status }}">
                        {{ ucfirst($presensi->status) }}
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="no-history">
            <i class="bx bx-calendar-x"></i>
            <p>Belum ada riwayat presensi bulan ini</p>
        </div>
    @endif
</div>
@endsection
