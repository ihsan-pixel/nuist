@extends('layouts.mobile')

@section('title', 'Laporan')
@section('subtitle', 'Laporan Presensi')

@section('content')
    <!-- Back Button -->
    <div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
        <a href="{{ route('mobile.dashboard') }}" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </a>
        <span class="fw-bold" style="color: #004b4c; font-size: 12px;">Kembali</span>
    </div>

<div class="row g-2">
    <div class="col-6">
        <a href="{{ route('mobile.riwayat-presensi') }}" class="text-decoration-none">
            <div class="card shadow-sm text-center p-3">
                <div class="avatar-md mx-auto mb-2">
                    <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                        <i class="bx bx-history fs-3"></i>
                    </div>
                </div>
                <h6 class="mb-0">Aktifitas Presensi</h6>
                <p class="text-muted small mb-0">Riwayat presensi harian Anda (bulan ini)</p>
            </div>
        </a>
    </div>

    <div class="col-6">
        <a href="{{ route('mobile.laporan.mengajar') }}" class="text-decoration-none">
            <div class="card shadow-sm text-center p-3">
                <div class="avatar-md mx-auto mb-2">
                    <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                        <i class="bx bx-chalkboard fs-3"></i>
                    </div>
                </div>
                <h6 class="mb-0">Presensi Mengajar</h6>
                <p class="text-muted small mb-0">Laporan dan presensi sesuai jadwal mengajar</p>
            </div>
        </a>
    </div>

    @if(Auth::user()->role === 'tenaga_pendidik' && Auth::user()->ketugasan === 'kepala madrasah/sekolah')
        <div class="col-6">
            <a href="{{ route('mobile.laporan.persentase-kehadiran') }}" class="text-decoration-none">
                <div class="card shadow-sm text-center p-3">
                    <div class="avatar-md mx-auto mb-2">
                        <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                            <i class="bx bx-pie-chart-alt-2 fs-3"></i>
                        </div>
                    </div>
                    <h6 class="mb-0">Persentase Presensi Kehadiran</h6>
                    <p class="text-muted small mb-0">Pilih tenaga pendidik di madrasah Anda lalu lihat persentasenya</p>
                </div>
            </a>
        </div>
    @endif
</div>

@endsection
