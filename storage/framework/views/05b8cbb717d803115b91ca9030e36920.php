<?php $__env->startSection('title', 'Profile ' . $ppdb->nama_sekolah . ' - PPDB NUIST'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/ppdb-custom.css')); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Enhanced School Page Styles */
    .hero-section {
        background: url('<?php echo e(asset("images/bg_ppdb2.png")); ?>');
        background-size: cover;
        background-position: center;
        background-attachment: absolute;
        min-height: 93vh;
        position: relative;
        display: flex;
        align-items: center;
        padding: 80px 0;
    }

    /* .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0, 75, 76, 0.9) 0%, rgba(0, 105, 92, 0.9) 100%);
        z-index: 1;
    } */

    .hero-content {
        position: relative;
        z-index: 2;
        color: #0a5f35;
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Custom Navbar for School Page -->
<nav class="navbar navbar-expand-lg navbar-light custom-navbar">
    <div class="container d-flex align-items-center justify-content-between">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="<?php echo e(route('ppdb.index')); ?>">
            <img src="<?php echo e(asset('images/logo1.png')); ?>" alt="Logo" style="height: 40px;">
        </a>

        <!-- Toggle (Mobile) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavSchool">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Tengah -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarNavSchool">
            <ul class="navbar-nav gap-4">

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('ppdb.sekolah') ? 'active' : ''); ?>"
                        href="<?php echo e(route('ppdb.sekolah', request()->route('slug'))); ?>">
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
                    <a class="nav-link" href="#about">
                        About
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
            <?php if(isset($ppdb->id)): ?>
                <a href="<?php echo e(route('ppdb.daftar', $ppdb->slug)); ?>" class="btn btn-orange px-4">
                    <i class="fas fa-edit me-1"></i>Daftar Sekarang
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('ppdb.index')); ?>" class="btn btn-outline-primary px-4">
                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda
                </a>
            <?php endif; ?>
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
                    <a class="nav-link <?php echo e(request()->routeIs('ppdb.sekolah') ? 'active' : ''); ?>" href="<?php echo e(route('ppdb.sekolah', request()->route('slug'))); ?>" onclick="closeMobileMenuSchool()">
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
                    <?php if($madrasah->logo): ?>
                        <img src="<?php echo e(asset('storage/app/public/' . $madrasah->logo)); ?>" alt="<?php echo e($madrasah->name); ?>" class="school-logo me-4">
                    <?php else: ?>
                        <div class="school-logo bg-light d-flex align-items-center justify-content-center me-4">
                            <i class="fas fa-school fa-2x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h1 class="display-4 fw-bold mb-2"><?php echo e($madrasah->name); ?></h1>
                        <?php if($madrasah->tagline): ?>
                            <p class="h4 text-warning mb-0"><?php echo e($madrasah->tagline); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($madrasah->deskripsi_singkat): ?>
                    <p class="lead mb-4"><?php echo e($madrasah->deskripsi_singkat); ?></p>
                <?php endif; ?>
                <div class="d-flex gap-3 flex-wrap">
                    <?php $currentSlug = request()->route('slug'); ?>
                    <?php if($currentSlug): ?>
                        <a href="<?php echo e(route('ppdb.daftar', $currentSlug)); ?>" class="btn btn-ppdb">
                            <i class="fas fa-edit me-2"></i>Daftar Sekarang
                        </a>
                        <a href="<?php echo e(route('ppdb.cek-status', $currentSlug)); ?>" class="btn btn-orange btn-lg px-4">
                            <i class="fas fa-edit me-2"></i>Lihat Status Pendaftaran
                        </a>
                        
                        
                    <?php else: ?>
                        <a href="<?php echo e(route('ppdb.index')); ?>" class="btn btn-ppdb">
                            <i class="fas fa-edit me-2"></i>Daftar Sekarang
                        </a>
                        <a href="#jurusan" class="btn btn-orange btn-lg px-4">
                            <i class="fas fa-graduation-cap me-2"></i>Lihat Jurusan
                        </a>
                        <a href="<?php echo e(route('ppdb.index')); ?>" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-user-plus me-2"></i>Daftar
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Image Kanan -->
            <div class="col-lg-6 text-center animate-slide-in-right">
                <?php if($ppdb): ?>
                    <div class="card-custom p-4 animate-bounce-in">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-calendar-alt me-2"></i>PPDB <?php echo e($ppdb->tahun); ?>

                        </h5>
                        <div class="mb-3">
                            <span class="badge fs-6 px-3 py-2 bg-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Pendaftaran Dibuka
                            </span>
                        </div>
                        <p class="mb-2">
                            <strong><i class="fas fa-clock me-2"></i>Jadwal:</strong>
                        </p>
                        <p class="mb-0"><?php echo e(isset($ppdb->jadwal_buka) && $ppdb->jadwal_buka ? $ppdb->jadwal_buka->format('d M Y') : 'Belum ditentukan'); ?> - <?php echo e(isset($ppdb->jadwal_tutup) && $ppdb->jadwal_tutup ? $ppdb->jadwal_tutup->format('d M Y') : 'Belum ditentukan'); ?></p>
                        <p class="text-muted small">
                            <i class="fas fa-hourglass-half me-1"></i>Sisa waktu: <?php echo e(method_exists($ppdb, 'remainingDays') ? $ppdb->remainingDays() : 'N/A'); ?> hari
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Jurusan/Program Studi -->
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
            <?php
                // Normalize jurusan data: prefer column `jurusn` (could be JSON or comma-separated),
                // fall back to existing $madrasah->jurusan structure.
                $jurusanData = [];

                if(!empty($madrasah->jurusn)) {
                    $decoded = json_decode($madrasah->jurusn, true);
                    if(is_array($decoded) && count($decoded) > 0) {
                        $jurusanData = $decoded;
                    } else {
                        // Assume comma-separated string of names
                        $pieces = array_filter(array_map('trim', explode(',', $madrasah->jurusn)));
                        foreach($pieces as $p) {
                            $jurusanData[] = ['name' => $p];
                        }
                    }
                } elseif(!empty($madrasah->jurusan) && count($madrasah->jurusan) > 0) {
                    foreach($madrasah->jurusan as $j) {
                        if(is_array($j)) {
                            $jurusanData[] = $j;
                        } else {
                            $jurusanData[] = ['name' => $j];
                        }
                    }
                }
            ?>

            <?php if(count($jurusanData) > 0): ?>
                <?php $__currentLoopData = $jurusanData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jurusan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="major-card">
                        <?php if(isset($jurusan['image']) && $jurusan['image']): ?>
                            <img src="<?php echo e(asset('storage/app/public/' . $jurusan['image'])); ?>" alt="<?php echo e($jurusan['name'] ?? $jurusan['nama'] ?? ''); ?>" class="w-100 rounded mb-3" style="height: 150px; object-fit: cover;">
                        <?php endif; ?>
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-book me-2"></i><?php echo e($jurusan['name'] ?? $jurusan['nama'] ?? $jurusan[0] ?? ''); ?>

                        </h5>
                        <p class="text-muted mb-3"><?php echo e($jurusan['description'] ?? $jurusan['deskripsi'] ?? ''); ?></p>
                        <?php if(isset($jurusan['prospects'])): ?>
                            <div class="mb-3">
                                <strong><i class="fas fa-briefcase me-2"></i>Prospek Karir:</strong>
                                <p class="text-muted small"><?php echo e($jurusan['prospects']); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($jurusan['skills'])): ?>
                            <div>
                                <strong><i class="fas fa-tools me-2"></i>Skill yang Dipelajari:</strong>
                                <ul class="text-muted small">
                                    <?php $__currentLoopData = $jurusan['skills'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($skill); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="card-custom p-5">
                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Program Studi Akan Segera Ditambahkan</h5>
                        <p class="text-muted">Informasi jurusan/program studi sedang dalam proses penyusunan.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Fasilitas Sekolah -->
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
            <?php if($madrasah->fasilitas && count($madrasah->fasilitas) > 0): ?>
                <?php $__currentLoopData = $madrasah->fasilitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fasilitas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="facility-card">
                        <?php if(isset($fasilitas['image']) && $fasilitas['image']): ?>
                            <img src="<?php echo e(asset('storage/app/public/' . $fasilitas['image'])); ?>" alt="<?php echo e($fasilitas['name']); ?>" class="facility-img w-100">
                        <?php else: ?>
                            <div class="facility-img bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-building bx-lg text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="p-3">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-cog me-2"></i><?php echo e($fasilitas['name'] ?? ''); ?>

                            </h6>
                            <p class="text-muted small mb-0"><?php echo e($fasilitas['description'] ?? ''); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="card-custom p-5">
                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Fasilitas Akan Segera Ditambahkan</h5>
                        <p class="text-muted">Informasi fasilitas sekolah sedang dalam proses penyusunan.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">
                    <i class="fas fa-info-circle text-warning me-3"></i>Tentang <span style="color: #efaa0c;"><?php echo e($madrasah->name); ?></span>
                </h2>
                <p class="lead">Mengenal lebih dalam tentang sekolah kami</p>
            </div>
        </div>

        <!-- Visi & Misi -->
        <?php if($madrasah->visi || $madrasah->misi): ?>
        <div class="row mb-5">
            <?php if($madrasah->visi): ?>
            <div class="col-lg-6 mb-4">
                <div class="card-custom p-4 h-100 text-center">
                    <div class="advantage-icon mb-4">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h4 class="text-white mb-4">Visi</h4>
                    <p class="lead text-white"><?php echo e($madrasah->visi); ?></p>
                </div>
            </div>
            <?php endif; ?>
            <?php if($madrasah->misi): ?>
            <div class="col-lg-6 mb-4">
                <div class="card-custom p-4 h-100 text-center">
                    <div class="advantage-icon mb-4">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h4 class="text-white mb-4">Misi</h4>
                    <ul class="list-unstyled text-start">
                        <?php $__currentLoopData = $madrasah->misi ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $misi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-warning me-2"></i>
                                <span class="text-white"><?php echo e($misi); ?></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Keunggulan Sekolah -->
        <?php if($madrasah->keunggulan): ?>
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h3 class="text-white">
                    <i class="fas fa-star text-warning me-2"></i>Keunggulan Sekolah
                </h3>
            </div>
            <?php $__currentLoopData = $madrasah->keunggulan ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keunggulan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-custom p-4 text-center h-100">
                    <div class="advantage-icon mb-3">
                        <i class="fas fa-<?php echo e($keunggulan['icon'] ?? 'star'); ?>"></i>
                    </div>
                    <h5 class="text-white mb-3"><?php echo e($keunggulan['title'] ?? ''); ?></h5>
                    <p class="text-white-50"><?php echo e($keunggulan['description'] ?? ''); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <!-- Prestasi Sekolah -->
        <?php if($madrasah->prestasi): ?>
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h3 class="text-white">
                    <i class="fas fa-trophy text-warning me-2"></i>Prestasi Sekolah
                </h3>
            </div>
            <?php $__currentLoopData = $madrasah->prestasi ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prestasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="achievement-badge">
                    <div class="mb-2">
                        <i class="fas fa-medal fa-2x text-warning"></i>
                    </div>
                    <h6 class="mb-2"><?php echo e($prestasi['title'] ?? ''); ?></h6>
                    <p class="small mb-0"><?php echo e($prestasi['description'] ?? ''); ?></p>
                    <?php if(isset($prestasi['year'])): ?>
                        <small class="text-warning"><?php echo e($prestasi['year']); ?></small>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
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
                    <p><?php echo e($madrasah->telepon ?? 'Tidak tersedia'); ?></p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100 animate-fade-in-up">
                    <i class="fas fa-envelope"></i>
                    <h5>Email</h5>
                    <p><?php echo e($madrasah->email ?? 'Tidak tersedia'); ?></p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100 animate-fade-in-up">
                    <i class="fas fa-map-marker-alt"></i>
                    <h5>Alamat</h5>
                    <p><?php echo e($madrasah->alamat ?? 'Alamat belum ditentukan'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sejarah Sekolah -->
<?php if($madrasah->sejarah || $madrasah->tahun_berdiri): ?>
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Sejarah Sekolah</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <?php if($madrasah->tahun_berdiri): ?>
                    <div class="timeline">
                        <div class="timeline-item">
                            <h5 class="text-primary"><?php echo e($madrasah->tahun_berdiri); ?></h5>
                            <p>Tahun berdiri <?php echo e($madrasah->name); ?></p>
                        </div>
                        <?php if($madrasah->sejarah): ?>
                            <div class="timeline-item">
                                <h5 class="text-primary">Perjalanan</h5>
                                <p><?php echo e($madrasah->sejarah); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
                <div class="card-custom p-4">
                    <h5 class="text-primary mb-3">Informasi Sekolah</h5>
                    <?php if($madrasah->akreditasi): ?>
                        <p class="mb-2"><strong>Akreditasi:</strong> <?php echo e($madrasah->akreditasi); ?></p>
                    <?php endif; ?>
                    <?php if($madrasah->nilai_nilai): ?>
                        <p class="mb-0"><strong>Nilai-Nilai:</strong> <?php echo e($madrasah->nilai_nilai); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Program Unggulan & Ekstrakurikuler -->
<?php if($madrasah->program_unggulan || $madrasah->ekstrakurikuler): ?>
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Program Unggulan & Ekstrakurikuler</h2>
            </div>
        </div>
        <div class="row">
            <?php if($madrasah->program_unggulan): ?>
            <div class="col-lg-6 mb-4">
                <div class="card-custom p-4">
                    <h4 class="text-primary mb-4">Program Unggulan</h4>
                    <div class="row">
                        <?php $__currentLoopData = $madrasah->program_unggulan ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span><?php echo e($program['name'] ?? $program); ?></span>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if($madrasah->ekstrakurikuler): ?>
            <div class="col-lg-6 mb-4">
                <div class="card-custom p-4">
                    <h4 class="text-primary mb-4">Ekstrakurikuler</h4>
                    <div class="row">
                        <?php $__currentLoopData = $madrasah->ekstrakurikuler ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ekstra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-run text-primary me-2"></i>
                                <span><?php echo e($ekstra['name'] ?? $ekstra); ?></span>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Testimoni -->
<?php if($madrasah->testimoni): ?>
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Testimoni Alumni & Orang Tua</h2>
            </div>
        </div>
        <div class="row">
            <?php $__currentLoopData = $madrasah->testimoni ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimoni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <?php if(isset($testimoni['avatar']) && $testimoni['avatar']): ?>
                            <img src="<?php echo e(asset('storage/app/public/' . $testimoni['avatar'])); ?>" alt="<?php echo e($testimoni['name']); ?>" class="testimonial-avatar me-3">
                        <?php else: ?>
                            <div class="testimonial-avatar bg-primary d-flex align-items-center justify-content-center me-3">
                                <span class="text-white fw-bold"><?php echo e(substr($testimoni['name'] ?? 'A', 0, 1)); ?></span>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h6 class="mb-0 text-primary"><?php echo e($testimoni['name'] ?? ''); ?></h6>
                            <small class="text-muted"><?php echo e($testimoni['position'] ?? ''); ?> | <?php echo e($testimoni['year'] ?? ''); ?></small>
                        </div>
                    </div>
                    <p class="text-muted italic">"<?php echo e($testimoni['message'] ?? ''); ?>"</p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Kepala Sekolah -->
<?php if($madrasah->kepala_sekolah_nama): ?>
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Kepala Sekolah</h2>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4">
                <?php if($madrasah->kepala_sekolah_foto): ?>
                    <img src="<?php echo e(asset('storage/app/public/' . $madrasah->kepala_sekolah_foto)); ?>" alt="<?php echo e($madrasah->kepala_sekolah_nama); ?>" class="rounded-circle" style="width: 200px; height: 200px; object-fit: cover; border: 5px solid #efaa0c;">
                <?php else: ?>
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto" style="width: 200px; height: 200px;">
                        <span class="text-white display-4"><?php echo e(substr($madrasah->kepala_sekolah_nama, 0, 1)); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-8">
                <h4 class="text-primary mb-2"><?php echo e($madrasah->kepala_sekolah_nama); ?></h4>
                <?php if($madrasah->kepala_sekolah_gelar): ?>
                    <p class="text-muted mb-3"><?php echo e($madrasah->kepala_sekolah_gelar); ?></p>
                <?php endif; ?>
                <?php if($madrasah->kepala_sekolah_sambutan): ?>
                    <p class="lead"><?php echo e($madrasah->kepala_sekolah_sambutan); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Data Statistik -->
<?php if($madrasah->jumlah_siswa || $madrasah->jumlah_guru || $madrasah->jumlah_jurusan || $madrasah->jumlah_sarana): ?>
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Data Statistik Sekolah</h2>
            </div>
        </div>
        <div class="row">
            <?php if($madrasah->jumlah_siswa): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-counter">
                    <div class="stats-number"><?php echo e(number_format($madrasah->jumlah_siswa)); ?></div>
                    <h6>Jumlah Siswa</h6>
                </div>
            </div>
            <?php endif; ?>
            <?php if($madrasah->jumlah_guru): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-counter">
                    <div class="stats-number"><?php echo e(number_format($madrasah->jumlah_guru)); ?></div>
                    <h6>Jumlah Guru</h6>
                </div>
            </div>
            <?php endif; ?>
            <?php if($madrasah->jumlah_jurusan): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-counter">
                    <div class="stats-number"><?php echo e($madrasah->jumlah_jurusan); ?></div>
                    <h6>Jumlah Jurusan</h6>
                </div>
            </div>
            <?php endif; ?>
            <?php if($madrasah->jumlah_sarana): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-counter">
                    <div class="stats-number"><?php echo e($madrasah->jumlah_sarana); ?></div>
                    <h6>Jumlah Sarana</h6>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Video Profile & Galeri -->
<?php if($madrasah->video_profile || $madrasah->galeri_foto): ?>
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Galeri & Media Sekolah</h2>
            </div>
        </div>
        <div class="row">
            <?php if($madrasah->video_profile): ?>
            <div class="col-lg-6 mb-4">
                <div class="card-custom p-4">
                    <h5 class="text-primary mb-3">Video Profile Sekolah</h5>
                    <div class="video-container">
                        <iframe src="<?php echo e($madrasah->video_profile); ?>" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if($madrasah->galeri_foto): ?>
            <div class="col-lg-<?php echo e($madrasah->video_profile ? '6' : '12'); ?> mb-4">
                <div class="card-custom p-4">
                    <h5 class="text-primary mb-3">Galeri Foto Kegiatan</h5>
                    <div class="row">
                        <?php $__currentLoopData = $madrasah->galeri_foto ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 mb-3">
                            <?php if(isset($foto['url']) && $foto['url']): ?>
                                <img src="<?php echo e(asset('storage/app/public/' . $foto['url'])); ?>" alt="<?php echo e($foto['caption'] ?? ''); ?>" class="gallery-img">
                            <?php endif; ?>
                            <?php if(isset($foto['caption']) && $foto['caption']): ?>
                                <p class="text-center mt-2 small text-muted"><?php echo e($foto['caption']); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- FAQ -->
<?php if($madrasah->faq): ?>
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">FAQ PPDB</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php $__currentLoopData = $madrasah->faq ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="faq-item">
                    <button class="faq-question">
                        <i class="fas fa-chevron-down me-2"></i>
                        <?php echo e($faq['question'] ?? ''); ?>

                    </button>
                    <div class="faq-answer">
                        <?php echo e($faq['answer'] ?? ''); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Alur Pendaftaran -->
<?php if($madrasah->alur_pendaftaran): ?>
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
                    <?php $__currentLoopData = $madrasah->alur_pendaftaran ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="timeline-item">
                        <h5 class="text-primary">Langkah <?php echo e($index + 1); ?></h5>
                        <p><?php echo e($step['description'] ?? $step); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="section-padding bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">Siap Bergabung dengan <?php echo e($madrasah->name); ?>?</h2>
        <p class="lead mb-4">Daftarkan diri Anda sekarang dan jadilah bagian dari sekolah unggul kami</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <?php if(isset($ppdb->id) && $ppdb->isPembukaan()): ?>
                <a href="<?php echo e(route('ppdb.daftar', $ppdb->slug)); ?>" class="btn btn-ppdb btn-lg">Daftar PPDB Sekarang</a>
            <?php endif; ?>
            <?php if($madrasah->brosur_pdf): ?>
                <a href="<?php echo e(asset('storage/app/public/' . $madrasah->brosur_pdf)); ?>" target="_blank" class="btn btn-outline-light btn-lg">Download Brosur</a>
            <?php endif; ?>
            <a href="https://wa.me/<?php echo e(str_replace(['+', '-', ' '], '', $madrasah->telepon ?? '6281234567890')); ?>?text=Halo,%20saya%20ingin%20bertanya%20tentang%20PPDB%20<?php echo e(urlencode($madrasah->name)); ?>" target="_blank" class="btn btn-outline-light btn-lg">
                <i class="fas fa-whatsapp me-2"></i>Hubungi Admin
            </a>
        </div>
    </div>
</section>

<!-- Custom Footer for School Page -->
<footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <!-- Logo dan Deskripsi Sekolah -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center mb-3">
                    <?php if($madrasah->logo): ?>
                        <img src="<?php echo e(asset('storage/app/public/' . $madrasah->logo)); ?>" alt="<?php echo e($madrasah->name); ?>" class="me-2" style="height: 40px; width: auto; border-radius: 5px;">
                    <?php endif; ?>
                    <span class="fw-bold text-primary"><?php echo e($madrasah->name); ?></span>
                </div>
                <p class="mb-3"><?php echo e($madrasah->deskripsi_singkat ?? 'Sekolah unggul di bawah naungan LP. Ma\'arif NU PWNU D.I. Yogyakarta'); ?></p>
                <div class="social-links">
                    <?php if($madrasah->facebook): ?>
                        <a href="<?php echo e($madrasah->facebook); ?>" class="text-light me-3" target="_blank"><i class="bi bi-facebook"></i></a>
                    <?php endif; ?>
                    <?php if($madrasah->instagram): ?>
                        <a href="<?php echo e($madrasah->instagram); ?>" class="text-light me-3" target="_blank"><i class="bi bi-instagram"></i></a>
                    <?php endif; ?>
                    <?php if($madrasah->youtube): ?>
                        <a href="<?php echo e($madrasah->youtube); ?>" class="text-light" target="_blank"><i class="bi bi-youtube"></i></a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Link Cepat -->
            <div class="col-lg-2 col-md-4">
                <h5 class="fw-bold mb-3">Link Cepat</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo e(route('ppdb.index')); ?>" class="text-light text-decoration-none">Beranda PPDB</a></li>
                    <li class="mb-2"><a href="#jurusan" class="text-light text-decoration-none">Jurusan</a></li>
                    <li class="mb-2"><a href="#fasilitas" class="text-light text-decoration-none">Fasilitas</a></li>
                    <li class="mb-2"><a href="#about" class="text-light text-decoration-none">Tentang</a></li>
                </ul>
            </div>

            <!-- Bantuan -->
            <div class="col-lg-2 col-md-4">
                <h5 class="fw-bold mb-3">Bantuan</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#faq" class="text-light text-decoration-none">FAQ PPDB</a></li>
                    <li class="mb-2"><a href="#alur" class="text-light text-decoration-none">Alur Pendaftaran</a></li>
                    <?php if($madrasah->brosur_pdf): ?>
                        <li class="mb-2"><a href="<?php echo e(asset('storage/app/public/' . $madrasah->brosur_pdf)); ?>" class="text-light text-decoration-none" target="_blank">Download Brosur</a></li>
                    <?php endif; ?>
                    <li class="mb-2"><a href="#kontak" class="text-light text-decoration-none">Kontak</a></li>
                </ul>
            </div>

            <!-- Kontak Sekolah -->
            <div class="col-lg-4 col-md-4">
                <h5 class="fw-bold mb-3">Hubungi <?php echo e($madrasah->name); ?></h5>
                <div class="d-flex align-items-start mb-2">
                    <i class="bi bi-geo-alt-fill text-primary me-3 mt-1"></i>
                    <div>
                        <p class="mb-0"><?php echo e($madrasah->alamat ?? 'Alamat belum ditentukan'); ?></p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-telephone-fill text-primary me-3"></i>
                    <p class="mb-0"><?php echo e($madrasah->telepon ?? 'Telepon belum ditentukan'); ?></p>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-envelope-fill text-primary me-3"></i>
                    <p class="mb-0"><?php echo e($madrasah->email ?? 'Email belum ditentukan'); ?></p>
                </div>
                <?php if($madrasah->website): ?>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-globe text-primary me-3"></i>
                    <a href="<?php echo e($madrasah->website); ?>" class="text-light text-decoration-none" target="_blank"><?php echo e($madrasah->website); ?></a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Copyright -->
        <hr class="my-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">&copy; 2025 <?php echo e($madrasah->name); ?>. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">Powered by <a href="<?php echo e(route('ppdb.index')); ?>" class="text-primary text-decoration-none">PPDB NUIST</a></p>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary position-fixed" style="bottom: 20px; right: 20px; display: none; border-radius: 50%; width: 50px; height: 50px; z-index: 1050;">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- Floating Daftar Button -->
<?php if(isset($ppdb->id)): ?>
<button id="floatingDaftarBtn" class="btn btn-ppdb position-fixed animate-pulse" style="bottom: 90px; right: 20px; border-radius: 50%; width: 60px; height: 60px; z-index: 1050; box-shadow: 0 5px 15px rgba(239, 170, 12, 0.4);">
    <i class="fas fa-edit fa-lg"></i>
</button>
<?php endif; ?>

<script>
    // Back to Top functionality
    const backToTopBtn = document.getElementById('backToTop');

    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.style.display = 'block';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });

    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Floating Daftar Button functionality
    const floatingDaftarBtn = document.getElementById('floatingDaftarBtn');
    if (floatingDaftarBtn) {
        floatingDaftarBtn.addEventListener('click', function() {
            <?php if(isset($ppdb->slug) && $ppdb->slug): ?>
                window.location.href = '<?php echo e(route("ppdb.daftar", $ppdb->slug)); ?>';
            <?php else: ?>
                window.location.href = '<?php echo e(route("ppdb.index")); ?>';
            <?php endif; ?>
        });

        // Show/hide floating button based on scroll
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 500) {
                floatingDaftarBtn.style.display = 'block';
            } else {
                floatingDaftarBtn.style.display = 'none';
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/ppdb/sekolah.blade.php ENDPATH**/ ?>