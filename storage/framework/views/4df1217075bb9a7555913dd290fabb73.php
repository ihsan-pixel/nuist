<?php $__env->startSection('title'); ?> Kegiatan dan Presensi MGMP - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> MGMP <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> Kegiatan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Kegiatan dan Presensi MGMP <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php echo $__env->make('mgmp.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php
    $canCreateActivity = in_array($user->role, ['super_admin', 'admin', 'pengurus']) || !empty($mgmpGroup);
    $now = \Carbon\Carbon::now('Asia/Jakarta');
?>

<div class="mgmp-page">

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <strong>Data belum valid.</strong>
        <ul class="mb-0 mt-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <li><?php echo e($error); ?></li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="mgmp-hero-strip mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <div class="mgmp-kicker mb-2">Kegiatan MGMP</div>
                        <h4 class="card-title mb-1">
                            <i class="mdi mdi-calendar-check me-2"></i>
                            Kegiatan dan Presensi MGMP
                        </h4>
                        <p class="mb-0 text-white-50">Buat kegiatan MGMP dan pantau presensi anggota berbasis GPS dan selfie.</p>
                    </div>
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#tambahKegiatanModal" <?php if(!$canCreateActivity): echo 'disabled'; endif; ?>>
                        <i class="mdi mdi-plus me-1"></i>
                        Tambah Kegiatan
                    </button>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$canCreateActivity): ?>
                    <div class="alert alert-warning mt-3 mb-0">
                        Anda belum memiliki data MGMP. Buat data MGMP terlebih dahulu sebelum membuat kegiatan.
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card mgmp-stat-card h-100">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                <i class="mdi mdi-calendar-multiple fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1"><?php echo e($totalLaporan); ?></h5>
                        <small class="text-muted">Total Kegiatan</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card mgmp-stat-card h-100">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                <i class="mdi mdi-calendar-month fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1"><?php echo e($laporanBulanIni); ?></h5>
                        <small class="text-muted">Bulan Ini</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card mgmp-stat-card h-100">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                                <i class="mdi mdi-account-check fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1"><?php echo e($totalPeserta); ?></h5>
                        <small class="text-muted">Total Presensi</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card mgmp-stat-card h-100">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                                <i class="mdi mdi-clock-outline fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1"><?php echo e($rataRataDurasi); ?></h5>
                        <small class="text-muted">Jam Rata-rata</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mgmp-panel">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold text-dark py-3 ps-4">No</th>
                                <th class="border-0 fw-semibold text-dark py-3">Kegiatan</th>
                                <th class="border-0 fw-semibold text-dark py-3">Jadwal</th>
                                <th class="border-0 fw-semibold text-dark py-3">Lokasi Presensi</th>
                                <th class="border-0 fw-semibold text-dark py-3">Presensi</th>
                                <th class="border-0 fw-semibold text-dark py-3 pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $laporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <?php
                                    $start = $report->tanggal && $report->waktu_mulai
                                        ? \Carbon\Carbon::parse($report->tanggal->format('Y-m-d') . ' ' . $report->waktu_mulai, 'Asia/Jakarta')
                                        : null;
                                    $end = $report->tanggal && $report->waktu_selesai
                                        ? \Carbon\Carbon::parse($report->tanggal->format('Y-m-d') . ' ' . $report->waktu_selesai, 'Asia/Jakarta')
                                        : null;
                                    $isOngoing = $start && $end && $now->betweenIncluded($start, $end);
                                    $isCancelled = $report->status === 'cancelled';
                                ?>
                                <tr class="border-bottom border-light <?php echo e($isCancelled ? 'table-light text-muted' : ''); ?>">
                                    <td class="py-3 ps-4"><?php echo e($loop->iteration); ?></td>
                                    <td class="py-3">
                                        <h6 class="mb-1"><?php echo e($report->judul); ?></h6>
                                        <div class="text-muted small"><?php echo e($report->mgmpGroup->name ?? 'MGMP tidak tersedia'); ?></div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($report->deskripsi): ?>
                                            <div class="text-muted small"><?php echo e(\Illuminate\Support\Str::limit($report->deskripsi, 90)); ?></div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td class="py-3">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($start && $end): ?>
                                            <div class="fw-medium"><?php echo e($start->format('d M Y')); ?></div>
                                            <small class="text-muted"><?php echo e($start->format('H:i')); ?> - <?php echo e($end->format('H:i')); ?> WIB</small>
                                            <div class="mt-1">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isCancelled): ?>
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                <?php elseif($isOngoing): ?>
                                                    <span class="badge bg-success">Sedang Berlangsung</span>
                                                <?php elseif($now->lt($start)): ?>
                                                    <span class="badge bg-info">Akan Datang</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Selesai</span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">Jadwal belum lengkap</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td class="py-3">
                                        <div class="small">
                                            <div><?php echo e($report->lokasi ?: 'Tanpa nama lokasi'); ?></div>
                                            <div class="text-muted"><?php echo e($report->latitude); ?>, <?php echo e($report->longitude); ?></div>
                                            <div class="text-muted">Radius <?php echo e($report->radius_meters ?? 100); ?> meter</div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#pesertaModal<?php echo e($report->id); ?>">
                                            <?php echo e($report->attendances_count); ?> hadir
                                        </button>
                                    </td>
                                    <td class="py-3 pe-4">
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="<?php echo e(route('mgmp.kegiatan.presensi', $report)); ?>" target="_blank" class="btn btn-sm btn-success <?php echo e($isCancelled ? 'disabled' : ''); ?>">
                                                <i class="mdi mdi-cellphone-check me-1"></i> Form Presensi
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-secondary btn-copy-presensi" data-url="<?php echo e(route('mgmp.kegiatan.presensi', $report)); ?>">
                                                <i class="mdi mdi-content-copy me-1"></i> Salin Link
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editKegiatanModal<?php echo e($report->id); ?>" <?php if($isCancelled): echo 'disabled'; endif; ?>>
                                                <i class="mdi mdi-pencil me-1"></i> Edit
                                            </button>
                                            <form action="<?php echo e(route('mgmp.laporan.cancel', $report)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Batalkan kegiatan MGMP ini? Presensi untuk kegiatan ini akan ditutup.');">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" <?php if($isCancelled): echo 'disabled'; endif; ?>>
                                                    <i class="mdi mdi-cancel me-1"></i> Batalkan
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="mdi mdi-calendar-remove fs-1"></i>
                                            </div>
                                        </div>
                                        <h6 class="text-muted">Belum ada kegiatan MGMP</h6>
                                        <p class="text-muted small mb-0">Klik tombol Tambah Kegiatan untuk membuat jadwal presensi MGMP.</p>
                                    </td>
                                </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $laporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
    <div class="modal fade" id="pesertaModal<?php echo e($report->id); ?>" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Presensi: <?php echo e($report->judul); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Waktu</th>
                                    <th>Jarak</th>
                                    <th>Selfie</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $report->attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td><?php echo e($attendance->user->name ?? 'User tidak tersedia'); ?></td>
                                        <td><?php echo e($attendance->attended_at ? $attendance->attended_at->format('d M Y H:i') : '-'); ?></td>
                                        <td><?php echo e($attendance->distance_meters ?? '-'); ?> m</td>
                                        <td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($attendance->selfie_path): ?>
                                                <a href="<?php echo e(route('foto.mgmp_attendance', $attendance)); ?>" target="_blank">Lihat</a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada anggota yang presensi.</td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editKegiatanModal<?php echo e($report->id); ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kegiatan MGMP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('mgmp.laporan.update', $report)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="row g-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($user->role, ['super_admin', 'admin', 'pengurus'])): ?>
                                <div class="col-12">
                                    <label class="form-label">Grup MGMP</label>
                                    <select class="form-select" name="mgmp_group_id" required>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $mgmpGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <option value="<?php echo e($group->id); ?>" <?php if($report->mgmp_group_id == $group->id): echo 'selected'; endif; ?>><?php echo e($group->name); ?></option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </select>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="col-12">
                                <label class="form-label">Nama Kegiatan</label>
                                <input type="text" class="form-control" name="judul" value="<?php echo e($report->judul); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" value="<?php echo e($report->tanggal ? $report->tanggal->format('Y-m-d') : ''); ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Waktu Mulai</label>
                                <input type="time" class="form-control" name="waktu_mulai" value="<?php echo e($report->waktu_mulai ? \Carbon\Carbon::parse($report->waktu_mulai)->format('H:i') : ''); ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Waktu Selesai</label>
                                <input type="time" class="form-control" name="waktu_selesai" value="<?php echo e($report->waktu_selesai ? \Carbon\Carbon::parse($report->waktu_selesai)->format('H:i') : ''); ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Nama Lokasi</label>
                                <input type="text" class="form-control" name="lokasi" value="<?php echo e($report->lokasi); ?>" placeholder="Contoh: Aula LP Ma'arif NU DIY">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Latitude</label>
                                <input type="number" step="0.00000001" class="form-control" name="latitude" value="<?php echo e($report->latitude); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Longitude</label>
                                <input type="number" step="0.00000001" class="form-control" name="longitude" value="<?php echo e($report->longitude); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Radius Presensi (meter)</label>
                                <input type="number" min="10" max="1000" class="form-control" name="radius_meters" value="<?php echo e($report->radius_meters ?? 100); ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Deskripsi (opsional)</label>
                                <textarea class="form-control" name="deskripsi" rows="3"><?php echo e($report->deskripsi); ?></textarea>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    Jika perlu mengubah titik lokasi dengan map/link, gunakan modal tambah sebagai acuan lalu salin koordinatnya ke field latitude dan longitude.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

