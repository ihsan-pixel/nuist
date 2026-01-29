<?php $__env->startSection('title', 'Sekolah/Madrasah - NUIST'); ?>
<?php $__env->startSection('description', 'Daftar Sekolah/Madrasah dibawah naungan LPMNU PWNU DIY'); ?>

<?php $__env->startSection('content'); ?>
<!-- NAVBAR -->
<nav class="navbar">
    <div class="container nav-flex">
        <div class="nav-left">
            <img src="<?php echo e(asset('images/logo1.png')); ?>" alt="Logo" style="height: 50px; margin-left: 20px;">
            <ul class="nav-menu" id="nav-menu">
                <li><a href="<?php echo e(route('landing')); ?>">Beranda</a></li>
                <li><a href="#" class="active">Sekolah</a></li>
                <li><a href="<?php echo e(route('landing')); ?>#about">Tentang</a></li>
                <li class="dropdown">
                    <a href="#" onclick="toggleSubmenu(event)">Fitur <i class="bx bx-chevron-down arrow"></i></a>
                    <ul class="submenu">
                        <li><a href="<?php echo e(route('landing')); ?>#features">Performa Tinggi</a></li>
                        <li><a href="<?php echo e(route('landing')); ?>#features">Responsif Penuh</a></li>
                        <li><a href="<?php echo e(route('landing')); ?>#features">Keamanan Terjamin</a></li>
                        <li><a href="<?php echo e(route('landing')); ?>#features">Template Modern</a></li>
                        <li><a href="<?php echo e(route('landing')); ?>#features">Analytics Terintegrasi</a></li>
                        <li><a href="<?php echo e(route('landing')); ?>#features">Dukungan 24/7</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo e(route('landing')); ?>#contact">Kontak</a></li>
            </ul>
            <div class="hamburger" id="hamburger" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <a href="#" class="btn-primary desktop-login" style="margin-right: 20px;">Login<i class='bx bx-arrow-back bx-rotate-180'></i></a>
    </div>
</nav>

<!-- HERO -->
<section id="hero" class="hero">
    <div class="container">
        <h1 class="hero-title animate fade-up">Sekolah/Madrasah</h1>
        <h1 class="hero-subtitle animate fade-up delay-1" style="color: #eda711">Dibawah Naungan LPMNU PWNU DIY</h1>
        <p class="animate fade-up delay-2">Temukan sekolah dan madrasah yang menjadi bagian dari ekosistem pendidikan kami. Klik pada sekolah untuk melihat profil lengkapnya.</p>
    </div>
</section>

<!-- SEKOLAH LIST -->
<section id="sekolah-list" class="sekolah-list">
    <div class="container">
        <h2 class="section-title animate fade-up">Daftar Sekolah/Madrasah</h2>
        <div class="schools-grid animate fade-up delay-1">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="school-card">
                    <a href="<?php echo e(route('ppdb.sekolah', $madrasah->slug ?? '#')); ?>" class="school-link">
                        <div class="school-logo">
                            <img src="<?php echo e(asset('storage/' . $madrasah->logo)); ?>" alt="<?php echo e($madrasah->name); ?>">
                        </div>
                        <div class="school-info">
                            <h3><?php echo e($madrasah->name); ?></h3>
                            <p><?php echo e($madrasah->kabupaten); ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <!-- Logo dan Deskripsi -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center mb-3">
                    <span class="fw-bold text-primary">NUIST</span>
                </div>
                <p class="mb-3">Sistem Informasi Digital LPMNU PWNU DIY untuk pengelolaan data sekolah, tenaga pendidik, dan aktivitas madrasah secara terintegrasi.</p>
                <div class="social-links">
                    <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-light me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-light me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-light"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <!-- Link Cepat -->
            <div class="col-lg-2 col-md-4">
                <h5 class="fw-bold mb-3">Link Cepat</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo e(route('landing')); ?>" class="text-light text-decoration-none">Beranda</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Sekolah</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('landing')); ?>#about" class="text-light text-decoration-none">Tentang</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('landing')); ?>#features" class="text-light text-decoration-none">Fitur</a></li>
                </ul>
            </div>

            <!-- Bantuan -->
            <div class="col-lg-2 col-md-4">
                <h5 class="fw-bold mb-3">Bantuan</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Panduan</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">FAQ</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Kontak Support</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none">Status</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div class="col-lg-4 col-md-4">
                <h5 class="fw-bold mb-3">Hubungi Kami</h5>
                <div class="d-flex align-items-start mb-2">
                    <i class="bi bi-geo-alt-fill text-primary me-3 mt-1"></i>
                    <div>
                        <p class="mb-0"><?php echo e($yayasan->alamat ?? 'Jl. KH. Wahid Hasyim No. 123'); ?></p>
                        <p class="mb-0">Yogyakarta, 55281</p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-telephone-fill text-primary me-3"></i>
                    <p class="mb-0">0811 2505 5675</p>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-envelope-fill text-primary me-3"></i>
                    <p class="mb-0">nuistnu@gmail.com</p>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <hr class="my-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">&copy; <?php echo e(date('Y')); ?> NUIST. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">Powered by <a href="#" class="text-primary text-decoration-none">Tim NUIST Developer</a></p>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary position-fixed" style="bottom: 20px; right: 20px; display: none; border-radius: 50%; width: 50px; height: 50px; z-index: 1050;">
    <i class="bi bi-arrow-up"></i>
