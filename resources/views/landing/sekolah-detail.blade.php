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

    .school-info-grid {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 40px;
        align-items: start;
    }

    .kepala-sekolah-photo {
        text-align: center;
    }

    .ks-photo-img {
        width: 180px;
        height: 220px;
        object-fit: cover;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .ks-photo-placeholder {
        width: 180px;
        height: 220px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }

    .ks-photo-placeholder i {
        font-size: 60px;
        color: #94a3b8;
    }

    .ks-photo-placeholder span {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
    }

    .school-details {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .school-name-main {
        font-size: 28px;
        font-weight: 800;
        color: #00393a;
        line-height: 1.3;
    }

    .detail-items {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
    }

    .detail-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .detail-item:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
    }

    .detail-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #00393a, #005555);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .detail-icon i {
        font-size: 20px;
        color: white;
    }

    .detail-content {
        flex: 1;
    }

    .detail-label {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .detail-value {
        font-size: 15px;
        color: #1e293b;
        font-weight: 600;
        line-height: 1.4;
    }

    .detail-value a {
        color: #00393a;
        text-decoration: none;
    }

    .detail-value a:hover {
        text-decoration: underline;
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

    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #004b4c;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 3px solid #eda711;
        display: inline-block;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 50px;
    }

    .info-card {
        display: flex;
        align-items: flex-start;
        gap: 18px;
        padding: 24px;
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        border-color: rgba(0, 75, 76, 0.1);
    }

    .info-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #004b4c, #006666);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-icon i {
        font-size: 24px;
        color: white;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 14px;
        color: #1e293b;
        font-weight: 700;
        line-height: 1.4;
        text-align: left;
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

        .school-info-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .kepala-sekolah-photo {
            order: -1;
        }

        .ks-photo-img {
            width: 150px;
            height: 180px;
        }

        .ks-photo-placeholder {
            width: 150px;
            height: 180px;
        }

        .school-name-main {
            font-size: 24px;
            text-align: center;
        }

        .detail-items {
            grid-template-columns: 1fr;
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

        .info-card {
            padding: 18px;
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
    {{-- <h2 class="section-title">Informasi Sekolah/Madrasah</h2> --}}

    <!-- School Info with Photo -->
    <div class="school-info-section">
        <div class="school-info-grid">
            <!-- Foto Kepala Sekolah -->
            <div class="kepala-sekolah-photo">
                @if($kepalaSekolah && $kepalaSekolah->avatar)
                    <img src="{{ asset('storage/' . $kepalaSekolah->avatar) }}" alt="Foto Kepala Sekolah" class="ks-photo-img">
                @else
                    <div class="ks-photo-placeholder">
                        <i class="bi bi-person-fill"></i>
                        <span>Foto Kepala Sekolah</span>
                    </div>
                @endif
            </div>

            <!-- Detail Sekolah -->
            <div class="school-details">
                <h3 class="school-name-main">{{ $madrasah->name }}</h3>

                <div class="detail-items">
                    @if($madrasah->alamat)
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Alamat</div>
                            <div class="detail-value">{{ $madrasah->alamat }}</div>
                        </div>
                    </div>
                    @endif

                    @if($madrasah->akreditasi)
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Akreditasi</div>
                            <div class="detail-value">{{ $madrasah->akreditasi }}</div>
                        </div>
                    </div>
                    @endif

                    @if($madrasah->telepon)
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Telepon</div>
                            <div class="detail-value">{{ $madrasah->telepon }}</div>
                        </div>
                    </div>
                    @endif

                    @if($madrasah->email)
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">
                                <a href="mailto:{{ $madrasah->email }}">{{ $madrasah->email }}</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($madrasah->website)
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-globe"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Website</div>
                            <div class="detail-value">
                                <a href="{{ $madrasah->website }}" target="_blank">{{ $madrasah->website }}</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($kepalaSekolah)
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Kepala Sekolah</div>
                            <div class="detail-value">{{ $kepalaSekolah->name }}</div>
                        </div>
                    </div>
                    @elseif($madrasah->kepala_sekolah_nama)
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Kepala Sekolah</div>
                            <div class="detail-value">{{ $madrasah->kepala_sekolah_gelar ?? '' }} {{ $madrasah->kepala_sekolah_nama }}</div>
                        </div>
                    </div>
                    @endif
                </div>
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

