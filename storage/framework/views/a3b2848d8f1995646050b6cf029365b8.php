<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Persentase Kehadiran Sekolah</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1f2937;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .header {
            margin-bottom: 16px;
        }

        .header h2 {
            font-size: 18px;
            margin-bottom: 4px;
        }

        .muted {
            color: #6b7280;
        }

        .meta-table,
        .summary-table,
        .teacher-table,
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
            font-size: 12px;
            font-weight: bold;
        }

        .summary-table th,
        .summary-table td,
        .teacher-table th,
        .teacher-table td,
        .detail-table th,
        .detail-table td {
            border: 1px solid #cfd8dc;
            padding: 6px 5px;
            text-align: left;
        }

        .summary-table th,
        .teacher-table th,
        .detail-table th {
            background: #eef5f3;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rekap Persentase Presensi Kehadiran</h2>
        <p class="muted">Laporan kepala sekolah untuk seluruh tenaga pendidik dalam satu sekolah.</p>
    </div>

    <table class="meta-table">
        <tr>
            <td width="140">Sekolah</td>
            <td width="10">:</td>
            <td><?php echo e($school->name ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Periode Mingguan</td>
            <td>:</td>
            <td><?php echo e($selectedWeekLabel); ?></td>
        </tr>
        <tr>
            <td>Periode Bulanan</td>
            <td>:</td>
            <td><?php echo e(ucfirst($selectedMonthLabel)); ?></td>
        </tr>
        <tr>
            <td>Dicetak Pada</td>
            <td>:</td>
            <td><?php echo e($generatedAt->translatedFormat('d M Y H:i')); ?></td>
        </tr>
    </table>

    <div class="section-title">Ringkasan Sekolah</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Total Guru</th>
                <th>Rata-rata Mingguan</th>
                <th>Rata-rata Bulanan</th>
                <th>Total Hadir Mingguan</th>
                <th>Total Hadir Bulanan</th>
                <th>Total Belum Mingguan</th>
                <th>Total Belum Bulanan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo e($schoolOverview['teacher_count']); ?></td>
                <td><?php echo e(number_format($schoolOverview['weekly_average'], 1)); ?>%</td>
                <td><?php echo e(number_format($schoolOverview['monthly_average'], 1)); ?>%</td>
                <td><?php echo e($schoolOverview['weekly_hadir_total']); ?></td>
                <td><?php echo e($schoolOverview['monthly_hadir_total']); ?></td>
                <td><?php echo e($schoolOverview['weekly_belum_total']); ?></td>
                <td><?php echo e($schoolOverview['monthly_belum_total']); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Rekap Seluruh Tenaga Pendidik</div>
    <table class="teacher-table">
        <thead>
            <tr>
                <th width="170">Nama</th>
                <th width="110">Ketugasan</th>
                <th class="text-center">Mingguan %</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Izin</th>
                <th class="text-center">Belum</th>
                <th class="text-center">Bulanan %</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Izin</th>
                <th class="text-center">Belum</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $schoolTeacherSummaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacherSummary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <tr>
                    <td><?php echo e($teacherSummary['teacher']->name); ?></td>
                    <td><?php echo e($teacherSummary['teacher']->ketugasan ?? 'Tenaga pendidik'); ?></td>
                    <td class="text-center"><?php echo e(number_format($teacherSummary['weekly']['persentase_kehadiran'], 1)); ?>%</td>
                    <td class="text-center"><?php echo e($teacherSummary['weekly']['total_hadir']); ?></td>
                    <td class="text-center"><?php echo e($teacherSummary['weekly']['total_izin']); ?></td>
                    <td class="text-center"><?php echo e($teacherSummary['weekly']['total_belum_hadir']); ?></td>
                    <td class="text-center"><?php echo e(number_format($teacherSummary['monthly']['persentase_kehadiran'], 1)); ?>%</td>
                    <td class="text-center"><?php echo e($teacherSummary['monthly']['total_hadir']); ?></td>
                    <td class="text-center"><?php echo e($teacherSummary['monthly']['total_izin']); ?></td>
                    <td class="text-center"><?php echo e($teacherSummary['monthly']['total_belum_hadir']); ?></td>
                </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="10">Tidak ada data tenaga pendidik.</td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedTeacher): ?>
        <div class="section-title">Detail Guru Terpilih: <?php echo e($selectedTeacher->name); ?></div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Persentase</th>
                    <th>Hari Kerja</th>
                    <th>Hadir</th>
                    <th>Izin Disetujui</th>
                    <th>Belum Hadir</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Mingguan</td>
                    <td><?php echo e(number_format($weeklySummary['persentase_kehadiran'], 1)); ?>%</td>
                    <td><?php echo e($weeklySummary['total_hari_kerja']); ?></td>
                    <td><?php echo e($weeklySummary['total_hadir']); ?></td>
                    <td><?php echo e($weeklySummary['total_izin']); ?></td>
                    <td><?php echo e($weeklySummary['total_belum_hadir']); ?></td>
                </tr>
                <tr>
                    <td>Bulanan</td>
                    <td><?php echo e(number_format($monthlySummary['persentase_kehadiran'], 1)); ?>%</td>
                    <td><?php echo e($monthlySummary['total_hari_kerja']); ?></td>
                    <td><?php echo e($monthlySummary['total_hadir']); ?></td>
                    <td><?php echo e($monthlySummary['total_izin']); ?></td>
                    <td><?php echo e($monthlySummary['total_belum_hadir']); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">Detail Mingguan Guru Terpilih</div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th width="80">Tanggal</th>
                    <th width="70">Hari</th>
                    <th width="110">Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $weeklySummary['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr>
                        <td><?php echo e($item['tanggal']->format('d-m-Y')); ?></td>
                        <td><?php echo e(ucfirst($item['hari'])); ?></td>
                        <td><?php echo e($item['status']); ?></td>
                        <td><?php echo e($item['keterangan'] ?: '-'); ?></td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="4">Tidak ada detail pada periode mingguan ini.</td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/pdf/mobile-attendance-percentage-school-rekap.blade.php ENDPATH**/ ?>