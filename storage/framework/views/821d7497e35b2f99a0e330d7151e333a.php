<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Pengajuan SK Yayasan</title>
    <style>
        body {
            color: #111827;
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            margin: 18px;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .header {
            margin-bottom: 14px;
        }

        .title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .subtitle {
            color: #4b5563;
            font-size: 10px;
            line-height: 1.45;
        }

        .meta {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-bottom: 14px;
            padding: 10px 12px;
        }

        .meta-table {
            width: 100%;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .meta-label {
            color: #4b5563;
            width: 110px;
        }

        table {
            border-collapse: collapse;
            table-layout: fixed;
            width: 100%;
        }

        thead {
            display: table-header-group;
        }

        th, td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            background: #e5e7eb;
            font-size: 8px;
            text-align: left;
        }

        tbody tr:nth-child(even) td {
            background: #f9fafb;
        }
    </style>
</head>
<body>
<?php
    $statusLabel = match ($batch->status) {
        'synced' => 'Tersinkron',
        'rejected' => 'Ditolak',
        default => 'Pending Review',
    };
?>

<div class="header">
    <div class="title">Rekap Pengajuan SK Yayasan</div>
    <p class="subtitle">
        <?php echo e($batch->madrasah?->name ?? '-'); ?>

    </p>
</div>

<div class="meta">
    <table class="meta-table">
        <tr>
            <td class="meta-label">Sekolah</td>
            <td><?php echo e($batch->madrasah?->name ?? '-'); ?></td>
            <td class="meta-label">Status Batch</td>
            <td><?php echo e($statusLabel); ?></td>
        </tr>
        <tr>
            <td class="meta-label">Uploader</td>
            <td><?php echo e($batch->uploader?->name ?? '-'); ?></td>
            <td class="meta-label">Tanggal Upload</td>
            <td><?php echo e(optional($batch->uploaded_at)->format('d/m/Y H:i') ?? '-'); ?></td>
        </tr>
        <tr>
            <td class="meta-label">Reviewer</td>
            <td><?php echo e($batch->reviewer?->name ?? '-'); ?></td>
            <td class="meta-label">Tanggal Sinkron</td>
            <td><?php echo e(optional($batch->synced_at)->format('d/m/Y H:i') ?? '-'); ?></td>
        </tr>
        <tr>
            <td class="meta-label">Jumlah Baris</td>
            <td><?php echo e(number_format($batch->rows->count())); ?> data</td>
            <td class="meta-label">Nama File</td>
            <td><?php echo e($batch->original_filename ?? '-'); ?></td>
        </tr>
    </table>
</div>

<table>
    <thead>
        <tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <th><?php echo e($label); ?></th>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <?php
                        $value = trim((string) data_get($row, $field, ''));
                    ?>
                    <td><?php echo e($value !== '' ? $value : '-'); ?></td>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <tr>
                <td colspan="<?php echo e(count($columns)); ?>">Tidak ada data pengajuan pada batch ini.</td>
            </tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </tbody>
</table>
</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/pdf/sk-yayasan-import-batch.blade.php ENDPATH**/ ?>