<?php $__env->startSection('title'); ?> MGMP - Musyawarah Guru Mata Pelajaran <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('landing.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- HERO -->
<section id="hero" class="hero">
    <div class="container">
        <h1 class="hero-title">MGMP NUIST</h1>
        <h1 class="hero-subtitle" style="color: #eda711">Musyawarah Guru Mata Pelajaran</h1>
        <p>Komunitas guru yang berkolaborasi untuk meningkatkan kualitas pembelajaran. Kelola anggota, kegiatan, dan laporan MGMP melalui sistem ini.</p>
        <div style="margin-top:20px;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::check() && Auth::user()->role === 'mgmp'): ?>
                <a href="<?php echo e(route('mgmp.dashboard')); ?>" class="btn btn-light btn-lg">
                    <i class="mdi mdi-view-dashboard me-2"></i>
                    Masuk Dashboard MGMP
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="btn btn-light btn-lg">
                    <i class="mdi mdi-login me-2"></i>
                    Login MGMP
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>

<!-- STATS -->
<section class="py-5" style="background:#f8fafc;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body p-4 text-center">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                <i class="mdi mdi-account-group fs-2"></i>
                            </div>
                        </div>
                        <h5 class="card-title mb-3">Anggota MGMP</h5>
                        <p class="text-muted mb-3">Komunitas guru yang berkolaborasi untuk meningkatkan kualitas pembelajaran</p>
                        <div class="text-primary fw-bold fs-4"><?php echo e($totalAnggota ?? 0); ?></div>
                        <small class="text-muted">Total Anggota</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body p-4 text-center">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                <i class="mdi mdi-calendar-check fs-2"></i>
                            </div>
                        </div>
                        <h5 class="card-title mb-3">Kegiatan</h5>
                        <p class="text-muted mb-3">Workshop, seminar, dan pengembangan profesi</p>
                        <div class="text-success fw-bold fs-4"><?php echo e($totalKegiatan ?? 0); ?></div>
                        <small class="text-muted">Kegiatan</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body p-4 text-center">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                                <i class="mdi mdi-book-open fs-2"></i>
                            </div>
                        </div>
                        <h5 class="card-title mb-3">Materi Pembelajaran</h5>
                        <p class="text-muted mb-3">Berbagi materi, metode, dan inovasi</p>
                        <div class="text-info fw-bold fs-4"><?php echo e($totalMateri ?? '0'); ?></div>
                        <small class="text-muted">Materi Tersedia</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- OBJECTIVES -->
<section class="py-5">
    <div class="container">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <h4 class="card-title mb-3 text-dark"><i class="mdi mdi-target text-primary me-2"></i> Tujuan MGMP</h4>
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Peningkatan Kompetensi</h6>
                        <p class="text-muted small">Mengembangkan kompetensi pedagogik, profesional, kepribadian, dan sosial guru</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Berbagi Pengalaman</h6>
                        <p class="text-muted small">Berbagi pengalaman, metode, dan inovasi pembelajaran antar sesama guru</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Inovasi Pembelajaran</h6>
                        <p class="text-muted small">Mengembangkan inovasi dan kreativitas dalam proses pembelajaran</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Jaringan Profesional</h6>
                        <p class="text-muted small">Membangun jaringan kerja sama dan kolaborasi antar guru dan sekolah</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mgmp/index.blade.php ENDPATH**/ ?>