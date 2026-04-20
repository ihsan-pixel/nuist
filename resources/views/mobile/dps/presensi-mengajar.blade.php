@extends('layouts.mobile')

@section('title', 'Rekap Presensi Mengajar')

@section('content')
<header class="mobile-header d-md-none mb-3">
    <div class="d-flex align-items-center justify-content-between px-2 py-2">
        <div>
            <div class="fw-semibold">Presensi Mengajar</div>
            <div class="text-muted small">{{ $madrasah->name }} (SCOD: {{ $madrasah->scod ?? '-' }})</div>
        </div>
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('mobile.dps.dashboard') }}">
            <i class="bx bx-home"></i>
        </a>
    </div>
</header>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-8">
                <label class="form-label mb-1">Bulan</label>
                <input type="month" name="month" class="form-control" value="{{ $selectedMonth }}">
            </div>
            <div class="col-4">
                <button class="btn btn-success w-100" type="submit">Terapkan</button>
            </div>
        </form>
        <div class="text-muted small mt-2">{{ $teaching['month_name'] }}</div>
    </div>
</div>

<div class="row g-2 mb-3">
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Terjadwal</div>
                <div class="fw-bold">{{ $teaching['summary']['total_scheduled_classes'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Tercatat</div>
                <div class="fw-bold">{{ $teaching['summary']['total_conducted_classes'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">%</div>
                <div class="fw-bold">{{ $teaching['summary']['persentase_pelaksanaan'] }}%</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="fw-semibold mb-2">Riwayat Presensi Mengajar</div>
        @if(empty($teaching['records']))
            <div class="text-muted text-center py-3">Belum ada data presensi mengajar.</div>
        @else
            <div class="d-grid gap-2">
                @foreach($teaching['records'] as $r)
                    <div class="border rounded p-2">
                        <div class="d-flex justify-content-between">
                            <div class="fw-semibold">{{ $r['teacher'] }}</div>
                            <div class="text-muted small">{{ $r['date'] }} {{ $r['time'] }}</div>
                        </div>
                        <div class="text-muted small">
                            {{ $r['subject'] }} | {{ $r['class_name'] }} | {{ $r['schedule_time'] }}
                        </div>
                        @if(!empty($r['materi']))
                            <div class="small mt-1"><span class="text-muted">Materi:</span> {{ $r['materi'] }}</div>
                        @endif
                        @if(!is_null($r['present_students']) && !is_null($r['class_total_students']))
                            <div class="small mt-1">
                                <span class="text-muted">Siswa:</span>
                                {{ $r['present_students'] }}/{{ $r['class_total_students'] }}
                                @if(!is_null($r['percentage']))
                                    ({{ $r['percentage'] }}%)
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

