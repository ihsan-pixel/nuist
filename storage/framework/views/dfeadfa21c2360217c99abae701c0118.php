<?php $__env->startSection('title', 'UPPM'); ?>

<?php $__env->startSection('content'); ?>
<?php

date_default_timezone_set('Asia/Jakarta');

$b = time();
$hour = date('G', $b);

if ($hour >= 0 && $hour <= 11) {
    $congrat = 'Selamat Pagi';
} elseif ($hour >= 12 && $hour <= 14) {
    $congrat = 'Selamat Siang ';
} elseif ($hour >= 15 && $hour <= 17) {
    $congrat = 'Selamat Sore ';
} elseif ($hour >= 17 && $hour <= 18) {
    $congrat = 'Selamat Petang ';
} elseif ($hour >= 19 && $hour <= 23) {
    $congrat = 'Selamat Malam ';
}

?>
<header class="mobile-header d-md-none" style="position: sticky; top: 0; z-index: 1050;">
    <div class="container-fluid px-0 py-0" style="background: transparent;">
        <div class="d-flex align-items-center justify-content-between">
            <!-- User Avatar (Left) -->
            <div class="avatar-sm me-3 ms-3">
                <img
                    src="<?php echo e(Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg')); ?>"
                    class="avatar-img rounded-circle"
                    alt="User"
                >
            </div>

            <!-- Welcome Text (Right-aligned) -->
            <div class="text-start grow">
                <small class="text-dark fw-medium" style="font-size: 11px;"><?php echo e($congrat); ?></small>
                <h6 class="mb-0 fw-semibold text-dark" style="font-size: 14px;"><?php echo e(Auth::user()->name); ?></h6>
            </div>

            <!-- Notification and Menu Buttons (Right) -->
            <div class="d-flex align-items-center">
                <!-- Notification Bell -->
                <a href="<?php echo e(route('mobile.notifications')); ?>" class="btn btn-link text-decoration-none p-0 me-2 position-relative">
                    <i class="bx bx-bell" style="font-size: 22px; color: #db3434;"></i>
                    <span id="notificationBadge" class="badge bg-danger rounded-pill position-absolute" style="font-size: 9px; padding: 2px 5px; top: -4px; right: -4px; display: none;">0</span>
                </a>

                <!-- Dropdown Menu -->
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded" style="font-size: 22px; color: #000000;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item py-2" href="<?php echo e(route('mobile.notifications')); ?>"><i class="bx bx-bell me-2"></i>Notifikasi</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item py-2" href="<?php echo e(route('dashboard')); ?>"><i class="bx bx-home me-2"></i>Dashboard</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item py-2 text-danger" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bx bx-log-out me-2"></i>Logout
                            </a>
                        </li>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                            <?php echo csrf_field(); ?>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        background-color: #f8f9fb;
    }

    .mobile-header,
    .mobile-header .container-fluid {
        background: transparent !important;
    }

    .mobile-header {
        box-shadow: none !important;
        border: none !important;
    }

    .avatar-sm {
        width: 40px;
        height: 40px;
        overflow: hidden;
        border-radius: 50%;
    }

    .avatar-sm .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    .card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .card-header {
        border-radius: 12px 12px 0 0 !important;
        padding: 16px;
    }
</style>

<div class="container py-3" style="max-width: 520px; margin: auto;">
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body py-2">
                    <h4 class="page-title mb-0" style="font-size: 16px; font-weight: 600; color: #004b4c;">
                        <i class="bx bx-briefcase me-2"></i>UPPM
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 14px; font-weight: 600; color: #004b4c;">
                        <i class="bx bx-info-circle me-2"></i>Informasi UPPM
                    </h5>
                    <p class="card-text" style="font-size: 12px;">
                        Halaman ini berisi informasi terkait Unit Pengelola Program Madrasah (UPPM).
                        Fitur ini sedang dalam pengembangan.
                    </p>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($uppmData) > 0): ?>
                        <div class="row">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $uppmData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title"><?php echo e($item['title'] ?? 'Judul'); ?></h6>
                                            <p class="card-text"><?php echo e($item['description'] ?? 'Deskripsi'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info" style="font-size: 12px;">
                            <i class="bx bx-info-circle me-2"></i>
                            Data UPPM sedang dalam proses pengembangan.
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="height: 80px;"></div> <!-- Spacer for bottom nav -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/pengurus/uppm.blade.php ENDPATH**/ ?>