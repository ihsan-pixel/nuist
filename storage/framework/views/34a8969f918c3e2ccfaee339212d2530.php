<?php $__env->startSection('title', 'Approval Event'); ?>
<?php $__env->startSection('subtitle', 'Persetujuan Kalender Akademik dan Jadwal Piket'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 720px; margin: auto;">
    <style>
        .approval-summary-card,
        .approval-item-card {
            border-radius: 18px;
        }

        .approval-summary-card {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
        }

        .approval-summary-card .card-body {
            padding: 18px;
        }

        .approval-summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-top: 14px;
        }

        .approval-summary-stat {
            background: rgba(255, 255, 255, 0.14);
            border-radius: 14px;
            padding: 10px 12px;
        }

        .approval-summary-stat small {
            display: block;
            opacity: 0.78;
            font-size: 11px;
            margin-bottom: 2px;
        }

        .approval-summary-stat strong {
            font-size: 18px;
            line-height: 1;
        }

        .approval-item-card {
            border: 1px solid #edf1ef;
            box-shadow: 0 10px 24px rgba(15, 56, 57, 0.08);
        }

        .approval-item-card .card-body {
            padding: 14px;
        }

        .approval-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            line-height: 1;
        }

        .approval-chip-type {
            background: #eef7f4;
            color: #0e8549;
        }

        .approval-chip-latest {
            background: #fff5d6;
            color: #946200;
        }

        .approval-status-pending {
            background: #fff3cd;
            color: #7a5a00;
        }

        .approval-status-approved {
            background: #d1e7dd;
            color: #0f5132;
        }

        .approval-status-rejected {
            background: #f8d7da;
            color: #842029;
        }

        .approval-item-title {
            font-size: 14px;
            line-height: 1.35;
        }

        .approval-item-subtitle,
        .approval-item-meta,
        .approval-item-detail,
        .approval-item-note {
            font-size: 12px;
            line-height: 1.45;
        }

        .approval-item-subtitle,
        .approval-item-meta,
        .approval-item-detail {
            color: #6c757d;
        }

        .approval-item-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 12px;
            margin-top: 8px;
        }

        .approval-item-detail-list {
            display: grid;
            gap: 6px;
            margin-top: 10px;
        }

        .approval-item-note {
            margin-top: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            background: #f6f8f8;
            color: #4d5a5a;
        }

        .approval-form {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #edf1ef;
        }

        .approval-form .form-control {
            min-height: 64px;
            font-size: 12px;
            padding: 10px 12px;
        }

        .approval-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 8px;
        }

        .approval-empty-card {
            border-radius: 18px;
        }

        @media (max-width: 576px) {
            .approval-actions {
                grid-template-columns: 1fr;
            }

            .approval-summary-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 8px;
            }

            .approval-summary-stat {
                padding: 10px 8px;
                text-align: center;
            }

            .approval-summary-stat small {
                font-size: 10px;
            }

            .approval-summary-stat strong {
                font-size: 16px;
            }
        }
    </style>

    <?php
        $pendingItems = $approvalItems->where('status', 'pending')->values();
        $pendingCount = $approvalItems->where('status', 'pending')->count();
        $approvedCount = $approvalItems->where('status', 'approved')->count();
        $rejectedCount = $approvalItems->where('status', 'rejected')->count();
    ?>

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

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($approvalItems->isEmpty()): ?>
        <div class="card border-0 shadow-sm approval-empty-card">
            <div class="card-body text-center py-5">
                <i class="bx bx-calendar-x fs-1 text-muted"></i>
                <h6 class="mt-3 mb-1">Belum ada pengajuan</h6>
                <small class="text-muted">Belum ada event akademik atau jadwal piket yang masuk ke antrian approval.</small>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm approval-summary-card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between gap-3">
                    <div>
                        <div class="fw-semibold" style="font-size: 16px;">Daftar approval</div>
                        <div style="font-size: 12px; opacity: 0.82;">Pengajuan terbaru tampil paling atas.</div>
                    </div>
                    <div class="text-end">
                        <div style="font-size: 11px; opacity: 0.78;">Total pengajuan</div>
                        <div class="fw-bold" style="font-size: 24px; line-height: 1;"><?php echo e($approvalItems->count()); ?></div>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingItems->isNotEmpty()): ?>
                    <div class="mt-3">
                        <button
                            type="button"
                            class="btn btn-light btn-sm w-100"
                            data-bs-toggle="modal"
                            data-bs-target="#approveAllModal"
                            style="border-radius: 12px; font-weight: 600;"
                        >
                            <i class="bx bx-check-double me-1"></i>Setujui Semua Pengajuan Pending
                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="approval-summary-grid">
                    <div class="approval-summary-stat">
                        <small>Menunggu</small>
                        <strong><?php echo e($pendingCount); ?></strong>
                    </div>
                    <div class="approval-summary-stat">
                        <small>Disetujui</small>
                        <strong><?php echo e($approvedCount); ?></strong>
                    </div>
                    <div class="approval-summary-stat">
                        <small>Ditolak</small>
                        <strong><?php echo e($rejectedCount); ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $approvalItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <?php
                    $model = $item['model'];
                    $isEvent = $item['kind'] === 'event';
                    $statusClass = match ($item['status']) {
                        'approved' => 'approval-status-approved',
                        'rejected' => 'approval-status-rejected',
                        default => 'approval-status-pending',
                    };
                    $typeLabel = $isEvent ? 'Event Akademik' : 'Jadwal Piket';
                    $title = $isEvent ? $model->name : ($model->user->name ?? '-');
                    $subtitle = $isEvent ? $model->resolved_type_label : ($model->period->name ?? 'Periode piket');
                    $requestedAt = $item['requested_at']
                        ? \Carbon\Carbon::parse($item['requested_at'])->timezone('Asia/Jakarta')->format('d M Y H:i')
                        : null;
                    $approverName = $model->approver->name ?? null;
                    $approvedAt = $model->approved_at
                        ? \Carbon\Carbon::parse($model->approved_at)->timezone('Asia/Jakarta')->format('d M Y H:i')
                        : null;
                    $helperNote = $isEvent
                        ? 'Jika disetujui, jadwal mengajar pada tanggal event ini akan tercatat sebagai izin.'
                        : 'Jika disetujui, hari yang dipilih akan menjadi jadwal piket resmi pada masa libur semester.';
                ?>

                <div class="card border-0 approval-item-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div class="pe-2">
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    <span class="approval-chip approval-chip-type"><?php echo e($typeLabel); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($loop->first): ?>
                                        <span class="approval-chip approval-chip-latest">Terbaru</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="fw-semibold approval-item-title"><?php echo e($title); ?></div>
                                <div class="approval-item-subtitle"><?php echo e($subtitle); ?></div>
                            </div>
                            <span class="approval-chip <?php echo e($statusClass); ?>"><?php echo e($model->approval_status_label); ?></span>
                        </div>

                        <div class="approval-item-meta">
                            <span><i class="bx bx-time-five me-1"></i>Diajukan <?php echo e($requestedAt ?: '-'); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEvent): ?>
                                <span><i class="bx bx-user me-1"></i><?php echo e($model->creator->name ?? '-'); ?></span>
                            <?php else: ?>
                                <span><i class="bx bx-calendar me-1"></i><?php echo e($model->period->date_range_label ?? '-'); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="approval-item-detail-list">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEvent): ?>
                                <div class="approval-item-detail"><i class="bx bx-calendar-event me-1"></i><?php echo e($model->date_range_label); ?></div>
                                <div class="approval-item-detail"><i class="bx bx-time me-1"></i><?php echo e($model->time_range_label); ?></div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($model->description): ?>
                                    <div class="approval-item-detail"><i class="bx bx-note me-1"></i><?php echo e(\Illuminate\Support\Str::limit($model->description, 140)); ?></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php else: ?>
                                <div class="approval-item-detail"><i class="bx bx-list-check me-1"></i><?php echo e($model->selected_dates_count); ?> hari dipilih</div>
                                <div class="approval-item-detail"><i class="bx bx-check-square me-1"></i><?php echo e(\Illuminate\Support\Str::limit(implode(', ', $model->selected_date_labels), 140)); ?></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($model->approval_notes): ?>
                            <div class="approval-item-note">
                                <i class="bx bx-message-detail me-1"></i><?php echo e($model->approval_notes); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($approverName): ?>
                            <div class="approval-item-note">
                                <i class="bx bx-check-shield me-1"></i><?php echo e($approverName); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($approvedAt): ?>
                                    <span class="text-muted"> • <?php echo e($approvedAt); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['status'] === \App\Models\AcademicCalendarEvent::APPROVAL_PENDING): ?>
                            <div class="approval-item-note"><?php echo e($helperNote); ?></div>

                            <form method="POST" action="<?php echo e($isEvent ? route('mobile.academic-calendar-approvals.approve', $model) : route('mobile.academic-calendar-approvals.picket-submissions.approve', $model)); ?>" class="approval-form">
                                <?php echo csrf_field(); ?>
                                <textarea name="approval_notes" class="form-control form-control-sm" placeholder="Catatan approval atau penolakan (opsional)"></textarea>
                                <div class="approval-actions">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bx bx-check-circle me-1"></i><?php echo e($isEvent ? 'Setujui Event' : 'Setujui Jadwal'); ?>

                                    </button>
                                    <button
                                        type="submit"
                                        class="btn btn-outline-danger"
                                        formaction="<?php echo e($isEvent ? route('mobile.academic-calendar-approvals.reject', $model) : route('mobile.academic-calendar-approvals.picket-submissions.reject', $model)); ?>"
                                        formmethod="POST"
                                    >
                                        <i class="bx bx-x-circle me-1"></i><?php echo e($isEvent ? 'Tolak Event' : 'Tolak Jadwal'); ?>

                                    </button>
                                </div>
                            </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingItems->isNotEmpty()): ?>
        <div class="modal fade" id="approveAllModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0" style="border-radius: 18px;">
                    <div class="modal-header">
                        <h5 class="modal-title">Setujui Semua Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-muted small mb-3">
                            Pengajuan berikut akan langsung disetujui sekaligus:
                        </div>

                        <div class="d-grid gap-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pendingItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <?php
                                    $model = $item['model'];
                                    $isEvent = $item['kind'] === 'event';
                                    $modalTitle = $isEvent ? $model->name : ($model->user->name ?? '-');
                                    $modalSubtitle = $isEvent
                                        ? ($model->resolved_type_label . ' • ' . $model->date_range_label)
                                        : (($model->period->name ?? 'Periode piket') . ' • ' . ($model->selected_dates_count ?? 0) . ' hari');
                                ?>
                                <div class="border rounded-3 p-2">
                                    <div class="fw-semibold" style="font-size: 13px;"><?php echo e($modalTitle); ?></div>
                                    <div class="text-muted" style="font-size: 11px;"><?php echo e($isEvent ? 'Event Akademik' : 'Jadwal Piket'); ?></div>
                                    <div class="text-muted" style="font-size: 11px;"><?php echo e($modalSubtitle); ?></div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <form method="POST" action="<?php echo e(route('mobile.academic-calendar-approvals.approve-all')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bx bx-check-double me-1"></i>Ya, Setujui Semua
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/academic-calendar-approvals.blade.php ENDPATH**/ ?>