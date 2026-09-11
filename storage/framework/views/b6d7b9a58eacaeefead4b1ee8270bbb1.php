<?php $__env->startSection('title', 'Profile Madrasah/Sekolah'); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />
<style>
    .profile-summary-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 1rem 2rem rgba(15, 23, 42, 0.08);
    }

    .profile-summary-card .progress {
        height: 0.55rem;
        background: #e9eef6;
    }

    .profile-summary-table th {
        white-space: nowrap;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .profile-summary-table td {
        vertical-align: middle;
    }

    .profile-metric {
        min-width: 160px;
    }

    .profile-rank-pill {
        width: 2rem;
        height: 2rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #1d4ed8;
        color: #fff;
        font-weight: 700;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Profile Madrasah/Sekolah <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-school me-2"></i>Profile Madrasah/Sekolah
                </h4>
            </div>
            <div class="card-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i><?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <form method="GET" action="<?php echo e(route('madrasah.profile')); ?>" class="mb-4">
                    <div class="row g-3">
                        <div class="col-xl-4 col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama madrasah..." value="<?php echo e($search ?? ''); ?>">
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <select name="yayasan_id" class="form-select">
                                <option value="">Pilih Yayasan</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $yayasans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yayasan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($yayasan->id); ?>" <?php echo e((int) ($yayasan_id ?? 0) === (int) $yayasan->id ? 'selected' : ''); ?>>
                                    <?php echo e($yayasan->name); ?>

                                </option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <select name="kabupaten" class="form-select">
                                <option value="">Pilih Kabupaten</option>
                                <option value="Kabupaten Bantul" <?php echo e(($kabupaten ?? '') == 'Kabupaten Bantul' ? 'selected' : ''); ?>>Kabupaten Bantul</option>
                                <option value="Kabupaten Gunungkidul" <?php echo e(($kabupaten ?? '') == 'Kabupaten Gunungkidul' ? 'selected' : ''); ?>>Kabupaten Gunungkidul</option>
                                <option value="Kabupaten Kulon Progo" <?php echo e(($kabupaten ?? '') == 'Kabupaten Kulon Progo' ? 'selected' : ''); ?>>Kabupaten Kulon Progo</option>
                                <option value="Kabupaten Sleman" <?php echo e(($kabupaten ?? '') == 'Kabupaten Sleman' ? 'selected' : ''); ?>>Kabupaten Sleman</option>
                                <option value="Kota Yogyakarta" <?php echo e(($kabupaten ?? '') == 'Kota Yogyakarta' ? 'selected' : ''); ?>>Kota Yogyakarta</option>
                            </select>
                        </div>
                        <div class="col-xl-2 col-md-6">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-search-alt-2 me-1"></i>Cari
                                </button>
                                <a href="<?php echo e(route('madrasah.profile.export', request()->query())); ?>" class="btn btn-success">
                                    <i class="bx bx-export me-1"></i>Export Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasahs->isEmpty()): ?>
                <div class="text-center p-4">
                    <div class="alert alert-info d-inline-block" role="alert">
                        <i class="bx bx-info-circle bx-lg me-2"></i>
                        <strong>Belum ada data madrasah</strong><br>
                        <small>Silakan tambahkan data madrasah terlebih dahulu melalui menu Master Data.</small>
                    </div>
                </div>
                <?php else: ?>
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card profile-summary-card h-100">
                            <div class="card-body">
                                <p class="text-muted text-uppercase fw-semibold small mb-2">Total Sekolah</p>
                                <h3 class="mb-1"><?php echo e(number_format($summaryStats['total_schools'])); ?></h3>
                                <small class="text-muted">Sekolah sesuai filter aktif</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card profile-summary-card h-100">
                            <div class="card-body">
                                <p class="text-muted text-uppercase fw-semibold small mb-2">Rata-rata Kelengkapan</p>
                                <h3 class="mb-1"><?php echo e($summaryStats['average_completion_percentage']); ?>%</h3>
                                <small class="text-muted">Rerata dari presensi, jadwal, jurnal, guru+pegawai, SK, dan siswa</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card profile-summary-card h-100">
                            <div class="card-body">
                                <p class="text-muted text-uppercase fw-semibold small mb-2">Skor 100%</p>
                                <h3 class="mb-1"><?php echo e(number_format($summaryStats['fully_complete_schools'])); ?></h3>
                                <small class="text-muted">Sudah 100% pada seluruh 6 indikator disiplin digitalisasi</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card profile-summary-card h-100">
                            <div class="card-body">
                                <p class="text-muted text-uppercase fw-semibold small mb-2">Periode Aktif</p>
                                <h3 class="mb-1"><?php echo e(number_format($summaryStats['schools_with_active_period'])); ?></h3>
                                <small class="text-muted">Sekolah yang saat ini memiliki periode jadwal mengajar aktif</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-0">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                            <div>
                                <h5 class="mb-1">Monitoring Kelengkapan Data Sekolah</h5>
                                <p class="text-muted mb-0">Tabel ini menampilkan status kelengkapan data guru dan pegawai, kedisiplinan presensi, periode dan jadwal mengajar, jurnal mengajar, pengajuan SK yayasan, serta data siswa untuk setiap sekolah.</p>
                            </div>
                            <span class="badge bg-info-subtle text-info">Persentase total dan rank dihitung dari 6 indikator dengan bobot sama</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle profile-summary-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>SCOD</th>
                                        <th>Nama Sekolah/Madrasah</th>
                                        <th>Guru + Pegawai</th>
                                        <th>Kelengkapan Data Users</th>
                                        <th>Presensi Kehadiran</th>
                                        <th>Disiplin Kehadiran</th>
                                        <th>Periode Aktif</th>
                                        <th>Guru Sudah Jadwal</th>
                                        <th>Guru Belum Jadwal</th>
                                        <th>Cakupan Jadwal Guru</th>
                                        <th>Jurnal Mengajar</th>
                                        <th>Disiplin Jurnal</th>
                                        <th>Pengajuan SK Yayasan</th>
                                        <th>Kelengkapan SK</th>
                                        <th>Data Siswa</th>
                                        <th>Kelengkapan Siswa</th>
                                        <th>Persentase</th>
                                        <th>Rank</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schoolSummaryRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td>
                                            <?php echo e($loop->iteration); ?>

                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($row['scod']); ?></div>
                                            <small class="text-muted"><?php echo e($row['kabupaten']); ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($row['school_name']); ?></div>
                                            <small class="text-muted"><?php echo e($row['yayasan_name']); ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($row['total_teacher_employees']); ?></div>
                                            <small class="text-muted">Guru <?php echo e($row['total_teachers']); ?> • Pegawai <?php echo e($row['total_employees']); ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e(number_format($row['user_completion_percentage'], 1)); ?>%</div>
                                            <small class="text-muted">Kelengkapan rata-rata data users</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e(number_format($row['actual_attendance'])); ?></div>
                                            <small class="text-muted"><?php echo e(number_format($row['expected_attendance'])); ?> target • <?php echo e($row['attendance_month_label']); ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e(number_format($row['attendance_discipline_percentage'], 1)); ?>%</div>
                                            <small class="text-muted">Kedisiplinan presensi kehadiran</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($row['has_active_period'] ? 'Ada' : 'Belum Ada'); ?></div>
                                            <small class="text-muted"><?php echo e($row['has_active_period'] ? $row['active_period_label'] : 'Terakhir: ' . $row['latest_period_label']); ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($row['total_teachers_with_schedule']); ?></div>
                                            <small class="text-muted">dari <?php echo e($row['eligible_teacher_total']); ?> guru yang wajib dijadwalkan</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($row['total_teachers_without_schedule']); ?></div>
                                            <small class="text-muted">Guru belum punya jadwal mengajar</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e(number_format($row['schedule_coverage_percentage'], 1)); ?>%</div>
                                            <small class="text-muted"><?php echo e($row['total_teaching_schedules']); ?> data jadwal pada periode aktif</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($row['total_teaching_attendances']); ?></div>
                                            <small class="text-muted"><?php echo e($row['journal_expected_meetings']); ?> target jurnal</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e(number_format($row['journal_discipline_percentage'], 1)); ?>%</div>
                                            <small class="text-muted">Kedisiplinan presensi jurnal mengajar</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($row['total_sk_submissions']); ?></div>
                                            <small class="text-muted">Pengajuan SK yayasan tercatat</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e(number_format($row['sk_completeness_percentage'], 1)); ?>%</div>
                                            <small class="text-muted">Valid <?php echo e($row['sk_latest_batch_valid_rows']); ?>/<?php echo e($row['sk_latest_batch_total_rows']); ?> • <?php echo e($row['sk_latest_batch_status']); ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($row['total_students']); ?></div>
                                            <small class="text-muted">Data siswa yang sudah dikirim</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e(number_format($row['student_completion_percentage'], 1)); ?>%</div>
                                            <small class="text-muted">Rata-rata kelengkapan data siswa</small>
                                        </td>
                                        <td class="profile-metric">
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span class="fw-semibold"><?php echo e(number_format($row['overall_completion_percentage'], 1)); ?>%</span>
                                                <span class="text-muted"><?php echo e($row['filled_indicator_count']); ?>/6</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo e($row['overall_completion_percentage']); ?>%"></div>
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                Users <?php echo e(number_format($row['user_completion_percentage'], 1)); ?>% • Hadir <?php echo e(number_format($row['attendance_discipline_percentage'], 1)); ?>% • Jadwal <?php echo e(number_format($row['schedule_coverage_percentage'], 1)); ?>%
                                            </small>
                                            <small class="text-muted d-block">
                                                Jurnal <?php echo e(number_format($row['journal_discipline_percentage'], 1)); ?>% • SK <?php echo e(number_format($row['sk_completeness_percentage'], 1)); ?>% • Siswa <?php echo e(number_format($row['student_completion_percentage'], 1)); ?>%
                                            </small>
                                        </td>
                                        <td>
                                            <span class="profile-rank-pill"><?php echo e($row['rank']); ?></span>
                                        </td>
                                    </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
<script>
<?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: '<?php echo e(session('success')); ?>',
        timer: 3000,
        showConfirmButton: false
    });
<?php endif; ?>

<?php if(session('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?php echo e(session('error')); ?>',
        timer: 3000,
        showConfirmButton: false
    });
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/masterdata/madrasah/profile.blade.php ENDPATH**/ ?>