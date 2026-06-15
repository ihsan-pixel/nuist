<?php $__env->startSection('title', 'Approval Event'); ?>
<?php $__env->startSection('subtitle', 'Persetujuan Kalender Akademik'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 720px; margin: auto;">
    <style>
        .approval-card {
            border-radius: 16px;
            overflow: hidden;
        }

        .approval-card .card-body {
            padding: 14px;
        }

        .approval-meta {
            display: grid;
            gap: 6px;
        }

        .approval-meta-item {
            font-size: 12px;
            color: #6c757d;
            line-height: 1.35;
        }

        .approval-badge {
            font-size: 10px;
            padding: 6px 9px;
            border-radius: 999px;
            white-space: normal;
            text-align: center;
            max-width: 140px;
        }

        .approval-note {
            font-size: 12px;
            border-radius: 12px;
            padding: 10px 12px;
            margin-bottom: 10px;
        }

        .approval-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .approval-textarea {
            min-height: 68px;
            resize: vertical;
            font-size: 12px;
            margin-bottom: 8px;
        }

        @media (max-width: 576px) {
            .approval-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="d-flex align-items-center mb-3">
        <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <div>
            <div class="fw-bold" style="color: #004b4c; font-size: 16px;">Approval Kegiatan</div>
            <small class="text-muted"><?php echo e($school->name); ?></small>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success border-0 shadow-sm"><?php echo e(session('success')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($events->isEmpty()): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bx bx-calendar-x fs-1 text-muted"></i>
                <h6 class="mt-3 mb-1">Belum ada event akademik</h6>
                <small class="text-muted">Admin sekolah belum mengajukan event untuk disetujui.</small>
            </div>
        </div>
    <?php else: ?>
        <div class="d-grid gap-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <?php
                    $badgeClass = match ($event->approval_status) {
                        \App\Models\AcademicCalendarEvent::APPROVAL_APPROVED => 'bg-success',
                        \App\Models\AcademicCalendarEvent::APPROVAL_REJECTED => 'bg-danger',
                        default => 'bg-warning text-dark',
                    };
                ?>
                <div class="card border-0 shadow-sm approval-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <div class="pe-2">
                                <h6 class="mb-1" style="font-size: 14px;"><?php echo e($event->name); ?></h6>
                                <div class="text-muted small"><?php echo e($event->resolved_type_label); ?></div>
                            </div>
                            <span class="badge <?php echo e($badgeClass); ?> approval-badge"><?php echo e($event->approval_status_label); ?></span>
                        </div>

                        <div class="approval-meta mb-2">
                            <div class="approval-meta-item">
                                <i class="bx bx-calendar me-1"></i><?php echo e($event->date_range_label); ?>

                            </div>
                            <div class="approval-meta-item">
                                <i class="bx bx-time me-1"></i><?php echo e($event->time_range_label); ?>

                            </div>
                            <div class="approval-meta-item">
                                <i class="bx bx-user me-1"></i><?php echo e($event->creator->name ?? '-'); ?>

                                <span class="mx-1">•</span>
                                diperbarui <?php echo e(optional($event->updated_at)->timezone('Asia/Jakarta')->format('d M Y H:i')); ?>

                            </div>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->description): ?>
                            <div class="approval-meta-item mb-2">
                                <i class="bx bx-note me-1"></i><?php echo e($event->description); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->approval_notes): ?>
                            <div class="approval-meta-item mb-2">
                                <i class="bx bx-message-detail me-1"></i><?php echo e($event->approval_notes); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->approver): ?>
                            <div class="approval-meta-item mb-2">
                                <i class="bx bx-check-shield me-1"></i><?php echo e($event->approver->name); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->approved_at): ?>
                                    pada <?php echo e(optional($event->approved_at)->timezone('Asia/Jakarta')->format('d M Y H:i')); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->approval_status === \App\Models\AcademicCalendarEvent::APPROVAL_PENDING): ?>
                            <div class="alert alert-warning border-0 approval-note">
                                Setelah disetujui, semua jadwal mengajar pada tanggal event ini akan berstatus izin.
                            </div>

                            <form method="POST" action="<?php echo e(route('mobile.academic-calendar-approvals.approve', $event)); ?>" id="approve-form-<?php echo e($event->id); ?>">
                                <?php echo csrf_field(); ?>
                                <textarea name="approval_notes" class="form-control form-control-sm approval-textarea" placeholder="Catatan approval atau penolakan (opsional)"></textarea>
                                <div class="approval-actions">
                                    <button type="submit" class="btn btn-success">
                                            <i class="bx bx-check-circle me-1"></i>Setujui Event
                                    </button>
                                    <button
                                        type="submit"
                                        class="btn btn-outline-danger"
                                        formaction="<?php echo e(route('mobile.academic-calendar-approvals.reject', $event)); ?>"
                                        formmethod="POST"
                                    >
                                            <i class="bx bx-x-circle me-1"></i>Tolak Event
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/academic-calendar-approvals.blade.php ENDPATH**/ ?>