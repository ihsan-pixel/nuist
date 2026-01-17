<?php $__env->startSection('title', 'Laporan Mengajar'); ?>
<?php $__env->startSection('subtitle', 'Riwayat Presensi Mengajar'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Back Button -->
    <div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
        <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <span class="fw-bold" style="color: #004b4c; font-size: 12px;">Kembali</span>
    </div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body d-flex align-items-center">
        <div class="avatar-lg me-3">
            <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                <i class="bx bx-history fs-2"></i>
            </div>
        </div>
        <div>
            <h6 class="mb-0">Riwayat Presensi Mengajar</h6>
            <small class="text-muted">Riwayat presensi mengajar untuk bulan ini</small>
        </div>
    </div>
</div>

<?php if($history->isEmpty()): ?>
    <div class="card empty-state shadow-sm border-0">
        <div class="card-body text-center py-5">
            <div class="avatar-xl mx-auto mb-4">
                <div class="avatar-title bg-light rounded-circle">
                    <i class="bx bx-list-ul fs-1 text-muted"></i>
                </div>
            </div>
            <h5 class="text-muted mb-2">Belum ada presensi mengajar</h5>
            <p class="text-muted mb-0">Belum ada catatan presensi mengajar pada periode ini.</p>
        </div>
    </div>
<?php else: ?>
    <div class="list-group">
        <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-3">
                    <div class="fw-semibold"><?php echo e(optional($item->teachingSchedule)->subject ?? 'Mata Pelajaran'); ?></div>
                    <div class="text-muted small"><?php echo e(optional($item->teachingSchedule->school)->name ?? '-'); ?></div>
                    <div class="text-muted small">Kelas: <?php echo e(optional($item->teachingSchedule)->class_name ?? '-'); ?></div>
                </div>
                <div class="text-end">
                    <div class="fw-semibold"><?php echo e(\Carbon\Carbon::parse($item->tanggal)->format('d M Y')); ?></div>
                    <div class="text-muted small">Waktu: <?php echo e($item->waktu); ?></div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/laporan-mengajar.blade.php ENDPATH**/ ?>