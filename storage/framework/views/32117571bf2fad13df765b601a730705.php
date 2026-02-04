<?php $__env->startSection('title', 'Detail Sekolah'); ?>
<?php $__env->startSection('subtitle', $madrasah->name); ?>

<?php $__env->startSection('content'); ?>

<header class="mobile-header d-md-none" style="position: sticky; top: 0; z-index: 1050;">
    <div class="container-fluid px-0 py-0" style="background: transparent;">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Back Button -->
            <a href="<?php echo e(route('mobile.pengurus.sekolah')); ?>" class="btn btn-link text-decoration-none p-0 me-2">
                <i class="bx bx-arrow-back" style="font-size: 22px; color: #000000;"></i>
            </a>
        </div>
    </div>
</header>

<div class="container py-3" style="max-width: 520px; margin: auto;">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            background-color: #f8f9fb;
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: linear-gradient(to bottom, rgba(248,249,251,0), #f8f9fb);
            z-index: -1;
        }

        .mobile-header,
        .mobile-header .container-fluid {
            background: transparent !important;
        }

        .mobile-header {
            box-shadow: none !important;
            border: none !important;
        }

        .profile-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border-radius: 16px;
            padding: 20px;
            color: white;
            margin-bottom: 16px;
            box-shadow: 0 4px 16px rgba(0, 75, 76, 0.3);
        }

        .profile-logo {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            object-fit: cover;
            background: rgba(255, 255, 255, 0.2);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .profile-school-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .profile-school-code {
            font-size: 12px;
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
        }

        .info-card {
            background: #fff;
            border-radius: 12px;
            padding: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 12px;
        }

        .info-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-card-header i {
            font-size: 20px;
            color: #004b4c;
            margin-right: 10px;
        }

        .info-card-header h6 {
            font-size: 14px;
            font-weight: 600;
            color: #004b4c;
            margin: 0;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 12px;
            color: #6c757d;
            width: 110px;
            flex-shrink: 0;
        }

        .info-value {
            font-size: 12px;
            color: #212529;
            font-weight: 500;
            word-break: break-word;
        }

        .info-value a {
            color: #004b4c;
            text-decoration: none;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }

        .stat-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 10px 6px;
            text-align: center;
        }

        .stat-item h4 {
            font-size: 16px;
            font-weight: 700;
            color: #004b4c;
            margin-bottom: 2px;
        }

        .stat-item small {
            font-size: 9px;
            color: #6c757d;
            display: block;
            line-height: 1.2;
        }

        .badge-info {
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .badge-green {
            background: #d4edda;
            color: #155724;
        }

        .badge-blue {
            background: #cce5ff;
            color: #004085;
        }

        .empty-value {
            color: #adb5bd;
            font-style: italic;
        }

        .section-divider {
            height: 1px;
            background: #e9ecef;
            margin: 16px 0;
        }

        .list-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-bullet {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #004b4c;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .nav-menu-card {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 12px;
        }

        .nav-menu-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }

        .nav-menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 12px 8px;
            border-radius: 10px;
            background: #f8f9fa;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .nav-menu-item:hover {
            background: #e9ecef;
        }

        .nav-menu-item.active {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border-color: #004b4c;
        }

        .nav-menu-item.active i,
        .nav-menu-item.active span {
            color: white !important;
        }

        .nav-menu-item i {
            font-size: 22px;
            margin-bottom: 6px;
            transition: color 0.2s ease;
        }

        .nav-menu-item span {
            font-size: 10px;
            font-weight: 600;
            color: #004b4c;
            text-align: center;
            transition: color 0.2s ease;
        }

        .nav-menu-item .badge-count {
            position: absolute;
            top: 4px;
            right: 4px;
            background: #dc3545;
            color: white;
            font-size: 8px;
            padding: 2px 5px;
            border-radius: 8px;
        }

        .nav-menu-item {
            position: relative;
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .avatar-sm {
            width: 45px;
            height: 45px;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            font-size: 16px;
            font-weight: 600;
        }
    </style>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->logo): ?>
                <img
                    src="<?php echo e(asset('storage/' . $madrasah->logo)); ?>"
                    alt="<?php echo e($madrasah->name); ?>"
                    class="profile-logo"
                    onerror="this.src='<?php echo e(asset('build/images/logo-light.png')); ?>'"
                >
                <?php else: ?>
                <img
                    src="<?php echo e(asset('build/images/logo-light.png')); ?>"
                    alt="<?php echo e($madrasah->name); ?>"
                    class="profile-logo"
                >
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="grow">
                <h4 class="profile-school-name mb-2"><?php echo e($madrasah->name); ?></h4>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->scod): ?>
                <span class="profile-school-code"><?php echo e($madrasah->scod); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="info-card">
        <div class="stat-grid">
            <div class="stat-item">
                <h4><?php echo e(number_format($dataSekolah->jumlah_siswa ?? 0)); ?></h4>
                <small>Siswa <?php echo e($dataSekolah ? '(' . $dataSekolah->tahun . ')' : ''); ?></small>
            </div>
            <div class="stat-item">
                <h4><?php echo e(number_format($jumlahGuru)); ?></h4>
                <small>Guru <?php echo e($dataSekolah ? '(' . $dataSekolah->tahun . ')' : ''); ?></small>
            </div>
            <div class="stat-item">
                <h4><?php echo e(number_format($jumlahJurusan)); ?></h4>
                <small>Jurusan</small>
            </div>
            <div class="stat-item">
                <h4><?php echo e(number_format($jumlahSarana)); ?></h4>
                <small>Sarana</small>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="nav-menu-card">
        <div class="nav-menu-grid">
            <a href="javascript:void(0)" class="nav-menu-item active" data-tab="informasi">
                <i class="bx bx-info-circle" style="color: #004b4c;"></i>
                <span>Informasi</span>
            </a>
            <a href="javascript:void(0)" class="nav-menu-item" data-tab="tenaga-pendidik">
                <i class="bx bx-user-voice" style="color: #0e8549;"></i>
                <span>Tenaga<br>Pendidik</span>
            </a>
            <a href="javascript:void(0)" class="nav-menu-item" data-tab="presensi">
                <i class="bx bx-check-circle" style="color: #6c5ce7;"></i>
                <span>Presensi</span>
            </a>
            <a href="javascript:void(0)" class="nav-menu-item" data-tab="presensi-mengajar">
                <i class="bx bx-chalkboard" style="color: #f5576c;"></i>
                <span>Presensi<br>Mengajar</span>
            </a>
        </div>
    </div>

    <!-- Content Sections -->
    <!-- Informasi Section -->
    <div class="content-section active" id="section-informasi">
        <!-- Basic Info -->
        <div class="info-card">
            <div class="info-card-header">
                <i class="bx bx-info-circle"></i>
                <h6>Informasi Dasar</h6>
            </div>

            <div class="info-row">
                <span class="info-label">Kabupaten</span>
                <span class="info-value"><?php echo e($madrasah->kabupaten ?: '-'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Alamat</span>
                <span class="info-value"><?php echo e($madrasah->alamat ?: '-'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Tahun Berdiri</span>
                <span class="info-value"><?php echo e($tahunBerdiri ?: '-'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Akreditasi</span>
                <span class="info-value">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($akreditasi): ?>
                    <span class="badge badge-blue"><?php echo e($akreditasi); ?></span>
                    <?php else: ?>
                    <span class="empty-value">-</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">SCOD</span>
                <span class="info-value"><?php echo e($madrasah->scod ?: '-'); ?></span>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="info-card">
            <div class="info-card-header">
                <i class="bx bx-phone"></i>
                <h6>Informasi Kontak</h6>
            </div>

            <div class="info-row">
                <span class="info-label">Telepon</span>
                <span class="info-value">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($telepon): ?>
                    <a href="tel:<?php echo e($telepon); ?>"><?php echo e($telepon); ?></a>
                    <?php else: ?>
                    <span class="empty-value">-</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($email): ?>
                    <a href="mailto:<?php echo e($email); ?>"><?php echo e($email); ?></a>
                    <?php else: ?>
                    <span class="empty-value">-</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Website</span>
                <span class="info-value">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($website): ?>
                    <a href="<?php echo e($website); ?>" target="_blank"><?php echo e($website); ?></a>
                    <?php else: ?>
                    <span class="empty-value">-</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </span>
            </div>
        </div>

        <!-- Kepala Sekolah -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->kepala_sekolah_nama): ?>
        <div class="info-card">
            <div class="info-card-header">
                <i class="bx bx-user"></i>
                <h6>Kepala Sekolah</h6>
            </div>

            <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-value">
                    <?php echo e($madrasah->kepala_sekolah_nama); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->kepala_sekolah_gelar): ?>
                    <small class="text-muted">, <?php echo e($madrasah->kepala_sekolah_gelar); ?></small>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </span>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Tagline & Deskripsi -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->tagline || $madrasah->deskripsi_singkat): ?>
        <div class="info-card">
            <div class="info-card-header">
                <i class="bx bx-text"></i>
                <h6>Tentang</h6>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->tagline): ?>
            <div class="info-row">
                <span class="info-label">Tagline</span>
                <span class="info-value"><?php echo e($madrasah->tagline); ?></span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->deskripsi_singkat): ?>
            <div class="info-row" style="flex-direction: column;">
                <span class="info-label" style="width: 100%; margin-bottom: 6px;">Deskripsi</span>
                <span class="info-value" style="width: 100%;"><?php echo e($madrasah->deskripsi_singkat); ?></span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Visi Misi -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->visi || $madrasah->misi): ?>
        <div class="info-card">
            <div class="info-card-header">
                <i class="bx bx-bullseye"></i>
                <h6>Visi & Misi</h6>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->visi): ?>
            <div class="info-row" style="flex-direction: column;">
                <span class="info-label" style="width: 100%; margin-bottom: 6px;">Visi</span>
                <span class="info-value" style="width: 100%;"><?php echo e($madrasah->visi); ?></span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->misi): ?>
            <div class="info-row" style="flex-direction: column;">
                <span class="info-label" style="width: 100%; margin-bottom: 6px;">Misi</span>
                <span class="info-value" style="width: 100%;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($madrasah->misi)): ?>
                    <ul style="margin: 0; padding-left: 18px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $madrasah->misi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $misi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li style="margin-bottom: 4px;"><?php echo e($misi); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                    <?php else: ?>
                    <?php echo e($madrasah->misi); ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Jurusan -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->jurusan && (is_array($madrasah->jurusan) && count($madrasah->jurusan) > 0)): ?>
        <div class="info-card">
            <div class="info-card-header">
                <i class="bx bx-book"></i>
                <h6>Jurusan</h6>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($madrasah->jurusan)): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $madrasah->jurusan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jurusan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="list-item">
                <span class="list-bullet"></span>
                <span style="font-size: 12px;"><?php echo e($jurusan); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
            <span class="info-value"><?php echo e($madrasah->jurusan); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Fasilitas -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fasilitasList && count($fasilitasList) > 0): ?>
        <div class="info-card">
            <div class="info-card-header">
                <i class="bx bx-building"></i>
                <h6>Fasilitas (<?php echo e(count($fasilitasList)); ?>)</h6>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fasilitasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fasilitas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="list-item" style="flex-direction: column; align-items: flex-start;">
                <div class="d-flex align-items-center w-100">
                    <span class="list-bullet" style="background: #0e8549;"></span>
                    <span style="font-size: 13px; font-weight: 600; color: #004b4c;"><?php echo e($fasilitas['name']); ?></span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($fasilitas['description']) && $fasilitas['description']): ?>
                <p style="font-size: 11px; color: #6c757d; margin: 6px 0 0 16px; line-height: 1.4;"><?php echo e($fasilitas['description']); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Keunggulan -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->keunggulan && (is_array($madrasah->keunggulan) && count($madrasah->keunggulan) > 0)): ?>
        <div class="info-card">
            <div class="info-card-header">
                <i class="bx bx-star"></i>
                <h6>Keunggulan</h6>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($madrasah->keunggulan)): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $madrasah->keunggulan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keunggulan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="list-item">
                <span class="list-bullet" style="background: #f5576c;"></span>
                <span style="font-size: 12px;"><?php echo e($keunggulan); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
            <span class="info-value"><?php echo e($madrasah->keunggulan); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Lokasi -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->latitude && $madrasah->longitude): ?>
        <div class="info-card">
            <div class="info-card-header">
                <i class="bx bx-map"></i>
                <h6>Lokasi</h6>
            </div>

            <div class="info-row">
                <span class="info-label">Koordinat</span>
                <span class="info-value">
                    <?php echo e($madrasah->latitude); ?>, <?php echo e($madrasah->longitude); ?>

                </span>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->map_link): ?>
            <div class="info-row">
                <span class="info-label">Google Maps</span>
                <span class="info-value">
                    <a href="<?php echo e($madrasah->map_link); ?>" target="_blank">Buka di Google Maps</a>
                </span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Hari KBM -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->hari_kbm): ?>
        <div class="info-card">
            <div class="info-card-header">
                <i class="bx bx-calendar"></i>
                <h6>Hari KBM</h6>
            </div>

            <div class="info-row">
                <span class="info-value"><?php echo e($madrasah->hari_kbm); ?></span>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Tenaga Pendidik Section -->
    <div class="content-section" id="section-tenaga-pendidik">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenagaPendidik && count($tenagaPendidik) > 0): ?>
        <div class="info-card">
            <div class="info-card-header">
                <i class="bx bx-user-voice"></i>
                <h6>Tenaga Pendidik (<?php echo e(count($tenagaPendidik)); ?>)</h6>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tenagaPendidik; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guru): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
        $isKepalaSekolah = false;
        $ketugasan = $guru->ketugasan ? strtolower(trim($guru->ketugasan)) : '';
        if ($ketugasan === 'kepala sekolah/madrasah' || strpos($ketugasan, 'kepala') !== false) {
            $isKepalaSekolah = true;
        }
        ?>
        <div class="info-card">
            <div class="d-flex align-items-center">
                <div class="avatar-sm me-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($guru->avatar): ?>
                    <img
                        src="<?php echo e(asset('storage/' . $guru->avatar)); ?>"
                        alt="<?php echo e($guru->name); ?>"
                        class="avatar-img rounded-circle"
                        style="width: 45px; height: 45px;"
                    >
                    <?php else: ?>
                    <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);">
                        <span class="text-white fw-bold" style="font-size: 16px;"><?php echo e(substr($guru->name, 0, 1)); ?></span>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="grow">
                    <div class="d-flex align-items-center flex-wrap gap-1">
                        <h6 class="mb-1 fw-semibold text-dark" style="font-size: 13px;"><?php echo e($guru->name); ?></h6>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isKepalaSekolah): ?>
                        <span class="badge" style="background: linear-gradient(135deg, #f5576c 0%, #fa709a 100%); color: white; font-size: 9px;">
                            <i class="bx bx-star me-1"></i>Kepala Sekolah
                        </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($guru->statusKepegawaian): ?>
                    <span class="badge" style="background: #d4edda; color: #155724; font-size: 10px;">
                        <?php echo e($guru->statusKepegawaian->name); ?>

                    </span>
                    <?php else: ?>
                    <span class="badge bg-secondary" style="font-size: 10px;">Status belum diisi</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php else: ?>
        <div class="info-card">
            <div class="info-card-header">
                <i class="bx bx-user-voice"></i>
                <h6>Tenaga Pendidik</h6>
            </div>
            <div class="empty-state py-3">
                <i class="bx bx-user-x" style="font-size: 36px; color: #dee2e6;"></i>
                <p class="mb-0 mt-2 text-muted" style="font-size: 12px;">Belum ada tenaga pendidik</p>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Presensi Section -->
    <div class="content-section" id="section-presensi">
        <div class="info-card">
            <div class="info-card-header">
                <i class="bx bx-check-circle"></i>
                <h6>Rekap Presensi Bulanan</h6>
                <small class="text-muted" style="text-align: right" id="currentMonthDisplay"><?php echo e($monthlyAttendance['month_info']['month_name']); ?></small>
            </div>

            <!-- Month Selector -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($monthlyAttendance['month_info']['available_months']) > 0): ?>
            <div class="mb-3">
                <select class="form-select form-select-sm" id="monthSelector" onchange="changeMonth(this.value)" style="font-size: 12px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $monthlyAttendance['month_info']['available_months']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($monthOption->month_year); ?>" <?php echo e($monthOption->month_year == $selectedMonth ? 'selected' : ''); ?>>
                        <?php echo e($monthOption->month_name); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Summary Stats -->
            <div class="row g-2 mb-3" id="summaryStats">
                <div class="col-6">
                    <div class="text-center p-2" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 8px; color: white;">
                        <div class="fw-bold" style="font-size: 16px;" id="totalHadir"><?php echo e($monthlyAttendance['summary']['total_hadir']); ?></div>
                        <small style="font-size: 10px;">Hadir</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-center p-2" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); border-radius: 8px; color: white;">
                        <div class="fw-bold" style="font-size: 16px;" id="totalIzin"><?php echo e($monthlyAttendance['summary']['total_izin']); ?></div>
                        <small style="font-size: 10px;">Izin</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-center p-2" style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); border-radius: 8px; color: white;">
                        <div class="fw-bold" style="font-size: 16px;" id="totalAlpha"><?php echo e($monthlyAttendance['summary']['total_alpha']); ?></div>
                        <small style="font-size: 10px;">Alpha</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-center p-2" style="background: linear-gradient(135deg, #6c5ce7 0%, #8c7ae6 100%); border-radius: 8px; color: white;">
                        <div class="fw-bold" style="font-size: 16px;" id="persentaseKehadiran"><?php echo e($monthlyAttendance['summary']['persentase_kehadiran']); ?>%</div>
                        <small style="font-size: 10px;">Kehadiran</small>
                    </div>
                </div>
            </div>

            <!-- Monthly Details -->
            <div class="monthly-attendance" id="monthlyDetails" style="max-height: 300px; overflow-y: auto;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $monthlyAttendance['monthly_data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom" style="border-color: #f0f0f0 !important;">
                    <div class="grow">
                        <div class="fw-semibold" style="font-size: 12px; color: #004b4c;">
                            <?php echo e($day['day_name']); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($day['is_holiday']): ?>
                                <span class="badge" style="background: #ffc107; color: #000; font-size: 8px;">Libur</span>
                            <?php elseif(!$day['is_working_day']): ?>
                                <span class="badge" style="background: #e9ecef; color: #6c757d; font-size: 8px;">Non-KBM</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <small class="text-muted" style="font-size: 10px;"><?php echo e(\Carbon\Carbon::parse($day['date'])->format('d/m/y')); ?></small>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($day['is_working_day']): ?>
                    <div class="d-flex gap-2">
                        <span class="badge" style="background: #d4edda; color: #155724; font-size: 10px; padding: 4px 6px;">
                            <i class="bx bx-check me-1"></i><?php echo e($day['hadir']); ?>

                        </span>
                        <span class="badge" style="background: #fff3cd; color: #856404; font-size: 10px; padding: 4px 6px;">
                            <i class="bx bx-time me-1"></i><?php echo e($day['izin']); ?>

                        </span>
                        <span class="badge" style="background: #f8d7da; color: #721c24; font-size: 10px; padding: 4px 6px;">
                            <i class="bx bx-x me-1"></i><?php echo e($day['alpha']); ?>

                        </span>
                    </div>
                    <?php else: ?>
                    <div class="text-muted" style="font-size: 12px;">-</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="text-center mt-3">
                <small class="text-muted" style="font-size: 10px;" id="summaryInfo">
                    Total Guru: <?php echo e($monthlyAttendance['summary']['total_guru']); ?> |
                    Hari KBM: <?php echo e($monthlyAttendance['summary']['hari_kbm']); ?> |
                    Hari Kerja: <?php echo e($monthlyAttendance['summary']['total_working_days']); ?>

                </small>
            </div>
        </div>
    </div>

    <!-- Presensi Mengajar Section -->
    <div class="content-section" id="section-presensi-mengajar">
        <div class="info-card">
            <div class="info-card-header">
                <i class="bx bx-chalkboard"></i>
                <h6>Rekap Presensi Mengajar</h6>
                <small class="text-muted" style="text-align: right" id="teachingCurrentMonthDisplay"><?php echo e($teachingAttendance['month_info']['month_name']); ?></small>
            </div>

            <!-- Month Selector -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($teachingAttendance['month_info']['available_months']) > 0): ?>
            <div class="mb-3">
                <select class="form-select form-select-sm" id="teachingMonthSelector" onchange="changeTeachingMonth(this.value)" style="font-size: 12px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $teachingAttendance['month_info']['available_months']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($monthOption->month_year); ?>" <?php echo e($monthOption->month_year == $selectedMonth ? 'selected' : ''); ?>>
                        <?php echo e($monthOption->month_name); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Summary Stats -->
            <div class="row g-2 mb-3" id="teachingSummaryStats">
                <div class="col-6">
                    <div class="text-center p-2" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); border-radius: 8px; color: white;">
                        <div class="fw-bold" style="font-size: 16px;" id="totalTeachers"><?php echo e($teachingAttendance['summary']['total_teachers']); ?></div>
                        <small style="font-size: 10px;">Guru</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-center p-2" style="background: linear-gradient(135deg, #6c5ce7 0%, #8c7ae6 100%); border-radius: 8px; color: white;">
                        <div class="fw-bold" style="font-size: 16px;" id="totalScheduled"><?php echo e($teachingAttendance['summary']['total_scheduled_classes']); ?></div>
                        <small style="font-size: 10px;">Jadwal</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-center p-2" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 8px; color: white;">
                        <div class="fw-bold" style="font-size: 16px;" id="totalConducted"><?php echo e($teachingAttendance['summary']['total_conducted_classes']); ?></div>
                        <small style="font-size: 10px;">Terlaksana</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-center p-2" style="background: linear-gradient(135deg, #f5576c 0%, #fa709a 100%); border-radius: 8px; color: white;">
                        <div class="fw-bold" style="font-size: 16px;" id="persentasePelaksanaan"><?php echo e($teachingAttendance['summary']['persentase_pelaksanaan']); ?>%</div>
                        <small style="font-size: 10px;">Pelaksanaan</small>
                    </div>
                </div>
            </div>

            <!-- Teaching Attendance Details -->
            <div class="teaching-attendance-list" id="teachingDetails" style="max-height: 400px; overflow-y: auto;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $teachingAttendance['monthly_data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($day['is_working_day'] && count($day['teachers']) > 0): ?>
                <div class="mb-3 p-2" style="background: #f8f9fa; border-radius: 8px;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-semibold" style="font-size: 12px; color: #004b4c;"><?php echo e($day['day_name']); ?></div>
                        <small class="text-muted" style="font-size: 10px;"><?php echo e(\Carbon\Carbon::parse($day['date'])->format('d/m/y')); ?></small>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge" style="background: #e3f2fd; color: #0d47a1; font-size: 9px;">
                            <i class="bx bx-calendar-check me-1"></i><?php echo e($day['total_conducted']); ?>/<?php echo e($day['total_scheduled']); ?> kelas
                        </span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $day['teachers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex align-items-center py-1 border-top" style="border-color: #e9ecef !important;">
                        <div class="grow">
                            <div class="fw-medium" style="font-size: 11px; color: #212529;"><?php echo e($teacher['teacher_name']); ?></div>
                            <small class="text-muted" style="font-size: 10px;"><?php echo e($teacher['subject']); ?> • <?php echo e($teacher['time']); ?></small>
                        </div>
                        <span class="badge bg-success" style="font-size: 8px;">
                            <i class="bx bx-check me-1"></i>Hadir
                        </span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php elseif($day['is_working_day']): ?>
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom" style="border-color: #f0f0f0 !important;">
                    <div class="grow">
                        <div class="fw-semibold" style="font-size: 12px; color: #004b4c;"><?php echo e($day['day_name']); ?></div>
                        <small class="text-muted" style="font-size: 10px;"><?php echo e(\Carbon\Carbon::parse($day['date'])->format('d/m/y')); ?></small>
                    </div>
                    <div class="text-muted" style="font-size: 11px;">Belum ada presensi</div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-4">
                    <i class="bx bx-calendar-x" style="font-size: 36px; color: #dee2e6;"></i>
                    <p class="mb-0 mt-2 text-muted" style="font-size: 12px;">Tidak ada data presensi mengajar</p>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="text-center mt-3">
                <small class="text-muted" style="font-size: 10px;" id="teachingSummaryInfo">
                    Total Guru: <?php echo e($teachingAttendance['summary']['total_teachers']); ?> |
                    Hari KBM: <?php echo e($teachingAttendance['summary']['hari_kbm']); ?> |
                    Hari Kerja: <?php echo e($teachingAttendance['summary']['total_working_days']); ?>

                </small>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize notification badge
    fetchUnreadNotifications();

    async function fetchUnreadNotifications() {
        try {
            const response = await fetch('<?php echo e(route("mobile.notifications.unread-count")); ?>');
            const data = await response.json();
            const badge = document.getElementById('notificationBadge');
            if (data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'block';
            }
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    }

    // Tab switching functionality
    const navItems = document.querySelectorAll('.nav-menu-item');
    const contentSections = document.querySelectorAll('.content-section');

    navItems.forEach(item => {
        item.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');

            // Remove active class from all nav items
            navItems.forEach(nav => nav.classList.remove('active'));

            // Add active class to clicked nav item
            this.classList.add('active');

            // Hide all content sections
            contentSections.forEach(section => section.classList.remove('active'));

            // Show the corresponding content section
            const targetSection = document.getElementById('section-' + tabName);
            if (targetSection) {
                targetSection.classList.add('active');

                // Scroll back to top of content area
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    });
});

// Month change functionality - defined globally so it can be called from HTML onchange
function changeMonth(monthValue) {
    const schoolId = '<?php echo e($madrasah->id); ?>';

    // Show loading state
    const summaryStats = document.getElementById('summaryStats');
    const monthlyDetails = document.getElementById('monthlyDetails');
    const currentMonthDisplay = document.getElementById('currentMonthDisplay');
    const summaryInfo = document.getElementById('summaryInfo');

    summaryStats.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading...</div>';
    monthlyDetails.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading...</div>';

    // Fetch new data via AJAX
    fetch(`<?php echo e(url('mobile/pengurus/sekolah')); ?>/${schoolId}/monthly-attendance-data?month=${monthValue}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update month display
        currentMonthDisplay.textContent = data.month_info.month_name;

        // Update summary stats
        const summaryHtml = `
            <div class="col-6">
                <div class="text-center p-2" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 8px; color: white;">
                    <div class="fw-bold" style="font-size: 16px;" id="totalHadir">${data.summary.total_hadir}</div>
                    <small style="font-size: 10px;">Hadir</small>
                </div>
            </div>
            <div class="col-6">
                <div class="text-center p-2" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); border-radius: 8px; color: white;">
                    <div class="fw-bold" style="font-size: 16px;" id="totalIzin">${data.summary.total_izin}</div>
                    <small style="font-size: 10px;">Izin</small>
                </div>
            </div>
            <div class="col-6">
                <div class="text-center p-2" style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); border-radius: 8px; color: white;">
                    <div class="fw-bold" style="font-size: 16px;" id="totalAlpha">${data.summary.total_alpha}</div>
                    <small style="font-size: 10px;">Alpha</small>
                </div>
            </div>
            <div class="col-6">
                <div class="text-center p-2" style="background: linear-gradient(135deg, #6c5ce7 0%, #8c7ae6 100%); border-radius: 8px; color: white;">
                    <div class="fw-bold" style="font-size: 16px;" id="persentaseKehadiran">${data.summary.persentase_kehadiran}%</div>
                    <small style="font-size: 10px;">Kehadiran</small>
                </div>
            </div>
        `;
        summaryStats.innerHTML = summaryHtml;

        // Update monthly details
        let detailsHtml = '';
        data.monthly_data.forEach(day => {
            detailsHtml += `
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom" style="border-color: #f0f0f0 !important;">
                    <div class="grow">
                        <div class="fw-semibold" style="font-size: 12px; color: #004b4c;">
                            ${day.day_name}
                            ${day.is_holiday ? '<span class="badge" style="background: #ffc107; color: #000; font-size: 8px;">Libur</span>' : ''}
                            ${!day.is_working_day && !day.is_holiday ? '<span class="badge" style="background: #e9ecef; color: #6c757d; font-size: 8px;">Non-KBM</span>' : ''}
                        </div>
                        <small class="text-muted" style="font-size: 10px;">${new Date(day.date).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit' })}</small>
                    </div>
                    ${day.is_working_day ? `
                    <div class="d-flex gap-2">
                        <span class="badge" style="background: #d4edda; color: #155724; font-size: 10px; padding: 4px 6px;">
                            <i class="bx bx-check me-1"></i>${day.hadir}
                        </span>
                        <span class="badge" style="background: #fff3cd; color: #856404; font-size: 10px; padding: 4px 6px;">
                            <i class="bx bx-time me-1"></i>${day.izin}
                        </span>
                        <span class="badge" style="background: #f8d7da; color: #721c24; font-size: 10px; padding: 4px 6px;">
                            <i class="bx bx-x me-1"></i>${day.alpha}
                        </span>
                    </div>
                    ` : '<div class="text-muted" style="font-size: 12px;">-</div>'}
                </div>
            `;
        });
        monthlyDetails.innerHTML = detailsHtml;

        // Update summary info
        summaryInfo.innerHTML = `Total Guru: ${data.summary.total_guru} | Hari KBM: ${data.summary.hari_kbm} | Hari Kerja: ${data.summary.total_working_days}`;

        // Update URL without page reload
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('month', monthValue);
        window.history.pushState({}, '', currentUrl.toString());
    })
    .catch(error => {
        console.error('Error fetching attendance data:', error);
        summaryStats.innerHTML = '<div class="text-center py-3 text-danger">Error loading data</div>';
        monthlyDetails.innerHTML = '<div class="text-center py-3 text-danger">Error loading data</div>';
    });
}

// Teaching Attendance Month change functionality
function changeTeachingMonth(monthValue) {
    const schoolId = '<?php echo e($madrasah->id); ?>';

    // Show loading state
    const teachingSummaryStats = document.getElementById('teachingSummaryStats');
    const teachingDetails = document.getElementById('teachingDetails');
    const teachingCurrentMonthDisplay = document.getElementById('teachingCurrentMonthDisplay');
    const teachingSummaryInfo = document.getElementById('teachingSummaryInfo');

    teachingSummaryStats.innerHTML = '<div class="text-center py-3 col-12"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading...</div>';
    teachingDetails.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading...</div>';

    // Fetch new data via AJAX
    fetch(`<?php echo e(url('mobile/pengurus/sekolah')); ?>/${schoolId}/monthly-teaching-attendance-data?month=${monthValue}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update month display
        teachingCurrentMonthDisplay.textContent = data.month_info.month_name;

        // Update summary stats
        const summaryHtml = `
            <div class="col-6">
                <div class="text-center p-2" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); border-radius: 8px; color: white;">
                    <div class="fw-bold" style="font-size: 16px;" id="totalTeachers">${data.summary.total_teachers}</div>
                    <small style="font-size: 10px;">Guru</small>
                </div>
            </div>
            <div class="col-6">
                <div class="text-center p-2" style="background: linear-gradient(135deg, #6c5ce7 0%, #8c7ae6 100%); border-radius: 8px; color: white;">
                    <div class="fw-bold" style="font-size: 16px;" id="totalScheduled">${data.summary.total_scheduled_classes}</div>
                    <small style="font-size: 10px;">Jadwal</small>
                </div>
            </div>
            <div class="col-6">
                <div class="text-center p-2" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 8px; color: white;">
                    <div class="fw-bold" style="font-size: 16px;" id="totalConducted">${data.summary.total_conducted_classes}</div>
                    <small style="font-size: 10px;">Terlaksana</small>
                </div>
            </div>
            <div class="col-6">
                <div class="text-center p-2" style="background: linear-gradient(135deg, #f5576c 0%, #fa709a 100%); border-radius: 8px; color: white;">
                    <div class="fw-bold" style="font-size: 16px;" id="persentasePelaksanaan">${data.summary.persentase_pelaksanaan}%</div>
                    <small style="font-size: 10px;">Pelaksanaan</small>
                </div>
            </div>
        `;
        teachingSummaryStats.innerHTML = summaryHtml;

        // Update monthly details
        let detailsHtml = '';
        if (data.monthly_data && data.monthly_data.length > 0) {
            let hasData = false;
            data.monthly_data.forEach(day => {
                if (day.is_working_day && day.teachers && day.teachers.length > 0) {
                    hasData = true;
                    let teachersHtml = '';
                    day.teachers.forEach(teacher => {
                        teachersHtml += `
                            <div class="d-flex align-items-center py-1 border-top" style="border-color: #e9ecef !important;">
                                <div class="grow">
                                    <div class="fw-medium" style="font-size: 11px; color: #212529;">${teacher.teacher_name}</div>
                                    <small class="text-muted" style="font-size: 10px;">${teacher.subject} • ${teacher.time}</small>
                                </div>
                                <span class="badge bg-success" style="font-size: 8px;">
                                    <i class="bx bx-check me-1"></i>Hadir
                                </span>
                            </div>
                        `;
                    });

                    detailsHtml += `
                        <div class="mb-3 p-2" style="background: #f8f9fa; border-radius: 8px;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="fw-semibold" style="font-size: 12px; color: #004b4c;">${day.day_name}</div>
                                <small class="text-muted" style="font-size: 10px;">${new Date(day.date).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit' })}</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge" style="background: #e3f2fd; color: #0d47a1; font-size: 9px;">
                                    <i class="bx bx-calendar-check me-1"></i>${day.total_conducted}/${day.total_scheduled} kelas
                                </span>
                            </div>
                            ${teachersHtml}
                        </div>
                    `;
                } else if (day.is_working_day) {
                    detailsHtml += `
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom" style="border-color: #f0f0f0 !important;">
                            <div class="grow">
                                <div class="fw-semibold" style="font-size: 12px; color: #004b4c;">${day.day_name}</div>
                                <small class="text-muted" style="font-size: 10px;">${new Date(day.date).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit' })}</small>
                            </div>
                            <div class="text-muted" style="font-size: 11px;">Belum ada presensi</div>
                        </div>
                    `;
                }
            });

            if (!hasData) {
                detailsHtml = `
                    <div class="text-center py-4">
                        <i class="bx bx-calendar-x" style="font-size: 36px; color: #dee2e6;"></i>
                        <p class="mb-0 mt-2 text-muted" style="font-size: 12px;">Tidak ada data presensi mengajar</p>
                    </div>
                `;
            }
        } else {
            detailsHtml = `
                <div class="text-center py-4">
                    <i class="bx bx-calendar-x" style="font-size: 36px; color: #dee2e6;"></i>
                    <p class="mb-0 mt-2 text-muted" style="font-size: 12px;">Tidak ada data presensi mengajar</p>
                </div>
            `;
        }
        teachingDetails.innerHTML = detailsHtml;

        // Update summary info
        teachingSummaryInfo.innerHTML = `Total Guru: ${data.summary.total_teachers} | Hari KBM: ${data.summary.hari_kbm} | Hari Kerja: ${data.summary.total_working_days}`;

        // Update URL without page reload
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('month', monthValue);
        window.history.pushState({}, '', currentUrl.toString());
    })
    .catch(error => {
        console.error('Error fetching teaching attendance data:', error);
        teachingSummaryStats.innerHTML = '<div class="text-center py-3 text-danger col-12">Error loading data</div>';
        teachingDetails.innerHTML = '<div class="text-center py-3 text-danger">Error loading data</div>';
    });
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.mobile-pengurus', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/pengurus/sekolah-detail.blade.php ENDPATH**/ ?>