<div class="modal fade" id="tambahKegiatanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kegiatan MGMP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('mgmp.laporan.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($user->role, ['super_admin', 'admin', 'pengurus'])): ?>
                            <div class="col-12">
                                <label class="form-label">Grup MGMP</label>
                                <select class="form-select" name="mgmp_group_id" required>
                                    <option value="">Pilih Grup MGMP</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $mgmpGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($group->id); ?>" <?php if(old('mgmp_group_id') == $group->id): echo 'selected'; endif; ?>><?php echo e($group->name); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="col-12">
                            <label class="form-label">Nama Kegiatan</label>
                            <input type="text" class="form-control" name="judul" value="<?php echo e(old('judul')); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" value="<?php echo e(old('tanggal')); ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Waktu Mulai</label>
                            <input type="time" class="form-control" name="waktu_mulai" value="<?php echo e(old('waktu_mulai')); ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Waktu Selesai</label>
                            <input type="time" class="form-control" name="waktu_selesai" value="<?php echo e(old('waktu_selesai')); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nama Lokasi</label>
                            <input type="text" class="form-control" name="lokasi" value="<?php echo e(old('lokasi')); ?>" placeholder="Contoh: Aula LP Ma'arif NU DIY">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Metode Penentuan Lokasi</label>
                            <div class="location-method-grid">
                                <label class="location-method-card">
                                    <input type="radio" name="location_method" value="link" class="location-method-input" checked>
                                    <span class="location-method-icon"><i class="mdi mdi-link-variant"></i></span>
                                    <span class="location-method-title">Gunakan Link</span>
                                    <small>Tempel link Google Maps / OpenStreetMap</small>
                                </label>
                                <label class="location-method-card">
                                    <input type="radio" name="location_method" value="current" class="location-method-input">
                                    <span class="location-method-icon"><i class="mdi mdi-crosshairs-gps"></i></span>
                                    <span class="location-method-title">Lokasi Saat Ini</span>
                                    <small>Ambil titik dari GPS perangkat</small>
                                </label>
                                <label class="location-method-card">
                                    <input type="radio" name="location_method" value="map" class="location-method-input">
                                    <span class="location-method-icon"><i class="mdi mdi-map-marker-radius"></i></span>
                                    <span class="location-method-title">Pilih di Map</span>
                                    <small>Klik peta atau geser marker</small>
                                </label>
                            </div>
                            <small class="text-muted">Pilih salah satu metode. Semua metode akan mengisi latitude dan longitude kegiatan.</small>
                        </div>

                        <div class="col-12 location-method-panel" data-location-panel="link">
                            <label class="form-label">Link Lokasi</label>
                            <div class="input-group">
                                <input type="url" class="form-control" id="locationLinkInput" placeholder="Tempel link Google Maps / OpenStreetMap di sini">
                                <button type="button" class="btn btn-outline-primary" id="btnApplyLocationLink">
                                    Gunakan Link
                                </button>
                            </div>
                            <small class="text-muted">Contoh: link Google Maps yang mengandung koordinat `@lat,lng` atau `q=lat,lng`.</small>
                        </div>

                        <div class="col-12 location-method-panel d-none" data-location-panel="current">
                            <div class="location-method-helper">
                                <div>
                                    <h6 class="mb-1">Gunakan Lokasi Saat Ini</h6>
                                    <small class="text-muted">Pastikan Anda sedang berada di lokasi kegiatan dan browser mengizinkan akses lokasi.</small>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="btnUseCurrentLocation">
                                    <i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Lokasi Saat Ini
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Latitude</label>
                            <input type="number" step="0.00000001" class="form-control" id="latitudeInput" name="latitude" value="<?php echo e(old('latitude')); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Longitude</label>
                            <input type="number" step="0.00000001" class="form-control" id="longitudeInput" name="longitude" value="<?php echo e(old('longitude')); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Radius Presensi (meter)</label>
                            <input type="number" min="10" max="1000" class="form-control" name="radius_meters" value="<?php echo e(old('radius_meters', 100)); ?>" required>
                        </div>
                        <div class="col-12 location-method-panel d-none" data-location-panel="map">
                            <div id="mgmpLocationMap" style="height: 320px; border-radius: 14px; overflow: hidden; border: 1px solid #dee2e6;"></div>
                            <small class="text-muted d-block mt-2">
                                Klik peta untuk menentukan titik lokasi atau geser marker yang muncul.
                            </small>
                        </div>
                        <div class="col-12">
                            <div id="locationPickerStatus" class="small text-muted">
                                Belum ada titik lokasi dipilih.
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi (opsional)</label>
                            <textarea class="form-control" name="deskripsi" rows="3"><?php echo e(old('deskripsi')); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" <?php if(!$canCreateActivity): echo 'disabled'; endif; ?>>Simpan Kegiatan</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const locationButton = document.getElementById('btnUseCurrentLocation');
    const applyLocationLinkButton = document.getElementById('btnApplyLocationLink');
    const locationLinkInput = document.getElementById('locationLinkInput');
    const latitudeInput = document.getElementById('latitudeInput');
    const longitudeInput = document.getElementById('longitudeInput');
    const locationStatus = document.getElementById('locationPickerStatus');
    const modalElement = document.getElementById('tambahKegiatanModal');
    const methodInputs = document.querySelectorAll('.location-method-input');
    const methodPanels = document.querySelectorAll('.location-method-panel');
    const methodCards = document.querySelectorAll('.location-method-card');
    const defaultLat = -7.80119450;
    const defaultLng = 110.36491730;
    let locationMap = null;
    let locationMarker = null;

    function setStatus(message, type = 'muted') {
        locationStatus.className = 'small mt-1 text-' + type;
        locationStatus.textContent = message;
    }

    function selectedLocationMethod() {
        const selected = document.querySelector('.location-method-input:checked');
        return selected ? selected.value : 'link';
    }

    function applyLocationMethod(method) {
        methodPanels.forEach(function (panel) {
            panel.classList.toggle('d-none', panel.dataset.locationPanel !== method);
        });

        methodCards.forEach(function (card) {
            const input = card.querySelector('.location-method-input');
            card.classList.toggle('active', input && input.checked);
        });

        if (method === 'map') {
            initializeLocationMap();
            setTimeout(function () {
                if (locationMap) {
                    locationMap.invalidateSize();
                }
            }, 120);
        }

        const methodMessages = {
            link: 'Tempel link lokasi lalu klik Gunakan Link.',
            current: 'Klik Ambil Lokasi Saat Ini untuk memakai GPS perangkat.',
            map: 'Klik peta untuk menentukan titik lokasi kegiatan.'
        };

        if (!latitudeInput.value || !longitudeInput.value) {
            setStatus(methodMessages[method] || 'Pilih metode penentuan lokasi.');
        }
    }

    function updateCoordinateInputs(lat, lng, message = null) {
        latitudeInput.value = Number(lat).toFixed(8);
        longitudeInput.value = Number(lng).toFixed(8);

        if (locationMarker) {
            locationMarker.setLatLng([lat, lng]);
        } else if (locationMap) {
            locationMarker = L.marker([lat, lng], { draggable: true }).addTo(locationMap);
            locationMarker.on('dragend', function (event) {
                const position = event.target.getLatLng();
                updateCoordinateInputs(position.lat, position.lng, 'Marker digeser. Koordinat diperbarui.');
                locationMap.panTo(position);
            });
        }

        if (locationMap) {
            locationMap.setView([lat, lng], Math.max(locationMap.getZoom(), 16));
        }

        setStatus(message || ('Titik dipilih: ' + Number(lat).toFixed(8) + ', ' + Number(lng).toFixed(8)), 'success');
    }

    function extractCoordinatesFromLink(url) {
        const patterns = [
            /@(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/,
            /[?&]q=(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/,
            /[?&]ll=(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/,
            /!3d(-?\d+(?:\.\d+)?)!4d(-?\d+(?:\.\d+)?)/,
            /#map=\d+\/(-?\d+(?:\.\d+)?)\/(-?\d+(?:\.\d+)?)/,
            /(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)/
        ];

        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match) {
                return {
                    lat: parseFloat(match[1]),
                    lng: parseFloat(match[2])
                };
            }
        }

        return null;
    }

    function initializeLocationMap() {
        if (locationMap) {
            locationMap.invalidateSize();
            return;
        }

        locationMap = L.map('mgmpLocationMap').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(locationMap);

        locationMap.on('click', function (event) {
            updateCoordinateInputs(event.latlng.lat, event.latlng.lng, 'Titik lokasi dipilih dari peta.');
        });

        const initialLat = parseFloat(latitudeInput.value);
        const initialLng = parseFloat(longitudeInput.value);
        if (!Number.isNaN(initialLat) && !Number.isNaN(initialLng)) {
            updateCoordinateInputs(initialLat, initialLng, 'Koordinat awal dimuat ke peta.');
        } else {
            setStatus('Belum ada titik lokasi dipilih.');
        }
    }

    if (modalElement) {
        modalElement.addEventListener('shown.bs.modal', function () {
            applyLocationMethod(selectedLocationMethod());
            if (selectedLocationMethod() === 'map' && locationMap) {
                setTimeout(function () {
                    locationMap.invalidateSize();
                }, 100);
            }
        });
    }

    methodInputs.forEach(function (input) {
        input.addEventListener('change', function () {
            applyLocationMethod(input.value);
        });
    });

    applyLocationMethod(selectedLocationMethod());

    if (locationButton) {
        locationButton.addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Browser tidak mendukung GPS.');
                return;
            }

            locationButton.disabled = true;
            locationButton.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Mengambil lokasi...';

            navigator.geolocation.getCurrentPosition(function (position) {
                updateCoordinateInputs(
                    position.coords.latitude,
                    position.coords.longitude,
                    'Lokasi saat ini berhasil digunakan.'
                );
                locationButton.disabled = false;
                locationButton.innerHTML = '<i class="mdi mdi-crosshairs-gps me-1"></i> Gunakan Lokasi Saat Ini';
            }, function () {
                alert('Gagal mengambil lokasi. Pastikan izin lokasi aktif.');
                locationButton.disabled = false;
                locationButton.innerHTML = '<i class="mdi mdi-crosshairs-gps me-1"></i> Gunakan Lokasi Saat Ini';
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        });
    }

    if (applyLocationLinkButton) {
        applyLocationLinkButton.addEventListener('click', function () {
            const rawLink = (locationLinkInput.value || '').trim();
            if (!rawLink) {
                setStatus('Tempel link lokasi terlebih dahulu.', 'danger');
                return;
            }

            const coordinates = extractCoordinatesFromLink(rawLink);
            if (!coordinates || Number.isNaN(coordinates.lat) || Number.isNaN(coordinates.lng)) {
                setStatus('Koordinat tidak ditemukan dari link lokasi tersebut.', 'danger');
                return;
            }

            updateCoordinateInputs(coordinates.lat, coordinates.lng, 'Koordinat berhasil diambil dari link lokasi.');
        });
    }

    [latitudeInput, longitudeInput].forEach(function (input) {
        input.addEventListener('change', function () {
            const lat = parseFloat(latitudeInput.value);
            const lng = parseFloat(longitudeInput.value);

            if (!Number.isNaN(lat) && !Number.isNaN(lng)) {
                updateCoordinateInputs(lat, lng, 'Koordinat manual diterapkan ke peta.');
            }
        });
    });

    document.querySelectorAll('.btn-copy-presensi').forEach(function (button) {
        button.addEventListener('click', async function () {
            const url = button.dataset.url;
            try {
                await navigator.clipboard.writeText(url);
                button.innerHTML = '<i class="mdi mdi-check me-1"></i> Tersalin';
                setTimeout(function () {
                    button.innerHTML = '<i class="mdi mdi-content-copy me-1"></i> Salin Link';
                }, 1500);
            } catch (e) {
                prompt('Salin link presensi berikut:', url);
            }
        });
    });
});
</script>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.location-method-grid {
    display: grid;
    gap: 10px;
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

.location-method-card {
    border: 1px solid #dee2e6;
    border-radius: 14px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 12px;
    transition: all 0.2s ease;
}

.location-method-card.active {
    background: rgba(13, 110, 253, 0.08);
    border-color: #0d6efd;
    box-shadow: 0 8px 22px rgba(13, 110, 253, 0.12);
}

.location-method-card input {
    display: none;
}

.location-method-icon {
    align-items: center;
    background: #f1f5f9;
    border-radius: 10px;
    display: inline-flex;
    height: 34px;
    justify-content: center;
    width: 34px;
}

.location-method-icon i {
    color: #0d6efd;
    font-size: 20px;
}

.location-method-title {
    color: #1f2937;
    font-weight: 700;
}

.location-method-card small {
    color: #6c757d;
    line-height: 1.35;
}

.location-method-helper {
    align-items: center;
    background: #f8fbff;
    border: 1px solid #dbeafe;
    border-radius: 14px;
    display: flex;
    gap: 12px;
    justify-content: space-between;
    padding: 14px;
}

#mgmpLocationMap .leaflet-control-attribution {
    font-size: 10px;
}

@media (max-width: 768px) {
    .location-method-grid {
        grid-template-columns: 1fr;
    }

    .location-method-helper {
        align-items: stretch;
        flex-direction: column;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mgmp/laporan.blade.php ENDPATH**/ ?>