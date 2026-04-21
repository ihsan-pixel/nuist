<?php $__env->startSection('title', 'Input Data Pemateri - Instrument Talenta'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Input Data Pemateri</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('instumen-talenta.index')); ?>">Instrument Talenta</a></li>
                    <li class="breadcrumb-item active">Input Pemateri</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Input Data Pemateri</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('instumen-talenta.store-pemateri')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="kode_pemateri" class="form-label">Kode Pemateri <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="kode_pemateri" name="kode_pemateri" value="<?php echo e($nextKodePemateri ?? 'T-P-01.001'); ?>" readonly required>
                                        <small class="form-text text-muted">Kode pemateri akan di-generate otomatis</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama" name="nama" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="pilih_materi" class="form-label">Pilih Materi <span class="text-danger">*</span></label>
                                        <select class="form-select" id="pilih_materi" name="pilih_materi[]" multiple required style="height: 120px;">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $materis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $materi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <option value="<?php echo e($materi->id); ?>"><?php echo e($materi->judul_materi); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                        <small class="form-text text-muted">Tekan Ctrl (atau Cmd di Mac) untuk memilih lebih dari satu materi</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="<?php echo e(route('instumen-talenta.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="reset" class="btn btn-outline-danger">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan Data
                                </button>
                            </div>
                        </div>

                        <!-- Info Panel -->
                        <div class="col-lg-4">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Informasi</h6>
                                    <p class="text-muted mb-0 small">
                                        Masukkan detail pemateri. Kode pemateri akan di-generate otomatis. Pastikan nama dan materi dipilih dengan benar.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Data Pemateri Terdaftar</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pemateri</th>
                                <th>Nama Pemateri</th>
                                <th>Materi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = \App\Models\TalentaPemateri::with('materis')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pemateri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($pemateri->kode_pemateri); ?></td>
                                    <td><?php echo e($pemateri->nama); ?></td>
                                    <td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pemateri->materis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $materi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <span class="badge bg-primary">
                                                <?php echo e($materi->judul_materi); ?>

                                            </span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pemateri->user_id): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i> Akun Dibuat
                                            </span>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-success btn-sm" onclick="openCreateUserModal('<?php echo e($pemateri->nama); ?>')">
                                                <i class="fas fa-user-plus me-1"></i> Buat Akun
                                            </button>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data pemateri</td>
                                </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal for Creating User -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Buat Akun Pemateri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createUserForm" action="<?php echo e(route('instumen-talenta.create-user-pemateri')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_nama" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_nama" name="nama" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="modal_email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="modal_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal_password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="modal_password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Buat Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/instumen-talenta.css')); ?>">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // SweetAlert untuk pesan sukses
    <?php if(session('success')): ?>
        Swal.fire({
            title: 'Berhasil!',
            text: '<?php echo e(session('success')); ?>',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>

    // SweetAlert untuk pesan error
    <?php if(session('error')): ?>
        Swal.fire({
            title: 'Error!',
            text: '<?php echo e(session('error')); ?>',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
});

// Function to open modal and pre-fill name
function openCreateUserModal(nama) {
    document.getElementById('modal_nama').value = nama;
    document.getElementById('modal_email').value = '';
    document.getElementById('modal_password').value = '';
    var modal = new bootstrap.Modal(document.getElementById('createUserModal'));
    modal.show();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/instumen-talenta/input-pemateri.blade.php ENDPATH**/ ?>