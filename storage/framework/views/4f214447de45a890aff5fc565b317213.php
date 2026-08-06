<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Persentase Presensi Tenaga Pendidik</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h2, h4, p { margin: 0; }
        .header { margin-bottom: 16px; }
        .meta { margin-top: 6px; color: #4b5563; }
        .summary-box { margin: 12px 0 18px; padding: 10px 12px; border: 1px solid #d1d5db; background: #f9fafb; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #9ca3af; padding: 6px; vertical-align: top; }
        th { background: #e5e7eb; text-align: center; }
        .text-center { text-align: center; }
        .small { font-size: 10px; color: #4b5563; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Persentase Presensi <?php echo e($summaryLabel); ?> Tenaga Pendidik</h2>
        <h4><?php echo e($madrasah->name); ?></h4>
        <p class="meta">
            Periode: <?php echo e($summaryStartDate->translatedFormat('d M Y')); ?> - <?php echo e($effectiveEndDate->translatedFormat('d M Y')); ?>

            | Dicetak: <?php echo e($generatedAt->translatedFormat('d M Y H:i')); ?>

        </p>
    </div>

    <div class="summary-box">
        <p><strong>Total Tenaga Pendidik:</strong> <?php echo e($attendancePercentageRows->count()); ?> orang</p>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search): ?>
            <p><strong>Filter Pencarian:</strong> <?php echo e($search); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 22%;">Nama</th>
                <th style="width: 16%;">Status Kepegawaian</th>
                <th style="width: 10%;">Hari Kerja</th>
                <th style="width: 8%;">Hadir</th>
                <th style="width: 8%;">Izin</th>
                <th style="width: 12%;">Belum Hadir</th>
                <th style="width: 10%;">Persentase</th>
                <th style="width: 10%;">Identitas</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $attendancePercentageRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <tr>
                    <td class="text-center"><?php echo e($index + 1); ?></td>
                    <td><?php echo e($row['nama']); ?></td>
                    <td><?php echo e($row['status_kepegawaian']); ?></td>
                    <td class="text-center"><?php echo e($row['total_hari_kerja']); ?></td>
                    <td class="text-center"><?php echo e($row['total_hadir']); ?></td>
                    <td class="text-center"><?php echo e($row['total_izin']); ?></td>
                    <td class="text-center"><?php echo e($row['total_belum_hadir']); ?></td>
                    <td class="text-center"><?php echo e(number_format($row['persentase_kehadiran'], 1)); ?>%</td>
                    <td class="small">
                        NIP: <?php echo e($row['nip'] ?: '-'); ?><br>
                        NUPTK: <?php echo e($row['nuptk'] ?: '-'); ?>

                    </td>
                </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data untuk diexport.</td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/pdf/presensi-persentase-summary.blade.php ENDPATH**/ ?>