<?php $__env->startSection('title', 'Instrument Talenta - Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Dashboard Instrument Talenta</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Instrument Talenta</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Welcome Card -->
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <h4 class="card-title mb-2">Selamat Datang di Instrument Talenta</h4>
                            <p class="text-muted mb-0">Platform pengembangan talenta profesional untuk meningkatkan kompetensi dan keterampilan tenaga pendidik di lingkungan LP. Ma'arif NU DIY</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <img src="<?php echo e(asset('images/logo favicon 1.png')); ?>" alt="Logo" class="img-fluid" style="max-height: 80px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm shrink-0">
                        <span class="avatar-title bg-primary rounded">
                            <i class="fas fa-users font-size-20"></i>
                        </span>
                    </div>
                    <div class="grow ms-3">
                        <p class="text-muted mb-1">Total Peserta</p>
                        <h4 class="mb-0"><?php echo e(number_format($totalPeserta)); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm shrink-0">
                        <span class="avatar-title bg-success rounded">
                            <i class="fas fa-chalkboard-teacher font-size-20"></i>
                        </span>
                    </div>
                    <div class="grow ms-3">
                        <p class="text-muted mb-1">Pemateri</p>
                        <h4 class="mb-0"><?php echo e(number_format($totalPemateri)); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm shrink-0">
                        <span class="avatar-title bg-info rounded">
                            <i class="fas fa-book-open font-size-20"></i>
                        </span>
                    </div>
                    <div class="grow ms-3">
                        <p class="text-muted mb-1">Materi</p>
                        <h4 class="mb-0"><?php echo e(number_format($totalMateri)); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm shrink-0">
                        <span class="avatar-title bg-warning rounded">
                            <i class="fas fa-star font-size-20"></i>
                        </span>
                    </div>
                    <div class="grow ms-3">
                        <p class="text-muted mb-1">Fasilitator</p>
                        <h4 class="mb-0"><?php echo e(number_format($totalFasilitator)); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Input Data Section -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Input Data</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <span class="avatar-title bg-primary rounded-circle">
                                    <i class="fas fa-user-plus font-size-24"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15 mb-2">Input Peserta</h5>
                            <p class="text-muted mb-3 font-size-12">Tambah data peserta baru ke dalam sistem</p>
                            <a href="<?php echo e(route('instumen-talenta.input-peserta')); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> Input Peserta
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <span class="avatar-title bg-success rounded-circle">
                                    <i class="fas fa-chalkboard-teacher font-size-24"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15 mb-2">Input Pemateri</h5>
                            <p class="text-muted mb-3 font-size-12">Tambah data pemateri/instruktur baru</p>
                            <a href="<?php echo e(route('instumen-talenta.input-pemateri')); ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-plus me-1"></i> Input Pemateri
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <span class="avatar-title bg-info rounded-circle">
                                    <i class="fas fa-user-cog font-size-24"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15 mb-2">Input Fasilitator</h5>
                            <p class="text-muted mb-3 font-size-12">Tambah data fasilitator/trainer baru</p>
                            <a href="<?php echo e(route('instumen-talenta.input-fasilitator')); ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-plus me-1"></i> Input Fasilitator
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <span class="avatar-title bg-warning rounded-circle">
                                    <i class="fas fa-book-open font-size-24"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15 mb-2">Input Materi</h5>
                            <p class="text-muted mb-3 font-size-12">Tambah data materi pembelajaran baru</p>
                            <a href="<?php echo e(route('instumen-talenta.input-materi')); ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-plus me-1"></i> Input Materi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Access Menu -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Akses Cepat</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <span class="avatar-title bg-primary rounded-circle">
                                    <i class="fas fa-users font-size-24"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15 mb-2">Dashboard Peserta</h5>
                            <p class="text-muted mb-3 font-size-12">Akses materi dan tracking progress</p>
                            <a href="<?php echo e(route('instumen-talenta.peserta')); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-sign-in-alt me-1"></i> Masuk
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <span class="avatar-title bg-success rounded-circle">
                                    <i class="fas fa-chalkboard-teacher font-size-24"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15 mb-2">Dashboard Pemateri</h5>
                            <p class="text-muted mb-3 font-size-12">Kelola konten dan materi kursus</p>
                            <a href="<?php echo e(route('instumen-talenta.pemateri')); ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-sign-in-alt me-1"></i> Masuk
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <span class="avatar-title bg-info rounded-circle">
                                    <i class="fas fa-user-cog font-size-24"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15 mb-2">Dashboard Fasilitator</h5>
                            <p class="text-muted mb-3 font-size-12">Pantau dan fasilitasi pembelajaran</p>
                            <a href="<?php echo e(route('instumen-talenta.fasilitator')); ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-sign-in-alt me-1"></i> Masuk
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <span class="avatar-title bg-danger rounded-circle">
                                    <i class="fas fa-cog font-size-24"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15 mb-2">Dashboard Admin</h5>
                            <p class="text-muted mb-3 font-size-12">Kelola sistem dan pengaturan</p>
                            <a href="<?php echo e(route('instumen-talenta.admin')); ?>" class="btn btn-danger btn-sm">
                                <i class="fas fa-sign-in-alt me-1"></i> Masuk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Platform Features -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Fitur Platform</h4>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-xs shrink-0 me-3">
                        <span class="avatar-title bg-primary rounded-circle">
                            <i class="fas fa-book-open font-size-12"></i>
                        </span>
                    </div>
                    <div class="grow">
                        <h6 class="mb-0 font-size-13">Materi Pembelajaran</h6>
                        <p class="text-muted mb-0 font-size-11">Akses materi interaktif</p>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-xs shrink-0 me-3">
                        <span class="avatar-title bg-success rounded-circle">
                            <i class="fas fa-chart-line font-size-12"></i>
                        </span>
                    </div>
                    <div class="grow">
                        <h6 class="mb-0 font-size-13">Tracking Progress</h6>
                        <p class="text-muted mb-0 font-size-11">Monitor perkembangan</p>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-xs shrink-0 me-3">
                        <span class="avatar-title bg-info rounded-circle">
                            <i class="fas fa-certificate font-size-12"></i>
                        </span>
                    </div>
                    <div class="grow">
                        <h6 class="mb-0 font-size-13">Sertifikasi</h6>
                        <p class="text-muted mb-0 font-size-11">Sertifikat kompetensi</p>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="avatar-xs shrink-0 me-3">
                        <span class="avatar-title bg-warning rounded-circle">
                            <i class="fas fa-users font-size-12"></i>
                        </span>
                    </div>
                    <div class="grow">
                        <h6 class="mb-0 font-size-13">Kolaborasi</h6>
                        <p class="text-muted mb-0 font-size-11">Forum diskusi & grup</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/instumen-talenta.css')); ?>">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/instumen-talenta/index.blade.php ENDPATH**/ ?>