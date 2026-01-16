<?php $__env->startSection('title'); ?>Pengaturan UPPM <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Pengaturan UPPM</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSettingModal">
                    <i class="bx bx-plus"></i> Tambah Pengaturan
                </button>
            </div>
            <div class="card-body">
                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tahun Anggaran</th>
                                <th>Nominal Siswa</th>
                                <th>Nominal PNS Sertifikasi</th>
                                <th>Nominal PNS Non Sertifikasi</th>
                                <th>Nominal GTY Sertifikasi</th>
                                <th>Nominal GTY Sertifikasi Inpassing</th>
                                <th>Nominal GTY Non Sertifikasi</th>
                                <th>Nominal GTT</th>
                                <th>Nominal PTY</th>
                                <th>Nominal PTT</th>
                                <th>Jatuh Tempo</th>
                                <th>Skema Pembayaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($setting->tahun_anggaran); ?></td>
                                <td>Rp <?php echo e(number_format($setting->nominal_siswa)); ?></td>
                                <td>Rp <?php echo e(number_format($setting->nominal_pns_sertifikasi)); ?></td>
                                <td>Rp <?php echo e(number_format($setting->nominal_pns_non_sertifikasi)); ?></td>
                                <td>Rp <?php echo e(number_format($setting->nominal_gty_sertifikasi)); ?></td>
                                <td>Rp <?php echo e(number_format($setting->nominal_gty_sertifikasi_inpassing)); ?></td>
                                <td>Rp <?php echo e(number_format($setting->nominal_gty_non_sertifikasi)); ?></td>
                                <td>Rp <?php echo e(number_format($setting->nominal_gtt)); ?></td>
                                <td>Rp <?php echo e(number_format($setting->nominal_pty)); ?></td>
                                <td>Rp <?php echo e(number_format($setting->nominal_ptt)); ?></td>
                                <td><?php echo e($setting->jatuh_tempo); ?></td>
                                <td><?php echo e(ucfirst($setting->skema_pembayaran)); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($setting->aktif ? 'success' : 'secondary'); ?>">
                                        <?php echo e($setting->aktif ? 'Aktif' : 'Tidak Aktif'); ?>

                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSettingModal<?php echo e($setting->id); ?>">
                                        <i class="bx bx-edit"></i> Edit
                                    </button>
                                    <form method="POST" action="<?php echo e(route('uppm.pengaturan.destroy', $setting->id)); ?>" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaturan ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bx bx-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editSettingModal<?php echo e($setting->id); ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Pengaturan UPPM <?php echo e($setting->tahun_anggaran); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="<?php echo e(route('uppm.pengaturan.update', $setting->id)); ?>" enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <div class="modal-body">
                                                <?php echo $__env->make('uppm.form', ['setting' => $setting], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="16" class="text-center">Belum ada pengaturan UPPM</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addSettingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengaturan UPPM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('uppm.pengaturan.store')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <?php echo $__env->make('uppm.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/uppm/pengaturan.blade.php ENDPATH**/ ?>