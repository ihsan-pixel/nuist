@extends('layouts.mobile')

@section('title', 'Dashboard DPS')

@section('content')
<header class="mobile-header d-md-none mb-3">
    <div class="d-flex align-items-center justify-content-between px-2 py-2">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar-sm">
                <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                    <i class="bx bx-shield-quarter"></i>
                </div>
            </div>
            <div>
                <div class="fw-semibold" style="line-height:1.1;">{{ $selectedMadrasah->name }}</div>
                <div class="text-muted small">SCOD: {{ $selectedMadrasah->scod ?? '-' }}</div>
            </div>
        </div>
        <div class="text-end">
            <div class="small text-muted">DPS</div>
            <div class="fw-semibold" style="font-size:13px;">{{ $user->name }}</div>
        </div>
    </div>
</header>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <label class="form-label mb-1">Pilih Sekolah</label>
            <select class="form-select" name="madrasah_id" onchange="this.form.submit()">
                @foreach($madrasahs as $m)
                    <option value="{{ $m->id }}" @selected($m->id === $selectedMadrasah->id)>
                        {{ $m->scod ?? '-' }} - {{ $m->name ?? '-' }}
                    </option>
                @endforeach
            </select>
        </form>

        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="text-muted small">Kepala Sekolah</div>
                <div class="fw-semibold">{{ $kepalaSekolah }}</div>
            </div>
            <div class="text-end">
                <div class="text-muted small">Kabupaten</div>
                <div class="fw-semibold">{{ $selectedMadrasah->kabupaten ?? '-' }}</div>
            </div>
        </div>
        <div class="text-muted small mt-2">
            {{ $selectedMadrasah->alamat ?? 'Alamat belum diisi.' }}
        </div>
    </div>
</div>

<div class="row g-2 mb-3">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Tenaga Pendidik</div>
                <div class="fw-bold" style="font-size:18px;">{{ $jumlahGuru }}</div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Jumlah Siswa</div>
                <div class="fw-bold" style="font-size:18px;">{{ $jumlahSiswa }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="fw-semibold">Data Tenaga Pendidik</div>
            <div class="text-muted small">Tampil maks 20</div>
        </div>
        @if($tenagaPendidik->isEmpty())
            <div class="text-muted text-center py-2">Belum ada data tenaga pendidik.</div>
        @else
            <div class="d-grid gap-1">
                @foreach($tenagaPendidik as $tp)
                    <div class="d-flex justify-content-between align-items-center border rounded px-2 py-1">
                        <div class="fw-semibold" style="font-size:13px;">{{ $tp->name }}</div>
                        <div class="text-muted" style="font-size:11px;">{{ $tp->ketugasan ?? '-' }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="fw-semibold mb-2">Menu DPS</div>
        <div class="d-grid gap-2">
            <a class="btn btn-outline-primary" href="{{ route('mobile.dps.presensi-kehadiran') }}">
                <i class="bx bx-check-square me-1"></i> Rekap Presensi Kehadiran
            </a>
            <a class="btn btn-outline-success" href="{{ route('mobile.dps.presensi-mengajar') }}">
                <i class="bx bx-calendar-check me-1"></i> Rekap Presensi Mengajar
            </a>
            <a class="btn btn-outline-secondary" href="{{ route('mobile.dps.profile') }}">
                <i class="bx bx-user me-1"></i> Profile DPS
            </a>
        </div>
    </div>
</div>
@endsection
