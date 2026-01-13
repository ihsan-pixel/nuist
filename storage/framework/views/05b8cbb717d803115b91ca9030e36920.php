<?php $__env->startSection('title', 'Profile ' . ($ppdb->nama_sekolah) . ' - PPDB NUIST'); ?>

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

        /* FIX TERPENTING */
        display: block; /* buang flex agar elemen tidak ketarik tengah */
        padding-top: 40px; /* beri ruang flayer lebih kecil */
        padding-bottom: 80px;
        min-height: 93vh;
        position: relative;
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

    .hero-flayer {
        text-align: center;
        margin-bottom: 120px;
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

    .btn-status {
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        border: none;
        color: white;
        padding: 15px 40px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 75, 76, 0.3);
    }

    .btn-status:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 75, 76, 0.4);
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

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .gallery-item:hover {
        transform: translateY(-5px);
    }

    .facility-gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .facility-gallery-item:hover {
        transform: translateY(-5px);
    }

    .facility-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 75, 76, 0.9));
        padding: 20px 15px 15px;
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }

    .facility-gallery-item:hover .facility-overlay {
        transform: translateY(0);
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
        font-weight: 600 !important;
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

    /* Adjustments for contact section with about class */
    .contact-section.about .contact-card {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }

    .contact-section.about .contact-card h5 {
        color: white;
    }

    .contact-section.about .contact-card p {
        color: rgba(255, 255, 255, 0.9);
    }

    .contact-section.about .contact-card .fas {
        color: #efaa0c;
    }

    .contact-section.about .text-muted {
        color: rgba(255, 255, 255, 0.7) !important;
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
                    <a class="nav-link" href="#hero" data-section="hero">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#profile" data-section="profile">
                        Profile
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#jurusan" data-section="jurusan">
                        Jurusan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#fasilitas" data-section="fasilitas">
                        Fasilitas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#program-ekstra" data-section="program-ekstra">
                        Program & Extra
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#prestasi" data-section="prestasi">
                        Prestasi
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#about" data-section="about">
                        About
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#kontak" data-section="kontak">
                        Kontak
                    </a>
                </li>

            </ul>
        </div>

        <!-- Tombol Kanan -->
        <div class="d-none d-lg-block">
            <?php if(isset($ppdb->id)): ?>
                <a href="<?php echo e(route('ppdb.daftar', $ppdb->slug)); ?>" class="btn btn-orange px-4">
                    <i class="fas fa-edit me-1"></i>Daftar
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
                    <a class="nav-link <?php echo e(request()->routeIs('ppdb.sekolah') ? 'active' : ''); ?>" href="#hero" onclick="closeMobileMenuSchool()" data-section="hero">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link" href="#profile" onclick="closeMobileMenuSchool()">
                        <i class="fas fa-user me-1"></i>Profile
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
                    <a class="nav-link" href="#program-ekstra" onclick="closeMobileMenuSchool()">
                        <i class="fas fa-star me-1"></i>Program & Extra
                    </a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link" href="#prestasi" onclick="closeMobileMenuSchool()">
                        <i class="fas fa-trophy me-1"></i>Prestasi
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
<section id="hero" class="hero-section">

    <!-- Flayer di atas -->
    <div class="container hero-flayer animate-fade-in-up">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <img src="<?php echo e(asset('images/flayer1.png')); ?>" class="img-fluid rounded animate-bounce-in" alt="Flayer PPDB">
            </div>
        </div>
    </div>

    <!-- Bagian Text + Image Hero -->
    <div class="container">
        <div class="row align-items-center">

            <!-- Text Kiri -->
            <div class="col-lg-6 animate-slide-in-left">
                <div class="d-flex align-items-center mb-4">
                    <?php if($ppdb->logo ?? $madrasah->logo): ?>
                        <img src="<?php echo e(asset('storage/' . ($ppdb->logo ?? $madrasah->logo))); ?>" alt="<?php echo e($ppdb->nama_sekolah); ?>" class="school-logo me-4">
                    <?php else: ?>
                        <div class="school-logo bg-light d-flex align-items-center justify-content-center me-4">
                            <i class="fas fa-school fa-2x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h1 class="display-4 fw-bold mb-2"><?php echo e($ppdb->nama_sekolah); ?></h1>
                        <?php if($ppdb->tagline): ?>
                            <p class="h4 text-warning mb-0"><?php echo e($ppdb->tagline); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($ppdb->deskripsi_singkat): ?>
                    <p class="lead mb-4"><?php echo e($ppdb->deskripsi_singkat); ?></p>
                <?php endif; ?>
                <div class="d-flex gap-3 flex-wrap">
                    <?php $currentSlug = request()->route('slug'); ?>
                    <?php if($currentSlug): ?>
                        <a href="<?php echo e(route('ppdb.daftar', $currentSlug)); ?>" class="btn btn-ppdb">
                            <i class="fas fa-edit me-2"></i>Daftar Sekarang
                        </a>
                        <a href="<?php echo e(route('ppdb.cek-status', $currentSlug)); ?>" class="btn btn-status">
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

<!-- Profile Sekolah -->
<?php
    $tahunBerdiri = $ppdb->tahun_berdiri;
    $sejarah = $ppdb->sejarah;
    $akreditasi = $ppdb->akreditasi;
    $nilaiNilai = $ppdb->nilai_nilai;
?>
<?php if(!empty($ppdb->tahun_berdiri) || !empty($ppdb->sejarah) || !empty($ppdb->akreditasi) || !empty($ppdb->nilai_nilai)): ?>
<section id="profile" class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">
                    <i class="fas fa-history text-warning me-3"></i>Profile <span style="color: #efaa0c;">Sekolah</span>
                </h2>
                <p class="lead">Mengenal lebih dalam tentang sekolah kami</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <?php if($ppdb->tahun_berdiri): ?>
                    <div class="timeline">
                        <div class="timeline-item">
                            <h5 class="text-warning"><?php echo e($ppdb->tahun_berdiri); ?></h5>
                            <p class="text-white">Tahun berdiri <?php echo e($ppdb->nama_sekolah ?? $ppdb->name); ?></p>
                        </div>
                        <?php if($ppdb->sejarah): ?>
                            <div class="timeline-item">
                                <h5 class="text-warning">Perjalanan</h5>
                                <p class="text-white"><?php echo e($ppdb->sejarah); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
                <div class="card-custom p-4" style="background: white; color: #004b4c;">
                    <h5 class="mb-3" style="color: #004b4c;">Informasi Sekolah</h5>
                    <?php if($ppdb->akreditasi): ?>
                        <p class="mb-2" style="color: #004b4c;"><strong>Akreditasi:</strong> <?php echo e($ppdb->akreditasi); ?></p>
                    <?php endif; ?>
                    <?php if($ppdb->nilai_nilai): ?>
                        <p class="mb-2" style="color: #004b4c;"><strong>Nilai-Nilai:</strong> <?php echo e($ppdb->nilai_nilai); ?></p>
                    <?php endif; ?>
                    <?php if($ppdb->tahun_berdiri): ?>
                        <p class="mb-0" style="color: #004b4c;"><strong>Tahun Berdiri:</strong> <?php echo e($ppdb->tahun_berdiri); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Jurusan/Program Studi -->
<section id="jurusan" class="section-padding">
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
                $jurusanData = $ppdb->jurusan;
                if (is_string($jurusanData)) {
                    $jurusanData = json_decode($jurusanData, true) ?? [];
                } elseif (!is_array($jurusanData)) {
                    $jurusanData = [];
                }
            ?>
            <?php if($jurusanData && count($jurusanData) > 0): ?>
                <?php $__currentLoopData = $jurusanData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jurusan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="major-card">
                        <?php if(isset($jurusan['image']) && $jurusan['image']): ?>
                            <img src="<?php echo e(asset('storage/' . $jurusan['image'])); ?>" alt="<?php echo e($jurusan['name'] ?? $jurusan['nama'] ?? ''); ?>" class="w-100 rounded mb-3" style="height: 150px; object-fit: cover;">
                        <?php endif; ?>
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-book me-2"></i>
                            <?php if(is_array($jurusan)): ?>
                                <?php echo e($jurusan['name'] ?? $jurusan['nama'] ?? ''); ?>

                            <?php else: ?>
                                <?php echo e($jurusan); ?>

                            <?php endif; ?>
                        </h5>
                        <?php if(is_array($jurusan)): ?>
                            <p class="text-muted mb-3"><?php echo e($jurusan['description'] ?? $jurusan['deskripsi'] ?? ''); ?></p>
                            <?php if(isset($jurusan['prospek_karir']) && !empty($jurusan['prospek_karir'])): ?>
                                <div class="mb-3">
                                    <strong><i class="fas fa-briefcase me-2"></i>Prospek Karir:</strong>
                                    <p class="text-muted small"><?php echo e($jurusan['prospek_karir']); ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if(isset($jurusan['skill_dipelajari']) && !empty($jurusan['skill_dipelajari'])): ?>
                                <div>
                                    <strong><i class="fas fa-tools me-2"></i>Skill yang Dipelajari:</strong>
                                    <ul class="text-muted small">
                                        <?php
                                            $skills = $jurusan['skill_dipelajari'];
                                            if (is_string($skills)) {
                                                $skills = array_map('trim', explode(',', $skills));
                                            } elseif (!is_array($skills)) {
                                                $skills = [$skills];
                                            }
                                        ?>
                                        <?php $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($skill); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted mb-3"><?php echo e($jurusan); ?></p>
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
<?php
    $fasilitasData = $ppdb->fasilitas;
    if (is_string($fasilitasData)) {
        $fasilitasData = json_decode($fasilitasData, true) ?? [];
    } elseif (!is_array($fasilitasData)) {
        $fasilitasData = [];
    }
?>
<section id="fasilitas" class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">
                    <i class="fas fa-building text-warning me-3"></i>Fasilitas <span style="color: #efaa0c;">Sekolah</span>
                </h2>
                <p class="lead">Fasilitas lengkap untuk mendukung pembelajaran siswa</p>
            </div>
        </div>
        <div class="row">
            <?php if($fasilitasData && count($fasilitasData) > 0): ?>
                <?php $__currentLoopData = $fasilitasData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fasilitas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card-custom p-4 text-center h-100" style="background: white; color: #004b4c;">
                        <?php if(isset($fasilitas['foto']) && $fasilitas['foto']): ?>
                            <img src="<?php echo e(asset('images/madrasah/galeri/' . $fasilitas['foto'])); ?>" alt="<?php echo e($fasilitas['name']); ?>" class="w-100 rounded mb-3" style="height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <img src="<?php echo e(asset('images/default-facility.jpg')); ?>" alt="<?php echo e($fasilitas['name']); ?>" class="w-100 rounded mb-3" style="height: 150px; object-fit: cover;">
                        <?php endif; ?>
                        <h6 class="mb-2" style="color: #004b4c;">
                            <?php echo e($fasilitas['name'] ?? ''); ?>

                        </h6>
                        <p class="small mb-0" style="color: #004b4c;"><?php echo e($fasilitas['description'] ?? ''); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="card-custom p-5" style="background: white; color: #004b4c;">
                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                        <h5 style="color: #004b4c;">Fasilitas Akan Segera Ditambahkan</h5>
                        <p style="color: #004b4c;">Informasi fasilitas sekolah sedang dalam proses penyusunan.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Program Unggulan & Ekstrakurikuler -->
<?php
    $programUnggulanData = $ppdb->program_unggulan;
    if (is_string($programUnggulanData)) {
        $programUnggulanData = json_decode($programUnggulanData, true) ?? [];
    } elseif (!is_array($programUnggulanData)) {
        $programUnggulanData = [];
    }

    $ekstrakurikulerData = $ppdb->ekstrakurikuler;
    if (is_string($ekstrakurikulerData)) {
        $ekstrakurikulerData = json_decode($ekstrakurikulerData, true) ?? [];
    } elseif (!is_array($ekstrakurikulerData)) {
        $ekstrakurikulerData = [];
    }
?>
<?php if($programUnggulanData || $ekstrakurikulerData): ?>
<section id="program-ekstra" class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">
                    <i class="fas fa-star text-primary me-3"></i>Program Unggulan & Ekstrakurikuler
                </h2>
            </div>
        </div>
        <div class="row">
            <?php if($programUnggulanData && count($programUnggulanData) > 0): ?>
                <?php $__currentLoopData = $programUnggulanData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="major-card">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-award me-2"></i>
                            <?php if(is_array($program)): ?>
                                <?php echo e($program['name'] ?? $program['nama'] ?? ''); ?>

                            <?php else: ?>
                                <?php echo e($program); ?>

                            <?php endif; ?>
                        </h5>
                        <?php if(is_array($program) && isset($program['description']) && !empty($program['description'])): ?>
                            <p class="text-muted mb-3"><?php echo e($program['description']); ?></p>
                        <?php else: ?>
                            <p class="text-muted mb-3">Program unggulan yang menjadi keunggulan sekolah kami.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <?php if($ekstrakurikulerData && count($ekstrakurikulerData) > 0): ?>
                <?php $__currentLoopData = $ekstrakurikulerData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ekstra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="major-card">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-futbol me-2"></i>
                            <?php if(is_array($ekstra)): ?>
                                <?php echo e($ekstra['name'] ?? $ekstra['nama'] ?? ''); ?>

                            <?php else: ?>
                                <?php echo e($ekstra); ?>

                            <?php endif; ?>
                        </h5>
                        <?php if(is_array($ekstra) && isset($ekstra['description']) && !empty($ekstra['description'])): ?>
                            <p class="text-muted mb-3"><?php echo e($ekstra['description']); ?></p>
                        <?php else: ?>
                            <p class="text-muted mb-3">Kegiatan ekstrakurikuler untuk mengembangkan bakat siswa.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <?php if((!$programUnggulanData || count($programUnggulanData) == 0) && (!$ekstrakurikulerData || count($ekstrakurikulerData) == 0)): ?>
                <div class="col-12 text-center">
                    <div class="card-custom p-5">
                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Program Unggulan & Ekstrakurikuler Akan Segera Ditambahkan</h5>
                        <p class="text-muted">Informasi program unggulan dan ekstrakurikuler sedang dalam proses penyusunan.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Prestasi Sekolah -->
<?php
    $prestasiData = $ppdb->prestasi;
    if (is_string($prestasiData)) {
        $prestasiData = json_decode($prestasiData, true) ?? [];
    } elseif (!is_array($prestasiData)) {
        $prestasiData = [];
    }
?>
<?php if($prestasiData && is_array($prestasiData) && count($prestasiData) > 0): ?>
<section id="prestasi" class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">
                    <i class="fas fa-trophy text-warning me-3"></i>Prestasi <span style="color: #efaa0c;">Sekolah</span>
                </h2>
                <p class="lead">Pencapaian dan penghargaan yang telah diraih sekolah kami</p>
            </div>
        </div>
        <div class="row">
            <?php $__currentLoopData = $prestasiData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prestasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
    </div>
</section>
<?php endif; ?>

<!-- About Section -->
<section id="about" class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    <i class="fas fa-info-circle text-primary me-3"></i>Tentang <span style="color: #efaa0c;"><?php echo e($ppdb->nama_sekolah); ?></span>
                </h2>
                <p class="lead text-muted">Mengenal lebih dalam tentang sekolah kami</p>
            </div>
        </div>

        <!-- Visi & Misi -->
        <?php if($ppdb->visi || $ppdb->misi): ?>
        <div class="row mb-5">
            <?php if($ppdb->visi): ?>
            <div class="col-lg-6 mb-4">
                <div class="major-card text-center">
                    <div class="advantage-icon mb-4">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h4 class="text-primary mb-4">Visi</h4>
                    <p class="text-muted"><?php echo e($ppdb->visi); ?></p>
                </div>
            </div>
            <?php endif; ?>
            <?php if($ppdb->misi): ?>
            <div class="col-lg-6 mb-4">
                <div class="major-card text-center">
                    <div class="advantage-icon mb-4">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h4 class="text-primary mb-4">Misi</h4>
                    <ul class="list-unstyled text-start">
                        <?php
                            $misiData = $ppdb->misi;
                            if (is_string($misiData)) {
                                $misiData = json_decode($misiData, true) ?? [];
                            } elseif (!is_array($misiData)) {
                                $misiData = [];
                            }
                        ?>
                        <?php $__currentLoopData = $misiData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $misi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-warning me-2"></i>
                                <span class="text-dark"><?php echo e($misi); ?></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Keunggulan Sekolah -->
        <?php
            $keunggulanData = $ppdb->keunggulan;
            if (is_string($keunggulanData)) {
                $keunggulanData = json_decode($keunggulanData, true) ?? [];
            } elseif (!is_array($keunggulanData)) {
                $keunggulanData = [];
            }
        ?>
        <?php if($keunggulanData && count($keunggulanData) > 0): ?>
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h3 class="text-primary">
                    <i class="fas fa-star text-warning me-2"></i>Keunggulan Sekolah
                </h3>
            </div>
            <?php $__currentLoopData = $keunggulanData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keunggulan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="major-card text-center">
                    <div class="advantage-icon mb-3">
                        <i class="fas fa-<?php echo e($keunggulan['icon'] ?? 'star'); ?>"></i>
                    </div>
                    <h5 class="text-primary mb-3"><?php echo e($keunggulan['title'] ?? ''); ?></h5>
                    <p class="text-muted"><?php echo e($keunggulan['description'] ?? ''); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>


    </div>
</section>

<!-- Lokasi & Kontak -->
<section id="kontak" class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">
                    <i class="fas fa-phone text-primary me-3"></i>Hubungi <span style="color: #efaa0c;">Kami</span>
                </h2>
                <p class="lead">Butuh bantuan? Tim kami siap membantu Anda</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100 animate-fade-in-up">
                    <i class="fas fa-phone"></i>
                    <h5>Telepon</h5>
                    <p><?php echo e($ppdb->telepon ?? 'Tidak tersedia'); ?></p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100 animate-fade-in-up">
                    <i class="fas fa-envelope"></i>
                    <h5>Email</h5>
                    <p><?php echo e($ppdb->email ?? 'Tidak tersedia'); ?></p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100 animate-fade-in-up">
                    <i class="fas fa-map-marker-alt"></i>
                    <h5>Alamat</h5>
                    <p><?php echo e($ppdb->alamat ?? 'Alamat belum ditentukan'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Kepala Sekolah -->
<?php if($kepalaSekolah): ?>
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Kepala Sekolah</h2>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4">
                <?php if($kepalaSekolah->avatar): ?>
                    <img src="<?php echo e(asset('storage/' . $kepalaSekolah->avatar)); ?>" alt="<?php echo e($kepalaSekolah->name); ?>" class="rounded-3" style="width: 180px; height: 240px; object-fit: cover; border: 5px solid #0f854a; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                <?php else: ?>
                    <div class="rounded-3 bg-primary d-flex align-items-center justify-content-center mx-auto" style="width: 180px; height: 240px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                        <span class="text-white display-4"><?php echo e(substr($kepalaSekolah->name, 0, 1)); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-8">
                <h4 class="text-primary mb-2"><?php echo e($kepalaSekolah->name); ?></h4>
                <?php if($kepalaSekolah->jabatan): ?>
                    <p class="text-muted mb-3"><?php echo e($kepalaSekolah->jabatan); ?></p>
                <?php endif; ?>
                <?php if($ppdb->kepala_sekolah_sambutan): ?>
                    <p class="lead"><?php echo e($ppdb->kepala_sekolah_sambutan); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Galeri & Media Sekolah -->
<?php
    $hasGallery = false;
    $galeriFotoData = $ppdb->galeri_foto;
    if (is_string($galeriFotoData)) {
        $galeriFotoData = json_decode($galeriFotoData, true) ?? [];
    } elseif (!is_array($galeriFotoData)) {
        $galeriFotoData = [];
    }
    if ($galeriFotoData && count($galeriFotoData) > 0) {
        $hasGallery = true;
    }

    $fasilitasData = $madrasah->fasilitas;
    if (is_string($fasilitasData)) {
        $fasilitasData = json_decode($fasilitasData, true) ?? [];
    } elseif (!is_array($fasilitasData)) {
        $fasilitasData = [];
    }
    $facilityPhotos = [];
    if ($fasilitasData && count($fasilitasData) > 0) {
        foreach ($fasilitasData as $fasilitas) {
            if (isset($fasilitas['image']) && $fasilitas['image']) {
                $facilityPhotos[] = $fasilitas;
                $hasGallery = true;
            }
        }
    }

    $hasVideo = false;
    $videoUrl = '';
    $linkVideoYoutube = $ppdb->video_profile;
    if ($linkVideoYoutube) {
        $videoUrl = $linkVideoYoutube;
        // Check if it's a YouTube URL and convert to embed format
        if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $matches)) {
                $videoUrl = 'https://www.youtube.com/embed/' . $matches[1];
            }
        }
        $hasVideo = true;
        $hasGallery = true;
    }
