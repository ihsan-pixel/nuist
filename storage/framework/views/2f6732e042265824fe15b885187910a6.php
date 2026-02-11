<?php $__env->startSection('title', 'Dashboard MGMP - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('mgmp.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* HERO SECTION */
    .hero {
        position: relative;
        margin-top: 100px;
        margin-bottom: 40px;
        padding: 60px 40px;
        background: linear-gradient(135deg, #00393a 0%, #005555 50%, #00393a 100%);
        border-radius: 24px;
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
        min-height: 35vh;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
        box-shadow: 0 20px 60px rgba(0, 57, 58, 0.3);
    }

    .hero-content {
        flex: 1;
    }

    .hero h1 {
        font-size: 42px;
        font-weight: 800;
        color: white;
        margin-bottom: 10px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .hero-welcome {
        font-size: 18px;
        color: rgba(255,255,255,0.9);
        margin-bottom: 20px;
    }

    .hero p {
        font-size: 16px;
        color: rgba(255,255,255,0.85);
        max-width: 500px;
        line-height: 1.7;
    }

    .hero-stats {
        display: flex;
        gap: 30px;
    }

    .hero-stat {
        text-align: center;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        padding: 25px 35px;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .hero-stat-number {
        font-size: 36px;
        font-weight: 800;
        color: #eda711;
        display: block;
    }

    .hero-stat-label {
        font-size: 14px;
        color: rgba(255,255,255,0.9);
        margin-top: 5px;
    }

    /* SECTION */
    .section {
        padding: 60px 0;
    }

    .section-title {
        font-size: 28px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 10px;
    }

    .section-subtitle {
        font-size: 15px;
        color: #64748b;
        margin-bottom: 40px;
    }

    /* STATS CARDS */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        max-width: 1400px;
        margin: 0 auto 40px;
        padding: 0 20px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 28px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.08);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .stat-icon.blue {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .stat-icon.green {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .stat-icon.orange {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .stat-icon.purple {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
    }

    .stat-info h3 {
        font-size: 28px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .stat-info p {
        font-size: 14px;
        color: #64748b;
    }

    /* MENU GRID */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .menu-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        text-decoration: none;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        display: block;
    }

    .menu-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.08);
        border-color: #004b4c;
    }

    .menu-card-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: linear-gradient(135deg, #004b4c, #006666);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: white;
        margin-bottom: 20px;
    }

    .menu-card h3 {
        font-size: 20px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 10px;
    }

    .menu-card p {
        font-size: 14px;
        color: #64748b;
        line-height: 1.6;
    }

    .menu-card-arrow {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 24px;
        color: #004b4c;
        opacity: 0;
        transition: all 0.3s ease;
    }

    /* ACTIVITY TABLE */
    .activity-section {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .activity-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid #e5e7eb;
    }

    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .activity-header h3 {
        font-size: 20px;
        font-weight: 600;
        color: #0f172a;
    }

    .activity-table {
        width: 100%;
        border-collapse: collapse;
    }

    .activity-table th {
        text-align: left;
        padding: 14px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #f8fafc;
        border-radius: 8px;
    }

    .activity-table td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #334155;
    }

    .activity-table tr:hover td {
        background: #f8fafc;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.completed {
        background: #dcfce7;
        color: #16a34a;
    }

    .status-badge.ongoing {
        background: #dbeafe;
        color: #2563eb;
    }

    .status-badge.upcoming {
        background: #fef3c7;
        color: #d97706;
    }

    /* ANIMATION */
    .animate {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease;
    }

    .animate.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* RESPONSIVE */
    @media (max-width: 1024px) {
        .hero {
            flex-direction: column;
            text-align: center;
            padding: 40px 30px;
        }

        .hero p {
            margin: 0 auto 30px;
        }

        .hero-stats {
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .hero {
            margin-top: 80px;
            padding: 30px 20px;
            border-radius: 20px;
        }

        .hero h1 {
            font-size: 28px;
        }

        .hero-stats {
            flex-direction: column;
            gap: 15px;
        }

        .hero-stat {
            padding: 20px 25px;
        }

        .stat-card {
            flex-direction: column;
            text-align: center;
        }

        .section-title {
            font-size: 22px;
        }

        .menu-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .hero h1 {
            font-size: 24px;
        }

        .hero-welcome {
            font-size: 16px;
        }
    }
</style>

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-content animate fade-up">
        <h1>Dashboard MGMP</h1>
        <p class="hero-welcome">Selamat Datang, <strong><?php echo e($user->name); ?></strong></p>
        <p>Kelola dan pantau kegiatan Musyawarah Guru Mata Pelajaran di LP Ma'arif NU DIY dengan mudah dan efisien.</p>
    </div>
    <div class="hero-stats animate fade-up delay-1">
        <div class="hero-stat">
            <span class="hero-stat-number"><?php echo e($totalAnggota); ?></span>
            <span class="hero-stat-label">Total Anggota</span>
        </div>
        <div class="hero-stat">
            <span class="hero-stat-number"><?php echo e($anggotaAktif); ?></span>
            <span class="hero-stat-label">Anggota Aktif</span>
        </div>
    </div>
</section>

<!-- STATS CARDS -->
<div class="stats-grid">
    <div class="stat-card animate fade-up delay-1">
        <div class="stat-icon blue">
            <i class='bx bx-group'></i>
        </div>
        <div class="stat-info">
            <h3><?php echo e($totalAnggota); ?></h3>
            <p>Total Anggota MGMP</p>
        </div>
    </div>

    <div class="stat-card animate fade-up delay-2">
        <div class="stat-icon green">
            <i class='bx bx-check-circle'></i>
        </div>
        <div class="stat-info">
            <h3><?php echo e($anggotaAktif); ?></h3>
            <p>Anggota Aktif</p>
        </div>
    </div>

    <div class="stat-card animate fade-up delay-3">
        <div class="stat-icon orange">
            <i class='bx bx-calendar-event'></i>
        </div>
        <div class="stat-info">
            <h3>12</h3>
            <p>Kegiatan Bulan Ini</p>
        </div>
    </div>

    <div class="stat-card animate fade-up delay-4">
        <div class="stat-icon purple">
            <i class='bx bx-file-blank'></i>
        </div>
        <div class="stat-info">
            <h3>48</h3>
            <p>Laporan Dibuat</p>
        </div>
    </div>
</div>

<!-- MENU GRID -->
<section class="section">
    <div class="container">
        <h2 class="section-title animate fade-up">Menu Utama</h2>
        <p class="section-subtitle animate fade-up">Akses cepat ke fitur-fitur penting MGMP</p>

        <div class="menu-grid">
            <a href="<?php echo e(route('mgmp.data-anggota')); ?>" class="menu-card animate fade-up delay-1">
                <div class="menu-card-icon">
                    <i class='bx bx-group'></i>
                </div>
                <h3>Data Anggota</h3>
                <p>Kelola dan lihat data anggota MGMP, termasuk informasi kontak dan status keanggotaan.</p>
            </a>

            <a href="<?php echo e(route('mgmp.laporan')); ?>" class="menu-card animate fade-up delay-2">
                <div class="menu-card-icon">
                    <i class='bx bx-file-blank'></i>
                </div>
                <h3>Laporan Kegiatan</h3>
                <p>Buat dan kelola laporan kegiatan MGMP, termasuk workshop, seminar, dan pelatihan.</p>
            </a>

            <a href="#" class="menu-card animate fade-up delay-3">
                <div class="menu-card-icon">
                    <i class='bx bx-calendar'></i>
                </div>
                <h3>Jadwal MGMP</h3>
                <p>Lihat dan kelola jadwal kegiatan MGMP secara terstruktur dan terintegrasi.</p>
            </a>

            <a href="#" class="menu-card animate fade-up delay-4">
                <div class="menu-card-icon">
                    <i class='bx bx-bar-chart-alt-2'></i>
                </div>
                <h3>Statistik</h3>
                <p>Lihat statistik dan analisis data MGMP secara real-time dan akurat.</p>
            </a>
        </div>
    </div>
</section>

<!-- RECENT ACTIVITY -->
<section class="section activity-section">
    <div class="activity-card animate fade-up">
        <div class="activity-header">
            <h3>Kegiatan Terbaru</h3>
            <a href="<?php echo e(route('mgmp.laporan')); ?>" style="color: #004b4c; font-weight: 600; font-size: 14px; text-decoration: none;">
                Lihat Semua <i class='bx bx-arrow-right'></i>
            </a>
        </div>
        <table class="activity-table">
            <thead>
                <tr>
                    <th>Kegiatan</th>
                    <th>Tanggal</th>
                    <th>Peserta</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Workshop Pembelajaran Matematika</td>
                    <td>15 Januari 2025</td>
                    <td>25 Guru</td>
                    <td><span class="status-badge completed">Selesai</span></td>
                </tr>
                <tr>
                    <td>Seminar Bahasa Indonesia</td>
                    <td>18 Januari 2025</td>
                    <td>30 Guru</td>
                    <td><span class="status-badge completed">Selesai</span></td>
                </tr>
                <tr>
                    <td>Pelatihan IPA Terpadu</td>
                    <td>22 Januari 2025</td>
                    <td>28 Guru</td>
                    <td><span class="status-badge ongoing">Berlangsung</span></td>
                </tr>
                <tr>
                    <td>Diskusi Kurikulum 2025</td>
                    <td>25 Januari 2025</td>
                    <td>20 Guru</td>
                    <td><span class="status-badge upcoming">Akan Datang</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const animatedElements = document.querySelectorAll(".animate");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("show");
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    });

    animatedElements.forEach(el => observer.observe(el));
});
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mgmp/dashboard.blade.php ENDPATH**/ ?>