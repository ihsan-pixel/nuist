@extends('layouts.mobile')

@section('title', 'Dashboard Peserta - Instrument Talenta')

@section('content')
<link rel="stylesheet" href="{{ asset('css/mobile/laporan-akhir-tahun-create.css') }}">

<style>
    body {
        background: #f8f9fb url('/images/bg.png') no-repeat center center;
        background-size: cover;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
    }

    .evaluation-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 16px;
        overflow: hidden;
    }

    .evaluation-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .evaluation-icon {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .evaluation-title {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }

    .evaluation-content {
        padding: 16px;
    }

    .criteria-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .criteria-table th,
    .criteria-table td {
        padding: 8px 4px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .criteria-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }

    .criteria-number {
        width: 30px;
        text-align: center;
    }

    .criteria-aspect {
        font-weight: 500;
        color: #212529;
    }

    .criteria-indicator {
        color: #6c757d;
        font-style: italic;
    }

    .scale-info {
        margin-top: 12px;
        padding: 12px;
        background: #e9ecef;
        border-radius: 8px;
        font-size: 11px;
    }

    .scale-info strong {
        color: #495057;
    }

    .scale-labels {
        display: flex;
        justify-content: space-between;
        margin-top: 8px;
        font-size: 10px;
        color: #6c757d;
    }
</style>

<!-- Header -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='{{ route('instumen-talenta.index') }}'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #ffffff;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #ffffff; font-size: 12px;">Kembali</span>
</div>

<div class="simfoni-header" style="margin-top: -10px;">
    <h4>INSTRUMENT TALENTA</h4>
    <p>Dashboard Peserta</p>
</div>

<!-- Main Container -->
<div class="form-container">
    <!-- Welcome Message -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-star"></i>
            </div>
            <h6 class="section-title">SELAMAT DATANG</h6>
        </div>
        <div class="section-content">
            <p style="margin: 0; color: #6c757d; font-size: 13px;">
                Terima kasih telah berpartisipasi dalam program pengembangan talenta. Silakan berikan penilaian Anda terhadap pemateri, fasilitator, dan tim layanan teknis yang telah mendukung kegiatan ini.
            </p>
        </div>
    </div>

    <!-- Instrumen Penilaian Menu -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-list-check"></i>
            </div>
            <h6 class="section-title">INSTRUMEN PENILAIAN</h6>
        </div>
        <div class="section-content">
            <!-- Action Buttons -->
            <div class="row g-2 mb-4">
                <div class="col-12">
                    <a href="{{ route('instumen-talenta.penilaian-pemateri') }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="padding: 12px; font-size: 14px;">
                        <i class="bx bx-chalkboard"></i>
                        <span>Instrumen Penilaian Pemateri</span>
                    </a>
                </div>
                <div class="col-12">
                    <a href="{{ route('instumen-talenta.penilaian-fasilitator') }}" class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="padding: 12px; font-size: 14px;">
                        <i class="bx bx-user-check"></i>
                        <span>Instrumen Penilaian Fasilitator</span>
                    </a>
                </div>
                <div class="col-12">
                    <a href="{{ route('instumen-talenta.penilaian-teknis') }}" class="btn btn-info w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="padding: 12px; font-size: 14px;">
                        <i class="bx bx-cog"></i>
                        <span>Instrumen Penilaian Tim Layanan Teknis</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
