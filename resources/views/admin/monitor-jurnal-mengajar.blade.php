@extends('layouts.master')
@section('title', 'Monitoring Jurnal Mengajar')
@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 18px; background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);">
            <div class="card-body p-4 text-white">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div>
                        <h4 class="mb-1 text-white">{{ Auth::user()->madrasah->name ?? '-' }}</h4>
                        <div class="text-white-50 small">Minggu {{ $summary['week_label'] ?? '-' }}</div>
                    </div>
                    <div class="text-end">
                        <div class="small text-white-50">Hari aktif</div>
                        <div class="fw-bold fs-4">{{ $selectedRecap['label'] ?? '-' }}</div>
                    </div>
                </div>

                <form method="GET" class="mt-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label text-white-50 mb-1">Tanggal acuan</label>
                            <input type="date" name="date" class="form-control form-control-sm" value="{{ $selectedDate->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-white-50 mb-1">Kelas</label>
                            <select name="class_name" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                @foreach($availableClasses as $className)
                                    <option value="{{ $className }}" @selected($selectedClass === $className)>{{ $className }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-light btn-sm w-100 fw-semibold" type="submit">Terapkan</button>
                        </div>
                    </div>
                </form>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    @foreach($weekDays as $day)
                        <a href="{{ request()->fullUrlWithQuery(['date' => $day['date'], 'day' => $day['key']]) }}"
                           class="btn btn-sm {{ ($selectedDay ?? '') === $day['key'] ? 'btn-light text-dark' : 'btn-outline-light' }}"
                           style="border-radius: 999px;">
                            {{ ucfirst($day['key']) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">Rekap Hari Ini</h5>
                        <div class="text-muted small">{{ $selectedRecap['label'] ?? '-' }}</div>
                    </div>
                    <span class="badge bg-success-subtle text-success">{{ $selectedRecap['total'] ?? 0 }} sesi</span>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-4"><div class="border rounded-3 p-3"><div class="text-muted small">Hadir</div><div class="fs-4 fw-bold">{{ $selectedRecap['hadir'] ?? 0 }}</div></div></div>
                    <div class="col-4"><div class="border rounded-3 p-3"><div class="text-muted small">Izin</div><div class="fs-4 fw-bold">{{ $selectedRecap['izin'] ?? 0 }}</div></div></div>
                    <div class="col-4"><div class="border rounded-3 p-3"><div class="text-muted small">Belum</div><div class="fs-4 fw-bold">{{ $selectedRecap['belum'] ?? 0 }}</div></div></div>
                </div>

                @php($groupedItems = collect($selectedRecap['items'] ?? []))
                @if($groupedItems->isEmpty())
                    <div class="text-center text-muted py-5">Tidak ada data untuk hari ini.</div>
                @else
                    <div class="d-grid gap-3">
                        @foreach($groupedItems as $group)
                            <div class="border rounded-4 p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fw-semibold">{{ $group['class_name'] ?? '-' }}</div>
                                    <span class="badge bg-light text-dark">{{ count($group['items'] ?? []) }} sesi</span>
                                </div>
                                <div class="d-grid gap-2">
                                    @foreach($group['items'] as $item)
                                        @php($schedule = $item['schedule'] ?? null)
                                        <div class="rounded-3 p-3" style="background:#f8fbf9;">
                                            <div class="d-flex justify-content-between gap-2">
                                                <div>
                                                    <div class="fw-semibold">{{ $schedule?->teacher?->name ?? ($item['teacher'] ?? '-') }}</div>
                                                    <div class="text-muted small">{{ $item['subject'] ?? '-' }}</div>
                                                </div>
                                                <span class="badge {{ ($item['status'] ?? 'belum') === 'izin' ? 'bg-info' : (($item['status'] ?? 'belum') === 'hadir' ? 'bg-success' : 'bg-warning') }}">
                                                    {{ strtoupper($item['status'] ?? 'belum') }}
                                                </span>
                                            </div>
                                            <div class="text-muted small mt-1">{{ $item['time'] ?? '-' }}</div>
                                            @if(($item['status'] ?? null) === 'izin' && !empty($item['event']))
                                                <div class="small text-success mt-1">{{ $item['event']?->name ?? 'Kegiatan sekolah' }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 18px;">
            <div class="card-body p-4">
                <h6 class="mb-3">Ringkasan Mingguan</h6>
                <div class="d-grid gap-2">
                    @foreach($dailyRecaps as $daily)
                        <a href="{{ request()->fullUrlWithQuery(['date' => $daily['date'], 'day' => \Carbon\Carbon::parse($daily['date'])->locale('id')->dayName]) }}" class="text-decoration-none">
                            <div class="border rounded-3 p-3 {{ ($selectedRecap['date'] ?? '') === $daily['date'] ? 'border-success' : '' }}">
                                <div class="fw-semibold">{{ $daily['label'] }}</div>
                                <div class="text-muted small">H {{ $daily['hadir'] }} | I {{ $daily['izin'] }} | B {{ $daily['belum'] }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
