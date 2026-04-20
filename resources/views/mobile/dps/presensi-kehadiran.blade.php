@extends('layouts.mobile')

@section('title', 'Rekap Presensi Kehadiran')

@section('content')
<header class="mobile-header d-md-none mb-3">
    <div class="d-flex align-items-center justify-content-between px-2 py-2">
        <div>
            <div class="fw-semibold">Presensi Kehadiran</div>
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
                <button class="btn btn-primary w-100" type="submit">Terapkan</button>
            </div>
        </form>
        <div class="text-muted small mt-2">
            {{ $monthly['month_name'] }} | Hari KBM: {{ $monthly['hari_kbm'] }} | Hari kerja: {{ $monthly['total_working_days'] }}
        </div>
    </div>
</div>

<div class="row g-2 mb-3">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Total Guru</div>
                <div class="fw-bold">{{ $monthly['summary']['total_teachers'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Persentase Hadir</div>
                <div class="fw-bold">{{ $monthly['summary']['persentase_sekolah'] }}%</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Hadir</div>
                <div class="fw-bold text-success">{{ $monthly['summary']['total_hadir'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Izin</div>
                <div class="fw-bold text-warning">{{ $monthly['summary']['total_izin'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Alpha</div>
                <div class="fw-bold text-danger">{{ $monthly['summary']['total_alpha'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="fw-semibold mb-2">Rekap Per Guru</div>
        <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th class="text-center">H</th>
                        <th class="text-center">I</th>
                        <th class="text-center">A</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthly['teachers'] as $t)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $t['name'] }}</div>
                                <div class="text-muted" style="font-size:11px;">{{ $t['ketugasan'] ?? '-' }}</div>
                            </td>
                            <td class="text-center text-success fw-semibold">{{ $t['hadir'] }}</td>
                            <td class="text-center text-warning fw-semibold">{{ $t['izin'] }}</td>
                            <td class="text-center text-danger fw-semibold">{{ $t['alpha'] }}</td>
                            <td class="text-center fw-semibold">{{ $t['persentase_hadir'] }}%</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

