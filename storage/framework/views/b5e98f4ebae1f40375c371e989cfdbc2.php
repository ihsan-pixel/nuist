<?php $__env->startSection('title', 'Riwayat Izin'); ?>
<?php $__env->startSection('subtitle', 'Riwayat Pengajuan Izin'); ?>

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
            <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                <i class="bx bx-history fs-2"></i>
            </div>
        </div>
        <div>
            <h6 class="mb-0">Riwayat Izin</h6>
            <small class="text-muted">Riwayat pengajuan izin Anda</small>
        </div>
    </div>
</div>

<?php if(isset($history) && $history->isEmpty()): ?>
    <div class="card empty-state shadow-sm border-0">
        <div class="card-body text-center py-5">
            <div class="avatar-xl mx-auto mb-4">
                <div class="avatar-title bg-light rounded-circle">
                    <i class="bx bx-list-ul fs-1 text-muted"></i>
                </div>
            </div>
            <h5 class="text-muted mb-2">Belum ada pengajuan izin</h5>
            <p class="text-muted mb-0">Belum ada catatan pengajuan izin pada periode ini.</p>
        </div>
    </div>
<?php else: ?>
    <div class="list-group">
        <!-- Placeholder for history items -->
        <div class="list-group-item d-flex justify-content-between align-items-start">
            <div class="me-3">
                <div class="fw-semibold">Izin Tidak Masuk</div>
                <div class="text-muted small">Tanggal: 01 Jan 2024</div>
                <div class="text-muted small">Status: Pending</div>
            </div>
            <div class="text-end">
                <span class="badge bg-warning">Pending</span>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/izin-history.blade.php ENDPATH**/ ?>