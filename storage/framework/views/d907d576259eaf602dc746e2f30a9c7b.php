<?php $__env->startSection('title', 'Home'); ?>
<?php $__env->startSection('description', 'Sistem Informasi Digital LPMNU PWNU DIY'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('landing.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- HERO -->
<section id="home" class="hero">
    <div class="container">
        <img src="<?php echo e(asset('images/image 1.png')); ?>" alt="Hero Image 1" class="hero-image animate zoom-soft" style="height: 100px; margin-top: 0px; margin-bottom: 50px;">
        <h1 class="hero-title animate fade-up"">
            Nuist - Sistem Informasi Digital
        </h1>
        <h1 class="hero-subtitle animate fade-up delay-1" style="color: #eda711">LP. Ma'arif NU PWNU DIY</h1>
        <p class="animate fade-up delay-2"><?php echo e($landing->content_hero ?? 'Kelola data kelembagaan, aktivitas, sistem informasi dan layanan dalam satu aplikasi yang modern, aman, dan mudah digunakan.'); ?></p>
         <img src="<?php echo e(asset('images/image 2.png')); ?>" alt="Hero Image 2" class="hero-image animate zoom-soft delay-3" style="height: 500px; margin-top: 0px; margin-bottom: -350px;">
    </div>
</section>

<!-- SEKOLAH/MADRASAH -->
<section id="sekolah" class="sekolah">
        <h2 class="section-title animate fade-up" style="font-size: 24px;">Sekolah/Madrasah dessous naungan kami</h2>
        <div class="carousel-container animate fade-up delay-1">
            <div class="carousel-track">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="madrasah-item">
                        <img src="<?php echo e(asset('storage/' . $madrasah->logo)); ?>" alt="<?php echo e($madrasah->name); ?>">
                        <p><?php echo e($madrasah->name); ?></p>
                        <p><?php echo e($madrasah->kabupaten); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="madrasah-item">
                        <img src="<?php echo e(asset('storage/' . $madrasah->logo)); ?>" alt="<?php echo e($madrasah->name); ?>">
                        <p><?php echo e($madrasah->name); ?></p>
                        <p><?php echo e($madrasah->kabupaten); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
</section>

<!-- PROFILE -->
<section id="about" class="profile">
    <h2 class="section-title animate fade-up" style="font-size:24px;">Profile Nuist</h2>
    <div class="container profile-flex animate fade-up delay-1" style="width: 100%;">
        <div class="profile-content" style="text-align: center;">
            <p>Nuist menghadirkan ekosistem aplikasi terintegrasi yang dirancang untuk mendukung pengelolaan administrasi sekolah secara menyeluruh. Melalui Nuist Desktop dan Nuist Mobile, sekolah dapat mengelola data, aktivitas, dan kehadiran secara terpusat, akurat, serta mudah diakses oleh administrator, tenaga pendidik, dan kepala sekolah dalam satu sistem yang saling terhubung.</p>
        </div>
    </div>
    <div class="container profile-flex animate fade-up delay-2" style="margin-top: 50px;">
            <img src="<?php echo e(asset('images/image 3.png')); ?>" alt="Profile Image 1" class="hero-image animate fade-left delay-1" style="height: 230px; margin-top: 0px; margin-bottom: -0px;">
            <img src="<?php echo e(asset('images/image 4.png')); ?>" alt="Profile Image 2" class="hero-image animate fade-right delay-2" style="height: 230px; margin-top: 0px; margin-bottom: -0px;">
    </div>
    <div class="container profile-flex animate fade-up delay-3" style="margin-top: 50px;">
        <div class="profile-content animate fade-left">
            <h2 class="title-with-dot"><span class="dot"></span>Nuist Dekstop</h2>
            <p><?php echo e($landing->content_2_profile ?? 'Aplikasi khusus untuk administrator sekolah dalam mengelola data sekolah dan data tenaga pendidik secara terpusat, aman, dan efisien. Dirancang untuk mendukung kebutuhan administrasi modern, Nuist Desktop membantu menyederhanakan pengelolaan data, meningkatkan akurasi informasi, serta mendukung pengambilan keputusan berbasis data.'); ?></p>
        </div>
        <div class="profile-content animate fade-right delay-1">
            <h2 class="title-with-dot"><span class="dot"></span>Nuist Mobile</h2>
            <p><?php echo e($landing->content_3_profile ?? 'Aplikasi berbasis mobile yang dirancang khusus untuk tenaga pendidik dan kepala sekolah dalam melakukan presensi, presensi mengajar, pengajuan izin, serta penyesuaian data pribadi secara praktis dan real-time. Aplikasi ini mendukung kemudahan akses, akurasi data, dan efisiensi administrasi dalam satu platform terpadu.'); ?></p>
        </div>
    </div>
    <div class="container profile-flex animate fade-up delay-1" style="margin-top: 50px; justify-content: center;">
        <div class="profile-content animate fade-up delay-1" style="text-align: center">
            <h1 id="count1" style="text-align:center; background: linear-gradient(135deg, #004b4c, #006666); color: white; padding: 12px 24px; border-radius: 50px; display: inline-block;">36</h1>
            <p>Sekolah/Madrasah</p>
        </div>
        <div class="profile-content animate fade-up delay-2" style="text-align: center">
            <h1 id="count2" style="text-align: center; background: linear-gradient(135deg, #004b4c, #006666); color: white; padding: 12px 24px; border-radius: 50px; display: inline-block;">750+</h1>
            <P>Tenaga Pendidik Aktif</P>
        </div>
        <div class="profile-content animate fade-up delay-3" style="text-align: center">
            <h1 id="count3" style="text-align: center; background: linear-gradient(135deg, #004b4c, #006666); color: white; padding: 12px 24px; border-radius: 50px; display: inline-block;">36</h1>
            <p>Admin Operator Aktif</p>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section id="features" class="features">
    <h2 class="section-title animate fade-up" style="color:aliceblue; margin-top: -60px; font-size: 24px;">Fitur Unggulan</h2>
    <p class="section-description animate fade-up delay-1" style="color:aliceblue; margin-top:60px;">Berbagai fitur canggih yang dirancang untuk memaksimalkan efisiensi dan keamanan dalam pengelolaan sekolah Anda.</p>
    <div class="grid animate fade-up delay-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($landing->features): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $landing->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card animate fade-up <?php echo e($feature['status'] == 'coming_soon' ? 'coming-soon' : ''); ?>" style="animation-delay: <?php echo e(0.3 + ($index * 0.1)); ?>s;">
            
            <h3><?php echo e($feature['name']); ?></h3>
            <p><?php echo e($feature['content']); ?></p>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($feature['status'] == 'coming_soon'): ?>
            <div class="status-ribbon">Coming Soon</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php else: ?>
        <div class="card animate fade-up delay-3">
            <div class="card-icon">⚡</div>
            <h3>Performa Tinggi</h3>
            <p>Website loading cepat dengan optimasi SEO otomatis untuk meningkatkan visibilitas.</p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>

<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
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

    /* HERO */
    .hero {
        position: relative;
        margin-top: 65px;
        margin-bottom: 40px;
        padding: 60px 20px 160px;
        background: linear-gradient(135deg, #00393a, #005555, #00393a);
        border-radius: 48px;
        max-width: 1600px;
        margin-left: auto;
        margin-right: auto;
        min-height: 87vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    /* .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    } */

    .hero h1 {
        font-size: 56px;
        font-weight: 800;
        line-height: 1.15;
        color: white;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .hero-subtitle {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #eda711;
    }

    .hero p {
        font-size: 18px;
        max-width: 720px;
        margin: 0 auto 40px;
        opacity: 0.9;
        color: white;
    }

    .hero-image {
        transition: transform 0.3s ease, filter 0.3s ease;
        cursor: pointer;
    }

    .hero-image:hover {
        transform: scale(1.05);
        filter: brightness(1.1);
    }

    /* FEATURES */
    .features {
        position: relative;
        background: linear-gradient(135deg, #00393a, #005555, #00393a);
        border-radius: 48px;
        max-width: 1600px;
        margin: 80px auto;
        overflow: hidden;
        padding: 120px 0 120px;
    }

    /* .features::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    } */

    .section-description {
        text-align: center;
        font-size: 18px;
        color: #6b7280;
        max-width: 800px;
        margin: 0 auto 80px;
        line-height: 1.6;
        opacity: 0.9;
    }

    .section-title {
        text-align: center;
        font-size: 24px;
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

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 32px;
        max-width: 1400px;
        margin: auto;
    }

    .card {
        position: relative;
        padding: 40px;
        border-radius: 28px;
        border: 2px solid #eee;
        background: #fff;
        transition: all 0.3s ease;
        text-align: center;
    }

    .card.active {
        border-color: #7c3aed;
    }

    .card-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: linear-gradient(135deg, #9333ea, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        font-size: 28px;
        color: white;
    }

    .card h3 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 12px;
        color: #111827;
    }

    .card p {
        font-size: 16px;
        line-height: 1.6;
        color: #6b7280;
        max-width: 320px;
        margin: 0 auto;
    }

    .status-ribbon {
        position: absolute;
        top: -3px;
        right: -20px;
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        color: white;
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transform: rotate(30deg);
        box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
        z-index: 10;
        border-radius: 4px;
    }

    /* PROFILE */
    .profile {
        padding: 80px 0;
        background: #ffffff;
        margin-top: -100px;
    }

    .profile-flex {
        display: flex;
        align-items: center;
        gap: 60px;
    }

    .profile-content h2 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #004b4c;
    }

    .profile-content h1 {
        font-size: 64px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #000000;
    }

    .profile-content p {
        font-size: 18px;
        color: #6d6b7b;
        margin-bottom: 30px;
        margin-top: 60px;
    }

    .title-with-dot {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 36px;
        font-weight: 800;
        color: #004b4c;
        letter-spacing: 1px;
    }

    .title-with-dot .dot {
        width: 18px;
        height: 18px;
        background: linear-gradient(135deg, #004b4c, #006666);
        border-radius: 50%;
        flex-shrink: 0;
    }

    /* SEKOLAH/MADRASAH CAROUSEL */
    .sekolah {
        padding: 80px 0;
        background: #ffffff;
        margin-top: 150px;
    }

    .carousel-container {
        overflow: hidden;
        width: 1600px;
        margin: 80px auto 0;
        display: flex;
        justify-content: center;
    }

    .carousel-track {
        display: flex;
        width: max-content;
        animation: infiniteScroll 100s linear infinite;
        justify-content: center;
    }

    .carousel-track img {
        height: 75px;
        width: 150px;
        object-fit: contain;
        flex-shrink: 0;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }

    .carousel-track img:hover {
        transform: scale(1.05);
    }

    .madrasah-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-right: 40px;
        flex-shrink: 0;
        min-height: 140px;
        gap: 5px;
    }

    .madrasah-item p {
        font-size: 12px;
        margin: 0;
        text-align: center;
        line-height: 1.3;
        max-width: 150px;
        word-wrap: break-word;
        color: #333;
    }

    @keyframes infiniteScroll {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }

    .carousel-track:hover,
    .madrasah-item:hover {
        animation-play-state: paused;
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

    .fade-left {
        transform: translateX(-40px);
    }

    .fade-right {
        transform: translateX(40px);
    }

    .zoom-soft {
        transform: scale(0.9);
    }

    .zoom-soft.show {
        transform: scale(1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero {
            padding: 40px 20px 120px;
            min-height: auto;
            margin-top: -10px;
        }

        .hero-title {
            font-size: 28px;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 24px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 16px;
            margin-bottom: 30px;
        }

        .hero-image {
            height: 80px !important;
            margin-bottom: 30px;
        }

        .hero .hero-image:nth-child(2) {
            height: 400px !important;
            margin-bottom: -300px;
        }

        .sekolah {
            padding: 60px 0;
            margin-top: 100px;
        }

        .carousel-container {
            margin-top: 40px;
        }

        .carousel-track {
            animation-duration: 25s;
        }

        .madrasah-item {
            margin-right: 20px;
            min-height: 120px;
        }

        .madrasah-item img {
            height: 60px;
            width: 120px;
        }

        .madrasah-item p {
            font-size: 11px;
            max-width: 120px;
        }

        .profile {
            padding: 60px 0;
            margin-top: -80px;
        }

        .profile-flex {
            flex-direction: column;
            gap: 40px;
        }

        .profile-content h2 {
            font-size: 28px;
        }

        .profile-content h1 {
            font-size: 48px;
        }

        .profile-content p {
            font-size: 16px;
        }

        .title-with-dot {
            font-size: 28px;
        }

        .features {
            padding: 80px 0;
        }

        .section-title {
            font-size: 20px;
            margin-bottom: 40px;
        }

        .section-description {
            font-size: 16px;
            margin-bottom: 60px;
        }

        .grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .card {
            padding: 30px 20px;
        }

        .card h3 {
            font-size: 20px;
        }

        .card p {
            font-size: 15px;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 0 15px;
        }

        .hero h1 {
            font-size: 28px;
        }

        .hero p {
            font-size: 15px;
        }

        .hero-image {
            height: 60px !important;
        }

        .hero .hero-image:nth-child(2) {
            height: 300px !important;
            margin-bottom: -250px;
        }

        .profile-content h1 {
            font-size: 36px;
        }

        .title-with-dot {
            font-size: 24px;
        }

        .card {
            padding: 25px 15px;
        }
    }

    /* Custom Cursor Effect */
    .cursor-small {
        position: fixed;
        width: 10px;
        height: 10px;
        background-color: #00ff00;
        border-radius: 50%;
        pointer-events: none;
        z-index: 9999;
        transition: transform 0.1s ease;
    }

    .cursor-large {
        position: fixed;
        width: 30px;
        height: 30px;
        background-color: #00ff88;
        border-radius: 50%;
        pointer-events: none;
        z-index: 9998;
        transition: transform 0.15s ease;
        opacity: 0.5;
    }
</style>

<script>
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

    // Custom Cursor Effect
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
</script>


<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/landing.blade.php ENDPATH**/ ?>