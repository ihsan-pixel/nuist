<?php $__env->startSection('title'); ?>
    Upload Surat Izin
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Dashboard
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Upload Surat Izin
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="bx bx-upload me-2"></i>Upload Surat Izin
                    </h4>
                </div>
                <div class="card-body">
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('izin.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <!-- Mobile-optimized date input -->
                        <div class="mb-4">
                            <label for="tanggal" class="form-label fw-bold">Tanggal Izin</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control form-control-lg" required>
                            <small class="text-muted">Pilih tanggal ketika Anda akan izin</small>
                        </div>

                        <!-- Mobile-optimized textarea -->
                        <div class="mb-4">
                            <label for="keterangan" class="form-label fw-bold">Keterangan Izin</label>
                            <textarea name="keterangan" id="keterangan" class="form-control form-control-lg" rows="4" placeholder="Jelaskan alasan izin Anda..." required></textarea>
                            <small class="text-muted">Berikan penjelasan yang jelas tentang alasan izin</small>
                        </div>

                        <!-- Mobile-optimized file upload -->
                        <div class="mb-4">
                            <label for="surat_izin" class="form-label fw-bold">Upload Surat Izin</label>
                            <div class="input-group">
                                <input type="file" name="surat_izin" id="surat_izin" class="form-control form-control-lg" accept=".pdf,.jpg,.jpeg,.png" required>
                                <label class="input-group-text" for="surat_izin">
                                    <i class="bx bx-file"></i>
                                </label>
                            </div>
                            <small class="text-muted">Format yang didukung: PDF, JPG, PNG. Maksimal 5MB</small>
                        </div>

                        <!-- Mobile-optimized submit button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg py-3">
                                <i class="bx bx-upload me-2"></i>Upload Surat Izin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/izin/create.blade.php ENDPATH**/ ?>