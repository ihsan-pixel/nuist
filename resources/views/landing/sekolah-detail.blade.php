@extends('layouts.master-without-nav')

@section('title', $madrasah->name . ' - NUIST')
@section('description', 'Profil ' . $madrasah->name . ' Dibawah Naungan LPMNU PWNU DIY')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: #f8fafc;
        color: #333;
        line-height: 1.6;
    }

    /* HERO */
    .hero {
        position: relative;
        padding: 80px 40px;
        background: linear-gradient(135deg, #00393a 0%, #005555 50%, #00393a 100%);
        color: white;
        text-align: center;
        min-height: 280px;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
        background-size: 25px 25px;
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 1400px;
        margin: 0 auto;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: white;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateX(-5px);
    }

    .hero-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .school-logo-large {
        width: 120px;
        height: 120px;
        background: white;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
    }

    .school-logo-large img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .school-logo-large i {
        font-size: 50px;
        color: #00393a;
    }

    .school-title {
        text-align: left;
    }

    .school-title h1 {
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 10px;
        text-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
        line-height: 1.2;
    }

    .school-title .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.25);
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 15px;
        font-weight: 600;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .school-title .scod {
        display: inline-block;
        margin-top: 10px;
        background: #eda711;
        color: #00393a;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
    }

    /* CONTENT */
    .content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 50px 40px;
    }

    /* School Info Section */
    .school-info-section {
        background: white;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 40px;
    }

    .school-info-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    .school-details {
        border-right: 1px solid #e2e8f0;
        padding-right: 30px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #00393a;
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 3px solid #eda711;
        display: inline-block;
    }

    .detail-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0;
    }

    .detail-row {
        display: flex;
        flex-direction: column;
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        border-right: 1px solid #e2e8f0;
    }

    .detail-row:nth-child(2n) {
        border-right: none;
    }

    .detail-row:nth-last-child(-n+2) {
        border-bottom: none;
    }

    .detail-label-text {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .detail-value-text {
        font-size: 14px;
        color: #1e293b;
        font-weight: 600;
        line-height: 1.5;
    }

    .detail-value-text a {
        color: #00393a;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .detail-value-text a:hover {
        color: #005555;
        text-decoration: underline;
    }

    /* Kepala Sekolah Section */
    .kepala-sekolah-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
    }

    .ks-title {
        font-size: 14px;
        font-weight: 700;
        color: #00393a;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .ks-photo-container {
        width: 160px;
        height: 180px;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ks-photo-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ks-photo-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #00393a, #005555);
    }

    .ks-photo-placeholder i {
        font-size: 60px;
        color: rgba(255, 255, 255, 0.5);
    }

    .ks-name {
        font-size: 18px;
        font-weight: 800;
        color: #1e293b;
        text-align: center;
        line-height: 1.3;
    }

    .ks-gelar {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        margin-top: 4px;
        text-align: center;
    }

    /* PPDB Button */
    .ppdb-button-row .detail-value-text {
        padding-top: 4px;
    }

    .ppdb-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px 20px;
        background: linear-gradient(135deg, #00393a 0%, #005555 50%, #00393a 100%);
        color: white !important;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 57, 58, 0.3);
    }

    .ppdb-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 57, 58, 0.4);
        color: white !important;
        text-decoration: none;
    }

    .ppdb-btn i {
        font-size: 18px;
    }

    /* Stats Cards */
    .stats-section {
        margin-bottom: 40px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 20px;
        background: white;
        padding: 28px;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        border-color: rgba(0, 75, 76, 0.1);
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon i {
        font-size: 30px;
        color: white;
    }

    .stat-icon.guru {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .stat-icon.siswa {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .stat-icon.jurusan {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .stat-info {
        flex: 1;
    }

    .stat-number {
        font-size: 36px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 6px;
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* FOOTER */
    .footer {
        background: linear-gradient(135deg, #1f2937, #374151);
        color: white;
        padding: 40px;
        margin-top: 80px;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .footer-logo img {
        height: 45px;
    }

    .footer-logo span {
        font-size: 20px;
        font-weight: 700;
        color: #eda711;
    }

    .footer-text {
        font-size: 14px;
        opacity: 0.8;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .school-info-layout {
            grid-template-columns: 1fr;
        }

        .school-details {
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
            padding-right: 0;
            padding-bottom: 30px;
        }
    }

    @media (max-width: 768px) {
        .hero {
            padding: 50px 20px;
        }

        .hero-header {
            flex-direction: column;
            text-align: center;
        }

        .school-title {
            text-align: center;
        }

        .school-title h1 {
            font-size: 28px;
        }

        .content {
            padding: 30px 20px;
        }

        .detail-list {
            grid-template-columns: 1fr;
        }

        .detail-row {
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-row:nth-last-child(-n+1) {
            border-bottom: none;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-number {
            font-size: 28px;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
        }

        .stat-icon i {
            font-size: 26px;
        }

        .footer-content {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <div style="text-align: left;">
            <a href="{{ route('landing.sekolah') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Sekolah
            </a>
        </div>
        <div class="hero-header">
            <div class="school-logo-large">
                @if($madrasah->logo)
                    <img src="{{ asset('storage/' . $madrasah->logo) }}" alt="{{ $madrasah->name }}">
                @else
                    <i class="bi bi-building"></i>
                @endif
            </div>
            <div class="school-title">
                <h1>{{ $madrasah->name }}</h1>
                <span class="badge">
                    <i class="bi bi-geo-alt-fill"></i> {{ $madrasah->kabupaten }}
                </span>
                @if($madrasah->scod)
                    <span class="scod">SCOD: {{ $madrasah->scod }}</span>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- CONTENT -->
<section class="content">
    <!-- School Info Section -->
    <div class="school-info-section">
        <div class="school-info-layout">
            <!-- Left Side: Informasi Sekolah (2/3) -->
            <div class="school-details">
                <h3 class="section-title">Informasi Sekolah/Madrasah</h3>
                <div class="detail-list">
                    <div class="detail-row">
                        <div class="detail-label-text">Akreditasi</div>
                        <div class="detail-value-text">{{ $ppdbSetting->akreditasi ?? ($madrasah->akreditasi ?? '-') }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label-text">Nomor Telepon</div>
                        <div class="detail-value-text">{{ $ppdbSetting->telepon ?? ($madrasah->telepon ?? '-') }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label-text">Email</div>
                        <div class="detail-value-text">
                            @if($ppdbSetting && $ppdbSetting->email)
                                <a href="mailto:{{ $ppdbSetting->email }}">{{ $ppdbSetting->email }}</a>
                            @elseif($madrasah->email)
                                <a href="mailto:{{ $madrasah->email }}">{{ $madrasah->email }}</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label-text">Website</div>
                        <div class="detail-value-text">
                            @if($ppdbSetting && $ppdbSetting->website)
                                <a href="{{ $ppdbSetting->website }}" target="_blank">{{ $ppdbSetting->website }}</a>
                            @elseif($madrasah->website)
                                <a href="{{ $madrasah->website }}" target="_blank">{{ $madrasah->website }}</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    @if($madrasah->alamat)
                    <div class="detail-row" style="grid-column: span 2;">
                        <div class="detail-label-text">Alamat Lengkap</div>
                        <div class="detail-value-text">{{ $madrasah->alamat }}</div>
                    </div>
                    @endif

                    @if($ppdbSlug)
                    <div class="detail-row ppdb-button-row" style="grid-column: span 2;">
                        <div class="detail-label-text">SPMB</div>
                        <div class="detail-value-text">
                            <a href="{{ route('ppdb.sekolah', $ppdbSlug) }}" class="ppdb-btn">
                                <i class="bi bi-pencil-square"></i> Halaman SPMB
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Side: Kepala Sekolah (1/3) -->
            <div class="kepala-sekolah-section">
                <div class="ks-title">Kepala Sekolah</div>
                <div class="ks-photo-container">
                    @if($kepalaSekolah && $kepalaSekolah->avatar)
                        <img src="{{ asset('storage/' . $kepalaSekolah->avatar) }}" alt="Foto Kepala Sekolah" class="ks-photo-img">
                    @else
                        <div class="ks-photo-placeholder">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    @endif
                </div>
                @if($kepalaSekolah)
                    <div class="ks-name">{{ $kepalaSekolah->name }}</div>
                    @if($kepalaSekolah->gelar_depan || $kepalaSekolah->gelar_belakang)
                        <div class="ks-gelar">{{ $kepalaSekolah->gelar_depan ?? '' }} {{ $kepalaSekolah->name }} {{ $kepalaSekolah->gelar_belakang ?? '' }}</div>
                    @endif
                @else
                    <div class="ks-name">-</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon guru">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-number">{{ $madrasah->jumlah_guru ?? ($ppdbSetting->jumlah_guru ?? '-') }}</div>
                    <div class="stat-label">Jumlah Guru</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon siswa">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-number">{{ $madrasah->jumlah_siswa ?? ($ppdbSetting->jumlah_siswa ?? '-') }}</div>
                    <div class="stat-label">Jumlah Siswa</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon jurusan">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-number">{{ $madrasah->jumlah_jurusan ?? ($ppdbSetting->jumlah_jurusan ?? '-') }}</div>
                    <div class="stat-label">Jumlah Jurusan</div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('landing.footer')

@endsection
