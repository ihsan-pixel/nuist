@extends('layouts.mobile')
@section('title', 'Monitoring Jurnal Mengajar')
@section('subtitle', 'Rekap mingguan kegiatan mengajar')
@section('content')
<div class="container py-3" style="max-width: 720px; margin:auto;">
    <style>
        body{background:#f7faf8;font-family:'Poppins',sans-serif;font-size:13px;}
        .hero{background:linear-gradient(135deg,#004b4c,#0e8549);color:#fff;border-radius:18px;box-shadow:0 10px 24px rgba(0,75,76,.18);}
        .chip{display:inline-flex;align-items:center;padding:7px 12px;border-radius:999px;border:1px solid rgba(255,255,255,.22);font-size:12px;font-weight:600;}
        .card-soft{border-radius:16px;box-shadow:0 8px 20px rgba(15,56,57,.06);border:1px solid #e7eeea;background:#fff;}
    </style>

    <div class="d-flex align-items-center mb-3">
        <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color:#004b4c;"><i class="bx bx-arrow-back" style="font-size:20px;"></i></button>
        <div>
            <div class="fw-bold" style="color:#004b4c;font-size:16px;">Monitoring Jurnal Mengajar</div>
            <small class="text-muted">Rekap mingguan dan navigasi hari</small>
        </div>
    </div>

    <div class="card border-0 hero mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between gap-3">
                <div>
                    <div class="fw-semibold">{{ Auth::user()->madrasah->name ?? '-' }}</div>
                    <div class="small" style="opacity:.85;">{{ $summary['week_label'] ?? '-' }}</div>
                </div>
                <div class="text-end">
                    <div class="small" style="opacity:.75;">Hari aktif</div>
                    <div class="fw-bold">{{ $selectedRecap['label'] ?? '-' }}</div>
                </div>
            </div>

            <form method="GET" class="mt-3">
                <div class="row g-2">
                    <div class="col-12">
                        <input type="date" name="date" class="form-control form-control-sm" value="{{ $selectedDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-12">
                        <select name="class_name" class="form-select form-select-sm">
                            <option value="">Semua kelas</option>
                            @foreach($availableClasses as $className)
                                <option value="{{ $className }}" @selected($selectedClass === $className)>{{ $className }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-light btn-sm w-100 fw-semibold">Terapkan</button>
                    </div>
                </div>
            </form>

            <div class="d-flex flex-wrap gap-2 mt-3">
                @foreach($weekDays as $day)
                    <a href="{{ request()->fullUrlWithQuery(['date' => $day['date'], 'day' => $day['key']]) }}" class="chip text-decoration-none {{ ($selectedDay ?? '') === $day['key'] ? 'bg-white text-dark' : 'text-white' }}">
                        {{ ucfirst($day['key']) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-4"><div class="card-soft p-3"><div class="text-muted small">Hadir</div><div class="fw-bold fs-4">{{ $selectedRecap['hadir'] ?? 0 }}</div></div></div>
        <div class="col-4"><div class="card-soft p-3"><div class="text-muted small">Izin</div><div class="fw-bold fs-4">{{ $selectedRecap['izin'] ?? 0 }}</div></div></div>
        <div class="col-4"><div class="card-soft p-3"><div class="text-muted small">Belum</div><div class="fw-bold fs-4">{{ $selectedRecap['belum'] ?? 0 }}</div></div></div>
    </div>

    @php($groupedItems = collect($selectedRecap['items'] ?? []))
    @if($groupedItems->isEmpty())
        <div class="card-soft">
            <div class="card-body text-center py-5 text-muted">
                Tidak ada data untuk hari ini.
            </div>
        </div>
    @else
        <div class="d-grid gap-2">
            @foreach($groupedItems as $group)
                <div class="card-soft">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="fw-semibold">{{ $group['class_name'] ?? '-' }}</div>
                            <span class="badge bg-light text-dark">{{ count($group['items'] ?? []) }} sesi</span>
                        </div>
                        <div class="d-grid gap-2">
                            @foreach($group['items'] as $item)
                                @php($schedule = $item['schedule'] ?? null)
                                <div class="border rounded-3 p-2" style="background:#f8fbf9;">
                                    <div class="d-flex justify-content-between gap-2">
                                        <div>
                                            <div class="fw-semibold" style="font-size:12px;">{{ $schedule?->teacher?->name ?? ($item['teacher'] ?? '-') }}</div>
                                            <div class="text-muted small">{{ $item['subject'] ?? '-' }}</div>
                                        </div>
                                        <span class="badge {{ ($item['status'] ?? 'belum') === 'izin' ? 'bg-info' : (($item['status'] ?? 'belum') === 'hadir' ? 'bg-success' : 'bg-warning') }}">{{ strtoupper($item['status'] ?? 'belum') }}</span>
                                    </div>
                                    <div class="text-muted small mt-1">{{ $item['time'] ?? '-' }}</div>
                                    @if(($item['status'] ?? null) === 'izin' && !empty($item['event']))
                                        <div class="small text-success mt-1">{{ $item['event']?->name ?? 'Kegiatan sekolah' }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
