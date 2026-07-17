@extends(request()->getHost() === 'spmb.nuist.id' ? 'layouts.master' : 'layouts.master-without-nav')

@section('title', 'Dashboard PPDB - ' . $ppdbSetting->nama_sekolah)

@section('content')
@php
    $isSpmbHost = request()->getHost() === 'spmb.nuist.id';
    $statusPpdb = $ppdbSetting->ppdb_status ?? 'tutup';
    $dashboardUrl = $isSpmbHost ? url('/dashboard') : route('ppdb.sekolah.dashboard');
    $pendaftarUrl = $isSpmbHost
        ? url('/ppdb/lp/pendaftar/' . $ppdbSetting->slug)
        : route('ppdb.lp.pendaftar', $ppdbSetting->slug);
    $pengaturanUrl = $isSpmbHost
        ? url('/ppdb/lp/ppdb-settings/' . $ppdbSetting->sekolah_id)
        : route('ppdb.lp.ppdb-settings', $ppdbSetting->sekolah_id);
    $profilUrl = $isSpmbHost
        ? url('/ppdb/lp/edit/' . $ppdbSetting->sekolah_id)
        : route('ppdb.lp.edit', $ppdbSetting->sekolah_id);
@endphp

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-8">
            <h3 class="mb-1">Dashboard PPDB</h3>
            <p class="text-muted mb-0">{{ $ppdbSetting->nama_sekolah }} - Tahun {{ $ppdbSetting->tahun }}</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <span class="badge {{ $statusPpdb === 'buka' ? 'bg-success' : 'bg-danger' }} fs-6 px-3 py-2">
                PPDB {{ strtoupper($statusPpdb) }}
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Pendaftar</p>
                    <h2 class="mb-0">{{ number_format($statistik['total_pendaftar']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2">Menunggu Verifikasi</p>
                    <h2 class="mb-0 text-warning">{{ number_format($statistik['pending']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2">Terverifikasi</p>
                    <h2 class="mb-0 text-info">{{ number_format($statistik['verifikasi']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2">Lulus</p>
                    <h2 class="mb-0 text-success">{{ number_format($statistik['lulus']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2">Tidak Lulus</p>
                    <h2 class="mb-0 text-danger">{{ number_format($statistik['tidak_lulus']) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1">Ringkasan PPDB</h5>
                            <p class="text-muted mb-0">Akses cepat ke menu PPDB admin sekolah.</p>
                        </div>
                        @if($isSpmbHost)
                        <a href="{{ $dashboardUrl }}" class="btn btn-outline-primary btn-sm">Refresh</a>
                        @endif
                    </div>

                    <div class="list-group list-group-flush">
                        <a href="{{ $pendaftarUrl }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Data Pendaftar</span>
                            <span class="badge bg-primary rounded-pill">{{ number_format($statistik['total_pendaftar']) }}</span>
                        </a>
                        <a href="{{ $pengaturanUrl }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Pengaturan PPDB</span>
                            <span class="text-muted small">Kelola jadwal, status, dan jalur</span>
                        </a>
                        <a href="{{ $profilUrl }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Edit Profile PPDB</span>
                            <span class="text-muted small">Perbarui informasi sekolah</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Status Saat Ini</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Nama Sekolah</span>
                            <span class="fw-semibold text-end">{{ $ppdbSetting->nama_sekolah }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Tahun PPDB</span>
                            <span class="fw-semibold">{{ $ppdbSetting->tahun }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span>Status</span>
                            <span class="fw-semibold {{ $statusPpdb === 'buka' ? 'text-success' : 'text-danger' }}">
                                {{ strtoupper($statusPpdb) }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Aksi Cepat</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ $pendaftarUrl }}" class="btn btn-primary">Buka Data Pendaftar</a>
                        <a href="{{ $pengaturanUrl }}" class="btn btn-outline-secondary">Pengaturan PPDB</a>
                        <a href="{{ $profilUrl }}" class="btn btn-outline-secondary">Edit Profile PPDB</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
