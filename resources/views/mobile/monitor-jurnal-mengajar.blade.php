@extends('layouts.mobile')

@section('title', 'Monitoring Jurnal Mengajar')
@section('subtitle', 'Rekap kegiatan mengajar guru')

@section('content')
<div class="container py-3" style="max-width: 720px; margin: auto;">
    <style>
        body {
            background: #f7faf8;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }

        .hero-card {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(0, 75, 76, 0.18);
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.14);
            border-radius: 14px;
            padding: 10px 12px;
        }

        .stat-box small {
            display: block;
            opacity: 0.8;
            font-size: 11px;
        }

        .stat-box strong {
            font-size: 18px;
            line-height: 1.1;
        }

        .journal-card {
            border: 1px solid #e7eeea;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(15, 56, 57, 0.06);
        }

        .journal-meta {
            font-size: 11px;
            color: #6c757d;
        }

        .journal-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            background: #eef7f4;
            color: #0e8549;
        }

        .journal-detail {
            display: grid;
            gap: 6px;
            margin-top: 10px;
        }

        .journal-detail-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 12px;
        }

        .journal-detail-row span:first-child {
            color: #6c757d;
            min-width: 88px;
        }

        .empty-state {
            border-radius: 18px;
            border: 1px dashed #cfe1d9;
            background: #fff;
        }

        @media (max-width: 576px) {
            .stat-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="d-flex align-items-center mb-3">
        <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <div>
            <div class="fw-bold" style="color: #004b4c; font-size: 16px;">Monitoring Jurnal Mengajar</div>
            <small class="text-muted">Kepala sekolah dapat melihat detail kegiatan guru per bulan</small>
        </div>
    </div>

    <div class="card border-0 hero-card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start gap-3">
                <div>
                    <div class="fw-semibold" style="font-size: 16px;">{{ Auth::user()->madrasah->name ?? '-' }}</div>
                    <div style="font-size: 12px; opacity: 0.85;">Periode {{ $summary['bulan'] }}</div>
                </div>
                <div class="text-end">
                    <div style="font-size: 11px; opacity: 0.75;">Total jurnal</div>
                    <div class="fw-bold" style="font-size: 24px; line-height: 1;">{{ $summary['total_jurnal'] }}</div>
                </div>
            </div>

            <form method="GET" class="mt-3">
                <div class="row g-2 align-items-end">
                    <div class="col-8">
                        <label class="form-label mb-1" style="font-size: 11px; opacity: 0.85;">Bulan</label>
                        <input type="month" name="month" class="form-control form-control-sm" value="{{ $selectedMonth }}">
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-light btn-sm w-100" style="font-weight: 600;">Terapkan</button>
                    </div>
                </div>
            </form>

            <div class="stat-grid mt-3">
                <div class="stat-box">
                    <small>Guru aktif</small>
                    <strong>{{ $summary['total_guru'] }}</strong>
                </div>
                <div class="stat-box">
                    <small>Jurnal tampil</small>
                    <strong>{{ $records->count() }}</strong>
                </div>
                <div class="stat-box">
                    <small>Halaman</small>
                    <strong>{{ $records->currentPage() }}</strong>
                </div>
            </div>
        </div>
    </div>

    @if($records->isEmpty())
        <div class="card border-0 empty-state">
            <div class="card-body text-center py-5">
                <i class="bx bx-book-open fs-1 text-muted"></i>
                <h6 class="mt-3 mb-1">Belum ada jurnal mengajar</h6>
                <p class="text-muted mb-0">Belum ada data presensi mengajar pada bulan yang dipilih.</p>
            </div>
        </div>
    @else
        <div class="d-grid gap-3">
            @foreach($records as $record)
                @php
                    $schedule = $record->teachingSchedule;
                @endphp
                <div class="card border-0 journal-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="fw-semibold">{{ $schedule->teacher->name ?? '-' }}</div>
                                <div class="journal-meta">
                                    {{ \Carbon\Carbon::parse($record->tanggal)->format('d M Y') }}
                                    {{ $record->waktu ? '• ' . \Carbon\Carbon::parse($record->waktu)->format('H:i') : '' }}
                                </div>
                            </div>
                            <span class="journal-pill">
                                {{ strtoupper($record->status ?? 'hadir') }}
                            </span>
                        </div>

                        <div class="journal-detail">
                            <div class="journal-detail-row">
                                <span>Kelas</span>
                                <strong class="text-dark text-end">{{ $schedule->classNameLabel() ?? $schedule->class_name ?? '-' }}</strong>
                            </div>
                            <div class="journal-detail-row">
                                <span>Mapel</span>
                                <strong class="text-dark text-end">{{ $schedule->subject ?? '-' }}</strong>
                            </div>
                            <div class="journal-detail-row">
                                <span>Jam</span>
                                <strong class="text-dark text-end">
                                    {{ trim(($schedule->start_time ?? '-') . ' - ' . ($schedule->end_time ?? '-')) }}
                                </strong>
                            </div>
                            <div class="journal-detail-row">
                                <span>Materi</span>
                                <strong class="text-dark text-end" style="max-width: 65%; word-break: break-word;">
                                    {{ $record->materi ?: '-' }}
                                </strong>
                            </div>
                            <div class="journal-detail-row">
                                <span>Siswa</span>
                                <strong class="text-dark text-end">
                                    @if(!is_null($record->present_students) && !is_null($record->class_total_students))
                                        {{ $record->present_students }}/{{ $record->class_total_students }}
                                        @if(!is_null($record->student_attendance_percentage))
                                            ({{ number_format($record->student_attendance_percentage, 1) }}%)
                                        @endif
                                    @else
                                        -
                                    @endif
                                </strong>
                            </div>
                            @if($record->lokasi)
                                <div class="journal-detail-row">
                                    <span>Lokasi</span>
                                    <strong class="text-dark text-end">{{ $record->lokasi }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $records->links('vendor.pagination.bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
