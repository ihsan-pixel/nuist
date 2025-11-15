@extends('layouts.master-without-nav')

@section('title', 'Profile ' . $ppdb->nama_sekolah . ' - PPDB NUIST')

@section('css')
<link rel="stylesheet" href="{{ asset('css/ppdb-custom.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Enhanced School Page Styles */
    .hero-section {
        background: url('{{ asset("images/bg_ppdb2.png") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 80vh;
        position: relative;
        display: flex;
        align-items: center;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0, 75, 76, 0.9) 0%, rgba(0, 105, 92, 0.9) 100%);
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        color: white;
    }

    .school-logo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid rgba(239, 170, 12, 0.3);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
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

    .section-padding {
        padding: 80px 0;
    }

    .section-title {
        color: #004b4c;
        font-weight: 700;
        margin-bottom: 50px;
        text-align: center;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(135deg, #004b4c 0%, #efaa0c 100%);
        border-radius: 2px;
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

    /* About Section */
    .about-section {
        background: linear-gradient(135deg, #004b4c 0%, #004b4c 100%);
        color: white;
        padding: 80px 0;
    }

    .about-section h2 {
        color: white;
    }

    .about-section p {
        color: rgba(255, 255, 255, 0.9);
    }

    .about-section ul li {
        color: rgba(255, 255, 255, 0.9);
    }

    .about-section .bi-check-circle-fill {
        color: #efaa0c;
    }

    .feature-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background-color: #c2facf;
        border-radius: 50%;
        margin-right: 15px;
        flex-shrink: 0;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 75, 76, 0.2);
    }

    .feature-icon:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(0, 75, 76, 0.4);
        background-color: #a7f3d0;
    }

    .feature-icon .fas {
        color: #004b4c;
        font-size: 1.2rem;
        transition: color 0.3s ease;
    }

    .feature-icon:hover .fas {
        color: #004b4c;
    }

    .feature-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .feature-content {
        flex: 1;
    }

    /* Contact Section */
    .contact-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 80px 0;
    }

    .contact-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        padding: 30px;
        text-align: center;
    }

    .contact-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .contact-card .fas {
        font-size: 3rem;
        color: #004b4c;
        margin-bottom: 20px;
    }

    .contact-card h5 {
        color: #004b4c;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .contact-card p {
        color: #666;
        font-size: 1rem;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out;
    }

    .animate-slide-in-left {
        animation: slideInLeft 0.8s ease-out;
    }

    .animate-slide-in-right {
        animation: slideInRight 0.8s ease-out;
    }

    .animate-bounce-in {
        animation: bounceIn 1s ease-out;
    }

    .animate-pulse {
        animation: pulse 2s infinite;
    }

    /* Enhanced hover effects */
    .school-card:hover .card-img-top {
        transform: scale(1.1) rotate(2deg);
    }

    .contact-card:hover .fas {
        animation: bounceIn 0.6s ease-out;
    }

    .feature-icon:hover {
        animation: pulse 0.6s ease-out;
    }

    .btn:hover {
        animation: bounceIn 0.4s ease-out;
    }

    /* Scroll-triggered animations */
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.8s ease-out;
    }

    .animate-on-scroll.animate {
        opacity: 1;
        transform: translateY(0);
    }

    .animate-slide-left-on-scroll {
        opacity: 0;
        transform: translateX(-50px);
        transition: all 0.8s ease-out;
    }

    .animate-slide-left-on-scroll.animate {
        opacity: 1;
        transform: translateX(0);
    }

    .animate-slide-right-on-scroll {
        opacity: 0;
        transform: translateX(50px);
        transition: all 0.8s ease-out;
    }

    .animate-slide-right-on-scroll.animate {
        opacity: 1;
        transform: translateX(0);
    }

    .animate-bounce-on-scroll {
        opacity: 0;
        transform: scale(0.8);
        transition: all 1s ease-out;
    }

    .animate-bounce-on-scroll.animate {
        opacity: 1;
        transform: scale(1);
    }

    /* Navbar Styles */
    nav.custom-navbar .navbar-nav .nav-link {
        font-size: 16px !important;
        font-weight: 500 !important;
        color: #667085 !important;
        padding: 12px 20px !important;
        line-height: 1 !important;
        transition: color 0.2s ease;
    }

    nav.custom-navbar .navbar-nav .nav-link:hover {
        color: #0f854a !important;
    }

    nav.custom-navbar .navbar-nav .nav-link.active {
        color: #0f854a !important;
        font-weight: 600 !important;
    }

    .navbar-light .navbar-nav .nav-link {
        font-size: 16px !important;
        color: #667085 !important;
    }

    .btn-orange {
        background: #0f854a;
        color: #fff !important;
        padding: 10px 24px;
        font-size: 16px;
        border-radius: 10px;
        font-weight: 500;
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
                        <i class="fas fa-home me-1"></i>Profil Sekolah
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#jurusan">
                        <i class="fas fa-graduation-cap me-1"></i>Jurusan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#fasilitas">
                        <i class="fas fa-building me-1"></i>Fasilitas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#about">
                        <i class="fas fa-info-circle me-1"></i>About
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#kontak">
                        <i class="fas fa-phone me-1"></i>Kontak
                    </a>
                </li>

            </ul>
        </div>

        <!-- Tombol Kanan -->
        <div class="d-none d-lg-block">
            @if(isset($ppdb->id) && $ppdb->isPembukaan())
                <a href="{{ route('ppdb.daftar', $ppdb->slug) }}" class="btn btn-orange px-4">
                    <i class="fas fa-edit me-1"></i>Daftar Sekarang
                </a>
            @else
                <a href="{{ route('ppdb.index') }}" class="btn btn-outline-primary px-4">
                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda
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
                    <a class="nav-link {{ request()->routeIs('ppdb.sekolah') ? 'active' : '' }}" href="{{ route('ppdb.sekolah', request()->route('slug')) }}" onclick="closeMobileMenuSchool()">
                        <i class="fas fa-home me-1"></i>Profil Sekolah
                    </a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link" href="#jurusan" onclick="closeMobileMenuSchool()">
                        <i class="fas fa-graduation-cap me-1"></i>Jurusan
                    </a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link" href="#fasilitas" onclick="closeMobileMenuSchool()">
                        <i class="fas fa-building me-1"></i>Fasilitas
                    </a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link" href="#about" onclick="closeMobileMenuSchool()">
                        <i class="fas fa-info-circle me-1"></i>About
                    </a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link" href="#kontak" onclick="closeMobileMenuSchool()">
                        <i class="fas fa-phone me-1"></i>Kontak
                    </a>
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

<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-content">
        <div class="row align-items-center">

            <!-- Text Kiri -->
            <div class="col-lg-6 animate-slide-in-left">
                <div class="d-flex align-items-center mb-4">
                    @if($madrasah->logo)
                        <img src="{{ asset('storage/app/public/' . $madrasah->logo) }}" alt="{{ $madrasah->name }}" class="school-logo me-4">
                    @else
                        <div class="school-logo bg-light d-flex align-items-center justify-content-center me-4">
                            <i class="fas fa-school fa-2x text-muted"></i>
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
                        <a href="{{ route('ppdb.daftar', $ppdb->slug) }}" class="btn btn-ppdb">
                            <i class="fas fa-edit me-2"></i>Daftar Sekarang
                        </a>
                    @endif
                    <a href="#jurusan" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-graduation-cap me-2"></i>Lihat Jurusan
                    </a>
                </div>
            </div>

            <!-- Image Kanan -->
            <div class="col-lg-6 text-center animate-slide-in-right">
                @if($ppdb)
                    <div class="card-custom p-4 animate-bounce-in">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-calendar-alt me-2"></i>PPDB {{ $ppdb->tahun }}
                        </h5>
                        <div class="mb-3">
                            <span class="badge fs-6 px-3 py-2 {{ isset($ppdb->id) && $ppdb->isPembukaan() ? 'bg-success' : 'bg-danger' }}">
                                <i class="fas fa-{{ isset($ppdb->id) && $ppdb->isPembukaan() ? 'check-circle' : 'times-circle' }} me-1"></i>
                                {{ isset($ppdb->id) && $ppdb->isPembukaan() ? 'Pendaftaran Dibuka' : 'Pendaftaran Ditutup' }}
                            </span>
                        </div>
                        @if(isset($ppdb->id) && $ppdb->isPembukaan())
                            <p class="mb-2">
                                <strong><i class="fas fa-clock me-2"></i>Jadwal:</strong>
                            </p>
                            <p class="mb-0">{{ $ppdb->jadwal_buka->format('d M Y') }} - {{ $ppdb->jadwal_tutup->format('d M Y') }}</p>
                            <p class="text-muted small">
                                <i class="fas fa-hourglass-half me-1"></i>Sisa waktu: {{ $ppdb->remainingDays() }} hari
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Jurusan/Program Studi -->
@if($madrasah->jurusan)
<section id="jurusan" class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">
                    <i class="fas fa-graduation-cap text-primary me-3"></i>Jurusan/Program Studi
                </h2>
            </div>
        </div>
        <div class="row">
            @foreach($madrasah->jurusan ?? [] as $jurusan)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="major-card">
                    @if(isset($jurusan['image']) && $jurusan['image'])
                        <img src="{{ asset('storage/app/public/' . $jurusan['image']) }}" alt="{{ $jurusan['name'] }}" class="w-100 rounded mb-3" style="height: 150px; object-fit: cover;">
                    @endif
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-book me-2"></i>{{ $jurusan['name'] ?? '' }}
                    </h5>
                    <p class="text-muted mb-3">{{ $jurusan['description'] ?? '' }}</p>
                    @if(isset($jurusan['prospects']))
                        <div class="mb-3">
                            <strong><i class="fas fa-briefcase me-2"></i>Prospek Karir:</strong>
                            <p class="text-muted small">{{ $jurusan['prospects'] }}</p>
                        </div>
                    @endif
                    @if(isset($jurusan['skills']))
                        <div>
                            <strong><i class="fas fa-tools me-2"></i>Skill yang Dipelajari:</strong>
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

<!-- Fasilitas Sekolah -->
@if($madrasah->fasilitas)
<section id="fasilitas" class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">
                    <i class="fas fa-building text-primary me-3"></i>Fasilitas Sekolah
                </h2>
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
                            <i class="fas fa-building bx-lg text-muted"></i>
                        </div>
                    @endif
                    <div class="p-3">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-cog me-2"></i>{{ $fasilitas['name'] ?? '' }}
                        </h6>
                        <p class="text-muted small mb-0">{{ $fasilitas['description'] ?? '' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- About Section -->
<section id="about" class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">
                    <i class="fas fa-info-circle text-warning me-3"></i>Tentang <span style="color: #efaa0c;">{{ $madrasah->name }}</span>
                </h2>
                <p class="lead">Mengenal lebih dalam tentang sekolah kami</p>
            </div>
        </div>

        <!-- Visi & Misi -->
        @if($madrasah->visi || $madrasah->misi)
        <div class="row mb-5">
            @if($madrasah->visi)
            <div class="col-lg-6 mb-4">
                <div class="card-custom p-4 h-100 text-center">
                    <div class="advantage-icon mb-4">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h4 class="text-white mb-4">Visi</h4>
                    <p class="lead text-white">{{ $madrasah->visi }}</p>
                </div>
            </div>
            @endif
            @if($madrasah->misi)
            <div class="col-lg-6 mb-4">
                <div class="card-custom p-4 h-100 text-center">
                    <div class="advantage-icon mb-4">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h4 class="text-white mb-4">Misi</h4>
                    <ul class="list-unstyled text-start">
                        @foreach($madrasah->misi ?? [] as $misi)
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-warning me-2"></i>
                                <span class="text-white">{{ $misi }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Keunggulan Sekolah -->
        @if($madrasah->keunggulan)
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h3 class="text-white">
                    <i class="fas fa-star text-warning me-2"></i>Keunggulan Sekolah
                </h3>
            </div>
            @foreach($madrasah->keunggulan ?? [] as $keunggulan)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-custom p-4 text-center h-100">
                    <div class="advantage-icon mb-3">
                        <i class="fas fa-{{ $keunggulan['icon'] ?? 'star' }}"></i>
                    </div>
                    <h5 class="text-white mb-3">{{ $keunggulan['title'] ?? '' }}</h5>
                    <p class="text-white-50">{{ $keunggulan['description'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Prestasi Sekolah -->
        @if($madrasah->prestasi)
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h3 class="text-white">
                    <i class="fas fa-trophy text-warning me-2"></i>Prestasi Sekolah
                </h3>
            </div>
            @foreach($madrasah->prestasi ?? [] as $prestasi)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="achievement-badge">
                    <div class="mb-2">
                        <i class="fas fa-medal fa-2x text-warning"></i>
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
        @endif
    </div>
</section>

<!-- Lokasi & Kontak -->
<section id="kontak" class="contact-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">
                    <i class="fas fa-phone text-primary me-3"></i>Hubungi <span style="color: #efaa0c;">Kami</span>
                </h2>
                <p class="lead text-muted">Butuh bantuan? Tim kami siap membantu Anda</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100 animate-fade-in-up">
                    <i class="fas fa-phone"></i>
                    <h5>Telepon</h5>
                    <p>{{ $madrasah->telepon ?? 'Tidak tersedia' }}</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100 animate-fade-in-up">
                    <i class="fas fa-envelope"></i>
                    <h5>Email</h5>
                    <p>{{ $madrasah->email ?? 'Tidak tersedia' }}</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100 animate-fade-in-up">
                    <i class="fas fa-map-marker-alt"></i>
                    <h5>Alamat</h5>
                    <p>{{ $madrasah->alamat ?? 'Alamat belum ditentukan' }}</p>
                </div>
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
                                <i class="fas fa-check-circle text-success me-2"></i>
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
                                <i class="fas fa-run text-primary me-2"></i>
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
                        <i class="fas fa-chevron-down me-2"></i>
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
                <i class="fas fa-whatsapp me-2"></i>Hubungi Admin
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
                icon.className = 'fas fa-chevron-down me-2';
            } else {
                answer.style.display = 'block';
                icon.className = 'fas fa-chevron-up me-2';
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

    // Scroll-triggered animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.animate-on-scroll, .animate-slide-left-on-scroll, .animate-slide-right-on-scroll, .animate-bounce-on-scroll').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endsection