</button>

<style>
    /* Reuse styles from landing.blade.php */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: #ffffff;
        color: #333;
        line-height: 1.6;
    }

    .container {
        max-width: 1500px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* NAVBAR */
    .navbar {
        background: rgb(255, 255, 255);
        backdrop-filter: blur(10px);
        position: fixed;
        top: 20px;
        width: 1400px;
        margin: 0 auto;
        z-index: 1000;
        border-radius: 50px;
        transition: background 0.3s ease, backdrop-filter 0.3s ease, width 0.3s ease, margin 0.3s ease, border-radius 0.3s ease, top 0.3s ease;
    }

    .navbar.transparent {
        background: rgb(255, 255, 255);
        backdrop-filter: blur(20px);
    }

    .navbar.full-width {
        width: 100%;
        left: 0;
        transform: none;
        border-radius: 0 0 28px 28px;
    }

    .navbar.scrolled {
        top: 0px;
    }

    .nav-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 80px;
        transition: justify-content 0.3s ease;
    }

    .nav-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .nav-menu {
        list-style: none;
        display: flex;
        gap: 20px;
        align-items: center;
        margin-top: 20px;
    }

    .nav-menu a {
        text-decoration: none;
        color: #004b4c;
        font-weight: 500;
        font-size: 18px;
        padding: 8px 16px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0);
        transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease, color 0.3s ease;
        transform: translateY(0) scale(1);
        box-shadow: 0 0 0 rgba(0, 75, 76, 0);
    }

    .nav-menu a:hover, .nav-menu a.active {
        color: #fefefe;
        background: linear-gradient(135deg, #004b4c, #006666);
    }

    .btn-primary {
        background: linear-gradient(135deg, #004b4c, #004b4c);
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
    }

    /* DROPDOWN SUBMENU */
    .dropdown {
        position: relative;
    }

    .dropdown:hover .submenu,
    .dropdown.open .submenu {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .arrow {
        display: inline-block;
        transition: transform 0.3s;
        transform: rotate(0deg);
        font-size: 20px;
        vertical-align: middle;
    }

    .dropdown:hover .arrow,
    .dropdown.open .arrow {
        transform: rotate(-180deg);
    }

    .submenu {
        position: absolute;
        top: 110%;
        left: 0;
        min-width: 240px;
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        padding: 12px;
        display: none;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease;
        z-index: 999;
    }

    .dropdown:hover .submenu,
    .dropdown.open .submenu {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .submenu li {
        list-style: none;
    }

    .submenu li a {
        display: block;
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 14px;
        color: #004b4c;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .submenu li a:hover {
        background: #f1f5ff;
        color: #eda711;
        padding-left: 18px;
    }

    /* HERO */
    .hero {
        padding: 120px 0 80px;
        color: white;
        text-align: center;
        background: linear-gradient(135deg, #00393a, #005555, #00393a);
        border-radius: 48px;
        max-width: 1600px;
        margin: 100px auto 0;
        min-height: 50vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .hero p {
        font-size: 20px;
        margin-bottom: 40px;
        opacity: 0.9;
        max-width: 720px;
        margin-left: auto;
        margin-right: auto;
    }

    /* SEKOLAH LIST */
    .sekolah-list {
        padding: 80px 0;
        background: #f8fafc;
    }

    .schools-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .school-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .school-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .school-link {
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .school-logo {
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        padding: 20px;
    }

    .school-logo img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    .school-info {
        padding: 20px;
        text-align: center;
    }

    .school-info h3 {
        font-size: 20px;
        font-weight: 600;
        color: #004b4c;
        margin-bottom: 8px;
    }

    .school-info p {
        color: #6b7280;
        font-size: 14px;
    }

    .section-title {
        text-align: center;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 60px;
        color: #004b4c;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        width: 0;
        height: 3px;
        background-color: #eda711;
        transition: width 0.3s ease, left 0.3s ease;
    }

    .section-title.active::after {
        width: 50%;
        left: 25%;
    }

    section:hover .section-title::after {
        width: 100%;
        left: 0;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .navbar {
            width: 95%;
            margin: 10px auto;
            padding: 0 15px;
        }

        .navbar.full-width {
            width: 100%;
            margin: 0;
            border-radius: 0;
        }

        .nav-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: #ffffff;
            flex-direction: column;
            gap: 0;
            padding: 20px 0;
            border-radius: 0 0 28px 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .nav-menu.show {
            display: flex;
        }

        .nav-menu a {
            padding: 15px 20px;
            border-radius: 0;
            text-align: center;
        }

        .hamburger {
            display: flex;
            flex-direction: column;
            cursor: pointer;
            gap: 4px;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: #004b4c;
            transition: 0.3s;
        }

        .hamburger.open span:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 6px);
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.open span:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -6px);
        }

        .hero {
            padding: 80px 20px;
            margin-top: 80px;
            min-height: auto;
        }

        .hero h1 {
            font-size: 32px;
        }

        .hero p {
            font-size: 16px;
        }

        .schools-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .school-card {
            margin: 0 10px;
        }
    }

    /* ANIMATION */
    .animate {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
    }

    .animate.show {
        opacity: 1;
        transform: translateY(0);
    }

    .fade-up {
        transform: translateY(30px);
    }

    .fade-up.delay-1 {
        transition-delay: 0.2s;
    }

    .fade-up.delay-2 {
        transition-delay: 0.4s;
    }
</style>

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

    function toggleSubmenu(e) {
        e.preventDefault();
        e.stopPropagation();

        const dropdown = e.target.closest('.dropdown');
        const isOpen = dropdown.classList.contains('open');

        document.querySelectorAll('.dropdown').forEach(drop => {
            drop.classList.remove('open');
        });

        if (!isOpen) {
            dropdown.classList.add('open');
        }
    }

    function toggleMobileMenu() {
        const navMenu = document.getElementById('nav-menu');
        const hamburger = document.getElementById('hamburger');
        navMenu.classList.toggle('show');
        hamburger.classList.toggle('open');
    }

    // Close submenu when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown').forEach(drop => {
                drop.classList.remove('open');
            });
        }

        if (!e.target.closest('.nav-left') && !e.target.closest('.hamburger')) {
            const navMenu = document.getElementById('nav-menu');
            const hamburger = document.getElementById('hamburger');
            if (navMenu && hamburger) {
                navMenu.classList.remove('show');
                hamburger.classList.remove('open');
            }
        }
    });

    // Navbar scroll effect
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.classList.add('transparent');
                    navbar.classList.add('full-width');
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('transparent');
                    navbar.classList.remove('full-width');
                    navbar.classList.remove('scrolled');
                }
                ticking = false;
            });
            ticking = true;
        }
    });

    // Section active on scroll and animation trigger
    document.addEventListener('DOMContentLoaded', function () {
        const animateElements = document.querySelectorAll('.animate');
        if (animateElements.length > 0) {
            const animateObserver = new IntersectionObserver(
                (entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('show');
                        }
                    });
                },
                {
                    threshold: 0.15
                }
            );

            animateElements.forEach(el => {
                if (el instanceof Element) {
                    animateObserver.observe(el);
                }
            });
        }
    });
