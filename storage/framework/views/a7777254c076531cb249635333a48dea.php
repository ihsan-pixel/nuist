<?php $__env->startSection('title', 'Pengguna Aktif'); ?>

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

    .table {
        font-size: 12px;
    }

    .table th {
        font-weight: 600;
        color: #004b4c;
        background: #f8f9fa;
    }

    .table td {
        vertical-align: middle;
    }

    .badge {
        font-size: 10px;
        padding: 4px 8px;
    }
</style>

<div class="container py-3" style="max-width: 520px; margin: auto;">
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body py-2">
                    <h4 class="page-title mb-0" style="font-size: 16px; font-weight: 600; color: #004b4c;">
                        <i class="bx bx-users me-2"></i>Pengguna Aktif
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Madrasah</th>
                                    <th>Terakhir Login</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $activeUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($user->name); ?></td>
                                    <td><?php echo e($user->email); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($user->role == 'pengurus' ? 'info' : ($user->role == 'tenaga_pendidik' ? 'success' : 'primary')); ?>">
                                            <?php echo e(ucfirst($user->role)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($user->madrasah->name ?? '-'); ?></td>
                                    <td><?php echo e($user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d M Y H:i') : '-'); ?></td>
                                    <td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->last_login_at && $user->last_login_at->isAfter(now()->subDays(7))): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php elseif($user->last_login_at && $user->last_login_at->isAfter(now()->subDays(30))): ?>
                                            <span class="badge bg-warning">Cukup Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data pengguna aktif.</td>
                                </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php echo e($activeUsers->links('vendor.pagination.bootstrap-5')); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<div style="height: 80px;"></div> <!-- Spacer for bottom nav -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/pengurus/pengguna-aktif.blade.php ENDPATH**/ ?>