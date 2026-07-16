<?php $__env->startSection('title', 'Pengaturan Presensi'); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />
<style>
    .settings-card {
        border: 0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        border-radius: 20px;
    }

    .runtime-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(13, 110, 253, 0.08);
        color: #0d6efd;
        font-size: 12px;
        font-weight: 600;
    }

    .section-shell {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: #fff;
    }

    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 16px;
    }

    .section-title {
        font-size: 17px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .section-subtitle {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 0;
    }

    .option-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fff;
    }

    .option-card.active {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.08);
        background: #f8fbff;
    }

    .option-card input[type="radio"] {
        margin-top: 2px;
    }

    .option-name {
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .option-desc {
        color: #64748b;
        font-size: 12px;
        line-height: 1.45;
    }

    .compact-hint {
        padding: 10px 12px;
        border-radius: 12px;
        background: #fff7ed;
        color: #9a3412;
        font-size: 12px;
        line-height: 1.5;
    }

    .school-runtime-table thead th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        border-bottom-width: 1px;
        white-space: nowrap;
    }

    .school-runtime-table tbody td {
        vertical-align: top;
        padding-top: 12px;
        padding-bottom: 12px;
        font-size: 13px;
    }

    .school-name {
        font-weight: 600;
        color: #0f172a;
    }

    .school-meta {
        color: #64748b;
        font-size: 12px;
    }

    .schedule-stack {
        min-width: 135px;
    }

    .schedule-stack .schedule-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 3px;
    }

    .schedule-stack .schedule-time {
        font-weight: 500;
        color: #0f172a;
        line-height: 1.45;
    }

    .schedule-stack .schedule-note {
        color: #64748b;
        font-size: 11px;
        margin-top: 4px;
        line-height: 1.4;
    }

    .note-compact {
        color: #64748b;
        font-size: 12px;
        line-height: 1.45;
        max-width: 280px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Presensi Admin <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Pengaturan Presensi Mobile <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card settings-card">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                    <div>
                        <h4 class="mb-1 fw-semibold">
                            <i class="bx bx-mobile-alt me-2"></i>Pengaturan Presensi Mobile
                        </h4>
                        <p class="text-muted mb-0 small">
                            Metode verifikasi aktif dan ringkasan runtime presensi seluruh sekolah.
                        </p>
                    </div>
                    <div
                        class="runtime-chip"
                        id="runtime-clock"
                        data-initial-time="<?php echo e($now->toIso8601String()); ?>"
                    >
                        <i class="bx bx-time-five"></i>
                        Runtime hari ini: <?php echo e($now->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm:ss')); ?>

                    </div>
                </div>

                <div class="d-flex justify-content-end mb-3">
                    <a href="<?php echo e(route('presensi_admin.kiosk_devices')); ?>" class="btn btn-outline-primary btn-sm">
                        <i class="bx bx-desktop me-1"></i>Kelola Komputer Presensi Sekolah
                    </a>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bx bx-check-circle me-2"></i><?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bx bx-error-circle me-2"></i><?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="section-shell mb-4">
                    <div class="card-body p-4">
                        <div class="section-head">
                            <div>
                                <div class="section-title">
                                    <i class="bx bx-shield-quarter me-2"></i>Metode Verifikasi Mobile
                                </div>
                                <p class="section-subtitle">Pilih metode verifikasi untuk presensi kehadiran mobile.</p>
                            </div>
                        </div>

                        <form method="POST" action="<?php echo e(route('presensi_admin.updateSettings')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="row g-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $verificationOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <div class="col-lg-6">
                                        <label class="option-card h-100 <?php echo e($verificationMode === $value ? 'active' : ''); ?>">
                                            <div class="d-flex gap-3">
                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="mobile_attendance_verification_mode"
                                                    value="<?php echo e($value); ?>"
                                                    <?php echo e($verificationMode === $value ? 'checked' : ''); ?>

                                                >
                                                <div>
                                                    <div class="option-name"><?php echo e($option['label']); ?></div>
                                                    <div class="option-desc"><?php echo e($option['description']); ?></div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>

                            <div class="compact-hint mt-3 mb-3">
                                <i class="bx bx-info-circle me-2"></i>
                                Jika memilih <strong>scan wajah</strong>, guru harus mendaftarkan wajah lebih dulu dari menu mobile.
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i>Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="section-shell">
                    <div class="card-body p-4">
                        <div class="section-head">
                            <div>
                                <div class="section-title">
                                    <i class="bx bx-calendar-event me-2"></i>Pengaturan yang Sedang Berjalan
                                </div>
                                <p class="section-subtitle">Seluruh sekolah, Hari KBM, serta jadwal hari ini, Jumat, dan Sabtu.</p>
                            </div>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                                <?php echo e($runtimeCards->count()); ?> sekolah
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0 school-runtime-table">
                                <thead>
                                    <tr>
                                        <th>Sekolah</th>
                                        <th>Hari KBM</th>
                                        <th>Hari Ini</th>
                                        <th>Jumat</th>
                                        <th>Sabtu</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $runtimeCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <tr>
                                            <td>
                                                <div class="school-name"><?php echo e($card['school_name']); ?></div>
                                                <div class="school-meta">
                                                    <?php echo e($card['kabupaten'] ?: 'Kabupaten belum diatur'); ?>

                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($card['scod']): ?>
                                                        • SCOD <?php echo e($card['scod']); ?>

                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                    <?php echo e($card['hari_kbm_label']); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <div class="schedule-stack">
                                                    <div class="schedule-label"><?php echo e($card['today_schedule']['label']); ?></div>
                                                    <div class="schedule-time">M: <?php echo e($card['today_schedule']['masuk']); ?></div>
                                                    <div class="schedule-time">P: <?php echo e($card['today_schedule']['pulang']); ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="schedule-stack">
                                                    <div class="schedule-time">M: <?php echo e($card['friday_schedule']['masuk']); ?></div>
                                                    <div class="schedule-time">P: <?php echo e($card['friday_schedule']['pulang']); ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="schedule-stack">
                                                    <div class="schedule-time">M: <?php echo e($card['saturday_schedule']['masuk']); ?></div>
                                                    <div class="schedule-time">P: <?php echo e($card['saturday_schedule']['pulang']); ?></div>
                                                </div>
                                            </td>
                                            <td class="note-compact">
                                                <?php echo e($card['note']); ?>

                                            </td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                Belum ada data sekolah untuk ditampilkan.
                                            </td>
                                        </tr>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info py-2 px-3 mb-0 mt-4 small">
                    <i class="bx bx-map me-2"></i>
                    Pengaturan jam presensi pada daftar di atas mengikuti data masing-masing madrasah. Jika ada override jam khusus sekolah, runtime yang tampil akan menyesuaikan override tersebut.
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    (function () {
        const runtimeClock = document.getElementById('runtime-clock');

        if (!runtimeClock) {
            return;
        }

        const initialTime = runtimeClock.dataset.initialTime;
        const startTime = initialTime ? new Date(initialTime) : new Date();

        if (Number.isNaN(startTime.getTime())) {
            return;
        }

        const startTimestamp = startTime.getTime();
        const clientStartTimestamp = Date.now();
        const formatter = new Intl.DateTimeFormat('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
            timeZone: 'Asia/Jakarta',
        });

        const renderClock = () => {
            const elapsed = Date.now() - clientStartTimestamp;
            const currentTime = new Date(startTimestamp + elapsed);
            runtimeClock.innerHTML = `
                <i class="bx bx-time-five"></i>
                Runtime hari ini: ${formatter.format(currentTime)}
            `;
        };

        renderClock();
        window.setInterval(renderClock, 1000);
    })();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/presensi_admin/settings.blade.php ENDPATH**/ ?>