<?php $__env->startSection('title', 'Dashboard Pengurus'); ?>
<?php $__env->startSection('subtitle', 'Ringkasan Aktivitas'); ?>

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

        .dashboard-header {
            background: #f8f9fb url('<?php echo e(asset("images/qwe1.png")); ?>') no-repeat center center;
            background-size: cover;
            border-radius: 14px;
            padding: 12px;
            color: #004b4c;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176);
        }

        .stats-form {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176);
            margin-bottom: 12px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
        }

        .stat-item {
            text-align: center;
            padding: 6px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .stat-item i {
            font-size: 18px;
        }

        .stat-item h6 {
            font-size: 12px;
            margin-bottom: 0;
            font-weight: 600;
        }

        .stat-item small {
            font-size: 9px;
            color: #6c757d;
        }

        .services-form {
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 12px;
            min-height: 50px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            text-align: center;
        }

        .service-wrapper {
            text-align: center;
        }

        .service-item {
            position: relative;
            border-radius: 8px;
            padding: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease-in-out;
            height: 64px;
            width: 100%;
            box-sizing: border-box;
            border: 0px solid rgba(0,75,76,0.2);
            box-shadow: #000000;
        }

        .service-label {
            font-size: 10px;
            font-weight: 600;
            margin-top: 6px;
            color: #333;
        }

        .service-item i {
            font-size: 28px;
            color: #003d3d;
        }

        .service-item h6 {
            font-size: 10px;
            margin-bottom: 0;
            font-weight: 600;
            color: #ffffff;
        }

        .mobile-header,
        .mobile-header .container-fluid {
            background: transparent !important;
        }

        .mobile-header {
            box-shadow: none !important;
            border: none !important;
        }

        .performance-card {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border-radius: 14px;
            padding: 14px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,.15);
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .performance-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,.2);
        }

        .performance-level {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
        }

        .level-badge {
            font-size: 9px;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 2px 6px;
            border-radius: 6px;
            font-weight: 600;
        }

        .performance-level strong {
            font-size: 10px;
            color: white;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: white;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 75%;
            background: #0e8549;
            border-radius: 999px;
            transition: width .4s ease;
        }

        .progress-text {
            text-align: center;
        }

        .progress-text strong {
            font-size: 14px;
            color: #fcffff;
        }

        .progress-text small {
            font-size: 9px;
            color: #ffffff;
        }

        .performance-progress {
            width: 100%;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 8px;
        }

        .timeline-accordion {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding-left: 20px;
        }

        .timeline-accordion::before {
            content: '';
            position: absolute;
            left: 16px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, rgba(255,255,255,0.8), rgba(255,255,255,0.4));
            border-radius: 1px;
        }

        .timeline-item-accordion {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 6px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .timeline-item-accordion:hover {
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .timeline-item-accordion.done {
            background: #d4edda;
            border-color: #c3e6cb;
            border-left: 4px solid #28a745;
        }

        .timeline-item-accordion .timeline-icon {
            position: absolute;
            left: -20px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1;
            font-size: 12px;
        }

        .timeline-item-accordion.done .timeline-icon {
            background: #28a745;
            color: white;
        }

        .timeline-item-accordion .timeline-content {
            flex: 1;
        }

        .timeline-item-accordion .timeline-content strong {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 4px;
        }

        .timeline-item-accordion .timeline-content small {
            font-size: 11px;
            color: #6c757d;
        }

        .timeline-item-accordion.done .timeline-content strong {
            color: #155724;
        }

        .timeline-item-accordion.done .timeline-content small {
            color: #155724;
        }
    </style>

    <!-- Show banner modal on page load -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showBanner): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var welcomeModal = new bootstrap.Modal(document.getElementById('welcomeBannerModal'), {
                backdrop: 'static',
                keyboard: false
            });
            welcomeModal.show();

            // Auto hide after 3 seconds
            setTimeout(function() {
                welcomeModal.hide();
            }, 3000);
        });
    </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Banner Modal -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bannerImage): ?>
    <div class="modal fade" id="welcomeBannerModal" tabindex="-1" aria-labelledby="welcomeBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="background: transparent;">
                <div class="modal-body p-0">
                    <div class="text-center">
                        <img src="<?php echo e($bannerImage); ?>" alt="Welcome Banner" class="img-fluid rounded" style="max-height: 60vh; width: auto;">
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 bg-transparent">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Performance Card for Pengurus -->
    <div class="performance-card">
        <div class="performance-level">
            <span class="level-badge">DASHBOARD PENGURUS</span>
            <strong>LP. Ma'arif NU PWNU DIY</strong>
        </div>

        <div class="performance-progress">
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
            <div class="progress-text">
                <strong>100%</strong>
            </div>
        </div>

        <div class="text-center mt-2" style="font-size: 10px">
            <small class="text-light">Selamat datang di Dashboard Mobile Pengurus</small>
        </div>
    </div>

    <small>Statistik Sistem</small>

    <!-- Stats Form -->
    <div class="stats-form">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-building text-primary"></i>
                </div>
                <h6><?php echo e($totalMadrasah); ?></h6>
                <small>Madrasah</small>
            </div>
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-user text-success"></i>
                </div>
                <h6><?php echo e($totalTenagaPendidik); ?></h6>
                <small>Tenaga Pendidik</small>
            </div>
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-group text-warning"></i>
                </div>
                <h6><?php echo e($totalPengurus); ?></h6>
                <small>Pengurus</small>
            </div>
        </div>
    </div>

    <small>Menu Pengurus</small>

    <!-- Services Form -->
    <div class="services-form">
        <div class="services-grid" id="servicesGrid">
            <div class="service-wrapper">
                <a href="<?php echo e(route('yayasan.index')); ?>" class="service-item">
                    <img src="<?php echo e(asset('images/menu_icon/yayasan.png')); ?>" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                </a>
                <div class="service-label">Data Yayasan</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(route('pengurus.index')); ?>" class="service-item">
                    <img src="<?php echo e(asset('images/menu_icon/pengurus.png')); ?>" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                </a>
                <div class="service-label">Data Pengurus</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(route('madrasah.index')); ?>" class="service-item">
                    <img src="<?php echo e(asset('images/menu_icon/madrasah.png')); ?>" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                </a>
                <div class="service-label">Data Madrasah</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(route('tenaga-pendidik.index')); ?>" class="service-item">
                    <img src="<?php echo e(asset('images/menu_icon/tenaga_pendidik.png')); ?>" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                </a>
                <div class="service-label">Tenaga Pendidik</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(route('status-kepegawaian.index')); ?>" class="service-item">
                    <img src="<?php echo e(asset('images/menu_icon/status_kepegawaian.png')); ?>" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                </a>
                <div class="service-label">Status Kepegawaian</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(route('tahun-pelajaran.index')); ?>" class="service-item">
                    <img src="<?php echo e(asset('images/menu_icon/tahun_pelajaran.png')); ?>" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                </a>
                <div class="service-label">Tahun Pelajaran</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(route('admin.teaching_progress')); ?>" class="service-item">
                    <img src="<?php echo e(asset('images/menu_icon/teaching_progress.png')); ?>" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                </a>
                <div class="service-label">Progres Mengajar</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(route('admin.data_madrasah')); ?>" class="service-item">
                    <img src="<?php echo e(asset('images/menu_icon/data_madrasah.png')); ?>" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                </a>
                <div class="service-label">Kelengkapan Data</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(route('uppm.index')); ?>" class="service-item">
                    <img src="<?php echo e(asset('images/menu_icon/uppm.png')); ?>" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                </a>
                <div class="service-label">UPPM</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(route('ppdb.index')); ?>" class="service-item">
                    <img src="<?php echo e(asset('images/menu_icon/ppdb.png')); ?>" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                </a>
                <div class="service-label">PPDB</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(route('development-history.index')); ?>" class="service-item">
                    <img src="<?php echo e(asset('images/menu_icon/development.png')); ?>" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                </a>
                <div class="service-label">Riwayat Pengembangan</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(route('active-users.index')); ?>" class="service-item">
                    <img src="<?php echo e(asset('images/menu_icon/active_users.png')); ?>" alt="Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; z-index: 0;">
                </a>
                <div class="service-label">Pengguna Aktif</div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/pengurus/dashboard-pengurus.blade.php ENDPATH**/ ?>