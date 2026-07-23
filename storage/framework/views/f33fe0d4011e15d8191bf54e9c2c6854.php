<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi Mengajar</title>
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
            vertical-align: top;
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
        <h2>Rekap Presensi Mengajar</h2>
        <p class="muted">
            <?php echo e($scope === 'all' ? 'Rekap Keseluruhan' : 'Rekap Bulanan'); ?>

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
            <td><?php echo e($periodLabel); ?></td>
        </tr>
    </table>

    <div class="section-title">Ringkasan</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Total Presensi</th>
                <th>Total Siswa Hadir</th>
                <th>Total Siswa Kelas</th>
                <th>Rata-rata Kehadiran Siswa</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo e($summary['total_entries'] ?? 0); ?></td>
                <td><?php echo e($summary['total_present_students'] ?? 0); ?></td>
                <td><?php echo e($summary['total_class_students'] ?? 0); ?></td>
                <td><?php echo e($summary['average_student_attendance'] ?? 0); ?>%</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Detail Riwayat</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th width="76">Tanggal</th>
                <th width="45">Jam</th>
                <th width="95">Mapel</th>
                <th width="70">Kelas</th>
                <th width="80">Sekolah</th>
                <th width="42">Hadir</th>
                <th width="42">Total</th>
                <th width="50">Persen</th>
                <th>Materi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <tr>
                    <td><?php echo e($record['date_label'] ?? '-'); ?></td>
                    <td><?php echo e($record['time'] ?? '-'); ?></td>
                    <td><?php echo e($record['subject'] ?? '-'); ?></td>
                    <td><?php echo e($record['class_name'] ?? '-'); ?></td>
                    <td><?php echo e($record['school_name'] ?? '-'); ?></td>
                    <td><?php echo e($record['present_students'] ?? 0); ?></td>
                    <td><?php echo e($record['class_total_students'] ?? 0); ?></td>
                    <td><?php echo e($record['student_attendance_percentage'] ?? 0); ?>%</td>
                    <td><?php echo e($record['materi'] ?? '-'); ?></td>
                </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="9">Tidak ada data pada periode ini.</td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/pdf/mobile-teaching-attendance-rekap.blade.php ENDPATH**/ ?>