</script>
<script>
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function smoothScrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
    }
}

function toggleSubmenu(e) {
    e.preventDefault();
    e.stopPropagation();

    const dropdown = e.target.closest('.dropdown');
    const isOpen = dropdown.classList.contains('open');

    document.querySelectorAll('.dropdown').forEach(drop => {
        drop.classList.remove('open');
    });

    if (!isOpen) {
        dropdown.classList.add('open');
    }
}

function toggleMobileMenu() {
    const navMenu = document.getElementById('nav-menu');
    const hamburger = document.getElementById('hamburger');
    navMenu.classList.toggle('show');
    hamburger.classList.toggle('open');
}

// Close submenu when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown').forEach(drop => {
            drop.classList.remove('open');
        });
    }

    // Close mobile menu when clicking outside
    if (!e.target.closest('.nav-left') && !e.target.closest('.hamburger')) {
        const navMenu = document.getElementById('nav-menu');
        const hamburger = document.getElementById('hamburger');
        if (navMenu && hamburger) {
            navMenu.classList.remove('show');
            hamburger.classList.remove('open');
        }
    }
});

// Navbar scroll effect
let ticking = false;
window.addEventListener('scroll', function() {
    if (!ticking) {
        requestAnimationFrame(function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('transparent');
                navbar.classList.add('full-width');
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('transparent');
                navbar.classList.remove('full-width');
                navbar.classList.remove('scrolled');
            }
            ticking = false;
        });
        ticking = true;
    }
});

