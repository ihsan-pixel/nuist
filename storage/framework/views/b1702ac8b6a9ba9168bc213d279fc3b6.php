<?php $__env->startSection('title'); ?>
Data Operator SPP
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Data Operator SPP <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Pending</div>
                <div class="display-6 fw-bold"><?php echo e($stats['pending']); ?></div>
                <div class="text-muted small">Menunggu approval Super Admin</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Approved</div>
                <div class="display-6 fw-bold"><?php echo e($stats['approved']); ?></div>
                <div class="text-muted small">Permohonan berhasil dibuatkan akun</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Rejected</div>
                <div class="display-6 fw-bold"><?php echo e($stats['rejected']); ?></div>
                <div class="text-muted small">Permohonan yang ditolak</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Akun Aktif</div>
                <div class="display-6 fw-bold"><?php echo e($stats['active_accounts']); ?></div>
                <div class="text-muted small">Operator SPP yang bisa login</div>
            </div>
        </div>
    </div>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <div>
            <h5 class="mb-1">Permohonan Operator SPP</h5>
            <div class="text-muted small">Approval dan penolakan pendaftaran akun Admin SPP dari sekolah.</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?php echo e(route('operator-spp.index')); ?>" class="btn btn-sm <?php echo e($status === '' ? 'btn-primary' : 'btn-outline-primary'); ?>">Semua</a>
            <a href="<?php echo e(route('operator-spp.index', ['status' => 'pending'])); ?>" class="btn btn-sm <?php echo e($status === 'pending' ? 'btn-primary' : 'btn-outline-primary'); ?>">Pending</a>
            <a href="<?php echo e(route('operator-spp.index', ['status' => 'approved'])); ?>" class="btn btn-sm <?php echo e($status === 'approved' ? 'btn-primary' : 'btn-outline-primary'); ?>">Approved</a>
            <a href="<?php echo e(route('operator-spp.index', ['status' => 'rejected'])); ?>" class="btn btn-sm <?php echo e($status === 'rejected' ? 'btn-primary' : 'btn-outline-primary'); ?>">Rejected</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Pengaju</th>
                        <th>Sekolah</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?php echo e($registration->name); ?></div>
                                <div class="text-muted small"><?php echo e($registration->jabatan); ?></div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo e($registration->madrasah->name ?? '-'); ?></div>
                                <div class="text-muted small"><?php echo e($registration->madrasah->kabupaten ?? 'Kabupaten belum diisi'); ?></div>
                            </td>
                            <td>
                                <div><?php echo e($registration->email); ?></div>
                                <div class="text-muted small"><?php echo e($registration->no_hp ?: 'No. HP belum diisi'); ?></div>
                            </td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($registration->status === 'pending'): ?>
                                    <span class="badge bg-warning-subtle text-warning">Pending</span>
                                <?php elseif($registration->status === 'approved'): ?>
                                    <span class="badge bg-success-subtle text-success">Approved</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($registration->review_notes): ?>
                                    <div class="text-muted small mt-2"><?php echo e($registration->review_notes); ?></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td>
                                <div><?php echo e(optional($registration->submitted_at)->format('d M Y')); ?></div>
                                <div class="text-muted small"><?php echo e(optional($registration->submitted_at)->format('H:i')); ?></div>
                            </td>
                            <td class="text-nowrap">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($registration->status === 'pending'): ?>
                                    <form action="<?php echo e(route('operator-spp.approve', $registration)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Setujui pendaftaran dan buat akun operator SPP?')">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo e($registration->id); ?>">Reject</button>
                                <?php else: ?>
                                    <span class="text-muted small">Sudah diproses</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>

                        <div class="modal fade" id="rejectModal<?php echo e($registration->id); ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="<?php echo e(route('operator-spp.reject', $registration)); ?>" method="POST" class="modal-content">
                                    <?php echo csrf_field(); ?>
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tolak Pendaftaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-3">Pendaftaran untuk <strong><?php echo e($registration->name); ?></strong> akan ditolak.</p>
                                        <label class="form-label" for="review_notes_<?php echo e($registration->id); ?>">Catatan Penolakan</label>
                                        <textarea id="review_notes_<?php echo e($registration->id); ?>" name="review_notes" class="form-control" rows="4" placeholder="Opsional"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Tolak Pendaftaran</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data pendaftaran operator SPP.</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($registrations->hasPages()): ?>
            <div class="mt-3">
                <?php echo e($registrations->links('pagination::bootstrap-5')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h5 class="mb-1">Akun Admin SPP</h5>
        <div class="text-muted small">Kelola akun operator SPP yang sudah aktif pada masing-masing sekolah.</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Sekolah</th>
                        <th>Email</th>
                        <th>Status Akun</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $approvedOperators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?php echo e($operator->name); ?></div>
                                <div class="text-muted small"><?php echo e($operator->jabatan ?: 'Operator SPP'); ?></div>
                            </td>
                            <td><?php echo e($operator->madrasah->name ?? '-'); ?></td>
                            <td>
                                <div><?php echo e($operator->email); ?></div>
                                <div class="text-muted small"><?php echo e($operator->no_hp ?: 'No. HP belum diisi'); ?></div>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($operator->is_active ? 'success' : 'secondary'); ?>">
                                    <?php echo e($operator->is_active ? 'Aktif' : 'Nonaktif'); ?>

                                </span>
                            </td>
                            <td><?php echo e($operator->created_at?->format('d M Y H:i')); ?></td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editOperatorModal<?php echo e($operator->id); ?>">Edit</button>
                                <form action="<?php echo e(route('operator-spp.accounts.status', $operator)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="is_active" value="<?php echo e($operator->is_active ? 0 : 1); ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-<?php echo e($operator->is_active ? 'secondary' : 'success'); ?>">
                                        <?php echo e($operator->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>

                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="editOperatorModal<?php echo e($operator->id); ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="<?php echo e(route('operator-spp.accounts.update', $operator)); ?>" method="POST" class="modal-content">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Akun Operator SPP</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Sekolah</label>
                                            <input type="text" class="form-control" value="<?php echo e($operator->madrasah->name ?? '-'); ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="name" class="form-control" value="<?php echo e($operator->name); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jabatan</label>
                                            <input type="text" name="jabatan" class="form-control" value="<?php echo e($operator->jabatan ?: 'Operator SPP'); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="<?php echo e($operator->email); ?>" required>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label">No. HP</label>
                                            <input type="text" name="no_hp" class="form-control" value="<?php echo e($operator->no_hp); ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada akun Admin SPP yang aktif.</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/masterdata/operator-spp/index.blade.php ENDPATH**/ ?>