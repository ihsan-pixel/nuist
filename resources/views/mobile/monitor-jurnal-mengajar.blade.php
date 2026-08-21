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
            gap: 8px;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.14);
            border-radius: 14px;
            padding: 8px 10px;
            min-width: 0;
        }

        .stat-box small {
            display: block;
            opacity: 0.8;
            font-size: 10px;
            line-height: 1.2;
        }

        .stat-box strong {
            font-size: 15px;
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
                    <div class="fw-semibold" style="font-size: 15px;">{{ Auth::user()->madrasah->name ?? '-' }}</div>
                    <div style="font-size: 11px; opacity: 0.85;">Periode {{ $summary['bulan'] }}</div>
                </div>
                <div class="text-end">
                    <div style="font-size: 11px; opacity: 0.75;">Total jurnal</div>
                    <div class="fw-bold" style="font-size: 22px; line-height: 1;">{{ $summary['total_jurnal'] }}</div>
                </div>
            </div>

            @if(!empty($approvedEventName))
                <div class="mt-3 p-3 rounded-4" style="background: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.18);">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bx bx-info-circle" style="font-size: 18px; line-height: 1.2;"></i>
                        <div>
                            <div class="fw-semibold" style="font-size: 12px;">{{ $approvedEventLabel ?? 'Kegiatan Sekolah' }} disetujui</div>
                            <div style="font-size: 11px; opacity: 0.9;">{{ $approvedEventName }}</div>
                            @if(!empty($approvedEventNote))
                                <div class="mt-1" style="font-size: 11px; opacity: 0.85;">{{ $approvedEventNote }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <form method="GET" class="mt-3">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label mb-1" style="font-size: 11px; opacity: 0.85;">Bulan</label>
                        <input type="month" name="month" class="form-control form-control-sm" value="{{ $selectedMonth }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label mb-1" style="font-size: 11px; opacity: 0.85;">Kelas</label>
                        <select name="class_name" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            @foreach($availableClasses as $className)
                                <option value="{{ $className }}" @selected($selectedClass === $className)>{{ $className }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-light btn-sm w-100 mt-2" style="font-weight: 600;">Terapkan</button>
            </form>

            <div class="stat-grid mt-3">
                <div class="stat-box">
                    <small>Guru aktif</small>
                    <strong>{{ $summary['total_guru'] }}</strong>
                </div>
                <div class="stat-box">
                    <small>Jurnal tampil</small>
                    <strong>{{ $summary['total_jurnal'] }}</strong>
                </div>
                <div class="stat-box">
                    <small>Izin kegiatan</small>
                    <strong>{{ $summary['total_izin'] ?? 0 }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 mb-3">
        <div class="card-body py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="fw-semibold">Jurnal sudah tercatat</div>
                <span class="text-muted small">{{ $completedJournals->count() }}</span>
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
        <div class="d-grid gap-2">
            @foreach($records as $record)
                @php
                    $schedule = $record->teachingSchedule;
                @endphp
                <div class="card border-0 journal-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="fw-semibold" style="font-size: 13px;">{{ $schedule->teacher->name ?? '-' }}</div>
                                <div class="journal-meta">
                                    {{ \Carbon\Carbon::parse($record->tanggal)->format('d M') }}
                                    {{ $record->waktu ? '• ' . \Carbon\Carbon::parse($record->waktu)->format('H:i') : '' }}
                                </div>
                            </div>
                            <span class="journal-pill">
                                @if(($record->status ?? 'hadir') === 'izin')
                                    IZIN
                                @else
                                    {{ strtoupper($record->status ?? 'hadir') }}
                                @endif
                            </span>
                        </div>

                        <div class="journal-detail mt-2">
                            <div class="journal-detail-row">
                                <span>{{ $schedule->classNameLabel() ?: ($schedule->class_name ?? '-') }}</span>
                                <strong class="text-dark text-end">{{ $schedule->subject ?? '-' }}</strong>
                            </div>
                            <div class="journal-detail-row">
                                <span>Jam</span>
                                <strong class="text-dark text-end">{{ trim(($schedule->start_time ?? '-') . ' - ' . ($schedule->end_time ?? '-')) }}</strong>
                            </div>
                            <div class="journal-detail-row">
                                <span>Materi</span>
                                <strong class="text-dark text-end text-truncate" style="max-width: 62%;">{{ $record->materi ?: '-' }}</strong>
                            </div>
                            @if(($record->status ?? 'hadir') === 'izin' && !empty($record->academicCalendarEvent))
                                <div class="journal-detail-row">
                                    <span>Keterangan</span>
                                    <strong class="text-dark text-end text-truncate" style="max-width: 62%;">{{ $record->academicCalendarEvent->name ?? $record->lokasi ?? 'Kegiatan sekolah' }}</strong>
                                </div>
                            @endif
                            <div class="journal-detail-row">
                                <span>Siswa</span>
                                <strong class="text-dark text-end">
                                    @if(!is_null($record->present_students) && !is_null($record->class_total_students))
                                        {{ $record->present_students }}/{{ $record->class_total_students }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $records->links('vendor.pagination.bootstrap-5') }}
        </div>
    @endif

    @if($missingJournals->isNotEmpty())
        <div class="card border-0 mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-semibold">Belum jurnal</div>
                    <span class="text-muted small">{{ $missingJournals->count() }}</span>
                </div>
                <div class="d-grid gap-2">
                    @foreach($missingJournals->take(10) as $item)
                        <div class="border rounded-3 px-2 py-2">
                            <div class="d-flex justify-content-between gap-2">
                                <div class="fw-semibold" style="font-size: 12px;">{{ $item['teacher'] }}</div>
                                <div class="text-muted small">{{ \Carbon\Carbon::parse($item['date'])->format('d M') }}</div>
                            </div>
                            <div class="text-muted small">
                                {{ $item['class_name'] }} · {{ $item['subject'] }} · {{ $item['time'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
