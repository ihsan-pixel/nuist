<?php $__env->startSection('title', 'Face Diagnostics'); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />
<style>
    .diag-hero {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        color: #fff;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 10px 24px rgba(0, 75, 76, 0.16);
    }

    .diag-card {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
    }

    .diag-stat {
        border-radius: 16px;
        color: #fff;
        padding: 18px;
        height: 100%;
    }

    .diag-stat small {
        display: block;
        opacity: 0.85;
    }

    .diag-badge {
        font-size: 11px;
        padding: 0.35rem 0.55rem;
        border-radius: 999px;
    }

    .mono {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
        font-size: 12px;
    }

    .table td, .table th {
        vertical-align: middle;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="diag-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="mb-1">Face Diagnostics</h4>
                <div class="opacity-75">Riwayat sukses, gagal, dan stuck untuk enrollment dan presensi wajah.</div>
            </div>
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control form-control-sm" placeholder="Cari user / device / alasan">
                </div>
                <div class="col-auto">
                    <select name="source" class="form-select form-select-sm">
                        <option value="">Semua sumber</option>
                        <option value="presensi" <?php if(request('source') === 'presensi'): echo 'selected'; endif; ?>>Presensi</option>
                        <option value="enrollment" <?php if(request('source') === 'enrollment'): echo 'selected'; endif; ?>>Enrollment</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="outcome" class="form-select form-select-sm">
                        <option value="">Semua hasil</option>
                        <option value="success" <?php if(request('outcome') === 'success'): echo 'selected'; endif; ?>>Success</option>
                        <option value="failed" <?php if(request('outcome') === 'failed'): echo 'selected'; endif; ?>>Failed</option>
                        <option value="stuck" <?php if(request('outcome') === 'stuck'): echo 'selected'; endif; ?>>Stuck</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-light btn-sm">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="diag-stat" style="background: linear-gradient(135deg, #334155 0%, #1e293b 100%);">
                <small>Total</small>
                <h3 class="mb-0"><?php echo e(number_format($summary['total'])); ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="diag-stat" style="background: linear-gradient(135deg, #0e8549 0%, #22c55e 100%);">
                <small>Success</small>
                <h3 class="mb-0"><?php echo e(number_format($summary['success'])); ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="diag-stat" style="background: linear-gradient(135deg, #b45309 0%, #f59e0b 100%);">
                <small>Failed</small>
                <h3 class="mb-0"><?php echo e(number_format($summary['failed'])); ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="diag-stat" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
                <small>Stuck</small>
                <h3 class="mb-0"><?php echo e(number_format($summary['stuck'])); ?></h3>
            </div>
        </div>
    </div>

    <div class="card diag-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Sumber</th>
                            <th>Hasil</th>
                            <th>Tahap</th>
                            <th>Alasan</th>
                            <th>Device / Browser</th>
                            <th>GPU / WebGL / TF</th>
                            <th>Video / Camera</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $diagnostics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr>
                                <td class="mono"><?php echo e($item->created_at?->format('d M Y H:i:s')); ?></td>
                                <td>
                                    <div class="fw-semibold"><?php echo e($item->user?->name ?? 'Unknown'); ?></div>
                                    <div class="text-muted small">ID: <?php echo e($item->user_id ?? '-'); ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary diag-badge text-uppercase"><?php echo e($item->source); ?></span>
                                </td>
                                <td>
                                    <?php
                                        $badge = match ($item->outcome) {
                                            'success' => 'bg-success',
                                            'failed' => 'bg-danger',
                                            'stuck' => 'bg-warning text-dark',
                                            default => 'bg-secondary',
                                        };
                                    ?>
                                    <span class="badge <?php echo e($badge); ?> diag-badge text-uppercase"><?php echo e($item->outcome); ?></span>
                                </td>
                                <td class="mono"><?php echo e($item->stage ?? '-'); ?></td>
                                <td style="max-width: 260px;">
                                    <div class="small"><?php echo e($item->reason ?? '-'); ?></div>
                                </td>
                                <td style="min-width: 220px;">
                                    <div class="small fw-semibold"><?php echo e($item->device ?? '-'); ?></div>
                                    <div class="text-muted mono"><?php echo e($item->browser ?? '-'); ?></div>
                                </td>
                                <td style="min-width: 220px;">
                                    <div class="mono">GPU: <?php echo e($item->gpu ?? '-'); ?></div>
                                    <div class="mono">WEBGL: <?php echo e($item->webgl ?? '-'); ?></div>
                                    <div class="mono">TF: <?php echo e($item->tf_backend ?? '-'); ?></div>
                                </td>
                                <td style="min-width: 200px;">
                                    <div class="mono">VIDEO: <?php echo e($item->video_size ?? '-'); ?></div>
                                    <div class="mono">READY: <?php echo e($item->ready_state ?? '-'); ?></div>
                                    <div class="mono">CAMERA: <?php echo e($item->camera_state ?? '-'); ?></div>
                                </td>
                            </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item->details)): ?>
                                <tr class="table-light">
                                    <td colspan="9">
                                        <div class="mono text-muted">
                                            <?php echo e(\Illuminate\Support\Str::limit(json_encode($item->details, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 320)); ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">Belum ada data diagnostik.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <?php echo e($diagnostics->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/admin/face_diagnostics/index.blade.php ENDPATH**/ ?>