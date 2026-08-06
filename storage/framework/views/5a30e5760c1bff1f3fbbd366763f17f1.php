<?php $__env->startSection('title'); ?>Update UPPM <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> UPPM <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Update UPPM <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<style>
    .uppm-page {
        --uppm-border: #e2e8f0;
        --uppm-muted: #64748b;
        --uppm-soft: #f8fafc;
        --uppm-accent: #0f766e;
    }

    .uppm-hero,
    .uppm-section,
    .uppm-metric {
        border: 1px solid var(--uppm-border);
        border-radius: 16px;
        background: #fff;
    }

    .uppm-hero {
        padding: 1.25rem 1.35rem;
        margin-bottom: 1rem;
    }

    .uppm-hero-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .uppm-hero-copy {
        color: var(--uppm-muted);
        margin-bottom: 0;
    }

    .uppm-metric {
        padding: 1rem 1.1rem;
        height: 100%;
    }

    .uppm-metric-label {
        color: var(--uppm-muted);
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.35rem;
    }

    .uppm-metric-value {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }

    .uppm-section {
        margin-bottom: 1rem;
    }

    .uppm-section-body {
        padding: 1.2rem;
    }

    .uppm-section-head {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .uppm-section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.2rem;
    }

    .uppm-section-copy {
        color: var(--uppm-muted);
        margin-bottom: 0;
        font-size: 0.92rem;
    }

    .uppm-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.42rem 0.72rem;
        border-radius: 999px;
        background: var(--uppm-soft);
        border: 1px solid var(--uppm-border);
        color: #334155;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .uppm-chip-value {
        color: var(--uppm-accent);
    }

    .uppm-filter {
        min-width: 150px;
    }

    .uppm-form-shell {
        background: var(--uppm-soft);
        border: 1px solid var(--uppm-border);
        border-radius: 14px;
        padding: 1rem;
    }

    .uppm-form-shell .form-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.35rem;
    }

    .uppm-table {
        margin-bottom: 0;
    }

    .uppm-table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        border-bottom: 1px solid var(--uppm-border);
        white-space: nowrap;
    }

    .uppm-table td {
        vertical-align: top;
        border-color: #eef2f7;
        font-size: 0.92rem;
    }

    .uppm-school-name {
        font-weight: 600;
        color: #0f172a;
    }

    .uppm-school-meta {
        color: var(--uppm-muted);
        font-size: 0.8rem;
    }

    .uppm-period-cell {
        min-width: 180px;
    }

    .uppm-payment-item {
        padding: 0.45rem 0;
        border-bottom: 1px dashed #dbe4ee;
    }

    .uppm-payment-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .uppm-payment-item:first-child {
        padding-top: 0;
    }

    .uppm-payment-date {
        font-weight: 600;
        color: #0f172a;
        font-size: 0.86rem;
    }

    .uppm-payment-note {
        color: var(--uppm-muted);
        font-size: 0.78rem;
    }

    .uppm-empty {
        color: #94a3b8;
        font-size: 0.84rem;
    }

    .uppm-amount {
        white-space: nowrap;
        font-weight: 600;
        color: #0f172a;
    }

    .uppm-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.28rem 0.62rem;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .uppm-status.lunas {
        background: #dcfce7;
        color: #166534;
    }

    .uppm-status.sebagian {
        background: #fef3c7;
        color: #92400e;
    }

    .uppm-status.belum_lunas {
        background: #fee2e2;
        color: #991b1b;
    }

    .uppm-actions {
        display: flex;
        gap: 0.45rem;
        flex-wrap: wrap;
    }

    @media (max-width: 991.98px) {
        .uppm-hero,
        .uppm-section-body,
        .uppm-form-shell {
            padding: 1rem;
        }
    }
</style>

