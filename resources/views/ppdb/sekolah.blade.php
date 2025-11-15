@extends('layouts.master-without-nav')

@section('title', 'Profile ' . $ppdb->nama_sekolah . ' - PPDB NUIST')

@section('css')
<link rel="stylesheet" href="{{ asset('css/ppdb-custom.css') }}">
<style>
    .hero-section {
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        color: white;
        padding: 80px 0;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('{{ asset("images/bg_ppdb2.png") }}') center/cover;
        opacity: 0.1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .school-logo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid rgba(239, 170, 12, 0.3);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .section-padding {
        padding: 80px 0;
    }

    .section-title {
        color: #004b4c;
        font-weight: 700;
        margin-bottom: 50px;
        text-align: center;
    }

    .card-custom {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-5px);
    }

    .facility-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .facility-card:hover {
        transform: scale(1.05);
    }

    .facility-img {
        height: 200px;
        object-fit: cover;
    }

    .major-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        height: 100%;
    }

    .major-card:hover {
        transform: translateY(-5px);
    }

    .achievement-badge {
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
    }

    .testimonial-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        position: relative;
    }

    .testimonial-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #efaa0c;
    }

    .stats-counter {
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        color: white;
        border-radius: 15px;
        padding: 40px 20px;
        text-align: center;
        margin-bottom: 30px;
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #efaa0c;
        margin-bottom: 10px;
    }

    .gallery-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    .gallery-img:hover {
        transform: scale(1.05);
    }

    .btn-ppdb {
        background: linear-gradient(135deg, #efaa0c 0%, #ff8f00 100%);
        border: none;
        color: white;
        padding: 15px 40px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(239, 170, 12, 0.3);
    }

    .btn-ppdb:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 170, 12, 0.4);
        color: white;
    }

    .advantage-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin: 0 auto 20px;
    }

    .map-container {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .contact-info {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 30px;
    }

    .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 15px;
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #efaa0c;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -22px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #efaa0c;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #004b4c;
    }

    .faq-item {
        background: white;
        border-radius: 10px;
        margin-bottom: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .faq-question {
        padding: 20px;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        font-weight: 600;
        color: #004b4c;
    }

    .faq-answer {
        padding: 0 20px 20px;
        color: #666;
        display: none;
    }
</style>
@endsection

@section('content')
<!-- Custom Navbar for School Page -->
<nav class="navbar navbar-expand-lg navbar-light custom-navbar">
    <div class="container d-flex align-items-center justify-content-between">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('ppdb.index') }}">
            <img src="{{ asset('images/logo1.png') }}" alt="Logo" style="height: 40px;">
        </a>

        <!-- Toggle (Mobile) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavSchool">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Tengah -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarNavSchool">
            <ul class="navbar-nav gap-4">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('ppdb.sekolah') ? 'active' : '' }}"
                        href="{{ route('ppdb.sekolah', request()->route('slug')) }}">
                        Profil Sekolah
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#jurusan">
                        Jurusan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#fasilitas">
                        Fasilitas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#kontak">
                        Kontak
                    </a>
                </li>

            </ul>
        </div>

        <!-- Tombol Kanan -->
        <div class="d-none d-lg-block">
            @if(isset($ppdb->id) && $ppdb->isPembukaan())
                <a href="{{ route('ppdb.daftar', $ppdb->slug) }}" class="btn btn-orange px-4">
                    Daftar Sekarang
                </a>
            @else
                <a href="{{ route('ppdb.index') }}" class="btn btn-outline-primary px-4">
                    Kembali ke Beranda
                </a>
            @endif
        </div>
    </div>
