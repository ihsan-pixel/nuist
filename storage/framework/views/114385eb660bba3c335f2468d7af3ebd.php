<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekap Jawaban Instrumen Deteksi Model Layanan Sekolah- <?php echo e(optional($score->school)->nama ?? 'Sekolah'); ?></title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #111; }
        .header { text-align: center; margin-bottom: 18px; }
        .meta { margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f5f5f7; text-align: left; }
        .small { font-size: 12px; color: #666; }
        .score { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rekap Jawaban - <?php echo e(optional($score->school)->nama ?? 'Sekolah'); ?></h2>
        <div class="small">Pengirim: <?php echo e(optional($score->submittedBy)->name ?? '-'); ?> &nbsp; | &nbsp; Sekolah: <?php echo e(optional($score->school)->nama ?? '-'); ?></div>
    </div>

    <div class="meta">
        <table style="margin-bottom:12px;">
            <thead>
                <tr>
                    <th>Dimensi</th>
                    <th style="width:80px; text-align:center">Total Jawaban</th>
                    <th style="width:100px; text-align:center">A (cnt / %)</th>
                    <th style="width:100px; text-align:center">B (cnt / %)</th>
                    <th style="width:100px; text-align:center">C (cnt / %)</th>
                    <th style="width:100px; text-align:center">D (cnt / %)</th>
                    <th style="width:100px; text-align:center">E (cnt / %)</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dimensionStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dim => $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr>
                        <td><?php echo e($dim); ?></td>
                        <td class="score" style="text-align:center"><?php echo e($stat['total_answers']); ?></td>
                        <td style="text-align:center"><?php echo e($stat['counts']['A'] ?? 0); ?> / <?php echo e($stat['percents']['A'] ?? 0); ?>%</td>
                        <td style="text-align:center"><?php echo e($stat['counts']['B'] ?? 0); ?> / <?php echo e($stat['percents']['B'] ?? 0); ?>%</td>
                        <td style="text-align:center"><?php echo e($stat['counts']['C'] ?? 0); ?> / <?php echo e($stat['percents']['C'] ?? 0); ?>%</td>
                        <td style="text-align:center"><?php echo e($stat['counts']['D'] ?? 0); ?> / <?php echo e($stat['percents']['D'] ?? 0); ?>%</td>
                        <td style="text-align:center"><?php echo e($stat['counts']['E'] ?? 0); ?> / <?php echo e($stat['percents']['E'] ?? 0); ?>%</td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <th>Total</th>
                    <th class="score" style="text-align:center"><?php echo e(array_sum(array_column($dimensionStats, 'total_answers'))); ?></th>
                    <th colspan="5" class="small" style="text-align:left">Total Skor: <strong><?php echo e($score->total_skor); ?></strong> &nbsp; | &nbsp; Level: <strong><?php echo e($score->level_sekolah); ?></strong></th>
                </tr>
            </tbody>
        </table>
    </div>

    <h4>Jawaban per Pertanyaan</h4>
    <table>
        <thead>
            <tr>
                <th style="width:50px">No</th>
                <th>Pertanyaan</th>
                <th style="width:70px">Jawaban</th>
                <th style="width:350px">Teks Pilihan</th>
                <th style="width:70px">Skor</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $ans): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <tr>
                    <td><?php echo e($i+1); ?></td>
                    <td><?php echo e(optional($ans->question)->pertanyaan ?? '-'); ?></td>
                    <td><?php echo e(strtoupper($ans->jawaban ?? '-')); ?></td>
                    <td><?php echo e($ans->choice_text ?? (optional($ans->question)->choice_texts[$ans->jawaban ?? ''] ?? '-')); ?></td>
                    <td class="score"><?php echo e($ans->skor ?? '-'); ?></td>
                </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr><td colspan="5">Tidak ada jawaban tersimpan.</td></tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top:18px; font-size:12px; color:#666;">Dicetak: <?php echo e(\Carbon\Carbon::now()->toDateTimeString()); ?></div>
</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/rekap/pdf.blade.php ENDPATH**/ ?>