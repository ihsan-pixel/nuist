<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('subtitle', 'Ringkasan Aktivitas'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb url('<?php echo e(asset("images/bg.png")); ?>') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 12px;
            padding: 12px 10px;
            /* box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3); */
            margin-bottom: 10px;
        }

        .dashboard-header img {
            border: 2px solid #fff;
        }

        .dashboard-header h6 {
            font-weight: 600;
            font-size: 12px;
        }

        .dashboard-header h5 {
            font-size: 14px;
        }

        .dashboard-header .welcome-text {
            color: #fff !important;
        }

        .stats-form {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 12px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }

        .stat-item {
            text-align: center;
            padding: 6px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .stat-item .icon-container {
            margin-bottom: 4px;
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
            /* background: #fff; */
            border-radius: 12px;
            padding: 12px;
            /* box-shadow: 0 2px 8px rgba(0,0,0,0.05); */
            margin-bottom: 12px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            text-align: center;
        }

        .service-item {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border-radius: 10px;
            padding: 14px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease-in-out;
        }

        .service-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        .service-item i {
            font-size: 20px;
            color: #fff;
        }

        .service-label {
            font-size: 10px;
            font-weight: 600;
            margin-top: 6px;
            color: #333;
        }

        .service-item i {
            font-size: 24px;
            color: #ffffff;
        }

        .service-item h6 {
            font-size: 10px;
            margin-bottom: 0;
            font-weight: 600;
            color: #ffffff;
        }

        .info-section {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 12px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .info-item {
            padding: 6px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .info-item small {
            color: #6c757d;
            font-size: 10px;
        }

        .info-item strong {
            font-size: 11px;
            color: #333;
        }

        .schedule-section {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            /* box-shadow: 0 2px 8px rgba(0,0,0,0.05); */
            margin-bottom: 12px;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .schedule-item {
            padding: 6px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .schedule-item strong {
            font-size: 11px;
            color: #333;
        }

        .schedule-item small {
            color: #6c757d;
            font-size: 10px;
        }

        .quick-actions {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 60px;
        }

        .quick-actions-header {
            background: #f8f9fa;
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .quick-actions-header h6 {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin: 0;
        }

        .quick-actions-content {
            padding: 12px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .action-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            border-radius: 8px;
            padding: 12px 8px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 500;
            text-align: center;
            transition: all 0.2s;
        }

        .action-button:hover {
            background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
            color: white;
            transform: translateY(-1px);
        }

        .action-button i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .section-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            color: #333;
        }

        .no-schedule {
            text-align: center;
            padding: 16px;
            color: #999;
        }

        .no-schedule i {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .no-schedule p {
            font-size: 12px;
            margin: 0;
        }

        /* Banner Modal Styles */
        .modal-content {
            border-radius: 15px;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.8);
        }

    </style>

    <!-- Show banner modal on page load -->
    <?php if($bannerImage): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var welcomeModal = new bootstrap.Modal(document.getElementById('welcomeBannerModal'), {
                backdrop: 'static',
                keyboard: false
            });
            welcomeModal.show();

            // Auto hide after 5 seconds
            setTimeout(function() {
                welcomeModal.hide();
            }, 5000);
        });
    </script>
    <?php endif; ?>

    <!-- Header -->
    

    <!-- Banner Modal -->
    <?php if($bannerImage): ?>
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
    <?php endif; ?>

    <!-- Stats Form -->
    <div class="stats-form">
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="welcome-text mb-1">Asal Madrasah/Sekolah</small>
                    <h5 class="fw-bold mb-0"><?php echo e(Auth::user()->madrasah?->name ?? 'Madrasah belum diatur'); ?></h5>
                </div>
            </div>
        </div>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-check-circle text-success"></i>
                </div>
                <h6><?php echo e($kehadiranPercent); ?>%</h6>
                <small>Kehadiran</small>
            </div>
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-calendar text-primary"></i>
                </div>
                <h6><?php echo e($totalBasis); ?></h6>
                <small>Presensi</small>
            </div>
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-time text-warning"></i>
                </div>
                <h6><?php echo e($izin); ?></h6>
                <small>Izin</small>
            </div>
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-x text-danger"></i>
                </div>
                <h6><?php echo e($alpha); ?></h6>
                <small>
                    <a href="<?php echo e(route('mobile.riwayat-presensi-alpha')); ?>" class="text-decoration-none text-danger" style="font-size: 9px;">Tidak Hadir</a>
                </small>
            </div>
        </div>
    </div>

    <small>Layanan</small>

    <!-- Services Form -->
    <div class="services-form">
        <div class="services-grid">
            <div>
                <a href="<?php echo e(route('mobile.presensi')); ?>" class="service-item">
                    <i class="bx bx-fingerprint"></i>
                </a>
                <div class="service-label">Presensi</div>
            </div>
            <div>
                <a href="<?php echo e(route('mobile.teaching-attendances')); ?>" class="service-item">
                    <i class="bx bx-chalkboard"></i>
                </a>
                <div class="service-label">Presensi Mengajar</div>
            </div>
            <div>
                <a href="<?php echo e(route('mobile.izin', ['type' => 'cuti'])); ?>" class="service-item">
                    <i class="bx bx-calendar-star"></i>
                </a>
                <div class="service-label">Izin Cuti</div>
            </div>
            <div>
                <a href="<?php echo e(route('mobile.izin', ['type' => 'terlambat'])); ?>" class="service-item">
                    <i class="bx bx-time-five"></i>
                </a>
                <div class="service-label">Izin Terlambat</div>
            </div>
            <div>
                <a href="<?php echo e(route('mobile.izin', ['type' => 'sakit'])); ?>" class="service-item">
                    <i class="bx bx-plus-medical"></i>
                </a>
                <div class="service-label">Izin Sakit</div>
            </div>
            <div>
                <a href="<?php echo e(route('mobile.izin', ['type' => 'tugas_luar'])); ?>" class="service-item">
                    <i class="bx bx-briefcase"></i>
                </a>
                <div class="service-label">Izin Dinas Luar</div>
            </div>
            <div>
                <a href="<?php echo e(route('mobile.jadwal')); ?>" class="service-item">
                    <i class="bx bx-calendar"></i>
                </a>
                <div class="service-label">Jadwal Mengajar</div>
            </div>
            <div>
                <a href="<?php echo e(route('mobile.laporan')); ?>" class="service-item">
                    <i class="bx bx-file"></i>
                </a>
                <div class="service-label">Laporan</div>
            </div>
            <div>
                <a href="<?php echo e(route('mobile.profile')); ?>" class="service-item">
                    <i class="bx bx-user"></i>
                </a>
                <div class="service-label">Profile</div>
            </div>
            <div>
                <a href="<?php echo e(route('mobile.ubah-akun')); ?>" class="service-item">
                    <i class="bx bx-cog"></i>
                </a>
                <div class="service-label">Pengaturan</div>
            </div>

            <?php if(Auth::user()->role === 'tenaga_pendidik' && Auth::user()->ketugasan === 'kepala madrasah/sekolah'): ?>
            <div>
                <a href="<?php echo e(route('mobile.kelola-izin')); ?>" class="service-item">
                    <i class="bx bx-edit"></i>
                </a>
                <div class="service-label">Kelola Izin</div>
            </div>
            <div>
                <a href="<?php echo e(route('mobile.monitor-presensi')); ?>" class="service-item">
                    <i class="bx bx-calendar-check"></i>
                </a>
                <div class="service-label">Data Presensi</div>
            </div>
            <?php endif; ?>
        </div>
    </div>


    <!-- Teacher Info -->
    

    <small>Jadwal Hari Ini</small>

    <!-- Schedule Section -->
    <div class="schedule-section">
        
        <?php if($todaySchedules->count() > 0): ?>
            <div class="schedule-grid">
                <?php $__currentLoopData = $todaySchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="schedule-item">
                        <strong class="d-block"><?php echo e($schedule->subject); ?></strong>
                        <small class="d-block text-muted"><?php echo e($schedule->class_name); ?></small>
                        <small class="d-block text-muted"><i class="bx bx-time-five"></i> <?php echo e($schedule->start_time); ?> - <?php echo e($schedule->end_time); ?></small>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="no-schedule">
                <i class="bx bx-calendar-x"></i>
                <p>Tidak ada jadwal mengajar hari ini</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/dashboard.blade.php ENDPATH**/ ?>