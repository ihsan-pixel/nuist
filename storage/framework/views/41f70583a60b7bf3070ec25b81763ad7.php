<?php $__env->startSection('title', 'Kelola Izin'); ?>
<?php $__env->startSection('subtitle', 'Pengelolaan Pengajuan Izin'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        .izin-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border: 1px solid #f0f0f0;
        }

        .izin-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .izin-user {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 4px;
        }

        .izin-date {
            font-size: 12px;
            color: #666;
        }

        .izin-type {
            font-size: 12px;
            color: #0e8549;
            font-weight: 500;
            background: #f0f8f0;
            padding: 2px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        .izin-description {
            font-size: 13px;
            color: #555;
            line-height: 1.4;
            margin-bottom: 12px;
        }

        .izin-actions {
            display: flex;
            gap: 8px;
        }

        .btn-approve {
            background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 500;
            flex: 1;
            text-align: center;
            text-decoration: none;
        }

        .btn-reject {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 500;
            flex: 1;
            text-align: center;
            text-decoration: none;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 14px;
            margin: 0;
        }

        .filter-tabs {
            display: flex;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 4px;
            margin-bottom: 16px;
        }

        .filter-tab {
            flex: 1;
            text-align: center;
            padding: 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            color: #666;
            text-decoration: none;
            transition: all 0.2s;
        }

        .filter-tab.active {
            background: white;
            color: #0e8549;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .back-btn {
            background: none;
            border: none;
            color: #004b4c;
            font-size: 16px;
            padding: 8px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            background: #f0f8f0;
        }
    </style>

    <!-- Back Button -->
    <a href="<?php echo e(route('mobile.dashboard')); ?>" class="back-btn" style="text-decoration: none;">
        <i class="bx bx-arrow-back"></i>
        <span>Kembali</span>
    </a>

    <h6 class="mb-3 fw-bold">Kelola Pengajuan Izin</h6>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="<?php echo e(route('mobile.kelola-izin', ['status' => 'pending'])); ?>"
           class="filter-tab <?php echo e(request('status', 'pending') === 'pending' ? 'active' : ''); ?>">
            Pending
        </a>
        <a href="<?php echo e(route('mobile.kelola-izin', ['status' => 'all'])); ?>"
           class="filter-tab <?php echo e(request('status') === 'all' ? 'active' : ''); ?>">
            Semua
        </a>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($izinRequests->isEmpty()): ?>
        <div class="empty-state">
            <i class="bx bx-file-blank"></i>
            <p>Tidak ada pengajuan izin <?php echo e(request('status') === 'all' ? '' : 'pending'); ?></p>
        </div>
    <?php else: ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $izinRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $izin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <div class="izin-card">
            <div class="izin-header">
                <div>
                    <div class="izin-user"><?php echo e($izin->user->name); ?></div>
                    <div class="izin-date"><?php echo e($izin->tanggal->format('d M Y')); ?></div>
                    <?php
                        $jenisIzin = 'Izin';
                        if ($izin->type === 'sakit') {
                            $jenisIzin = 'Izin Sakit';
                        } elseif ($izin->type === 'tidak_masuk') {
                            $jenisIzin = 'Izin Tidak Masuk';
                        } elseif ($izin->type === 'terlambat') {
                            $jenisIzin = 'Izin Terlambat';
                        } elseif ($izin->type === 'tugas_luar') {
                            $jenisIzin = 'Izin Tugas Luar';
                        } elseif ($izin->type === 'cuti') {
                            $jenisIzin = 'Izin Cuti';
                        }
                    ?>
                    <div class="izin-type"><?php echo e($jenisIzin); ?></div>
                </div>
                <div>
                    <span class="status-badge status-<?php echo e($izin->status); ?>">
                        <?php echo e(ucfirst($izin->status)); ?>

                    </span>
                </div>
            </div>

            <div class="izin-description">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($izin->type === 'tugas_luar'): ?>
                    <strong>Deskripsi:</strong> <?php echo e($izin->deskripsi_tugas); ?><br>
                    <strong>Lokasi:</strong> <?php echo e($izin->lokasi_tugas); ?><br>
                    <strong>Waktu:</strong> <?php echo e($izin->waktu_masuk); ?> - <?php echo e($izin->waktu_keluar); ?>

                <?php else: ?>
                    <?php echo e($izin->alasan); ?>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($izin->file_path): ?>
            <div class="mb-3">
                <a href="<?php echo e(asset('storage/' . $izin->file_path)); ?>"
                   target="_blank"
                   class="text-decoration-none"
                   style="color: #0e8549; font-size: 12px;">
                    <i class="bx bx-file"></i> Lihat Surat Izin
                </a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($izin->status === 'pending'): ?>
            <div class="izin-actions">
                <!-- Izin dari tabel izins -->
                <form action="<?php echo e(route('izin.model.approve', $izin->id)); ?>" method="POST" style="flex: 1;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-approve">
                        <i class="bx bx-check"></i> Setujui
                    </button>
                </form>

                <form action="<?php echo e(route('izin.model.reject', $izin->id)); ?>" method="POST" style="flex: 1;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-reject">
                        <i class="bx bx-x"></i> Tolak
                    </button>
                </form>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        <!-- Pagination -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($izinRequests->hasPages()): ?>
        <div class="d-flex justify-content-center mt-3">
            <?php echo e($izinRequests->appends(request()->query())->links()); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve/reject actions with SweetAlert confirmation
    document.querySelectorAll('form[action*="approve"], form[action*="reject"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const isApprove = this.action.includes('approve');
            const confirmMessage = isApprove ?
                'Apakah Anda yakin ingin menyetujui pengajuan izin ini?' :
                'Apakah Anda yakin ingin menolak pengajuan izin ini?';
            const confirmTitle = isApprove ? 'Setujui Izin' : 'Tolak Izin';

            Swal.fire({
                title: confirmTitle,
                text: confirmMessage,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: isApprove ? '#0e8549' : '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: isApprove ? 'Ya, Setujui' : 'Ya, Tolak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    const button = this.querySelector('button');
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';
                    button.disabled = true;

                    // Submit form
                    this.submit();
                }
            });
        });
    });

    // Show success/error alerts from session
    <?php if(session('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '<?php echo e(session("success")); ?>',
            timer: 3000,
            showConfirmButton: false
        });
    <?php endif; ?>

    <?php if(session('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '<?php echo e(session("error")); ?>',
            timer: 3000,
            showConfirmButton: false
        });
    <?php endif; ?>
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/kelola-izin.blade.php ENDPATH**/ ?>