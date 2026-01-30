<?php $__env->startSection('title', $madrasah->name . ' - NUIST'); ?>
<?php $__env->startSection('description', 'Profil ' . $madrasah->name . ' Dibawah Naungan LPMNU PWNU DIY'); ?>

<?php $__env->startSection('content'); ?>
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

    /* School Header Grid */
    .school-header-grid {
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 40px;
        align-items: center;
        margin-top: 20px;
    }

    .kepala-sekolah-photo {
        display: flex;
        justify-content: center;
    }

    .ks-photo-img {
        width: 160px;
        height: 180px;
        object-fit: cover;
        border-radius: 12px;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
    }

    .ks-photo-placeholder {
        width: 160px;
        height: 180px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid rgba(255, 255, 255, 0.3);
    }

    .ks-photo-placeholder i {
        font-size: 60px;
        color: rgba(255, 255, 255, 0.6);
    }

    .school-info {
        text-align: left;
    }

    .school-info-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .school-logo-small {
        width: 60px;
        height: 60px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        flex-shrink: 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .school-logo-small img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .school-name {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 20px;
        text-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
        line-height: 1.2;
    }

    .school-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 25px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        opacity: 0.95;
    }

    .meta-item i {
        font-size: 18px;
        color: #eda711;
    }

    .meta-item a:hover {
        text-decoration: underline !important;
    }

    .kepala-sekolah-info {
        display: inline-flex;
        flex-direction: column;
        background: rgba(255, 255, 255, 0.15);
        padding: 15px 25px;
        border-radius: 12px;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .ks-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.8;
        margin-bottom: 5px;
    }

    .ks-name {
        font-size: 18px;
        font-weight: 700;
    }

    /* Stats Section Wrapper */
    .stats-section-wrapper {
        max-width: 1400px;
        margin: -40px auto 50px;
        padding: 0 40px;
        position: relative;
        z-index: 10;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 20px;
        background: white;
        padding: 25px 30px;
        border-radius: 18px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon i {
        font-size: 28px;
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

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
    }

    /* ACTION BUTTONS */
    .action-section {
        margin-top: 50px;
    }

    .action-buttons {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 18px 36px;
        background: linear-gradient(135deg, #00393a, #005555, #004b4c);
        color: white;
        text-decoration: none;
        border-radius: 16px;
        font-weight: 700;
        font-size: 17px;
        transition: all 0.3s ease;
        box-shadow: 0 6px 25px rgba(0, 75, 76, 0.35);
        border: none;
        cursor: pointer;
    }

    .btn-action:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 12px 40px rgba(0, 75, 76, 0.45);
        background: linear-gradient(135deg, #004b4c, #006666, #005555);
    }

    .btn-action.secondary {
        background: white;
        color: #004b4c;
        border: 2px solid #004b4c;
        box-shadow: none;
    }

    .btn-action.secondary:hover {
        background: #f0fdf4;
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 75, 76, 0.15);
    }

    .btn-action i {
        font-size: 22px;
    }

    /* STATS SECTION */
    .stats-section {
        background: white;
        border-radius: 24px;
        padding: 40px;
        margin-top: 50px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        text-align: center;
    }

    .stat-item {
        padding: 20px;
    }

    .stat-number {
        font-size: 42px;
        font-weight: 800;
        background: linear-gradient(135deg, #00393a, #005555);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
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

        /* School Header Grid Mobile */
        .school-header-grid {
            grid-template-columns: 1fr;
            gap: 25px;
            text-align: center;
        }

        .kepala-sekolah-photo {
            margin-bottom: 10px;
        }

        .ks-photo-img {
            width: 140px;
            height: 160px;
        }

        .ks-photo-placeholder {
            width: 140px;
            height: 160px;
        }

        .ks-photo-placeholder i {
            font-size: 50px;
        }

        .school-info {
            text-align: center;
        }

        .school-info-header {
            flex-direction: column;
            gap: 12px;
        }

        .school-logo-small {
            width: 55px;
            height: 55px;
        }

        .school-name {
            font-size: 26px;
        }

        .school-meta {
            justify-content: center;
        }

        .meta-item {
            width: 100%;
            justify-content: center;
        }

        .kepala-sekolah-info {
            width: 100%;
            text-align: center;
        }

        /* Stats Mobile */
        .stats-section-wrapper {
            padding: 0 20px;
            margin-top: -30px;
        }

        .stats-container {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-number {
            font-size: 28px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
        }

        .stat-icon i {
            font-size: 24px;
        }

        .content {
            padding: 30px 20px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
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
            <a href="<?php echo e(route('landing.sekolah')); ?>" class="back-btn">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Sekolah
            </a>
        </div>

        <div class="school-header-grid">
            <!-- Foto Kepala Sekolah -->
            <div class="kepala-sekolah-photo">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->kepala_sekolah_foto): ?>
                    <img src="<?php echo e(asset('storage/' . $madrasah->kepala_sekolah_foto)); ?>" alt="Foto Kepala Sekolah" class="ks-photo-img">
                <?php else: ?>
                    <div class="ks-photo-placeholder">
                        <i class="bi bi-person-fill"></i>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Info Sekolah & Kepala Sekolah -->
            <div class="school-info">
                <div class="school-info-header">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->logo): ?>
                    <div class="school-logo-small">
                        <img src="<?php echo e(asset('storage/' . $madrasah->logo)); ?>" alt="Logo <?php echo e($madrasah->name); ?>">
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <h1 class="school-name"><?php echo e($madrasah->name); ?></h1>
                </div>

                <div class="school-meta">
                    <div class="meta-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span><?php echo e($madrasah->alamat ?? 'Belum ada data alamat'); ?></span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->akreditasi): ?>
                    <div class="meta-item">
                        <i class="bi bi-patch-check-fill"></i>
                        <span>Akreditasi: <?php echo e($madrasah->akreditasi); ?></span>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->telepon): ?>
                    <div class="meta-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span><?php echo e($madrasah->telepon); ?></span>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->email): ?>
                    <div class="meta-item">
                        <i class="bi bi-envelope-fill"></i>
                        <span><?php echo e($madrasah->email); ?></span>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->website): ?>
                    <div class="meta-item">
                        <i class="bi bi-globe"></i>
                        <a href="<?php echo e($madrasah->website); ?>" target="_blank" style="color: white; text-decoration: none;"><?php echo e($madrasah->website); ?></a>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->kepala_sekolah_nama): ?>
                <div class="kepala-sekolah-info">
                    <span class="ks-label">Kepala Sekolah</span>
                    <span class="ks-name"><?php echo e($madrasah->kepala_sekolah_gelar ?? ''); ?> <?php echo e($madrasah->kepala_sekolah_nama); ?></span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- STATS SECTION -->
