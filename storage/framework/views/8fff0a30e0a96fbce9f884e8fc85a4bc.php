<?php $__env->startSection('title', 'Komputer Presensi Sekolah'); ?>

<?php $__env->startSection('css'); ?>
<style>
    :root {
        --kiosk-bg: #f5f7fb;
        --kiosk-card: #ffffff;
        --kiosk-border: #e5eaf2;
        --kiosk-text: #0f172a;
        --kiosk-muted: #64748b;
        --kiosk-primary: #2563eb;
        --kiosk-success: #16a34a;
    }

    .kiosk-page {
        background:
            radial-gradient(circle at top left, rgba(37, 99, 235, 0.08), transparent 30%),
            linear-gradient(180deg, #ffffff 0%, var(--kiosk-bg) 100%);
        min-height: 100vh;
        padding-bottom: 24px;
    }

    .kiosk-shell {
        border: 0;
        border-radius: 22px;
        box-shadow: 0 14px 36px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .kiosk-section {
        border: 1px solid var(--kiosk-border);
        border-radius: 18px;
        background: var(--kiosk-card);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.03);
    }

    .kiosk-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--kiosk-text);
        margin-bottom: 4px;
    }

    .kiosk-subtitle {
        font-size: 13px;
        color: var(--kiosk-muted);
        margin-bottom: 0;
    }

    .kiosk-header {
        padding: 22px 22px 10px;
    }

    .kiosk-header-card {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.08), rgba(22, 163, 74, 0.06));
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 18px;
        padding: 18px 20px;
    }

    .kiosk-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .kiosk-actions .btn {
        min-height: 44px;
        padding-inline: 16px;
        border-radius: 12px;
    }

    .quick-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #fff;
        border: 1px solid var(--kiosk-border);
        color: var(--kiosk-muted);
        font-size: 12px;
        font-weight: 600;
    }

    .device-table th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        white-space: nowrap;
    }

    .device-table td {
        vertical-align: top;
        font-size: 13px;
    }

    .device-name {
        font-weight: 600;
        color: #0f172a;
    }

    .device-meta {
        color: #64748b;
        font-size: 12px;
    }

    .token-box {
        padding: 12px 14px;
        border-radius: 14px;
        background: #0f172a;
        color: #e2e8f0;
        font-size: 12px;
        word-break: break-all;
        user-select: all;
    }

    .summary-box {
        border: 1px solid var(--kiosk-border);
        border-radius: 16px;
        background: #fff;
        padding: 16px;
        height: 100%;
    }

    .summary-label {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 6px;
    }

    .summary-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--kiosk-text);
        line-height: 1;
    }

    .compact-table td,
    .compact-table th {
        padding-top: 0.85rem;
        padding-bottom: 0.85rem;
    }

    .action-group {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .action-group .btn {
        border-radius: 10px;
    }

    .section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }

    .section-note {
        color: var(--kiosk-muted);
        font-size: 12px;
    }

    .log-table th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        white-space: nowrap;
    }

    .log-table td {
        vertical-align: top;
        font-size: 13px;
    }

    .kiosk-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .kiosk-pagination-info {
        font-size: 12px;
        color: #64748b;
    }

    .kiosk-pagination-links {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .kiosk-pagination-links .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        border-radius: 10px;
        border: 1px solid #dbe4f0;
        background: #fff;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
        text-decoration: none;
        box-shadow: none;
    }

    .kiosk-pagination-links .page-link i {
        font-size: 16px;
    }

    .kiosk-pagination-links .page-link.active {
        background: #0f172a;
        border-color: #0f172a;
        color: #fff;
    }

    .kiosk-pagination-links .page-link.disabled {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #94a3b8;
        pointer-events: none;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Presensi Admin <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Komputer Presensi Sekolah <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="kiosk-page">
<div class="row">
    <div class="col-12">
        <div class="card kiosk-shell">
            <div class="kiosk-header">
                <div class="kiosk-header-card">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                        <div class="me-lg-4">
                            <h4 class="mb-1 fw-semibold">
                                <i class="bx bx-desktop me-2"></i>Komputer Presensi Sekolah
                            </h4>
                            <p class="kiosk-subtitle mb-3">
                                Kelola komputer kiosk, sinkronkan IP, lalu buka mode presensi dari perangkat yang diizinkan.
                            </p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="quick-chip"><i class="bx bx-shield-quarter"></i>IP tervalidasi</span>
                                <span class="quick-chip"><i class="bx bx-id-card"></i>Token perangkat</span>
                                <span class="quick-chip"><i class="bx bx-face"></i>Scan wajah</span>
                            </div>
                        </div>
                        <div class="kiosk-actions">
                            <a href="<?php echo e(route('presensi_admin.settings')); ?>" class="btn btn-light">
                                <i class="bx bx-arrow-back me-1"></i>Pengaturan
                            </a>
                            <a href="<?php echo e(route('school-kiosk.index')); ?>" class="btn btn-primary">
                                <i class="bx bx-right-arrow-alt me-1"></i>Buka Kiosk
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 pt-0">

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bx bx-check-circle me-2"></i><?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <i class="bx bx-error-circle me-2"></i><?php echo e($errors->first()); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('kiosk_registration')): ?>
                    <?php
                        $registration = session('kiosk_registration');
                    ?>
                    <div class="alert alert-info">
                        <div class="fw-semibold mb-2">Perangkat baru berhasil didaftarkan</div>
                        <div class="small text-muted mb-2">
                            Komputer ini sudah terhubung ke perangkat <strong><?php echo e($registration['device_name'] ?? '-'); ?></strong> untuk <?php echo e($registration['madrasah_name'] ?? '-'); ?>.
                        </div>
                        <div class="token-box"><?php echo e($registration['plain_token'] ?? '-'); ?></div>
                        <div class="small mt-2 mb-0 text-muted">
                            Token ini ditampilkan sekali. Simpan hanya jika Anda perlu migrasi ulang browser kiosk.
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-xl-3">
                        <div class="summary-box">
                            <div class="summary-label">Total Perangkat</div>
                            <div class="summary-value"><?php echo e($stats['total_devices']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="summary-box">
                            <div class="summary-label">Perangkat Aktif</div>
                            <div class="summary-value"><?php echo e($stats['active_devices']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="summary-box">
                            <div class="summary-label">Submit Berhasil Hari Ini</div>
                            <div class="summary-value"><?php echo e($stats['submit_success_today']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="summary-box">
                            <div class="summary-label">Akses Ditolak Hari Ini</div>
                            <div class="summary-value"><?php echo e($stats['access_denied_today']); ?></div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-xl-4">
                        <div class="kiosk-section h-100">
                            <div class="card-body p-4">
                                <div class="kiosk-title">Daftarkan Komputer</div>
                                <p class="kiosk-subtitle mb-4">Gunakan komputer yang akan dipakai presensi. Isi seperlunya saja.</p>

                                <form method="POST" action="<?php echo e(route('presensi_admin.kiosk_devices.store')); ?>">
                                    <?php echo csrf_field(); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canChooseMadrasah): ?>
                                        <div class="mb-3">
                                            <label class="form-label">Madrasah</label>
                                            <select name="madrasah_id" class="form-select" required>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                    <option value="<?php echo e($school->id); ?>" <?php echo e((int) old('madrasah_id', $selectedMadrasahId) === (int) $school->id ? 'selected' : ''); ?>>
                                                        <?php echo e($school->name); ?>

                                                    </option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                            </select>
                                        </div>
                                    <?php else: ?>
                                        <input type="hidden" name="madrasah_id" value="<?php echo e($selectedMadrasahId); ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Madrasah</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                value="<?php echo e(optional($schools->firstWhere('id', $selectedMadrasahId))->name ?? 'Madrasah'); ?>"
                                                readonly
                                            >
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <div class="mb-3">
                                        <label class="form-label">Nama Komputer</label>
                                        <input type="text" name="name" class="form-control" placeholder="Contoh: Front Office Kiosk 1" value="<?php echo e(old('name')); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">IP yang Diizinkan</label>
                                        <textarea name="allowed_ip_addresses" class="form-control" rows="3" placeholder="Kosongkan untuk memakai IP saat ini"><?php echo e(old('allowed_ip_addresses', $currentIp)); ?></textarea>
                                        <div class="form-text">Pisahkan lebih dari satu IP dengan koma atau baris baru.</div>
                                    </div>

                                    <div class="alert alert-warning small mb-3">
                                        <i class="bx bx-info-circle me-1"></i>
                                        Simpan IP sesuai perangkat yang dipakai agar tombol buka kiosk aktif.
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bx bx-save me-1"></i>Daftarkan Komputer Ini
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8">
                        <div class="kiosk-section">
                            <div class="card-body p-4">
                                <div class="section-heading">
                                    <div>
                                        <div class="kiosk-title">Perangkat Terdaftar</div>
                                        <div class="section-note">Klik sinkron IP jika perangkat pindah jaringan, lalu buka kiosk.</div>
                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canChooseMadrasah): ?>
                                        <form method="GET" action="<?php echo e(route('presensi_admin.kiosk_devices')); ?>">
                                            <select name="madrasah_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="" <?php echo e(blank($selectedMadrasahId) ? 'selected' : ''); ?>>Semua Sekolah</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                    <option value="<?php echo e($school->id); ?>" <?php echo e((int) $selectedMadrasahId === (int) $school->id ? 'selected' : ''); ?>>
                                                        <?php echo e($school->name); ?>

                                                    </option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                            </select>
                                        </form>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 device-table compact-table">
                                        <thead>
                                            <tr>
                                                <th>Perangkat</th>
                                                <th>Madrasah</th>
                                                <th>IP Aktif</th>
                                                <th>Terakhir Aktif</th>
                                                <th>Status</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <tr>
                                                    <td>
                                                        <div class="device-name"><?php echo e($device->name); ?></div>
                                                        <div class="device-meta">
                                                            Didaftarkan oleh <?php echo e($device->registeredBy?->name ?? '-'); ?>

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="device-name"><?php echo e($device->madrasah?->name ?? '-'); ?></div>
                                                        <div class="device-meta">
                                                            <?php echo e($device->madrasah?->kabupaten ?: 'Kabupaten belum diatur'); ?>

                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($device->madrasah?->scod): ?>
                                                                • SCOD <?php echo e($device->madrasah->scod); ?>

                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div><?php echo e(collect($device->allowed_ip_addresses ?? [])->join(', ') ?: '-'); ?></div>
                                                        <div class="device-meta">Terakhir: <?php echo e($device->last_ip_address ?: '-'); ?></div>
                                                    </td>
                                                    <td>
                                                        <div><?php echo e($device->last_seen_at?->format('d/m/Y H:i') ?: '-'); ?></div>
                                                        <div class="device-meta text-truncate" style="max-width: 180px;">
                                                            <?php echo e($device->last_user_agent ?: '-'); ?>

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $deviceAllowedIps = collect($device->allowed_ip_addresses ?? [])->filter()->values();
                                                            $ipMatches = $deviceAllowedIps->contains((string) $currentIp);
                                                        ?>
                                                        <div class="d-flex flex-column gap-2">
                                                            <span class="badge <?php echo e($device->is_active ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border border-secondary-subtle'); ?>">
                                                                <?php echo e($device->is_active ? 'Aktif' : 'Nonaktif'); ?>

                                                            </span>
                                                            <span class="badge <?php echo e($ipMatches ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle'); ?>">
                                                                <?php echo e($ipMatches ? 'IP Sesuai' : 'IP Belum Sesuai'); ?>

                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="action-group">
                                                            <form method="POST" action="<?php echo e(route('presensi_admin.kiosk_devices.sync_ip', $device)); ?>">
                                                                <?php echo csrf_field(); ?>
                                                                <button type="submit" class="btn btn-sm btn-light">
                                                                    Sinkronkan IP
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="<?php echo e(route('presensi_admin.kiosk_devices.toggle', $device)); ?>">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('PATCH'); ?>
                                                                <button type="submit" class="btn btn-sm <?php echo e($device->is_active ? 'btn-outline-danger' : 'btn-outline-success'); ?>">
                                                                    <?php echo e($device->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>

                                                                </button>
                                                            </form>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($device->is_active && $ipMatches): ?>
                                                                <a href="<?php echo e(route('presensi_admin.kiosk_devices.open', $device)); ?>" class="btn btn-sm btn-primary">
                                                                    Buka Mode Kiosk
                                                                </a>
                                                            <?php else: ?>
                                                                <button type="button" class="btn btn-sm btn-primary" disabled>
                                                                    Buka Mode Kiosk
                                                                </button>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            <form method="POST" action="<?php echo e(route('presensi_admin.kiosk_devices.destroy', $device)); ?>" onsubmit="return confirm('Hapus perangkat <?php echo e(addslashes($device->name)); ?>?');">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-4">
                                                        Belum ada komputer presensi yang terdaftar.
                                                    </td>
                                                </tr>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kiosk-section mt-4">
                    <div class="card-body p-4">
                        <div class="section-heading">
                            <div>
                                <div class="kiosk-title">Aktivitas Kiosk Terbaru</div>
                                <div class="section-note">Log terbaru untuk memantau akses, verifikasi, dan hasil presensi.</div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0 log-table">
                                <thead>
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Aksi</th>
                                        <th>Perangkat</th>
                                        <th>Operator</th>
                                        <th>Guru</th>
                                        <th>Status</th>
                                        <th>IP</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <tr>
                                            <td><?php echo e($log->created_at?->format('d/m/Y H:i') ?: '-'); ?></td>
                                            <td><?php echo e(str_replace('_', ' ', $log->action)); ?></td>
                                            <td><?php echo e($log->device?->name ?? '-'); ?></td>
                                            <td><?php echo e($log->operator?->name ?? '-'); ?></td>
                                            <td><?php echo e($log->targetUser?->name ?? '-'); ?></td>
                                            <td>
                                                <span class="badge <?php echo e($log->status === 'success' ? 'bg-success-subtle text-success border border-success-subtle' : ($log->status === 'denied' ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle')); ?>">
                                                    <?php echo e($log->status); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e($log->ip_address ?: '-'); ?></td>
                                            <td class="text-muted small">
                                                <?php echo e($log->payload_snapshot['message'] ?? ($log->payload_snapshot['mode'] ?? '-')); ?>

                                            </td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                Belum ada aktivitas kiosk yang tercatat.
                                            </td>
                                        </tr>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logs->hasPages()): ?>
                            <div class="kiosk-pagination mt-3">
                                <div class="kiosk-pagination-info">
                                    Menampilkan <?php echo e($logs->firstItem()); ?>-<?php echo e($logs->lastItem()); ?> dari <?php echo e($logs->total()); ?> aktivitas
                                </div>
                                <div class="kiosk-pagination-links">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logs->onFirstPage()): ?>
                                        <span class="page-link disabled">
                                            <i class="bx bx-chevron-left"></i>
                                        </span>
                                    <?php else: ?>
                                        <a href="<?php echo e($logs->previousPageUrl()); ?>" class="page-link" aria-label="Previous">
                                            <i class="bx bx-chevron-left"></i>
                                        </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $logs->getUrlRange(max(1, $logs->currentPage() - 2), min($logs->lastPage(), $logs->currentPage() + 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($page === $logs->currentPage()): ?>
                                            <span class="page-link active"><?php echo e($page); ?></span>
                                        <?php else: ?>
                                            <a href="<?php echo e($url); ?>" class="page-link"><?php echo e($page); ?></a>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logs->hasMorePages()): ?>
                                        <a href="<?php echo e($logs->nextPageUrl()); ?>" class="page-link" aria-label="Next">
                                            <i class="bx bx-chevron-right"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="page-link disabled">
                                            <i class="bx bx-chevron-right"></i>
                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/admin/attendance-kiosk-devices.blade.php ENDPATH**/ ?>