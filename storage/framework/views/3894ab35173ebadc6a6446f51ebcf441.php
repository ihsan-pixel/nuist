<?php $__env->startSection('title', 'Teaching Progress'); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Teaching Progress</h3>
                    <div class="card-tools">
                        <form method="GET" class="d-inline">
                            <div class="input-group input-group-sm">
                                <input type="hidden" name="month" value="<?php echo e($month); ?>">
                                <input type="week" name="week" value="<?php echo e($startOfWeek->format('o-\\WW')); ?>" class="form-control" onchange="this.form.submit()">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th rowspan="2" class="text-center align-middle" style="position: sticky; left: 0; background: #f8f9fa; z-index: 10;">SCOD</th>
                                <th rowspan="2" class="text-center align-middle" style="position: sticky; left: 60px; background: #f8f9fa; z-index: 10;">Nama Sekolah / Madrasah</th>
                                <th rowspan="2" class="text-center align-middle">Hari KBM</th>
                                <th colspan="3" class="text-center">Jumlah Tenaga Pendidik</th>
                                <th colspan="2" class="text-center">Senin</th>
                                <th colspan="2" class="text-center">Selasa</th>
                                <th colspan="2" class="text-center">Rabu</th>
                                <th colspan="2" class="text-center">Kamis</th>
                                <th colspan="2" class="text-center">Jumat</th>
                                <th colspan="2" class="text-center">Sabtu</th>
                                <th rowspan="2" class="text-center align-middle">Persentase Kehadiran (%)</th>
                            </tr>
                            <tr>
                                <th class="text-center">Sudah</th>
                                <th class="text-center">Belum</th>
                                <th class="text-center">Total</th>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 6; $i++): ?>
                                <th class="text-center">Hadir</th>
                                <th class="text-center">Alpha</th>
                                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $laporanData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kabupaten): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr class="bg-info">
                                <td colspan="22" class="font-weight-bold text-center"><?php echo e($kabupaten['kabupaten']); ?></td>
                            </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = collect($kabupaten['madrasahs'])->sortBy(function($madrasah) { return (int)$madrasah['scod']; }); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr>
                                <td class="text-center" style="position: sticky; left: 0; background: white;"><?php echo e($madrasah['scod']); ?></td>
                                <td style="position: sticky; left: 60px; background: white;"><?php echo e($madrasah['nama']); ?></td>
                                <td class="text-center"><?php echo e($madrasah['hari_kbm']); ?></td>
                                <td class="text-center"><?php echo e($madrasah['sudah']); ?></td>
                                <td class="text-center"><?php echo e($madrasah['belum']); ?></td>
                                <td class="text-center"><?php echo e($madrasah['total']); ?></td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasah['presensi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $presensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <td class="text-center"><?php echo e($presensi['hadir']); ?></td>
                                <td class="text-center"><?php echo e($presensi['alpha']); ?></td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <td class="text-center font-weight-bold"><?php echo e(number_format($madrasah['persentase_kehadiran'], 2)); ?>%</td>
                            </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr class="bg-warning font-weight-bold">
                                <td colspan="3" class="text-center" style="position: sticky; left: 0; background: #fff3cd;">TOTAL <?php echo e($kabupaten['kabupaten']); ?></td>
                                <td class="text-center"><?php echo e(collect($kabupaten['madrasahs'])->sum('sudah')); ?></td>
                                <td class="text-center"><?php echo e(collect($kabupaten['madrasahs'])->sum('belum')); ?></td>
                                <td class="text-center"><?php echo e(collect($kabupaten['madrasahs'])->sum('total')); ?></td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 6; $i++): ?>
                                <td class="text-center"><?php echo e($kabupaten['total_hadir']); ?></td>
                                <td class="text-center"><?php echo e($kabupaten['total_alpha']); ?></td>
                                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <td class="text-center"><?php echo e(number_format($kabupaten['persentase_kehadiran'], 2)); ?>%</td>
                            </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Bulanan</h3>
                    <div class="card-tools">
                        <form method="GET" class="d-inline">
                            <div class="input-group input-group-sm">
                                <input type="hidden" name="week" value="<?php echo e($startOfWeek->format('o-\\WW')); ?>">
                                <input type="month" name="month" value="<?php echo e($month); ?>" class="form-control" onchange="this.form.submit()">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center align-middle" style="position: sticky; left: 0; background: #f8f9fa; z-index: 10;">SCOD</th>
                                <th class="text-center align-middle" style="position: sticky; left: 60px; background: #f8f9fa; z-index: 10;">Nama Sekolah / Madrasah</th>
                                <th class="text-center align-middle">Hari KBM</th>
                                <th class="text-center align-middle">Sudah</th>
                                <th class="text-center align-middle">Belum</th>
                                <th class="text-center align-middle">Total</th>
                                <th class="text-center align-middle">Total Hadir</th>
                                <th class="text-center align-middle">Total Alpha</th>
                                <th class="text-center align-middle">Persentase Kehadiran (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $monthlyGrandSudah = collect($laporanBulananData)->sum(fn ($kabupaten) => collect($kabupaten['madrasahs'])->sum('sudah'));
                                $monthlyGrandBelum = collect($laporanBulananData)->sum(fn ($kabupaten) => collect($kabupaten['madrasahs'])->sum('belum'));
                                $monthlyGrandTotal = collect($laporanBulananData)->sum(fn ($kabupaten) => collect($kabupaten['madrasahs'])->sum('total'));
                                $monthlyGrandHadir = collect($laporanBulananData)->sum('total_hadir');
                                $monthlyGrandAlpha = collect($laporanBulananData)->sum('total_alpha');
                                $monthlyGrandPresensi = collect($laporanBulananData)->sum('total_presensi');
                                $monthlyGrandPercentage = $monthlyGrandPresensi > 0
                                    ? ($monthlyGrandHadir / $monthlyGrandPresensi) * 100
                                    : 0;
                            ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $laporanBulananData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kabupaten): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr class="bg-info">
                                <td colspan="9" class="font-weight-bold text-center"><?php echo e($kabupaten['kabupaten']); ?> - <?php echo e($startOfMonth->translatedFormat('F Y')); ?></td>
                            </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = collect($kabupaten['madrasahs'])->sortBy(function($madrasah) { return (int)$madrasah['scod']; }); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr>
                                <td class="text-center" style="position: sticky; left: 0; background: white;"><?php echo e($madrasah['scod']); ?></td>
                                <td style="position: sticky; left: 60px; background: white;"><?php echo e($madrasah['nama']); ?></td>
                                <td class="text-center"><?php echo e($madrasah['hari_kbm']); ?></td>
                                <td class="text-center"><?php echo e($madrasah['sudah']); ?></td>
                                <td class="text-center"><?php echo e($madrasah['belum']); ?></td>
                                <td class="text-center"><?php echo e($madrasah['total']); ?></td>
                                <td class="text-center"><?php echo e($madrasah['total_hadir']); ?></td>
                                <td class="text-center"><?php echo e($madrasah['total_alpha']); ?></td>
                                <td class="text-center font-weight-bold"><?php echo e(number_format($madrasah['persentase_kehadiran'], 2)); ?>%</td>
                            </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr class="bg-warning font-weight-bold">
                                <td colspan="3" class="text-center" style="position: sticky; left: 0; background: #fff3cd;">TOTAL <?php echo e($kabupaten['kabupaten']); ?></td>
                                <td class="text-center"><?php echo e(collect($kabupaten['madrasahs'])->sum('sudah')); ?></td>
                                <td class="text-center"><?php echo e(collect($kabupaten['madrasahs'])->sum('belum')); ?></td>
                                <td class="text-center"><?php echo e(collect($kabupaten['madrasahs'])->sum('total')); ?></td>
                                <td class="text-center"><?php echo e($kabupaten['total_hadir']); ?></td>
                                <td class="text-center"><?php echo e($kabupaten['total_alpha']); ?></td>
                                <td class="text-center"><?php echo e(number_format($kabupaten['persentase_kehadiran'], 2)); ?>%</td>
                            </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr class="bg-warning font-weight-bold">
                                <td colspan="3" class="text-center" style="position: sticky; left: 0; background: #fff3cd;">TOTAL RATA-RATA BULANAN</td>
                                <td class="text-center"><?php echo e($monthlyGrandSudah); ?></td>
                                <td class="text-center"><?php echo e($monthlyGrandBelum); ?></td>
                                <td class="text-center"><?php echo e($monthlyGrandTotal); ?></td>
                                <td class="text-center"><?php echo e($monthlyGrandHadir); ?></td>
                                <td class="text-center"><?php echo e($monthlyGrandAlpha); ?></td>
                                <td class="text-center"><?php echo e(number_format($monthlyGrandPercentage, 2)); ?>%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                        <div>
                            <h3 class="card-title mb-1">Rekap Presensi dan Jadwal Mengajar Tenaga Pendidik</h3>
                            <div class="text-muted small">
                                Periode <?php echo e($teachingRecapData['label']); ?>. Data mengecualikan status kepegawaian 1, 2, 7, 8 dan kepala sekolah.
                            </div>
                        </div>
                        <form method="GET" class="d-flex flex-column flex-sm-row align-items-sm-center gap-2 mb-0">
                            <input type="hidden" name="week" value="<?php echo e($startOfWeek->format('o-\\WW')); ?>">
                            <input type="hidden" name="month" value="<?php echo e($month); ?>">

                            <select name="teaching_recap_period" id="teachingRecapPeriod" class="form-select form-select-sm" style="min-width: 130px;">
                                <option value="week" <?php echo e($teachingRecapData['period'] === 'week' ? 'selected' : ''); ?>>Mingguan</option>
                                <option value="month" <?php echo e($teachingRecapData['period'] === 'month' ? 'selected' : ''); ?>>Bulanan</option>
                            </select>

                            <input type="week"
                                name="teaching_recap_week"
                                id="teachingRecapWeek"
                                class="form-control form-control-sm"
                                value="<?php echo e($teachingRecapData['week_value']); ?>"
                                style="min-width: 150px;">

                            <input type="month"
                                name="teaching_recap_month"
                                id="teachingRecapMonth"
                                class="form-control form-control-sm"
                                value="<?php echo e($teachingRecapData['month_value']); ?>"
                                style="min-width: 150px;">

                            <button type="submit" class="btn btn-primary btn-sm px-3">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Total Tenaga Pendidik</div>
                                <div class="h4 mb-0"><?php echo e(number_format($teachingRecapData['summary']['total_tenaga_pendidik'])); ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Tidak Presensi Mengajar</div>
                                <div class="h4 mb-0 text-danger"><?php echo e(number_format($teachingRecapData['summary']['total_tidak_presensi'])); ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Sudah Punya Jadwal</div>
                                <div class="h4 mb-0 text-success"><?php echo e(number_format($teachingRecapData['summary']['total_sudah_jadwal'])); ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Belum Punya Jadwal</div>
                                <div class="h4 mb-0 text-warning"><?php echo e(number_format($teachingRecapData['summary']['total_belum_jadwal'])); ?></div>
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3">Rekap Tenaga Pendidik, Jadwal Mengajar, dan Presensi Mengajar</h5>
                    <div class="table-responsive teaching-recap-table">
                        <table id="datatable-teaching-recap" class="table table-bordered dt-responsive nowrap w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">SCOD</th>
                                    <th>Nama User</th>
                                    <th>Asal Sekolah</th>
                                    <th>Status Kepegawaian</th>
                                    <th class="text-center">Jadwal Master</th>
                                    <th class="text-center">Jadwal Periode</th>
                                    <th class="text-center">Status Jadwal</th>
                                    <th class="text-center">Jadwal Berjalan</th>
                                    <th class="text-center">Sudah Presensi</th>
                                    <th class="text-center">Tidak Presensi</th>
                                    <th class="text-center">% Tidak Presensi</th>
                                    <th class="text-center">Status Presensi</th>
                                    <th>Rincian Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $teachingRecapData['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr>
                                    <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                    <td class="text-center"><?php echo e($teacher['scod']); ?></td>
                                    <td><?php echo e($teacher['name']); ?></td>
                                    <td><?php echo e($teacher['madrasah']); ?></td>
                                    <td><?php echo e($teacher['status_kepegawaian']); ?></td>
                                    <td class="text-center"><?php echo e($teacher['jumlah_jadwal_master']); ?></td>
                                    <td class="text-center"><?php echo e($teacher['total_jadwal_periode']); ?></td>
                                    <td class="text-center">
                                        <span class="badge <?php echo e($teacher['jumlah_jadwal_master'] > 0 ? 'bg-success' : 'bg-warning text-dark'); ?>">
                                            <?php echo e($teacher['status_jadwal']); ?>

                                        </span>
                                    </td>
                                    <td class="text-center"><?php echo e($teacher['total_jadwal_berjalan']); ?></td>
                                    <td class="text-center"><?php echo e($teacher['total_presensi']); ?></td>
                                    <td class="text-center">
                                        <span class="badge <?php echo e($teacher['total_belum_presensi'] > 0 ? 'bg-danger' : 'bg-success'); ?>">
                                            <?php echo e($teacher['total_belum_presensi']); ?>

                                        </span>
                                    </td>
                                    <td class="text-center"><?php echo e(number_format($teacher['persentase_tidak_presensi'], 1)); ?>%</td>
                                    <td class="text-center">
                                        <?php
                                            $presensiBadge = $teacher['total_belum_presensi'] > 0
                                                ? 'bg-danger'
                                                : ($teacher['jumlah_jadwal_master'] > 0 ? 'bg-success' : 'bg-secondary');
                                        ?>
                                        <span class="badge <?php echo e($presensiBadge); ?>">
                                            <?php echo e($teacher['status_presensi']); ?>

                                        </span>
                                    </td>
                                    <td class="small"><?php echo e($teacher['rincian_tanggal']); ?></td>
                                </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<style>
.table-responsive {
    max-height: 70vh;
    overflow-y: auto;
}

.table thead th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 5;
}

.table thead th[rowspan="2"] {
    z-index: 10;
}

.table tbody tr:hover {
    background-color: #f5f5f5;
}

.bg-info {
    background-color: #d1ecf1 !important;
}

.bg-warning {
    background-color: #fff3cd !important;
}

.teaching-recap-table {
    max-height: 520px;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/jszip/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/pdfmake/build/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/pdfmake/build/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')); ?>"></script>
<script>
$(document).ready(function () {
    function toggleTeachingRecapFilters() {
        let period = $('#teachingRecapPeriod').val();
        $('#teachingRecapWeek').toggle(period === 'week');
        $('#teachingRecapMonth').toggle(period === 'month');
    }

    let teachingRecapTable = $('#datatable-teaching-recap');
    if (teachingRecapTable.length) {
        let recapTable = teachingRecapTable.DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            order: [[10, 'desc'], [1, 'asc']],
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
        });

        recapTable.buttons().container()
            .appendTo('#datatable-teaching-recap_wrapper .col-md-6:eq(0)');
    }

    toggleTeachingRecapFilters();
    $('#teachingRecapPeriod').on('change', toggleTeachingRecapFilters);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/admin/teaching_progress.blade.php ENDPATH**/ ?>