// Custom Cursor Effect
document.addEventListener('DOMContentLoaded', function() {
    // Create cursor elements
    const cursorSmall = document.createElement('div');
    cursorSmall.className = 'cursor-small';
    document.body.appendChild(cursorSmall);

    const cursorLarge = document.createElement('div');
    cursorLarge.className = 'cursor-large';
    document.body.appendChild(cursorLarge);

    let mouseX = 0;
    let mouseY = 0;
    let cursorSmallX = 0;
    let cursorSmallY = 0;
    let cursorLargeX = 0;
    let cursorLargeY = 0;

    // Track mouse movement
    document.addEventListener('mousemove', function(e) {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    // Animate cursor positions
    function animateCursor() {
        // Smooth follow for small cursor
        cursorSmallX += (mouseX - cursorSmallX) * 0.2;
        cursorSmallY += (mouseY - cursorSmallY) * 0.2;

        // Slower follow for large cursor
        cursorLargeX += (mouseX - cursorLargeX) * 0.1;
        cursorLargeY += (mouseY - cursorLargeY) * 0.1;

        cursorSmall.style.left = cursorSmallX - 5 + 'px';
        cursorSmall.style.top = cursorSmallY - 5 + 'px';

        cursorLarge.style.left = cursorLargeX - 15 + 'px';
        cursorLarge.style.top = cursorLargeY - 15 + 'px';

        requestAnimationFrame(animateCursor);
    }

    animateCursor();

    // Hide cursors on mobile devices
    if ('ontouchstart' in window) {
        cursorSmall.style.display = 'none';
        cursorLarge.style.display = 'none';
    }
});

// Section active on scroll and animation trigger
document.addEventListener('DOMContentLoaded', function () {
    // Section titles observer
    const titles = document.querySelectorAll('.section-title');
    if (titles.length > 0) {
        const titleObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                } else {
                    entry.target.classList.remove('active');
                }
            });
        }, { threshold: 0.5 });

        titles.forEach(title => {
            if (title instanceof Element) {
                titleObserver.observe(title);
            }
        });
    }

    // Animation elements observer
    const animateElements = document.querySelectorAll('.animate');
    if (animateElements.length > 0) {
        const animateObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show');
                    }
                });
            },
            {
                threshold: 0.15
            }
        );

        animateElements.forEach(el => {
            if (el instanceof Element) {
                animateObserver.observe(el);
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/sekolah.blade.php ENDPATH**/ ?>