?>


<section id="galeri" class="section-padding" style="background: url('<?php echo e(asset('images/bg_ppdb4.png')); ?>'); background-size: cover; background-position: center; background-attachment: absolute;">
    <div class="container" style="background: rgba(255, 255, 255, 0.9); padding: 40px; border-radius: 20px; margin-top: -20px; position: relative; z-index: 2;">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">
                    <i class="fas fa-images text-primary me-3"></i>Galeri & Media Sekolah
                </h2>
            </div>
        </div>

        <!-- Video Section -->
        <?php if($hasVideo): ?>
        <div class="row mb-5">
            <div class="col-lg-8">
                <div class="card-custom p-4">
                    <h4 class="text-primary mb-4 text-center">
                        <i class="fas fa-play-circle me-2"></i>Video Profile Sekolah
                    </h4>
                    <div class="video-container">
                        <iframe src="<?php echo e($videoUrl); ?>" frameborder="0" allowfullscreen></iframe>
                    </div>
                    <?php if(strpos($ppdb->video_profile, 'youtube.com') !== false || strpos($ppdb->video_profile, 'youtu.be') !== false): ?>
                        <div class="text-center mt-3">
                            <a href="<?php echo e($ppdb->video_profile); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-youtube me-2"></i>Tonton di YouTube
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-custom p-4 h-100">
                    <h4 class="text-primary mb-4 text-center">
                        <i class="fas fa-download me-2"></i>Download Brosur
                    </h4>
                    <?php if($ppdb->brosur_pdf): ?>
                        <div class="text-center">
                            <p class="text-muted mb-3">Dapatkan informasi lengkap tentang sekolah kami dalam format PDF.</p>
                            <a href="<?php echo e(asset('uploads/brosur/' . $ppdb->brosur_pdf)); ?>" target="_blank" class="btn btn-primary btn-lg">
                                <i class="fas fa-file-pdf me-2"></i>Download Brosur PDF
                            </a>
                            <p class="text-muted small mt-2">
                                <i class="fas fa-info-circle me-1"></i>Ukuran file: PDF
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Brosur sedang dalam proses penyusunan.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Standalone Brosur Section (if no video) -->
        <?php if(!$hasVideo && $ppdb->brosur_pdf): ?>
        <div class="row mb-5">
            <div class="col-12">
                <div class="card-custom p-4 text-center">
                    <h4 class="text-primary mb-4">
                        <i class="fas fa-download me-2"></i>Download Brosur Sekolah
                    </h4>
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <p class="text-muted mb-4">Dapatkan informasi lengkap tentang sekolah kami dalam format PDF yang mudah diunduh dan dibagikan.</p>
                            <a href="<?php echo e(asset('uploads/brosur/' . $ppdb->brosur_pdf)); ?>" target="_blank" class="btn btn-primary btn-lg">
                                <i class="fas fa-file-pdf me-2"></i>Download Brosur PDF
                            </a>
                            <p class="text-muted small mt-3">
                                <i class="fas fa-info-circle me-1"></i>Klik untuk mengunduh brosur informasi sekolah
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Photo Gallery -->
        <?php
            $galeriFotoData = $ppdb->galeri_foto;
            if (is_string($galeriFotoData)) {
                $galeriFotoData = json_decode($galeriFotoData, true) ?? [];
            } elseif (!is_array($galeriFotoData)) {
                $galeriFotoData = [];
            }
        ?>
        <?php if($galeriFotoData && count($galeriFotoData) > 0 || $facilityPhotos && count($facilityPhotos) > 0): ?>
        <div class="row">
            <!-- Galeri Foto PPDB -->
            <?php if($ppdb->galeri_foto && count($ppdb->galeri_foto) > 0): ?>
            <div class="col-12 mb-4">
                <h4 class="text-primary mb-3">
                    <i class="fas fa-images me-2"></i>Galeri Foto PPDB
                </h4>
                <p class="text-muted mb-3">Koleksi foto-foto kegiatan dan fasilitas sekolah dari pengaturan PPDB</p>
                <div class="row">
                    <?php $__currentLoopData = $galeriFotoData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="gallery-item">
                            <img src="<?php echo e(asset('images/madrasah/galeri/' . $foto)); ?>" alt="Galeri Foto PPDB" class="gallery-img w-100">
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Foto Fasilitas -->
            <?php if($facilityPhotos && count($facilityPhotos) > 0): ?>
            <div class="col-12">
                <h4 class="text-primary mb-3">
                    <i class="fas fa-building me-2"></i>Foto Fasilitas Sekolah
                </h4>
                <div class="row">
                    <?php $__currentLoopData = $facilityPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fasilitas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="text-center">
                            <div class="facility-gallery-item mb-2">
                                <img src="<?php echo e(asset('images/madrasah/galeri/' . $fasilitas['image'])); ?>" alt="<?php echo e($fasilitas['name'] ?? 'Fasilitas'); ?>" class="gallery-img w-100">
                            </div>
                            <h6 class="text-primary"><?php echo e($fasilitas['name'] ?? ''); ?></h6>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>