</nav>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobileMenuOverlaySchool" style="display: none;">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm d-lg-none">
        <div class="container-fluid px-4">
            <!-- Close Button -->
            <button class="btn btn-link text-dark ms-auto" onclick="closeMobileMenuSchool()">
                <i class="bi bi-x-lg fs-2"></i>
            </button>
        </div>

        <div class="w-100">
            <ul class="navbar-nav flex-column text-center py-4">
                <li class="nav-item mb-3">
                    <a class="nav-link {{ request()->routeIs('ppdb.sekolah') ? 'active' : '' }}" href="{{ route('ppdb.sekolah', request()->route('slug')) }}" onclick="closeMobileMenuSchool()">Profil Sekolah</a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link" href="#jurusan" onclick="closeMobileMenuSchool()">Jurusan</a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link" href="#fasilitas" onclick="closeMobileMenuSchool()">Fasilitas</a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link" href="#kontak" onclick="closeMobileMenuSchool()">Kontak</a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<script>
    // Mobile menu functionality for school page
    document.addEventListener('DOMContentLoaded', function() {
        const toggler = document.querySelector('.navbar-toggler[data-bs-target="#navbarNavSchool"]');
        const overlay = document.getElementById('mobileMenuOverlaySchool');

        if (toggler && overlay) {
            toggler.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';

                if (isExpanded) {
                    overlay.style.display = 'block';
                    setTimeout(() => {
                        overlay.style.opacity = '1';
                        overlay.classList.add('show');
                    }, 10);
                } else {
                    overlay.style.opacity = '0';
                    overlay.classList.remove('show');
                    setTimeout(() => {
                        overlay.style.display = 'none';
                    }, 300);
                }
            });
        }
    });

    function closeMobileMenuSchool() {
        const overlay = document.getElementById('mobileMenuOverlaySchool');
        const toggler = document.querySelector('.navbar-toggler[data-bs-target="#navbarNavSchool"]');

        overlay.style.opacity = '0';
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);

        // Reset Bootstrap collapse state
        const bsCollapse = new bootstrap.Collapse(document.getElementById('navbarNavSchool'), {
            hide: true
        });
    }

    // Close mobile menu when clicking outside
    document.getElementById('mobileMenuOverlaySchool').addEventListener('click', function(e) {
        if (e.target === this) {
            closeMobileMenuSchool();
        }
    });
</script>

