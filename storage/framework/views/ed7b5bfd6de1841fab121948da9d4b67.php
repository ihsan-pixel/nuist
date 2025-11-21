<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="mdi mdi-cog-outline me-2 text-primary"></i>
                Pengaturan PPDB
            </h2>
            <p class="text-muted mb-0">Konfigurasi pengaturan PPDB untuk <?php echo e($madrasah->name); ?></p>
        </div>
        <a href="<?php echo e(route('ppdb.lp.dashboard')); ?>" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left me-1"></i>Kembali ke Dashboard
        </a>
    </div>

    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('ppdb.lp.update-ppdb-settings', $madrasah->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Status PPDB -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-toggle-switch me-2"></i>Status PPDB
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="ppdb_status" class="form-label">Status PPDB</label>
                    <div class="d-flex align-items-center gap-3">
                        <select class="form-select <?php $__errorArgs = ['ppdb_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="ppdb_status" name="ppdb_status" style="max-width: 200px;">
                            <option value="tutup" <?php echo e(old('ppdb_status', $madrasah->ppdb_status ?? 'tutup') == 'tutup' ? 'selected' : ''); ?>>Tutup</option>
                            <option value="buka" <?php echo e(old('ppdb_status', $madrasah->ppdb_status ?? 'tutup') == 'buka' ? 'selected' : ''); ?>>Buka</option>
                        </select>
                        <div class="status-indicator status-<?php echo e(old('ppdb_status', $madrasah->ppdb_status ?? 'tutup')); ?>" style="width: 20px; height: 20px; border-radius: 50%; display: inline-block;"></div>
                        <small class="text-muted">Status akan berubah otomatis berdasarkan jadwal</small>
                        <?php if(old('ppdb_status', $madrasah->ppdb_status ?? 'tutup') == 'buka'): ?>
                            <div class="d-flex align-items-center gap-2 ms-2">
                                <span class="text-primary fw-bold"><?php echo e(url('/ppdb/' . $madrasah->name)); ?></span>
                                <button type="button"
                                    class="btn btn-sm btn-outline-secondary"
                                    onclick="window.copyToClipboard(event, <?php echo e(json_encode(url('/ppdb/' . $madrasah->name))); ?>)">
                                    <i class="mdi mdi-content-copy me-1"></i>Salin Link
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php $__errorArgs = ['ppdb_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Jadwal PPDB -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-calendar me-2"></i>Jadwal PPDB
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ppdb_jadwal_buka" class="form-label">Jadwal Buka PPDB</label>
                            <input type="datetime-local" class="form-control <?php $__errorArgs = ['ppdb_jadwal_buka'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="ppdb_jadwal_buka" name="ppdb_jadwal_buka"
                                   value="<?php echo e(old('ppdb_jadwal_buka', $madrasah->ppdb_jadwal_buka ? $madrasah->ppdb_jadwal_buka->format('Y-m-d\TH:i') : '')); ?>">
                            <?php $__errorArgs = ['ppdb_jadwal_buka'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ppdb_jadwal_tutup" class="form-label">Jadwal Tutup PPDB</label>
                            <input type="datetime-local" class="form-control <?php $__errorArgs = ['ppdb_jadwal_tutup'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="ppdb_jadwal_tutup" name="ppdb_jadwal_tutup"
                                   value="<?php echo e(old('ppdb_jadwal_tutup', $madrasah->ppdb_jadwal_tutup ? $madrasah->ppdb_jadwal_tutup->format('Y-m-d\TH:i') : '')); ?>">
                            <?php $__errorArgs = ['ppdb_jadwal_tutup'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ppdb_jadwal_pengumuman" class="form-label">Jadwal Pengumuman</label>
                            <input type="datetime-local" class="form-control <?php $__errorArgs = ['ppdb_jadwal_pengumuman'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="ppdb_jadwal_pengumuman" name="ppdb_jadwal_pengumuman"
                                   value="<?php echo e(old('ppdb_jadwal_pengumuman', $madrasah->ppdb_jadwal_pengumuman ? $madrasah->ppdb_jadwal_pengumuman->format('Y-m-d\TH:i') : '')); ?>">
                            <?php $__errorArgs = ['ppdb_jadwal_pengumuman'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kuota PPDB -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-sigma me-2"></i>Kuota PPDB
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="ppdb_kuota_total" class="form-label">Kuota Total</label>
                    <input type="number" class="form-control <?php $__errorArgs = ['ppdb_kuota_total'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           id="ppdb_kuota_total" name="ppdb_kuota_total" value="<?php echo e(old('ppdb_kuota_total', $madrasah->ppdb_kuota_total ?? '')); ?>">
                    <small class="text-muted">Total kuota pendaftar untuk semua jurusan</small>
                    <?php $__errorArgs = ['ppdb_kuota_total'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Kuota per Jurusan</label>
                    <small class="text-muted d-block mb-3">Kuota per jurusan otomatis mengikuti jurusan yang sudah diisi di profil madrasah.</small>
                    <div id="ppdb-quota-jurusan-container">
                        <?php
                            $jurusanArray = old('jurusan', $madrasah->jurusan ?? []);
                            $kuotaJurusan = old('ppdb_kuota_jurusan', $madrasah->ppdb_kuota_jurusan ?? []);
                        ?>
                        <?php if(is_array($jurusanArray) && count($jurusanArray) > 0): ?>
                            <?php $__currentLoopData = $jurusanArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $jurusan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" value="<?php echo e($jurusan); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control kuota-jurusan-input" name="ppdb_kuota_jurusan[<?php echo e($jurusan); ?>]" value="<?php echo e($kuotaJurusan[$jurusan] ?? ''); ?>" placeholder="Kuota" min="0" onchange="updateKuotaTotal()">
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="mdi mdi-information me-2"></i>Isi jurusan terlebih dahulu di profil madrasah untuk mengatur kuota per jurusan.
                            </div>
                        <?php endif; ?>
                    </div>
                    <script>
                    function updateKuotaTotal() {
                        let total = 0;
                        document.querySelectorAll('.kuota-jurusan-input').forEach(function(input) {
                            let val = parseInt(input.value);
                            if (!isNaN(val)) total += val;
                        });
                        let totalInput = document.getElementById('ppdb_kuota_total');
                        if (totalInput) totalInput.value = total;
                    }
                    document.addEventListener('DOMContentLoaded', function() {
                        updateKuotaTotal();
                        document.querySelectorAll('.kuota-jurusan-input').forEach(function(input) {
                            input.addEventListener('input', updateKuotaTotal);
                        });
                    });
                    </script>
                    <?php $__errorArgs = ['ppdb_kuota_jurusan.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-2"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Jalur Pendaftaran -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-routes me-2"></i>Jalur Pendaftaran
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Jalur Pendaftaran</label>
                    <div id="ppdb-jalur-container" class="array-input-container">
                        <?php $jalurArray = old('ppdb_jalur', $madrasah->ppdb_jalur ?? []); ?>
                        <?php if(is_array($jalurArray) && count($jalurArray) > 0): ?>
                            <?php $__currentLoopData = $jalurArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $jalur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="array-input-item mb-2">
                                    <div class="d-flex gap-2">
                                        <input type="text" class="form-control <?php $__errorArgs = ['ppdb_jalur.' . $index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               name="ppdb_jalur[]" value="<?php echo e($jalur); ?>" placeholder="Contoh: Jalur Prestasi, Jalur Reguler">
                                        <button type="button" class="btn btn-danger btn-remove-array remove-array-item">
                                            <i class="mdi mdi-minus"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        <div class="array-input-item">
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control" name="ppdb_jalur[]" placeholder="Contoh: Jalur Prestasi, Jalur Reguler">
                                <button type="button" class="btn btn-danger btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-success add-array-item mt-2" data-target="ppdb-jalur-container">
                        <i class="mdi mdi-plus me-1"></i>Tambah Jalur
                    </button>
                    <?php $__errorArgs = ['ppdb_jalur.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-2"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Biaya Pendaftaran -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-cash me-2"></i>Informasi Biaya
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="ppdb_biaya_pendaftaran" class="form-label">Biaya Pendaftaran</label>
                    <textarea class="form-control <?php $__errorArgs = ['ppdb_biaya_pendaftaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              id="ppdb_biaya_pendaftaran" name="ppdb_biaya_pendaftaran" rows="3"
                              placeholder="Informasi biaya pendaftaran PPDB"><?php echo e(old('ppdb_biaya_pendaftaran', $madrasah->ppdb_biaya_pendaftaran ?? '')); ?></textarea>
                    <?php $__errorArgs = ['ppdb_biaya_pendaftaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Catatan Pengumuman -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-note-text me-2"></i>Catatan Pengumuman
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="ppdb_catatan_pengumuman" class="form-label">Catatan Pengumuman</label>
                    <textarea class="form-control <?php $__errorArgs = ['ppdb_catatan_pengumuman'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              id="ppdb_catatan_pengumuman" name="ppdb_catatan_pengumuman" rows="3"
                              placeholder="Catatan tambahan untuk pengumuman hasil PPDB"><?php echo e(old('ppdb_catatan_pengumuman', $madrasah->ppdb_catatan_pengumuman ?? '')); ?></textarea>
                    <?php $__errorArgs = ['ppdb_catatan_pengumuman'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('ppdb.lp.dashboard')); ?>" class="btn btn-secondary">
                        <i class="mdi mdi-close me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save me-1"></i>Simpan Pengaturan PPDB
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Array input management
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-array-item') || e.target.closest('.add-array-item')) {
            const button = e.target.classList.contains('add-array-item') ? e.target : e.target.closest('.add-array-item');
            const targetId = button.getAttribute('data-target');
            const container = document.getElementById(targetId);

            const newItem = document.createElement('div');
            newItem.className = 'array-input-item mb-2';
            newItem.innerHTML = `
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" name="${targetId.replace('-container', '[]')}" placeholder="${getPlaceholderText(targetId)}">
                    <button type="button" class="btn btn-danger btn-remove-array remove-array-item">
                        <i class="mdi mdi-minus"></i>
                    </button>
                </div>
            `;

            // Insert before the last item (which should be empty)
            const items = container.querySelectorAll('.array-input-item');
            if (items.length > 0) {
                container.insertBefore(newItem, items[items.length - 1]);
            } else {
                container.appendChild(newItem);
            }
        }

        if (e.target.classList.contains('remove-array-item') || e.target.closest('.remove-array-item')) {
            const item = e.target.closest('.array-input-item');
            if (item) {
                item.remove();
            }
        }
    });

    function getPlaceholderText(containerId) {
        const placeholders = {
            'ppdb-jalur-container': 'Contoh: Jalur Prestasi, Jalur Reguler'
        };
        return placeholders[containerId] || '';
    }

    // Copy to clipboard function
    window.copyToClipboard = function(event, text) {
        navigator.clipboard.writeText(text).then(function() {
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="mdi mdi-check me-1"></i>Berhasil Disalin!';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-success');

            setTimeout(function() {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 2000);
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/ppdb/dashboard/ppdb-settings.blade.php ENDPATH**/ ?>