<section class="stats-section-wrapper">
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon guru">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo e($madrasah->jumlah_guru ?? ($ppdbSetting->jumlah_guru ?? '-')); ?></div>
                <div class="stat-label">Jumlah Guru</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon siswa">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo e($madrasah->jumlah_siswa ?? ($ppdbSetting->jumlah_siswa ?? '-')); ?></div>
                <div class="stat-label">Jumlah Siswa</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon jurusan">
                <i class="bi bi-book-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo e($madrasah->jumlah_jurusan ?? ($ppdbSetting->jumlah_jurusan ?? '-')); ?></div>
                <div class="stat-label">Jumlah Jurusan</div>
            </div>
        </div>
    </div>
</section>

<!-- CONTENT -->
<section class="content">
    <h2 class="section-title">Informasi Sekolah</h2>

    <div class="info-grid">
        <div class="info-card">
            <div class="info-icon">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Alamat</div>
                <div class="info-value" style="text-align: left;"><?php echo e($madrasah->alamat ?? 'Belum ada data alamat'); ?></div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-icon">
                <i class="bi bi-award-fill"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Kode SCOD</div>
                <div class="info-value" style="text-align: left;"><?php echo e($madrasah->scod ?? '-'); ?></div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-icon">
                <i class="bi bi-envelope-fill"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Email</div>
                <div class="info-value" style="text-align: left;"><?php echo e($madrasah->email ?? '-'); ?></div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-icon">
                <i class="bi bi-telephone-fill"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Telepon</div>
                <div class="info-value" style="text-align: left;"><?php echo e($madrasah->telepon ?? '-'); ?></div>
            </div>
        </div>
    </div>
</section>

<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/sekolah-detail.blade.php ENDPATH**/ ?>