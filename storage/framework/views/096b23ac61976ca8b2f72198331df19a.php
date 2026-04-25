<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .header {
            margin-bottom: 18px;
        }

        .header h2 {
            font-size: 18px;
            margin-bottom: 4px;
        }

        .meta-table,
        .summary-table,
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .section-title {
            margin: 16px 0 8px;
            font-size: 13px;
            font-weight: bold;
        }

        .summary-table th,
        .summary-table td,
        .detail-table th,
        .detail-table td {
            border: 1px solid #cfd8dc;
            padding: 7px 6px;
            text-align: left;
        }

        .summary-table th,
        .detail-table th {
            background: #eef5f3;
            font-weight: bold;
        }

        .muted {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rekap Presensi Kehadiran</h2>
        <p class="muted">
            <?php echo e(match ($type) {
                    'weekly' => 'Rekap Mingguan',
                    'all' => 'Rekap Keseluruhan',
                    default => 'Rekap Bulanan',
                }); ?>

        </p>
    </div>

    <table class="meta-table">
        <tr>
            <td width="120">Nama</td>
            <td width="10">:</td>
            <td><?php echo e($user->name); ?></td>
        </tr>
        <tr>
            <td>Madrasah</td>
            <td>:</td>
            <td><?php echo e($user->madrasah->name ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Periode</td>
            <td>:</td>
            <td><?php echo e($summary['periode_label'] ?? ($startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y'))); ?></td>
        </tr>
    </table>

    <div class="section-title">Ringkasan</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Persentase Kehadiran</th>
                <th>Total Hari Kerja</th>
                <th>Total Hadir + Izin</th>
                <th>Total Izin Disetujui</th>
                <th>Total Belum Hadir</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo e($summary['persentase_kehadiran'] ?? 0); ?>%</td>
                <td><?php echo e($summary['total_hari_kerja'] ?? 0); ?></td>
                <td><?php echo e($summary['total_hadir_efektif'] ?? 0); ?></td>
                <td><?php echo e($summary['total_izin'] ?? 0); ?></td>
                <td><?php echo e($summary['total_belum_hadir'] ?? 0); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Detail Riwayat</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th width="75">Tanggal</th>
                <th width="55">Hari</th>
                <th width="55">Jenis</th>
                <th width="70">Status</th>
                <th width="55">Masuk</th>
                <th width="55">Keluar</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <tr>
                    <td><?php echo e($record->tanggal->format('Y-m-d')); ?></td>
                    <td><?php echo e(ucfirst($record->tanggal->locale('id')->dayName)); ?></td>
                    <td><?php echo e($record->model_type === 'izin' ? 'Izin' : 'Presensi'); ?></td>
                    <td><?php echo e(ucfirst(str_replace('_', ' ', $record->status ?? '-'))); ?></td>
                    <td><?php echo e($record->waktu_masuk ? \Carbon\Carbon::parse($record->waktu_masuk)->format('H:i') : '-'); ?></td>
                    <td><?php echo e($record->waktu_keluar ? \Carbon\Carbon::parse($record->waktu_keluar)->format('H:i') : '-'); ?></td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->model_type === 'izin'): ?>
                            <?php echo e($record->deskripsi_tugas ?: ($record->alasan ?? '-')); ?>

                        <?php else: ?>
                            <?php echo e($record->keterangan ?? '-'); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="7">Tidak ada data pada periode ini.</td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/pdf/mobile-presensi-rekap.blade.php ENDPATH**/ ?>