<div class="uppm-page">
    <div class="uppm-hero">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="uppm-hero-title">Update Pembayaran UPPM</div>
                <p class="uppm-hero-copy">Input pembayaran per sekolah dengan tampilan ringkas. Status lunas dihitung otomatis dari total pembayaran pada tahun anggaran yang dipilih.</p>
            </div>
            <form method="GET" action="<?php echo e(route('uppm.update-uppm')); ?>" class="uppm-filter">
                <label class="form-label small text-muted mb-1">Tahun Anggaran</label>
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $yearOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yearOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <option value="<?php echo e($yearOption); ?>" <?php if((int) $tahun === (int) $yearOption): echo 'selected'; endif; ?>><?php echo e($yearOption); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="uppm-metric">
                <div class="uppm-metric-label">Tahun Anggaran</div>
                <div class="uppm-metric-value"><?php echo e($tahun); ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="uppm-metric">
                <div class="uppm-metric-label">Sekolah Lunas</div>
                <div class="uppm-metric-value text-success"><?php echo e(number_format($lunasCount)); ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="uppm-metric">
                <div class="uppm-metric-label">Bayar Sebagian</div>
                <div class="uppm-metric-value text-warning"><?php echo e(number_format($partialCount)); ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="uppm-metric">
                <div class="uppm-metric-label">Total Pembayaran</div>
                <div class="uppm-metric-value">Rp <?php echo e(number_format($totalPaid, 0, ',', '.')); ?></div>
            </div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <li><?php echo e($error); ?></li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="uppm-section">
        <div class="uppm-section-body">
            <div class="uppm-section-head">
                <div>
                    <div class="uppm-section-title">Input Pembayaran</div>
                    <p class="uppm-section-copy">Masukkan data transfer baru tanpa keluar dari halaman rekap.</p>
                </div>
                <div class="uppm-chip">
                    Target Tahun Ini
                    <span class="uppm-chip-value">Rp <?php echo e(number_format($totalTarget, 0, ',', '.')); ?></span>
                </div>
            </div>

            <div class="uppm-form-shell">
                <form method="POST" action="<?php echo e(route('uppm.update-uppm.store')); ?>" class="row g-3 align-items-end">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="tahun_anggaran" value="<?php echo e($tahun); ?>">

                    <div class="col-lg-4">
                        <label class="form-label">Sekolah</label>
                        <select name="madrasah_id" class="form-select" required>
                            <option value="">Pilih sekolah</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($madrasah->id); ?>" <?php if(old('madrasah_id') == $madrasah->id): echo 'selected'; endif; ?>><?php echo e($madrasah->scod ?: '-'); ?> - <?php echo e($madrasah->name); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label">Periode</label>
                        <select name="payment_period" class="form-select" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $periodOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodKey => $periodLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($periodKey); ?>" <?php if(old('payment_period') === $periodKey): echo 'selected'; endif; ?>><?php echo e($periodLabel); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label">Tgl Transfer</label>
                        <input type="date" name="transfer_date" class="form-control" value="<?php echo e(old('transfer_date', now()->toDateString())); ?>" required>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="amount" class="form-control" min="0" step="0.01" value="<?php echo e(old('amount')); ?>" required>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="note" class="form-control" value="<?php echo e(old('note')); ?>" placeholder="Opsional">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="uppm-section">
        <div class="uppm-section-body">
            <div class="uppm-section-head">
                <div>
                    <div class="uppm-section-title">Rekap Pembayaran Tahun <?php echo e($tahun); ?></div>
                    <p class="uppm-section-copy">Ringkasan per sekolah untuk periode `Januari - Juni` dan `Juli - Desember`.</p>
                </div>
                <div class="uppm-chip"><?php echo e(number_format($summaryRows->count())); ?> sekolah</div>
            </div>

            <div class="table-responsive">
                <table class="table uppm-table align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Sekolah</th>
                            <th>Januari - Juni</th>
                            <th>Juli - Desember</th>
                            <th>Total</th>
                            <th>Tagihan</th>
                            <th>Sisa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $summaryRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <?php ($madrasah = $row['madrasah']); ?>
                            <?php ($summary = $row['summary']); ?>
                            <?php ($paymentsByPeriod = $summary['payments_by_period'] ?? collect()); ?>
                            <?php ($status = $summary['status'] ?? 'belum_lunas'); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td>
                                    <div class="uppm-school-name"><?php echo e($madrasah->scod ?: '-'); ?> - <?php echo e($madrasah->name); ?></div>
                                    <div class="uppm-school-meta"><?php echo e($madrasah->kabupaten ?: 'Kabupaten belum diisi'); ?></div>
                                </td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['jan_jun', 'jul_des']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodKey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <td class="uppm-period-cell">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = ($paymentsByPeriod->get($periodKey) ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <div class="uppm-payment-item">
                                                <div class="uppm-payment-date"><?php echo e($payment->transfer_date?->format('d/m/Y')); ?></div>
                                                <div class="uppm-amount">Rp <?php echo e(number_format((float) $payment->amount, 0, ',', '.')); ?></div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($payment->note): ?>
                                                    <div class="uppm-payment-note"><?php echo e($payment->note); ?></div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                            <span class="uppm-empty">Belum ada pembayaran</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <td class="uppm-amount">Rp <?php echo e(number_format((float) ($summary['total_paid'] ?? 0), 0, ',', '.')); ?></td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($summary['has_target'])): ?>
                                        <span class="uppm-amount">Rp <?php echo e(number_format((float) ($summary['target_nominal'] ?? 0), 0, ',', '.')); ?></span>
                                    <?php else: ?>
                                        <span class="uppm-empty">Belum ada nominal</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($summary['has_target'])): ?>
                                        <span class="uppm-amount">Rp <?php echo e(number_format((float) ($summary['remaining'] ?? 0), 0, ',', '.')); ?></span>
                                    <?php else: ?>
                                        <span class="uppm-empty">-</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td>
                                    <span class="uppm-status <?php echo e($status); ?>"><?php echo e($summary['status_label'] ?? 'Belum Lunas'); ?></span>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="uppm-section">
        <div class="uppm-section-body">
            <div class="uppm-section-head">
                <div>
                    <div class="uppm-section-title">Riwayat Input</div>
                    <p class="uppm-section-copy">Gunakan edit atau hapus jika ada koreksi input.</p>
                </div>
                <div class="uppm-chip"><?php echo e(number_format($paymentUpdates->count())); ?> baris</div>
            </div>

            <div class="table-responsive">
                <table class="table uppm-table align-middle">
                    <thead>
                        <tr>
                            <th>Tgl Transfer</th>
                            <th>Sekolah</th>
                            <th>Periode</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $paymentUpdates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentUpdate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr>
                                <td><?php echo e($paymentUpdate->transfer_date?->format('d/m/Y')); ?></td>
                                <td>
                                    <div class="uppm-school-name"><?php echo e($paymentUpdate->madrasah?->scod ?: '-'); ?> - <?php echo e($paymentUpdate->madrasah?->name); ?></div>
                                </td>
                                <td><?php echo e($paymentUpdate->payment_period_label); ?></td>
                                <td class="uppm-amount">Rp <?php echo e(number_format((float) $paymentUpdate->amount, 0, ',', '.')); ?></td>
                                <td><?php echo e($paymentUpdate->note ?: '-'); ?></td>
                                <td>
                                    <div class="uppm-actions">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPaymentUpdate<?php echo e($paymentUpdate->id); ?>">
                                            Edit
                                        </button>
                                        <form method="POST" action="<?php echo e(route('uppm.update-uppm.destroy', $paymentUpdate)); ?>" onsubmit="return confirm('Hapus update pembayaran ini?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat input untuk tahun anggaran ini.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $paymentUpdates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentUpdate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
    <div class="modal fade" id="editPaymentUpdate<?php echo e($paymentUpdate->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="<?php echo e(route('uppm.update-uppm.update', $paymentUpdate)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Update UPPM</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Sekolah</label>
                                <select name="madrasah_id" class="form-select" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($madrasah->id); ?>" <?php if((int) $paymentUpdate->madrasah_id === (int) $madrasah->id): echo 'selected'; endif; ?>><?php echo e($madrasah->scod ?: '-'); ?> - <?php echo e($madrasah->name); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tahun Anggaran</label>
                                <input type="number" name="tahun_anggaran" class="form-control" value="<?php echo e($paymentUpdate->tahun_anggaran); ?>" min="2020" max="<?php echo e(now()->year + 1); ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Periode</label>
                                <select name="payment_period" class="form-select" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $periodOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodKey => $periodLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($periodKey); ?>" <?php if($paymentUpdate->payment_period === $periodKey): echo 'selected'; endif; ?>><?php echo e($periodLabel); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tgl Transfer</label>
                                <input type="date" name="transfer_date" class="form-control" value="<?php echo e(optional($paymentUpdate->transfer_date)->format('Y-m-d')); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="amount" class="form-control" min="0" step="0.01" value="<?php echo e((float) $paymentUpdate->amount); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Keterangan</label>
                                <input type="text" name="note" class="form-control" value="<?php echo e($paymentUpdate->note); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/uppm/update-uppm.blade.php ENDPATH**/ ?>