<?php $__env->startSection('title', 'Face Enrollment Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Pendaftaran Wajah - Daftar Guru</h4>
                    <p class="card-title-desc">Kelola pendaftaran wajah untuk guru. Klik "Daftar / Re-enroll" untuk membuka halaman pendaftaran wajah.</p>

                    <!-- Filter by Madrasah -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select id="madrasah-filter" class="form-select">
                                <option value="">Semua Madrasah</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($madrasah->id); ?>" <?php echo e(request('madrasah_id') == $madrasah->id ? 'selected' : ''); ?>>
                                    <?php echo e($madrasah->nama); ?>

                                </option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="face-status-filter" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="registered" <?php echo e(request('face_status') == 'registered' ? 'selected' : ''); ?>>Sudah Terdaftar</option>
                                <option value="not_registered" <?php echo e(request('face_status') == 'not_registered' ? 'selected' : ''); ?>>Belum Terdaftar</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button id="apply-filters" class="btn btn-primary">Terapkan Filter</button>
                        </div>
                    </div>

                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>NIP / NUPTK</th>
                                <th>Madrasah</th>
                                <th>Status Wajah</th>
                                <th>Tanggal Daftar</th>
                                <th>Verifikasi Diperlukan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <strong><?php echo e($u->name); ?></strong>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($u->face_verification_required): ?>
                                    <br><small class="text-muted">Verifikasi wajah aktif</small>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td><?php echo e($u->nip ?? $u->nuptk ?? '-'); ?></td>
                                <td><?php echo e($u->madrasah->nama ?? '-'); ?></td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($u->face_registered_at): ?>
                                        <span class="badge bg-success">Terdaftar</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Belum Terdaftar</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td><?php echo e($u->face_registered_at ? $u->face_registered_at->format('d/m/Y H:i') : '-'); ?></td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($u->face_verification_required): ?>
                                        <span class="badge bg-info">Ya</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Tidak</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('face.enrollment', ['user_id' => $u->id])); ?>" class="btn btn-sm btn-primary">
                                            <i class="bx bx-camera me-1"></i>Daftar / Re-enroll
                                        </a>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($u->face_registered_at): ?>
                                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteFace(<?php echo e($u->id); ?>, '<?php echo e($u->name); ?>')">
                                            <i class="bx bx-trash me-1"></i>Hapus
                                        </button>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($users->appends(request()->query())->links()); ?>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteFaceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus Data Wajah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data wajah untuk guru: <strong id="delete-user-name"></strong>?</p>
                <p class="text-danger">Data wajah yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Hapus</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
function confirmDeleteFace(userId, userName) {
    document.getElementById('delete-user-name').textContent = userName;
    document.getElementById('confirm-delete-btn').onclick = function() {
        deleteFaceData(userId);
    };
    new bootstrap.Modal(document.getElementById('deleteFaceModal')).show();
}

function deleteFaceData(userId) {
    fetch(`/admin/face-enrollment/${userId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal menghapus data wajah: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus data wajah');
    });
}

// Filter functionality
document.getElementById('apply-filters').addEventListener('click', function() {
    const madrasahId = document.getElementById('madrasah-filter').value;
    const faceStatus = document.getElementById('face-status-filter').value;

    let url = '<?php echo e(route("face.enrollment.list")); ?>';
    const params = new URLSearchParams();

    if (madrasahId) params.append('madrasah_id', madrasahId);
    if (faceStatus) params.append('face_status', faceStatus);

    if (params.toString()) {
        url += '?' + params.toString();
    }

    window.location.href = url;
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/admin/face-enrollment-list.blade.php ENDPATH**/ ?>