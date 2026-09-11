<?php $__env->startSection('title', 'Progress Mengajar'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('mgmp.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php
    $activeTab = request('tab', 'progress');
    $weeklyGrandSudah = collect($laporanData)->sum(fn ($kabupaten) => collect($kabupaten['madrasahs'])->sum('sudah'));
    $weeklyGrandBelum = collect($laporanData)->sum(fn ($kabupaten) => collect($kabupaten['madrasahs'])->sum('belum'));
    $weeklyGrandTotal = collect($laporanData)->sum(fn ($kabupaten) => collect($kabupaten['madrasahs'])->sum('total'));
    $weeklyGrandJadwal = collect($laporanData)->sum('total_jadwal_berjalan');
    $weeklyGrandHadir = collect($laporanData)->sum('total_hadir');
    $weeklyGrandIzin = collect($laporanData)->sum('total_izin');
    $weeklyGrandTidakPresensi = collect($laporanData)->sum('total_tidak_presensi_jurnal');
    $weeklyGrandAlpha = collect($laporanData)->sum('total_alpha');
    $weeklyGrandPresensi = collect($laporanData)->sum('total_presensi');
    $weeklyGrandPercentage = $weeklyGrandPresensi > 0
        ? (($weeklyGrandHadir + $weeklyGrandIzin) / $weeklyGrandPresensi) * 100
        : 0;

    $monthlyGrandSudah = collect($laporanBulananData)->sum(fn ($kabupaten) => collect($kabupaten['madrasahs'])->sum('sudah'));
    $monthlyGrandBelum = collect($laporanBulananData)->sum(fn ($kabupaten) => collect($kabupaten['madrasahs'])->sum('belum'));
    $monthlyGrandTotal = collect($laporanBulananData)->sum(fn ($kabupaten) => collect($kabupaten['madrasahs'])->sum('total'));
    $monthlyGrandJadwal = collect($laporanBulananData)->sum('total_jadwal_berjalan');
    $monthlyGrandHadir = collect($laporanBulananData)->sum('total_hadir');
    $monthlyGrandIzin = collect($laporanBulananData)->sum('total_izin');
    $monthlyGrandTidakPresensi = collect($laporanBulananData)->sum('total_tidak_presensi_jurnal');
    $monthlyGrandAlpha = collect($laporanBulananData)->sum('total_alpha');
    $monthlyGrandPresensi = collect($laporanBulananData)->sum('total_presensi');
    $monthlyGrandPercentage = $monthlyGrandPresensi > 0
        ? (($monthlyGrandHadir + $monthlyGrandIzin) / $monthlyGrandPresensi) * 100
        : 0;

    $tabProgressUrl = route('admin.teaching_progress', array_merge(request()->query(), ['tab' => 'progress']));
    $tabLaporanUrl = route('admin.teaching_progress', array_merge(request()->query(), ['tab' => 'laporan']));
?>

<div class="container-fluid mgmp-page teaching-progress-page">
    <div class="mgmp-hero-strip mb-4">
        <div class="d-flex flex-column flex-xl-row justify-content-between gap-3 align-items-xl-end">
            <div>
                <div class="mgmp-kicker">Monitoring Mengajar</div>
                <h4 class="mb-2">Progress Mengajar per Sekolah</h4>
                <p class="mb-0">Pantau laporan mengajar per sekolah, per tanggal, dan per guru dalam tampilan yang lebih rapi.</p>
            </div>
            <form method="GET" action="<?php echo e(route('admin.teaching_progress')); ?>" class="row g-2 align-items-end hero-filter-form">
                <input type="hidden" name="tab" value="<?php echo e($activeTab); ?>">
                <input type="hidden" name="week" value="<?php echo e($startOfWeek->format('o-\\WW')); ?>">
                <input type="hidden" name="teaching_recap_period" value="<?php echo e($teachingRecapData['period']); ?>">
                <input type="hidden" name="teaching_recap_week" value="<?php echo e($teachingRecapData['week_value']); ?>">
                <input type="hidden" name="teaching_recap_month" value="<?php echo e($teachingRecapData['month_value']); ?>">
                <div class="col-sm-auto">
                    <label for="month" class="form-label text-white mb-1">Periode Bulan</label>
                    <input type="month" id="month" name="month" value="<?php echo e($month); ?>" class="form-control">
                </div>
                <div class="col-sm-auto">
                    <button type="submit" class="btn btn-light px-4">
                        <i class="bx bx-filter-alt me-1"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="teaching-tab-switch mb-4">
        <a href="<?php echo e($tabProgressUrl); ?>" class="tab-pill <?php echo e($activeTab === 'progress' ? 'active' : ''); ?>">
            <i class="bx bx-bar-chart-alt-2"></i>
            <span>Progress Mengajar</span>
        </a>
        <a href="<?php echo e($tabLaporanUrl); ?>" class="tab-pill <?php echo e($activeTab === 'laporan' ? 'active' : ''); ?>">
            <i class="bx bx-file"></i>
            <span>Laporan Mengajar</span>
        </a>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'laporan'): ?>
        <div class="card mgmp-panel mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                    <div>
                        <h5 class="mb-1">Laporan Mengajar</h5>
                        <p class="text-muted mb-0">Rekap mingguan, bulanan, dan rincian guru dipisahkan ke bagian ini agar halaman utama tetap fokus.</p>
                    </div>
                    <div class="report-note">
                        Alpha hanya untuk guru tanpa jadwal master. Tidak Presensi Jurnal berarti jadwal ada tetapi jurnal mengajar belum diisi.
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion report-accordion" id="teachingReportsAccordion">
            <div class="accordion-item mb-3">
                <h2 class="accordion-header" id="weeklyReportHeading">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#weeklyReportCollapse" aria-expanded="true" aria-controls="weeklyReportCollapse">
                        Rekap Mingguan
                    </button>
                </h2>
                <div id="weeklyReportCollapse" class="accordion-collapse collapse show" aria-labelledby="weeklyReportHeading" data-bs-parent="#teachingReportsAccordion">
                    <div class="accordion-body">
                        <div class="section-toolbar mb-3">
                            <form method="GET" action="<?php echo e(route('admin.teaching_progress')); ?>" class="row g-2 align-items-end">
                                <input type="hidden" name="tab" value="laporan">
                                <input type="hidden" name="month" value="<?php echo e($month); ?>">
                                <input type="hidden" name="teaching_recap_period" value="<?php echo e($teachingRecapData['period']); ?>">
                                <input type="hidden" name="teaching_recap_week" value="<?php echo e($teachingRecapData['week_value']); ?>">
                                <input type="hidden" name="teaching_recap_month" value="<?php echo e($teachingRecapData['month_value']); ?>">
                                <div class="col-md-3 col-lg-2">
                                    <label class="form-label">Pilih Minggu</label>
                                    <input type="week" name="week" value="<?php echo e($startOfWeek->format('o-\\WW')); ?>" class="form-control">
                                </div>
                                <div class="col-md-auto">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-refresh me-1"></i> Perbarui
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive report-table-wrapper">
                            <table class="table align-middle report-table">
                                <thead>
                                    <tr>
                                        <th rowspan="2">SCOD</th>
                                        <th rowspan="2">Sekolah / Madrasah</th>
                                        <th rowspan="2" class="text-center">Hari KBM</th>
                                        <th colspan="3" class="text-center">Tenaga Pendidik</th>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 6; $i++): ?>
                                            <th colspan="5" class="text-center">
                                                <?php echo e(\Carbon\Carbon::parse($weeklyDayMarkers[$i]['date'])->locale('id')->translatedFormat('D, d M')); ?>

                                            </th>
                                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <th colspan="5" class="text-center">Total Minggu Ini</th>
                                        <th rowspan="2" class="text-center">Kehadiran</th>
                                        <th rowspan="2" class="text-center">Rank</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Sudah Jadwal</th>
                                        <th class="text-center">Belum Jadwal</th>
                                        <th class="text-center">Total</th>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 6; $i++): ?>
                                            <th class="text-center">Jadwal</th>
                                            <th class="text-center">Hadir</th>
                                            <th class="text-center">Izin</th>
                                            <th class="text-center">Belum Jurnal</th>
                                            <th class="text-center">Alpha</th>
                                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <th class="text-center">Jadwal</th>
                                        <th class="text-center">Hadir</th>
                                        <th class="text-center">Izin</th>
                                        <th class="text-center">Belum Jurnal</th>
                                        <th class="text-center">Alpha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $laporanData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kabupaten): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <tr class="report-group-row">
                                            <td colspan="43"><?php echo e($kabupaten['kabupaten']); ?></td>
                                        </tr>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = collect($kabupaten['madrasahs'])->sortBy(fn ($madrasah) => (int) $madrasah['scod']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <tr>
                                                <td><?php echo e($madrasah['scod']); ?></td>
                                                <td><?php echo e($madrasah['nama']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['hari_kbm']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['sudah']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['belum']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['total']); ?></td>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasah['presensi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $presensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                    <td class="text-center"><?php echo e($presensi['jadwal']); ?></td>
                                                    <td class="text-center"><?php echo e($presensi['hadir']); ?></td>
                                                    <td class="text-center"><?php echo e($presensi['izin']); ?></td>
                                                    <td class="text-center"><?php echo e($presensi['tidak_presensi_jurnal']); ?></td>
                                                    <td class="text-center"><?php echo e($presensi['alpha']); ?></td>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                <td class="text-center"><?php echo e($madrasah['total_jadwal_berjalan']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['total_hadir']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['total_izin']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['total_tidak_presensi_jurnal']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['total_alpha']); ?></td>
                                                <td class="text-center fw-semibold"><?php echo e(number_format($madrasah['persentase_kehadiran'], 2)); ?>%</td>
                                                <td class="text-center fw-semibold"><?php echo e($madrasah['rank'] ?? '-'); ?></td>
                                            </tr>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <tr class="report-total-row">
                                            <td colspan="3">TOTAL <?php echo e($kabupaten['kabupaten']); ?></td>
                                            <td class="text-center"><?php echo e(collect($kabupaten['madrasahs'])->sum('sudah')); ?></td>
                                            <td class="text-center"><?php echo e(collect($kabupaten['madrasahs'])->sum('belum')); ?></td>
                                            <td class="text-center"><?php echo e(collect($kabupaten['madrasahs'])->sum('total')); ?></td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 6; $i++): ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($weeklyDayMarkers[$i]['is_holiday'])): ?>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center">-</td>
                                                <?php else: ?>
                                                    <td class="text-center"><?php echo e($kabupaten['daily_totals'][$i]['jadwal']); ?></td>
                                                    <td class="text-center"><?php echo e($kabupaten['daily_totals'][$i]['hadir']); ?></td>
                                                    <td class="text-center"><?php echo e($kabupaten['daily_totals'][$i]['izin']); ?></td>
                                                    <td class="text-center"><?php echo e($kabupaten['daily_totals'][$i]['tidak_presensi_jurnal']); ?></td>
                                                    <td class="text-center"><?php echo e($kabupaten['daily_totals'][$i]['alpha']); ?></td>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <td class="text-center"><?php echo e($kabupaten['total_jadwal_berjalan']); ?></td>
                                            <td class="text-center"><?php echo e($kabupaten['total_hadir']); ?></td>
                                            <td class="text-center"><?php echo e($kabupaten['total_izin']); ?></td>
                                            <td class="text-center"><?php echo e($kabupaten['total_tidak_presensi_jurnal']); ?></td>
                                            <td class="text-center"><?php echo e($kabupaten['total_alpha']); ?></td>
                                            <td class="text-center fw-semibold"><?php echo e(number_format($kabupaten['persentase_kehadiran'], 2)); ?>%</td>
                                            <td class="text-center fw-semibold">-</td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr class="report-grand-total">
                                        <td colspan="3">TOTAL SEMUA KABUPATEN</td>
                                        <td class="text-center"><?php echo e($weeklyGrandSudah); ?></td>
                                        <td class="text-center"><?php echo e($weeklyGrandBelum); ?></td>
                                        <td class="text-center"><?php echo e($weeklyGrandTotal); ?></td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 6; $i++): ?>
                                            <?php $grandDay = collect($laporanData)->sum(fn ($kabupaten) => $kabupaten['daily_totals'][$i]['jadwal']); ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($weeklyDayMarkers[$i]['is_holiday'])): ?>
                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                            <?php else: ?>
                                                <td class="text-center"><?php echo e(collect($laporanData)->sum(fn ($kabupaten) => $kabupaten['daily_totals'][$i]['jadwal'])); ?></td>
                                                <td class="text-center"><?php echo e(collect($laporanData)->sum(fn ($kabupaten) => $kabupaten['daily_totals'][$i]['hadir'])); ?></td>
                                                <td class="text-center"><?php echo e(collect($laporanData)->sum(fn ($kabupaten) => $kabupaten['daily_totals'][$i]['izin'])); ?></td>
                                                <td class="text-center"><?php echo e(collect($laporanData)->sum(fn ($kabupaten) => $kabupaten['daily_totals'][$i]['tidak_presensi_jurnal'])); ?></td>
                                                <td class="text-center"><?php echo e(collect($laporanData)->sum(fn ($kabupaten) => $kabupaten['daily_totals'][$i]['alpha'])); ?></td>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <td class="text-center"><?php echo e($weeklyGrandJadwal); ?></td>
                                        <td class="text-center"><?php echo e($weeklyGrandHadir); ?></td>
                                        <td class="text-center"><?php echo e($weeklyGrandIzin); ?></td>
                                        <td class="text-center"><?php echo e($weeklyGrandTidakPresensi); ?></td>
                                        <td class="text-center"><?php echo e($weeklyGrandAlpha); ?></td>
                                        <td class="text-center fw-semibold"><?php echo e(number_format($weeklyGrandPercentage, 2)); ?>%</td>
                                        <td class="text-center fw-semibold">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item mb-3">
                <h2 class="accordion-header" id="monthlyReportHeading">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#monthlyReportCollapse" aria-expanded="false" aria-controls="monthlyReportCollapse">
                        Rekap Bulanan
                    </button>
                </h2>
                <div id="monthlyReportCollapse" class="accordion-collapse collapse" aria-labelledby="monthlyReportHeading" data-bs-parent="#teachingReportsAccordion">
                    <div class="accordion-body">
                        <div class="section-toolbar mb-3">
                            <form method="GET" action="<?php echo e(route('admin.teaching_progress')); ?>" class="row g-2 align-items-end">
                                <input type="hidden" name="tab" value="laporan">
                                <input type="hidden" name="week" value="<?php echo e($startOfWeek->format('o-\\WW')); ?>">
                                <input type="hidden" name="teaching_recap_period" value="<?php echo e($teachingRecapData['period']); ?>">
                                <input type="hidden" name="teaching_recap_week" value="<?php echo e($teachingRecapData['week_value']); ?>">
                                <input type="hidden" name="teaching_recap_month" value="<?php echo e($teachingRecapData['month_value']); ?>">
                                <div class="col-md-3 col-lg-2">
                                    <label class="form-label">Pilih Bulan</label>
                                    <input type="month" name="month" value="<?php echo e($month); ?>" class="form-control">
                                </div>
                                <div class="col-md-auto">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-refresh me-1"></i> Perbarui
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive report-table-wrapper">
                            <table class="table align-middle report-table">
                                <thead>
                                    <tr>
                                        <th>SCOD</th>
                                        <th>Sekolah / Madrasah</th>
                                        <th class="text-center">Hari KBM</th>
                                        <th class="text-center">Sudah Jadwal</th>
                                        <th class="text-center">Belum Jadwal</th>
                                        <th class="text-center">Total Guru</th>
                                        <th class="text-center">Jadwal Berjalan</th>
                                        <th class="text-center">Hadir</th>
                                        <th class="text-center">Izin</th>
                                        <th class="text-center">Belum Jurnal</th>
                                        <th class="text-center">Alpha</th>
                                        <th class="text-center">Kehadiran</th>
                                        <th class="text-center">Rank</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $laporanBulananData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kabupaten): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <tr class="report-group-row">
                                            <td colspan="13"><?php echo e($kabupaten['kabupaten']); ?> - <?php echo e($startOfMonth->locale('id')->translatedFormat('F Y')); ?></td>
                                        </tr>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = collect($kabupaten['madrasahs'])->sortBy(fn ($madrasah) => (int) $madrasah['scod']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <tr>
                                                <td><?php echo e($madrasah['scod']); ?></td>
                                                <td><?php echo e($madrasah['nama']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['hari_kbm']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['sudah']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['belum']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['total']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['total_jadwal_berjalan']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['total_hadir']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['total_izin']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['total_tidak_presensi_jurnal']); ?></td>
                                                <td class="text-center"><?php echo e($madrasah['total_alpha']); ?></td>
                                                <td class="text-center fw-semibold"><?php echo e(number_format($madrasah['persentase_kehadiran'], 2)); ?>%</td>
                                                <td class="text-center fw-semibold"><?php echo e($madrasah['rank'] ?? '-'); ?></td>
                                            </tr>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <tr class="report-total-row">
                                            <td colspan="3">TOTAL <?php echo e($kabupaten['kabupaten']); ?></td>
                                            <td class="text-center"><?php echo e(collect($kabupaten['madrasahs'])->sum('sudah')); ?></td>
                                            <td class="text-center"><?php echo e(collect($kabupaten['madrasahs'])->sum('belum')); ?></td>
                                            <td class="text-center"><?php echo e(collect($kabupaten['madrasahs'])->sum('total')); ?></td>
                                            <td class="text-center"><?php echo e($kabupaten['total_jadwal_berjalan']); ?></td>
                                            <td class="text-center"><?php echo e($kabupaten['total_hadir']); ?></td>
                                            <td class="text-center"><?php echo e($kabupaten['total_izin']); ?></td>
                                            <td class="text-center"><?php echo e($kabupaten['total_tidak_presensi_jurnal']); ?></td>
                                            <td class="text-center"><?php echo e($kabupaten['total_alpha']); ?></td>
                                            <td class="text-center fw-semibold"><?php echo e(number_format($kabupaten['persentase_kehadiran'], 2)); ?>%</td>
                                            <td class="text-center fw-semibold">-</td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr class="report-grand-total">
                                        <td colspan="3">TOTAL BULANAN SEMUA KABUPATEN</td>
                                        <td class="text-center"><?php echo e($monthlyGrandSudah); ?></td>
                                        <td class="text-center"><?php echo e($monthlyGrandBelum); ?></td>
                                        <td class="text-center"><?php echo e($monthlyGrandTotal); ?></td>
                                        <td class="text-center"><?php echo e($monthlyGrandJadwal); ?></td>
                                        <td class="text-center"><?php echo e($monthlyGrandHadir); ?></td>
                                        <td class="text-center"><?php echo e($monthlyGrandIzin); ?></td>
                                        <td class="text-center"><?php echo e($monthlyGrandTidakPresensi); ?></td>
                                        <td class="text-center"><?php echo e($monthlyGrandAlpha); ?></td>
                                        <td class="text-center fw-semibold"><?php echo e(number_format($monthlyGrandPercentage, 2)); ?>%</td>
                                        <td class="text-center fw-semibold">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="teacherReportHeading">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#teacherReportCollapse" aria-expanded="false" aria-controls="teacherReportCollapse">
                        Rincian Guru dan Jadwal Mengajar
                    </button>
                </h2>
                <div id="teacherReportCollapse" class="accordion-collapse collapse" aria-labelledby="teacherReportHeading" data-bs-parent="#teachingReportsAccordion">
                    <div class="accordion-body">
                        <div class="section-toolbar mb-3">
                            <form method="GET" action="<?php echo e(route('admin.teaching_progress')); ?>" class="row g-2 align-items-end">
                                <input type="hidden" name="tab" value="laporan">
                                <input type="hidden" name="week" value="<?php echo e($startOfWeek->format('o-\\WW')); ?>">
                                <input type="hidden" name="month" value="<?php echo e($month); ?>">
                                <div class="col-md-3 col-lg-2">
                                    <label class="form-label">Mode Rekap</label>
                                    <select name="teaching_recap_period" id="teachingRecapPeriod" class="form-select">
                                        <option value="week" <?php echo e($teachingRecapData['period'] === 'week' ? 'selected' : ''); ?>>Mingguan</option>
                                        <option value="month" <?php echo e($teachingRecapData['period'] === 'month' ? 'selected' : ''); ?>>Bulanan</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-lg-2" id="teachingRecapWeekWrapper">
                                    <label class="form-label">Pilih Minggu</label>
                                    <input type="week" name="teaching_recap_week" id="teachingRecapWeek" class="form-control" value="<?php echo e($teachingRecapData['week_value']); ?>">
                                </div>
                                <div class="col-md-3 col-lg-2" id="teachingRecapMonthWrapper">
                                    <label class="form-label">Pilih Bulan</label>
                                    <input type="month" name="teaching_recap_month" id="teachingRecapMonth" class="form-control" value="<?php echo e($teachingRecapData['month_value']); ?>">
                                </div>
                                <div class="col-md-auto">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-refresh me-1"></i> Perbarui
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <div class="mini-stat-card">
                                    <span>Total Tenaga Pendidik</span>
                                    <strong><?php echo e(number_format($teachingRecapData['summary']['total_tenaga_pendidik'])); ?></strong>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mini-stat-card danger">
                                    <span>Belum Presensi Mengajar</span>
                                    <strong><?php echo e(number_format($teachingRecapData['summary']['total_tidak_presensi'])); ?></strong>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mini-stat-card success">
                                    <span>Sudah Punya Jadwal</span>
                                    <strong><?php echo e(number_format($teachingRecapData['summary']['total_sudah_jadwal'])); ?></strong>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mini-stat-card warning">
                                    <span>Belum Punya Jadwal</span>
                                    <strong><?php echo e(number_format($teachingRecapData['summary']['total_belum_jadwal'])); ?></strong>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive report-table-wrapper">
                            <table class="table align-middle report-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>SCOD</th>
                                        <th>Nama Guru</th>
                                        <th>Asal Sekolah</th>
                                        <th>Status Kepegawaian</th>
                                        <th class="text-center">Jadwal Master</th>
                                        <th class="text-center">Jadwal Periode</th>
                                        <th class="text-center">Jadwal Berjalan</th>
                                        <th class="text-center">Presensi</th>
                                        <th class="text-center">Izin</th>
                                        <th class="text-center">Belum Presensi</th>
                                        <th class="text-center">% Tidak Presensi</th>
                                        <th>Status</th>
                                        <th>Rincian Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $teachingRecapData['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <?php
                                            $statusClass = $teacher['total_belum_presensi'] > 0
                                                ? 'danger'
                                                : ($teacher['jumlah_jadwal_master'] > 0 ? 'success' : 'secondary');
                                        ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($teacher['scod']); ?></td>
                                            <td><?php echo e($teacher['name']); ?></td>
                                            <td><?php echo e($teacher['madrasah']); ?></td>
                                            <td><?php echo e($teacher['status_kepegawaian']); ?></td>
                                            <td class="text-center"><?php echo e($teacher['jumlah_jadwal_master']); ?></td>
                                            <td class="text-center"><?php echo e($teacher['total_jadwal_periode']); ?></td>
                                            <td class="text-center"><?php echo e($teacher['total_jadwal_berjalan']); ?></td>
                                            <td class="text-center"><?php echo e($teacher['total_presensi']); ?></td>
                                            <td class="text-center"><?php echo e($teacher['total_izin']); ?></td>
                                            <td class="text-center"><?php echo e($teacher['total_belum_presensi']); ?></td>
                                            <td class="text-center"><?php echo e(number_format($teacher['persentase_tidak_presensi'], 1)); ?>%</td>
                                            <td>
                                                <span class="table-badge <?php echo e($statusClass); ?>"><?php echo e($teacher['status_presensi']); ?></span>
                                            </td>
                                            <td><?php echo e($teacher['rincian_tanggal']); ?></td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card mgmp-stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Total Sekolah</div>
                                <h3 class="mb-0"><?php echo e(number_format($schoolProgressData['summary']['total_schools'])); ?></h3>
                            </div>
                            <div class="mgmp-icon-bubble"><i class="bx bx-buildings"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mgmp-stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Sekolah Sudah Lapor</div>
                                <h3 class="mb-0"><?php echo e(number_format($schoolProgressData['summary']['schools_with_reports'])); ?></h3>
                            </div>
                            <div class="mgmp-icon-bubble"><i class="bx bx-check-shield"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mgmp-stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Total Laporan</div>
                                <h3 class="mb-0"><?php echo e(number_format($schoolProgressData['summary']['total_reports'])); ?></h3>
                            </div>
                            <div class="mgmp-icon-bubble"><i class="bx bx-clipboard"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mgmp-stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Total Siswa Hadir</div>
                                <h3 class="mb-0"><?php echo e(number_format($schoolProgressData['summary']['total_present_students'])); ?></h3>
                            </div>
                            <div class="mgmp-icon-bubble"><i class="bx bx-group"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mgmp-panel mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                    <div>
                        <h5 class="mb-1">Data Progress <?php echo e($schoolProgressData['month_label']); ?></h5>
                        <p class="text-muted mb-0">Setiap sekolah menampilkan ringkasan laporan mengajar dan detail harian dalam tabel yang rapi.</p>
                    </div>
                    <div class="progress-summary-chip">
                        <?php echo e(number_format($schoolProgressData['summary']['reported_teachers'])); ?> guru tercatat mengirim laporan pada periode ini
                    </div>
                </div>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $schoolProgressData['kabupaten_groups']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div class="card mgmp-panel mb-4">
                <div class="card-header border-0 p-4 pb-0 bg-transparent">
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                        <div>
                            <h5 class="mb-1"><?php echo e($group['kabupaten']); ?></h5>
                            <p class="text-muted mb-0"><?php echo e($group['schools_with_reports']); ?> dari <?php echo e($group['total_schools']); ?> sekolah sudah mengirim <?php echo e(number_format($group['total_reports'])); ?> laporan.</p>
                        </div>
                        <span class="mgmp-chip"><?php echo e($schoolProgressData['month_label']); ?></span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive school-summary-table">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>SCOD</th>
                                    <th>Sekolah / Madrasah</th>
                                    <th class="text-center">Guru</th>
                                    <th class="text-center">Laporan</th>
                                    <th class="text-center">Hari Lapor</th>
                                    <th class="text-center">Progress</th>
                                    <th class="text-center">Update Terakhir</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $group['schools']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <?php $detailId = 'school-progress-' . $school['id']; ?>
                                    <tr>
                                        <td class="fw-semibold"><?php echo e($school['scod']); ?></td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($school['name']); ?></div>
                                            <div class="text-muted school-subline">
                                                <?php echo e($school['reported_teachers']); ?> guru pelapor dari <?php echo e($school['total_teachers']); ?> guru
                                            </div>
                                        </td>
                                        <td class="text-center"><?php echo e(number_format($school['total_teachers'])); ?></td>
                                        <td class="text-center"><?php echo e(number_format($school['total_reports'])); ?></td>
                                        <td class="text-center"><?php echo e(number_format($school['total_days_reported'])); ?></td>
                                        <td class="text-center">
                                            <div class="progress-ring-text"><?php echo e(number_format($school['progress_percentage'], 1)); ?>%</div>
                                        </td>
                                        <td class="text-center"><?php echo e($school['latest_report_label'] ?? '-'); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-outline-primary btn-sm px-3" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo e($detailId); ?>" aria-expanded="false" aria-controls="<?php echo e($detailId); ?>">
                                                Lihat Detail
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="detail-row">
                                        <td colspan="8" class="p-0 border-0">
                                            <div class="collapse" id="<?php echo e($detailId); ?>">
                                                <div class="school-detail-panel">
                                                    <div class="school-detail-stats">
                                                        <div class="detail-stat">
                                                            <span>Jadwal Berjalan</span>
                                                            <strong><?php echo e(number_format($school['scheduled_sessions'])); ?></strong>
                                                        </div>
                                                        <div class="detail-stat">
                                                            <span>Laporan Terkirim</span>
                                                            <strong><?php echo e(number_format($school['total_reports'])); ?></strong>
                                                        </div>
                                                        <div class="detail-stat">
                                                            <span>Siswa Hadir</span>
                                                            <strong><?php echo e(number_format($school['total_present_students'])); ?></strong>
                                                        </div>
                                                        <div class="detail-stat">
                                                            <span>Hari KBM</span>
                                                            <strong><?php echo e($school['hari_kbm'] ?: '-'); ?></strong>
                                                        </div>
                                                    </div>

                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($school['daily_reports']) > 0): ?>
                                                        <div class="accordion daily-report-accordion" id="accordion-<?php echo e($school['id']); ?>">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $school['daily_reports']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dateIndex => $dailyReport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                                <?php
                                                                    $dailyHeadingId = 'daily-heading-' . $school['id'] . '-' . $dateIndex;
                                                                    $dailyCollapseId = 'daily-collapse-' . $school['id'] . '-' . $dateIndex;
                                                                ?>
                                                                <div class="accordion-item mb-3">
                                                                    <h2 class="accordion-header" id="<?php echo e($dailyHeadingId); ?>">
                                                                        <button class="accordion-button <?php echo e($dateIndex === 0 ? '' : 'collapsed'); ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo e($dailyCollapseId); ?>" aria-expanded="<?php echo e($dateIndex === 0 ? 'true' : 'false'); ?>" aria-controls="<?php echo e($dailyCollapseId); ?>">
                                                                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center w-100 pe-3 gap-2">
                                                                                <div>
                                                                                    <div class="fw-semibold"><?php echo e($dailyReport['day_label']); ?>, <?php echo e($dailyReport['date_label']); ?></div>
                                                                                </div>
                                                                                <div class="daily-report-meta">
                                                                                    <span><?php echo e($dailyReport['total_reports']); ?> laporan</span>
                                                                                    <span><?php echo e($dailyReport['total_teachers']); ?> guru</span>
                                                                                    <span><?php echo e(number_format($dailyReport['total_present_students'])); ?> siswa hadir</span>
                                                                                </div>
                                                                            </div>
                                                                        </button>
                                                                    </h2>
                                                                    <div id="<?php echo e($dailyCollapseId); ?>" class="accordion-collapse collapse <?php echo e($dateIndex === 0 ? 'show' : ''); ?>" aria-labelledby="<?php echo e($dailyHeadingId); ?>" data-bs-parent="#accordion-<?php echo e($school['id']); ?>">
                                                                        <div class="accordion-body">
                                                                            <div class="table-responsive detail-table-wrapper">
                                                                                <table class="table align-middle detail-table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>No</th>
                                                                                            <th>Nama Guru</th>
                                                                                            <th>Keterangan Presensi</th>
                                                                                            <th>Jumlah Siswa</th>
                                                                                            <th>Materi</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dailyReport['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                                                            <tr>
                                                                                                <td><?php echo e($loop->iteration); ?></td>
                                                                                                <td>
                                                                                                    <div class="fw-semibold"><?php echo e($row['teacher_name']); ?></div>
                                                                                                    <div class="text-muted school-subline">
                                                                                                        <?php echo e($row['subject'] ?: '-'); ?>

                                                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($row['class_name']): ?>
                                                                                                            • <?php echo e($row['class_name']); ?>

                                                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($row['time'] !== '-'): ?>
                                                                                                            • <?php echo e($row['time']); ?>

                                                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <span class="table-badge <?php echo e($row['status_class']); ?>"><?php echo e($row['status_label']); ?></span>
                                                                                                </td>
                                                                                                <td><?php echo e($row['student_summary']); ?></td>
                                                                                                <td><?php echo e($row['materi']); ?></td>
                                                                                            </tr>
                                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="mgmp-empty-state">
                                                            <i class="bx bx-folder-open"></i>
                                                            <div class="fw-semibold">Belum ada laporan mengajar</div>
                                                            <div>Data untuk sekolah ini belum tercatat pada periode <?php echo e($schoolProgressData['month_label']); ?>.</div>
                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="card mgmp-panel">
                <div class="card-body">
                    <div class="mgmp-empty-state">
                        <i class="bx bx-calendar-x"></i>
                        <div class="fw-semibold">Belum ada data progress mengajar</div>
                        <div>Silakan pilih periode lain atau tunggu laporan masuk dari sekolah.</div>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<style>
    .teaching-progress-page .hero-filter-form .form-control {
        background: rgba(255, 255, 255, 0.95);
        min-width: 170px;
    }

    .teaching-tab-switch {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .teaching-tab-switch .tab-pill {
        align-items: center;
        background: #fff;
        border: 1px solid #dfe9e4;
        border-radius: 999px;
        color: #36534c;
        display: inline-flex;
        font-weight: 700;
        gap: 8px;
        padding: 11px 18px;
        text-decoration: none;
        transition: all .2s ease;
    }

    .teaching-tab-switch .tab-pill.active,
    .teaching-tab-switch .tab-pill:hover {
        background: linear-gradient(135deg, rgba(0, 75, 76, 0.10), rgba(14, 133, 73, 0.14));
        border-color: rgba(14, 133, 73, 0.28);
        color: #0e8549;
    }

    .teaching-progress-page .card-header {
        border-bottom: 1px solid #edf3ef !important;
    }

    .progress-summary-chip,
    .report-note {
        background: #f5faf7;
        border: 1px solid #deebe4;
        border-radius: 999px;
        color: #35534b;
        font-weight: 600;
        padding: 10px 16px;
    }

    .school-summary-table .table,
    .detail-table,
    .report-table {
        margin-bottom: 0;
    }

    .school-summary-table thead th,
    .detail-table thead th,
    .report-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .school-summary-table .table tbody td {
        vertical-align: middle;
    }

    .school-subline {
        font-size: 12px;
    }

    .progress-ring-text {
        color: #0e8549;
        font-weight: 800;
    }

    .detail-row td {
        background: #fbfdfc;
    }

    .school-detail-panel {
        background: linear-gradient(180deg, #fbfdfc 0%, #f6fbf8 100%);
        border-top: 1px solid #e5eee9;
        padding: 22px;
    }

    .school-detail-stats {
        display: grid;
        gap: 14px;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        margin-bottom: 20px;
    }

    .detail-stat {
        background: #fff;
        border: 1px solid #e5eee9;
        border-radius: 16px;
        padding: 14px 16px;
    }

    .detail-stat span,
    .mini-stat-card span {
        color: #6b7b75;
        display: block;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
        text-transform: uppercase;
    }

    .detail-stat strong,
    .mini-stat-card strong {
        color: #102d28;
        font-size: 24px;
        line-height: 1;
    }

    .daily-report-accordion .accordion-item,
    .report-accordion .accordion-item {
        background: #fff;
        border: 1px solid #e5eee9;
        border-radius: 18px;
        overflow: hidden;
    }

    .daily-report-accordion .accordion-button,
    .report-accordion .accordion-button {
        background: #fff;
        box-shadow: none;
        color: #102d28;
        font-weight: 700;
    }

    .daily-report-accordion .accordion-button:not(.collapsed),
    .report-accordion .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, rgba(0, 75, 76, 0.04), rgba(14, 133, 73, 0.08));
        color: #102d28;
    }

    .daily-report-meta {
        color: #517068;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 13px;
        font-weight: 600;
    }

    .detail-table-wrapper,
    .report-table-wrapper {
        max-height: 70vh;
        overflow: auto;
    }

    .table-badge {
        border-radius: 999px;
        display: inline-flex;
        font-size: 12px;
        font-weight: 700;
        padding: 7px 11px;
    }

    .table-badge.success {
        background: rgba(14, 133, 73, 0.12);
        color: #0e8549;
    }

    .table-badge.warning {
        background: rgba(239, 170, 12, 0.18);
        color: #8a6100;
    }

    .table-badge.info {
        background: rgba(13, 110, 253, 0.12);
        color: #0b5ed7;
    }

    .table-badge.danger {
        background: rgba(220, 53, 69, 0.12);
        color: #c62828;
    }

    .table-badge.secondary {
        background: rgba(108, 117, 125, 0.12);
        color: #55616d;
    }

    .report-group-row td {
        background: linear-gradient(135deg, rgba(0, 75, 76, 0.10), rgba(14, 133, 73, 0.10));
        color: #102d28;
        font-weight: 800;
    }

    .report-total-row td {
        background: #fff7dd;
        font-weight: 700;
    }

    .report-grand-total td {
        background: #eef8f2;
        font-weight: 800;
    }

    .section-toolbar {
        background: #f8fbf9;
        border: 1px solid #e4eee9;
        border-radius: 16px;
        padding: 16px;
    }

    .mini-stat-card {
        background: #fff;
        border: 1px solid #e5eee9;
        border-radius: 16px;
        height: 100%;
        padding: 16px;
    }

    .mini-stat-card.success strong {
        color: #0e8549;
    }

    .mini-stat-card.warning strong {
        color: #a36c00;
    }

    .mini-stat-card.danger strong {
        color: #c62828;
    }

    #teachingRecapMonthWrapper {
        display: none;
    }

    @media (max-width: 768px) {
        .teaching-tab-switch {
            flex-direction: column;
        }

        .teaching-tab-switch .tab-pill {
            justify-content: center;
        }

        .school-detail-panel {
            padding: 16px;
        }

        .daily-report-meta {
            font-size: 12px;
            gap: 8px;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('script'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const periodSelect = document.getElementById('teachingRecapPeriod');
        const weekWrapper = document.getElementById('teachingRecapWeekWrapper');
        const monthWrapper = document.getElementById('teachingRecapMonthWrapper');

        function toggleRecapPeriodInputs() {
            if (!periodSelect || !weekWrapper || !monthWrapper) {
                return;
            }

            const isMonth = periodSelect.value === 'month';
            weekWrapper.style.display = isMonth ? 'none' : '';
            monthWrapper.style.display = isMonth ? '' : 'none';
        }

        if (periodSelect) {
            toggleRecapPeriodInputs();
            periodSelect.addEventListener('change', toggleRecapPeriodInputs);
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/admin/teaching_progress.blade.php ENDPATH**/ ?>