<style>
    /* --- FIX FINAL NAVBAR TEXT STYLE PADA NAV BLADE --- */
    nav.custom-navbar .navbar-nav .nav-link {
        font-size: 16px !important;
        font-weight: 200 !important;
        color: #667085 !important;
        padding: 12px 20px !important;
        line-height: 1 !important;
        transition: color 0.2s ease;
    }

    /* Hover hijau */
    nav.custom-navbar .navbar-nav .nav-link:hover {
        color: #0f854a !important;
    }

    /* Active hijau */
    nav.custom-navbar .navbar-nav .nav-link.active {
        color: #0f854a !important;
        font-weight: 200 !important;
    }

    /* Override bootstrap kuat */
    .navbar-light .navbar-nav .nav-link {
        font-size: 26px !important;
        color: #667085 !important;
    }
    /* Tombol Green */
    .btn-orange {
        background: #0f854a;
        color: #fff !important;
        padding: 10px 24px;
        font-size: 16px;
        border-radius: 10px;
        font-weight: 400;
        transition: background .2s ease;
    }

    .btn-orange:hover {
        background: #0a5f35;
    }

    .mobile-menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .mobile-menu-overlay .navbar {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        max-width: 300px;
        height: 100%;
        background: white;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .mobile-menu-overlay.show .navbar {
        transform: translateX(0);
    }

    /* Ensure navbar stays visible when scrolling */
    .navbar {
        position: sticky !important;
        top: 0 !important;
        z-index: 1030 !important;
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.95) !important;
        transition: all 0.3s ease;
    }

    .mobile-menu-overlay .nav-link {
        font-size: 1.1rem;
        padding: 1rem 2rem;
        border-bottom: 1px solid #f8f9fa;
    }

    .mobile-menu-overlay .nav-link:hover {
        background-color: #f8f9fa;
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    @if($madrasah->logo)
                        <img src="{{ asset('storage/app/public/' . $madrasah->logo) }}" alt="{{ $madrasah->name }}" class="school-logo me-4">
                    @else
                        <div class="school-logo bg-light d-flex align-items-center justify-content-center me-4">
                            <i class="bx bx-school bx-lg text-muted"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="display-4 fw-bold mb-2">{{ $madrasah->name }}</h1>
                        @if($madrasah->tagline)
                            <p class="h4 text-warning mb-0">{{ $madrasah->tagline }}</p>
                        @endif
                    </div>
                </div>
                @if($madrasah->deskripsi_singkat)
                    <p class="lead mb-4">{{ $madrasah->deskripsi_singkat }}</p>
                @endif
                <div class="d-flex gap-3 flex-wrap">
                    @if(isset($ppdb->id) && $ppdb->isPembukaan())

                        <a href="{{ route('ppdb.daftar', $ppdb->slug) }}" class="btn btn-ppdb">Daftar Sekarang</a>

                    @endif
                    <a href="#jurusan" class="btn btn-outline-light btn-lg px-4">Lihat Jurusan</a>
                </div>
            </div>
            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                @if($ppdb)
                    <div class="card-custom p-4">
                        <h5 class="text-primary mb-3">PPDB {{ $ppdb->tahun }}</h5>
                        <div class="mb-3">

                            <span class="badge fs-6 px-3 py-2 {{ isset($ppdb->id) && $ppdb->isPembukaan() ? 'bg-success' : 'bg-danger' }}">

                                {{ isset($ppdb->id) && $ppdb->isPembukaan() ? 'Pendaftaran Dibuka' : 'Pendaftaran Ditutup' }}

                            </span>

                        </div>
                        @if(isset($ppdb->id) && $ppdb->isPembukaan())
                            <p class="mb-2"><strong>Jadwal:</strong></p>
                            <p class="mb-0">{{ $ppdb->jadwal_buka->format('d M Y') }} - {{ $ppdb->jadwal_tutup->format('d M Y') }}</p>
                            <p class="text-muted small">Sisa waktu: {{ $ppdb->remainingDays() }} hari</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Sejarah Sekolah -->
@if($madrasah->sejarah || $madrasah->tahun_berdiri)
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Sejarah Sekolah</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                @if($madrasah->tahun_berdiri)
                    <div class="timeline">
                        <div class="timeline-item">
                            <h5 class="text-primary">{{ $madrasah->tahun_berdiri }}</h5>
                            <p>Tahun berdiri {{ $madrasah->name }}</p>
                        </div>
                        @if($madrasah->sejarah)
                            <div class="timeline-item">
                                <h5 class="text-primary">Perjalanan</h5>
                                <p>{{ $madrasah->sejarah }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            <div class="col-lg-4">
                <div class="card-custom p-4">
                    <h5 class="text-primary mb-3">Informasi Sekolah</h5>
                    @if($madrasah->akreditasi)
                        <p class="mb-2"><strong>Akreditasi:</strong> {{ $madrasah->akreditasi }}</p>
                    @endif
                    @if($madrasah->nilai_nilai)
                        <p class="mb-0"><strong>Nilai-Nilai:</strong> {{ $madrasah->nilai_nilai }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Visi & Misi -->
@if($madrasah->visi || $madrasah->misi)
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Visi & Misi</h2>
            </div>
        </div>
        <div class="row">
            @if($madrasah->visi)
            <div class="col-lg-6 mb-4">
                <div class="card-custom p-4 h-100">
                    <div class="text-center mb-4">
                        <div class="advantage-icon">
                            <i class="bx bx-target-lock"></i>
                        </div>
                        <h4 class="text-primary">Visi</h4>
                    </div>
                    <p class="text-center lead">{{ $madrasah->visi }}</p>
                </div>
            </div>
            @endif
            @if($madrasah->misi)
            <div class="col-lg-6 mb-4">
                <div class="card-custom p-4 h-100">
                    <div class="text-center mb-4">
                        <div class="advantage-icon">
                            <i class="bx bx-bullseye"></i>
                        </div>
                        <h4 class="text-primary">Misi</h4>
                    </div>
                    <ul class="list-unstyled">
                        @foreach($madrasah->misi ?? [] as $misi)
                            <li class="mb-3">
                                <i class="bx bx-check-circle text-success me-2"></i>
                                {{ $misi }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Keunggulan Sekolah -->
@if($madrasah->keunggulan)
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Keunggulan Sekolah</h2>
            </div>
        </div>
        <div class="row">
            @foreach($madrasah->keunggulan ?? [] as $keunggulan)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-custom p-4 text-center h-100">
                    <div class="advantage-icon mb-3">
                        <i class="bx bx-{{ $keunggulan['icon'] ?? 'star' }}"></i>
                    </div>
                    <h5 class="text-primary mb-3">{{ $keunggulan['title'] ?? '' }}</h5>
                    <p class="text-muted">{{ $keunggulan['description'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Fasilitas Sekolah -->
@if($madrasah->fasilitas)
<section id="fasilitas" class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Fasilitas Sekolah</h2>
            </div>
        </div>
        <div class="row">
            @foreach($madrasah->fasilitas ?? [] as $fasilitas)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="facility-card">
                    @if(isset($fasilitas['image']) && $fasilitas['image'])
                        <img src="{{ asset('storage/app/public/' . $fasilitas['image']) }}" alt="{{ $fasilitas['name'] }}" class="facility-img w-100">
                    @else
                        <div class="facility-img bg-light d-flex align-items-center justify-content-center">
                            <i class="bx bx-building bx-lg text-muted"></i>
                        </div>
                    @endif
                    <div class="p-3">
                        <h6 class="text-primary mb-2">{{ $fasilitas['name'] ?? '' }}</h6>
                        <p class="text-muted small mb-0">{{ $fasilitas['description'] ?? '' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Jurusan/Program Studi -->
@if($madrasah->jurusan)
<section id="jurusan" class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Jurusan/Program Studi</h2>
            </div>
        </div>
        <div class="row">
            @foreach($madrasah->jurusan ?? [] as $jurusan)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="major-card">
                    @if(isset($jurusan['image']) && $jurusan['image'])
                        <img src="{{ asset('storage/app/public/' . $jurusan['image']) }}" alt="{{ $jurusan['name'] }}" class="w-100 rounded mb-3" style="height: 150px; object-fit: cover;">
                    @endif
                    <h5 class="text-primary mb-3">{{ $jurusan['name'] ?? '' }}</h5>
                    <p class="text-muted mb-3">{{ $jurusan['description'] ?? '' }}</p>
                    @if(isset($jurusan['prospects']))
                        <div class="mb-3">
                            <strong>Prospek Karir:</strong>
                            <p class="text-muted small">{{ $jurusan['prospects'] }}</p>
                        </div>
                    @endif
                    @if(isset($jurusan['skills']))
                        <div>
                            <strong>Skill yang Dipelajari:</strong>
                            <ul class="text-muted small">
                                @foreach($jurusan['skills'] ?? [] as $skill)
                                    <li>{{ $skill }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Prestasi Sekolah -->
@if($madrasah->prestasi)
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Prestasi Sekolah</h2>
            </div>
        </div>
        <div class="row">
            @foreach($madrasah->prestasi ?? [] as $prestasi)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="achievement-badge">
                    <div class="mb-2">
                        <i class="bx bx-trophy bx-lg"></i>
                    </div>
                    <h6 class="mb-2">{{ $prestasi['title'] ?? '' }}</h6>
                    <p class="small mb-0">{{ $prestasi['description'] ?? '' }}</p>
                    @if(isset($prestasi['year']))
                        <small class="text-warning">{{ $prestasi['year'] }}</small>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Program Unggulan & Ekstrakurikuler -->
@if($madrasah->program_unggulan || $madrasah->ekstrakurikuler)
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Program Unggulan & Ekstrakurikuler</h2>
            </div>
        </div>
        <div class="row">
            @if($madrasah->program_unggulan)
            <div class="col-lg-6 mb-4">
                <div class="card-custom p-4">
                    <h4 class="text-primary mb-4">Program Unggulan</h4>
                    <div class="row">
                        @foreach($madrasah->program_unggulan ?? [] as $program)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-check-circle text-success me-2"></i>
                                <span>{{ $program['name'] ?? $program }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            @if($madrasah->ekstrakurikuler)
            <div class="col-lg-6 mb-4">
                <div class="card-custom p-4">
                    <h4 class="text-primary mb-4">Ekstrakurikuler</h4>
                    <div class="row">
                        @foreach($madrasah->ekstrakurikuler ?? [] as $ekstra)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-run text-primary me-2"></i>
                                <span>{{ $ekstra['name'] ?? $ekstra }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Testimoni -->
@if($madrasah->testimoni)
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Testimoni Alumni & Orang Tua</h2>
            </div>
        </div>
        <div class="row">
            @foreach($madrasah->testimoni ?? [] as $testimoni)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        @if(isset($testimoni['avatar']) && $testimoni['avatar'])
                            <img src="{{ asset('storage/app/public/' . $testimoni['avatar']) }}" alt="{{ $testimoni['name'] }}" class="testimonial-avatar me-3">
                        @else
                            <div class="testimonial-avatar bg-primary d-flex align-items-center justify-content-center me-3">
                                <span class="text-white fw-bold">{{ substr($testimoni['name'] ?? 'A', 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-0 text-primary">{{ $testimoni['name'] ?? '' }}</h6>
                            <small class="text-muted">{{ $testimoni['position'] ?? '' }} | {{ $testimoni['year'] ?? '' }}</small>
                        </div>
                    </div>
                    <p class="text-muted italic">"{{ $testimoni['message'] ?? '' }}"</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Kepala Sekolah -->
@if($madrasah->kepala_sekolah_nama)
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Kepala Sekolah</h2>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4">
                @if($madrasah->kepala_sekolah_foto)
                    <img src="{{ asset('storage/app/public/' . $madrasah->kepala_sekolah_foto) }}" alt="{{ $madrasah->kepala_sekolah_nama }}" class="rounded-circle" style="width: 200px; height: 200px; object-fit: cover; border: 5px solid #efaa0c;">
                @else
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto" style="width: 200px; height: 200px;">
                        <span class="text-white display-4">{{ substr($madrasah->kepala_sekolah_nama, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="col-lg-8">
                <h4 class="text-primary mb-2">{{ $madrasah->kepala_sekolah_nama }}</h4>
                @if($madrasah->kepala_sekolah_gelar)
                    <p class="text-muted mb-3">{{ $madrasah->kepala_sekolah_gelar }}</p>
                @endif
                @if($madrasah->kepala_sekolah_sambutan)
                    <p class="lead">{{ $madrasah->kepala_sekolah_sambutan }}</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

<!-- Data Statistik -->
@if($madrasah->jumlah_siswa || $madrasah->jumlah_guru || $madrasah->jumlah_jurusan || $madrasah->jumlah_sarana)
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Data Statistik Sekolah</h2>
            </div>
        </div>
        <div class="row">
            @if($madrasah->jumlah_siswa)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-counter">
                    <div class="stats-number">{{ number_format($madrasah->jumlah_siswa) }}</div>
                    <h6>Jumlah Siswa</h6>
                </div>
            </div>
            @endif
            @if($madrasah->jumlah_guru)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-counter">
                    <div class="stats-number">{{ number_format($madrasah->jumlah_guru) }}</div>
                    <h6>Jumlah Guru</h6>
                </div>
            </div>
            @endif
            @if($madrasah->jumlah_jurusan)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-counter">
                    <div class="stats-number">{{ $madrasah->jumlah_jurusan }}</div>
                    <h6>Jumlah Jurusan</h6>
                </div>
            </div>
            @endif
            @if($madrasah->jumlah_sarana)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-counter">
                    <div class="stats-number">{{ $madrasah->jumlah_sarana }}</div>
                    <h6>Jumlah Sarana</h6>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Video Profile & Galeri -->
@if($madrasah->video_profile || $madrasah->galeri_foto)
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Galeri & Media Sekolah</h2>
            </div>
        </div>
        <div class="row">
            @if($madrasah->video_profile)
            <div class="col-lg-6 mb-4">
                <div class="card-custom p-4">
                    <h5 class="text-primary mb-3">Video Profile Sekolah</h5>
                    <div class="video-container">
                        <iframe src="{{ $madrasah->video_profile }}" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            @endif
            @if($madrasah->galeri_foto)
            <div class="col-lg-{{ $madrasah->video_profile ? '6' : '12' }} mb-4">
                <div class="card-custom p-4">
                    <h5 class="text-primary mb-3">Galeri Foto Kegiatan</h5>
                    <div class="row">
                        @foreach($madrasah->galeri_foto ?? [] as $foto)
                        <div class="col-md-6 mb-3">
                            @if(isset($foto['url']) && $foto['url'])
                                <img src="{{ asset('storage/app/public/' . $foto['url']) }}" alt="{{ $foto['caption'] ?? '' }}" class="gallery-img">
                            @endif
                            @if(isset($foto['caption']) && $foto['caption'])
                                <p class="text-center mt-2 small text-muted">{{ $foto['caption'] }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Lokasi & Kontak -->
<section id="kontak" class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Lokasi & Kontak</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 mb-4">
                @if($madrasah->map_link)
                <div class="map-container mb-4">
                    <iframe src="{{ $madrasah->map_link }}" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
                @endif
            </div>
            <div class="col-lg-6 mb-4">
                <div class="contact-info">
                    <h5 class="text-primary mb-4">Informasi Kontak</h5>
                    <div class="mb-3">
                        <i class="bx bx-map-pin text-primary me-2"></i>
                        <strong>Alamat:</strong><br>
                        {{ $madrasah->alamat ?? 'Alamat belum ditentukan' }}
                    </div>
                    @if($madrasah->telepon)
                    <div class="mb-3">
                        <i class="bx bx-phone text-primary me-2"></i>
                        <strong>Telepon:</strong><br>
                        {{ $madrasah->telepon }}
                    </div>
                    @endif
                    @if($madrasah->email)
                    <div class="mb-3">
                        <i class="bx bx-envelope text-primary me-2"></i>
                        <strong>Email:</strong><br>
                        {{ $madrasah->email }}
                    </div>
                    @endif
                    @if($madrasah->website)
                    <div class="mb-3">
                        <i class="bx bx-globe text-primary me-2"></i>
                        <strong>Website:</strong><br>
                        <a href="{{ $madrasah->website }}" target="_blank">{{ $madrasah->website }}</a>
                    </div>
                    @endif
                    @if($madrasah->jam_operasional_buka && $madrasah->jam_operasional_tutup)
                    <div class="mb-3">
                        <i class="bx bx-time text-primary me-2"></i>
                        <strong>Jam Operasional:</strong><br>
                        {{ $madrasah->jam_operasional_buka }} - {{ $madrasah->jam_operasional_tutup }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
@if($madrasah->faq)
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">FAQ PPDB</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @foreach($madrasah->faq ?? [] as $faq)
                <div class="faq-item">
                    <button class="faq-question">
                        <i class="bx bx-chevron-down me-2"></i>
                        {{ $faq['question'] ?? '' }}
                    </button>
                    <div class="faq-answer">
                        {{ $faq['answer'] ?? '' }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- Alur Pendaftaran -->
@if($madrasah->alur_pendaftaran)
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Alur Pendaftaran PPDB</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="timeline">
                    @foreach($madrasah->alur_pendaftaran ?? [] as $index => $step)
                    <div class="timeline-item">
                        <h5 class="text-primary">Langkah {{ $index + 1 }}</h5>
                        <p>{{ $step['description'] ?? $step }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="section-padding bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">Siap Bergabung dengan {{ $madrasah->name }}?</h2>
        <p class="lead mb-4">Daftarkan diri Anda sekarang dan jadilah bagian dari komunitas unggul kami</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            @if(isset($ppdb->id) && $ppdb->isPembukaan())
                <a href="{{ route('ppdb.daftar', $ppdb->slug) }}" class="btn btn-ppdb btn-lg">Daftar PPDB Sekarang</a>
            @endif
            @if($madrasah->brosur_pdf)
                <a href="{{ asset('storage/app/public/' . $madrasah->brosur_pdf) }}" target="_blank" class="btn btn-outline-light btn-lg">Download Brosur</a>
            @endif
            <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $madrasah->telepon ?? '6281234567890') }}?text=Halo,%20saya%20ingin%20bertanya%20tentang%20PPDB%20{{ urlencode($madrasah->name) }}" target="_blank" class="btn btn-outline-light btn-lg">
                <i class="bx bxl-whatsapp me-2"></i>Hubungi Admin
            </a>
        </div>
    </div>
</section>

@include('partials.ppdb.footer')
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Toggle
    document.querySelectorAll('.faq-question').forEach(button => {
        button.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            const icon = this.querySelector('i');

            if (answer.style.display === 'block') {
                answer.style.display = 'none';
                icon.className = 'bx bx-chevron-down me-2';
            } else {
                answer.style.display = 'block';
                icon.className = 'bx bx-chevron-up me-2';
            }
        });
    });

    // Smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
</script>
@endsection
