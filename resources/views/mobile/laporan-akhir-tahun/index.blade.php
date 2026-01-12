@extends('layouts.mobile')

@section('title', 'Laporan Akhir Tahun')

@section('content')
<link rel="stylesheet" href="{{ asset('css/mobile/laporan-akhir-tahun-create.css') }}">

<style>
    body {
    background: #f8f9fb url('/images/bg.png') no-repeat center center;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
}

    .report-actions .btn {
        font-size: 10px;
        padding: 0.2rem 0.4rem;
    }
</style>

<!-- Header -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='{{ route('mobile.profile') }}'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #ffffff;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #ffffff; font-size: 12px;">Kembali</span>
</div>

<div class="simfoni-header" style="margin-top: -10px;">
    <h4>LAPORAN AKHIR TAHUN</h4>
    <p>Kepala Sekolah/Madrasah</p>
</div>

<!-- Main Container -->
<div class="form-container">
    <!-- Success Alert will be shown via SweetAlert -->
    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" style="display: none;"></div>
    @endif

    <!-- Info Alert -->
    @if (session('info'))
        <div class="info-alert">
            ℹ {{ session('info') }}
        </div>
    @endif

    <!-- Warning Alert -->
    @if (session('warning'))
        <div class="warning-alert">
            ⚠ {{ session('warning') }}
        </div>
    @endif

    <!-- Reports List Card -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-list-ul"></i>
            </div>
            <h6 class="section-title">DAFTAR LAPORAN</h6>
        </div>

        <div class="section-content" style="width: 100%; margin-right: auto; margin-left: auto;">
            @if($laporans->count() > 0)
                <div class="reports-list">
                    @foreach($laporans as $laporan)
                        <div class="report-card">
                            <div class="report-header">
                                <div class="report-title">
                                    <h6>{{ $laporan->nama_madrasah }}</h6>
                                    <span>
                                        Berhasil Terkirim
                                    </span>
                                </div>
                                <div class="report-meta">
                                    <span class="year">Tahun {{ $laporan->tahun_pelaporan }}</span>
                                    <span class="date">{{ $laporan->created_at ? \Carbon\Carbon::parse($laporan->created_at)->format('d/m/Y') : '-' }}</span>
                                </div>
                            </div>
                            <div class="report-actions">
                                <a href="{{ route('mobile.laporan-akhir-tahun.show', $laporan->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                    <i class="bx bx-show"></i>
                                    Lihat
                                </a>
                                <a href="{{ route('mobile.laporan-akhir-tahun.edit', $laporan->id) }}" class="btn btn-sm btn-outline-warning" title="Edit Laporan">
                                    <i class="bx bx-edit"></i>
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bx bx-file-blank"></i>
                    </div>
                    <h5>Belum Ada Laporan</h5>
                    <p>Anda belum membuat laporan akhir tahun. Mulai buat laporan pertama Anda sekarang.</p>
                    <a href="{{ route('mobile.laporan-akhir-tahun.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i>
                        Buat Laporan Baru
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK'
        });
    @endif
</script>
