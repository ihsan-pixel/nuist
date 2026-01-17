<?php $__env->startSection('title'); ?>Perhitungan Iuran UPPM <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> UPPM <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Perhitungan Iuran <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <h4 class="card-title text-white mb-4">
                    <i class="bx bx-calculator me-2"></i>
                    Perhitungan Iuran UPPM
                </h4>
                <p class="text-white-50 mb-0">
                    Hitung dan pantau iuran UPPM berdasarkan data sekolah untuk tahun <?php echo e(request('tahun', date('Y'))); ?>

                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bx bx-calculator"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Perhitungan</p>
                        <h5 class="mb-0"><?php echo e($perhitungan->count()); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="bx bx-group"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Siswa</p>
                        <h5 class="mb-0"><?php echo e($perhitungan->sum('data.jumlah_siswa')); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Guru</p>
                        <h5 class="mb-0"><?php echo e($perhitungan->sum('data.jumlah_guru_tetap') + $perhitungan->sum('data.jumlah_guru_tidak_tetap')); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="bx bx-briefcase"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Karyawan</p>
                        <h5 class="mb-0"><?php echo e($perhitungan->sum('data.jumlah_karyawan_tetap') + $perhitungan->sum('data.jumlah_karyawan_tidak_tetap')); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-danger">
                        <i class="bx bx-money"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Bulanan</p>
                        <h5 class="mb-0">Rp <?php echo e(number_format($perhitungan->sum('nominal_bulanan'))); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bx bx-calendar"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Tahunan</p>
                        <h5 class="mb-0">Rp <?php echo e(number_format($perhitungan->sum('total_tahunan'))); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('uppm.perhitungan-iuran')); ?>">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="tahun" class="form-label">Tahun Anggaran</label>
                            <select class="form-select" id="tahun" name="tahun">
                                <?php for($i = date('Y') - 2; $i <= date('Y') + 1; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e(request('tahun', date('Y')) == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-search me-1"></i> Filter
                                </button>
                                <a href="<?php echo e(route('uppm.perhitungan-iuran')); ?>" class="btn btn-secondary">
                                    <i class="bx bx-refresh me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if($perhitungan->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table-modern table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Sekolah</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Jumlah Guru Tetap</th>
                                    <th>Jumlah Guru Tidak Tetap</th>
                                    <th>Jumlah Guru PNS</th>
                                    <th>Jumlah Guru PPPK</th>
                                    <th>Jumlah Karyawan Tetap</th>
                                    <th>Jumlah Karyawan Tidak Tetap</th>
                                    <th>Nominal Bulanan</th>
                                    <th>Total Tahunan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $perhitungan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="card-icon calculator me-3">
                                                <i class="bx bx-calculator"></i>
                                            </div>
                                            <div>
                                                <strong><?php echo e($item['madrasah']->name); ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo e(number_format($item['data']->jumlah_siswa)); ?></td>
                                    <td><?php echo e(number_format($item['data']->jumlah_guru_tetap)); ?></td>
                                    <td><?php echo e(number_format($item['data']->jumlah_guru_tidak_tetap)); ?></td>
                                    <td><?php echo e(number_format($item['data']->jumlah_guru_pns)); ?></td>
                                    <td><?php echo e(number_format($item['data']->jumlah_guru_pppk)); ?></td>
                                    <td><?php echo e(number_format($item['data']->jumlah_karyawan_tetap)); ?></td>
                                    <td><?php echo e(number_format($item['data']->jumlah_karyawan_tidak_tetap)); ?></td>
                                    <td>
                                        <strong class="text-info">Rp <?php echo e(number_format($item['nominal_bulanan'])); ?></strong>
                                    </td>
                                    <td>
                                        <strong class="text-success">Rp <?php echo e(number_format($item['total_tahunan'])); ?></strong>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bx bx-calculator"></i>
                        </div>
                        <h5>Tidak Ada Data Perhitungan</h5>
                        <p class="text-muted">Belum ada data perhitungan iuran untuk tahun <?php echo e(request('tahun', date('Y'))); ?>.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
<?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?php echo e(session('success')); ?>',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
<?php endif; ?>

<?php if(session('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '<?php echo e(session('error')); ?>',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/uppm/perhitungan-iuran.blade.php ENDPATH**/ ?>