<!-- FAQ -->
<?php
    $faqData = $ppdb->faq;
    if (is_string($faqData)) {
        $faqData = json_decode($faqData, true) ?? [];
    } elseif (!is_array($faqData)) {
        $faqData = [];
    }
?>
<?php if($faqData): ?>
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">FAQ PPDB</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php $__currentLoopData = $faqData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
<?php
    $alurPendaftaranData = $ppdb->alur_pendaftaran;
    if (is_string($alurPendaftaranData)) {
        $alurPendaftaranData = json_decode($alurPendaftaranData, true) ?? [];
    } elseif (!is_array($alurPendaftaranData)) {
        $alurPendaftaranData = [];
    }
?>
<?php if($alurPendaftaranData && count($alurPendaftaranData) > 0): ?>
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
                    <?php $__currentLoopData = $alurPendaftaranData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
        <h2 class="display-5 fw-bold mb-4">Siap Bergabung dengan <?php echo e($ppdb->nama_sekolah ?? $ppdb->name); ?>?</h2>
        <p class="lead mb-4">Daftarkan diri Anda sekarang dan jadilah bagian dari sekolah unggul kami</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <?php if(isset($ppdb->id) && $ppdb->isPembukaan()): ?>
                <a href="<?php echo e(route('ppdb.daftar', $ppdb->slug)); ?>" class="btn btn-ppdb btn-lg">Daftar PPDB Sekarang</a>
            <?php endif; ?>
            <?php
                $brosurData = $ppdb->brosur_pdf ?? $madrasah->brosur_pdf;
            ?>
            <?php if($brosurData): ?>
                <a href="<?php echo e(asset('uploads/brosur/' . $brosurData)); ?>" target="_blank" class="btn btn-outline-light btn-lg">Download Brosur</a>
            <?php endif; ?>
            <a href="https://wa.me/<?php echo e(str_replace(['+', '-', ' '], '', $ppdb->telepon ?? '6281234567890')); ?>?text=Halo,%20saya%20ingin%20bertanya%20tentang%20PPDB%20<?php echo e(urlencode($ppdb->nama_sekolah ?? $ppdb->name)); ?>" target="_blank" class="btn btn-outline-light btn-lg">
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
                    <?php if($ppdb->logo): ?>
                        <img src="<?php echo e(asset('storage/' . $ppdb->logo)); ?>" alt="<?php echo e($ppdb->nama_sekolah ?? $ppdb->name); ?>" class="me-2" style="height: 40px; width: auto; border-radius: 5px;">
                    <?php endif; ?>
                    <span class="fw-bold text-primary"><?php echo e($ppdb->nama_sekolah ?? $ppdb->name); ?></span>
                </div>
                <p class="mb-3"><?php echo e($ppdb->deskripsi_singkat ?? 'Sekolah unggul di bawah naungan LP. Ma\'arif NU PWNU D.I. Yogyakarta'); ?></p>
                <div class="social-links">
                    <?php if($ppdb->facebook): ?>
                        <a href="<?php echo e($ppdb->facebook); ?>" class="text-light me-3" target="_blank"><i class="bi bi-facebook"></i></a>
                    <?php endif; ?>
                    <?php if($ppdb->instagram): ?>
                        <a href="<?php echo e($ppdb->instagram); ?>" class="text-light me-3" target="_blank"><i class="bi bi-instagram"></i></a>
                    <?php endif; ?>
                    <?php if($ppdb->youtube): ?>
                        <a href="<?php echo e($ppdb->youtube); ?>" class="text-light" target="_blank"><i class="bi bi-youtube"></i></a>
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
                    <li class="mb-2"><a href="#program-ekstra" class="text-light text-decoration-none">Program & Ekstra</a></li>
                    <li class="mb-2"><a href="#about" class="text-light text-decoration-none">Tentang</a></li>
                    <li class="mb-2"><a href="#sejarah" class="text-light text-decoration-none">Sejarah</a></li>
                    <li class="mb-2"><a href="#galeri" class="text-light text-decoration-none">Galeri</a></li>
                    <li class="mb-2"><a href="#brosur" class="text-light text-decoration-none">Brosur</a></li>
                </ul>
            </div>

            <!-- Bantuan -->
            <div class="col-lg-2 col-md-4">
                <h5 class="fw-bold mb-3">Bantuan</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#faq" class="text-light text-decoration-none">FAQ PPDB</a></li>
                    <li class="mb-2"><a href="#alur" class="text-light text-decoration-none">Alur Pendaftaran</a></li>
                    <?php
                        $brosurData = $ppdb->brosur_pdf ?? $madrasah->brosur_pdf;
                    ?>
                    <?php if($brosurData): ?>
                        <li class="mb-2"><a href="<?php echo e(asset('storage/' . $brosurData)); ?>" class="text-light text-decoration-none" target="_blank">Download Brosur</a></li>
                    <?php endif; ?>
                    <li class="mb-2"><a href="#kontak" class="text-light text-decoration-none">Kontak</a></li>
                </ul>
            </div>

            <!-- Kontak Sekolah -->
            <div class="col-lg-4 col-md-4">
                <h5 class="fw-bold mb-3">Hubungi <?php echo e($ppdb->nama_sekolah); ?></h5>
                <div class="d-flex align-items-start mb-2">
                    <i class="bi bi-geo-alt-fill text-primary me-3 mt-1"></i>
                    <div>
                        <p class="mb-0"><?php echo e($ppdb->alamat ?? $madrasah->alamat ?? 'Alamat belum ditentukan'); ?></p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-telephone-fill text-primary me-3"></i>
                    <p class="mb-0"><?php echo e($ppdb->telepon ?? 'Telepon belum ditentukan'); ?></p>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-envelope-fill text-primary me-3"></i>
                    <p class="mb-0"><?php echo e($ppdb->email ?? 'Email belum ditentukan'); ?></p>
                </div>
                <?php if($ppdb->website): ?>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-globe text-primary me-3"></i>
                    <a href="<?php echo e($ppdb->website); ?>" class="text-light text-decoration-none" target="_blank"><?php echo e($ppdb->website); ?></a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Copyright -->
        <hr class="my-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">&copy; 2025 <?php echo e($ppdb->nama_sekolah ?? $ppdb->name); ?>. All rights reserved.</p>
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

    // Active navigation on scroll
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link[data-section]');

    function setActiveNav() {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100;
            const sectionHeight = section.clientHeight;
            if (pageYOffset >= sectionTop && pageYOffset < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('data-section') === current) {
                link.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', setActiveNav);
    setActiveNav(); // Set active on page load
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/ppdb/sekolah.blade.php ENDPATH**/ ?>