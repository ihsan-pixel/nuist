<?php $__env->startSection('title', 'Pendaftaran Wajah'); ?>
<?php $__env->startSection('subtitle', 'Daftarkan Wajah Anda'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
        }

        .enrollment-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 16px;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            width: 100%;
        }
    </style>

    <div class="enrollment-card">
        <h6 class="text-center mb-3">Pendaftaran Wajah</h6>
        <p class="text-muted small text-center mb-4">
            Fitur pendaftaran wajah sedang dalam pengembangan. Silakan gunakan metode presensi lainnya untuk saat ini.
        </p>

        <a href="<?php echo e(route('mobile.presensi')); ?>" class="btn btn-primary-custom">
            Kembali ke Presensi
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/face-enrollment.blade.php ENDPATH**/ ?>