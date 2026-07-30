<?php $__env->startSection('title'); ?>
    Pending Registrations
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'pengurus']);
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAllowed): ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?> Admin <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?> Pending Registrations <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <?php echo $__env->make('mgmp.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="mgmp-page">
        <div class="mgmp-hero-strip mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <div class="mgmp-kicker mb-2">Admin</div>
                    <h4 class="mb-1">Persetujuan Registrasi Pengguna</h4>
                    <p class="mb-0 text-white-50">Kelola antrean registrasi yang menunggu review dan pantau user yang sudah aktif setelah disetujui.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="mgmp-chip bg-white text-success"><?php echo e($stats['pending_total'] ?? 0); ?> pending</span>
                    <span class="mgmp-chip bg-white text-success"><?php echo e($stats['approved_total'] ?? 0); ?> approved</span>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card mgmp-stat-card registration-stat-card p-3 h-100">
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-md">
                            <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                <i class="bx bx-time-five fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="registration-stat-label">Antrean Pending</div>
                            <div class="registration-stat-number"><?php echo e($stats['pending_total'] ?? 0); ?></div>
                            <small class="text-muted">Registrasi menunggu persetujuan</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mgmp-stat-card registration-stat-card p-3 h-100">
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-md">
                            <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                <i class="bx bx-check-shield fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="registration-stat-label">User Approved</div>
                            <div class="registration-stat-number"><?php echo e($stats['approved_total'] ?? 0); ?></div>
                            <small class="text-muted">Akun aktif hasil persetujuan</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mgmp-stat-card registration-stat-card p-3 h-100">
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-md">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="bx bx-briefcase-alt-2 fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="registration-stat-label">Pending Pengurus</div>
                            <div class="registration-stat-number"><?php echo e($stats['pending_pengurus'] ?? 0); ?></div>
                            <small class="text-muted">Calon pengurus belum diproses</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mgmp-stat-card registration-stat-card p-3 h-100">
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-md">
                            <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                <i class="bx bx-book-reader fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="registration-stat-label">Pending Tenaga Pendidik</div>
                            <div class="registration-stat-number"><?php echo e($stats['pending_tenaga_pendidik'] ?? 0); ?></div>
                            <small class="text-muted">Calon guru belum diproses</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
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

                <div class="registration-section-header mb-4">
                    <div>
                        <div class="mgmp-kicker text-success mb-2">Review Queue</div>
                        <h5 class="mb-1">Registrasi Menunggu Persetujuan</h5>
                        <p class="text-muted mb-0">Periksa identitas pendaftar, peran, dan asal sekolah sebelum menyetujui atau menolak.</p>
                    </div>
                    <span class="mgmp-chip"><?php echo e($pendingRegistrations->total()); ?> total antrean</span>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingRegistrations->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table align-middle registration-table mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Peran</th>
                                    <th>Jabatan</th>
                                    <th>Asal Sekolah</th>
                                    <th>Diajukan</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pendingRegistrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td><?php echo e($pendingRegistrations->firstItem() + $loop->index); ?></td>
                                        <td>
                                            <div class="fw-semibold text-dark"><?php echo e($registration->name); ?></div>
                                        </td>
                                        <td><?php echo e($registration->email); ?></td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary"><?php echo e(ucfirst(str_replace('_', ' ', $registration->role))); ?></span>
                                        </td>
                                        <td><?php echo e($registration->jabatan ?: '-'); ?></td>
                                        <td><?php echo e(optional($registration->madrasah)->name ?? '-'); ?></td>
                                        <td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($registration->submitted_at): ?>
                                                <div class="fw-semibold"><?php echo e($registration->submitted_at->format('d M Y')); ?></div>
                                                <small class="text-muted"><?php echo e($registration->submitted_at->format('H:i')); ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                                <button type="button"
                                                    class="btn btn-sm btn-success approve-btn"
                                                    data-id="<?php echo e($registration->id); ?>"
                                                    data-name="<?php echo e($registration->name); ?>">
                                                    <i class="bx bx-check me-1"></i> Approve
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger reject-btn"
                                                    data-id="<?php echo e($registration->id); ?>"
                                                    data-name="<?php echo e($registration->name); ?>">
                                                    <i class="bx bx-x me-1"></i> Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <?php echo e($pendingRegistrations->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="mgmp-empty-state py-5">
                        <i class="bx bx-check-shield"></i>
                        <strong>Tidak ada registrasi pending</strong>
                        <small>Semua pendaftaran sudah diproses.</small>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="registration-section-header mb-4">
                    <div>
                        <div class="mgmp-kicker text-success mb-2">Approved Users</div>
                        <h5 class="mb-1">Data Nama User Yang Sudah Di-Approve</h5>
                        <p class="text-muted mb-0">Menampilkan akun aktif terbaru dengan peran pengurus atau tenaga pendidik.</p>
                    </div>
                    <span class="mgmp-chip"><?php echo e($approvedUsers->count()); ?> ditampilkan</span>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($approvedUsers->isNotEmpty()): ?>
                    <div class="row g-3 mb-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $approvedUsers->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $approvedUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <div class="col-xl-4 col-md-6">
                                <div class="registration-approved-card h-100">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="registration-approved-avatar">
                                            <?php echo e(strtoupper(\Illuminate\Support\Str::substr($approvedUser->name, 0, 1))); ?>

                                        </div>
                                        <div class="grow">
                                            <div class="fw-semibold text-dark mb-1"><?php echo e($approvedUser->name); ?></div>
                                            <div class="small text-muted mb-2"><?php echo e($approvedUser->email); ?></div>
                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                <span class="badge bg-success-subtle text-success"><?php echo e(ucfirst(str_replace('_', ' ', $approvedUser->role))); ?></span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($approvedUser->madrasah): ?>
                                                    <span class="badge bg-light text-dark"><?php echo e($approvedUser->madrasah->name); ?></span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <small class="text-muted">
                                                Aktif sejak <?php echo e(optional($approvedUser->created_at)->format('d M Y H:i') ?? '-'); ?>

                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle registration-table mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama User</th>
                                    <th>Email</th>
                                    <th>Peran</th>
                                    <th>Sekolah / Jabatan</th>
                                    <th>Aktif Sejak</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $approvedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $approvedUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <div class="fw-semibold text-dark"><?php echo e($approvedUser->name); ?></div>
                                        </td>
                                        <td><?php echo e($approvedUser->email); ?></td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success"><?php echo e(ucfirst(str_replace('_', ' ', $approvedUser->role))); ?></span>
                                        </td>
                                        <td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($approvedUser->role === 'pengurus'): ?>
                                                <?php echo e($approvedUser->ketugasan ?: $approvedUser->jabatan ?: '-'); ?>

                                            <?php else: ?>
                                                <?php echo e(optional($approvedUser->madrasah)->name ?? '-'); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($approvedUser->created_at): ?>
                                                <div class="fw-semibold"><?php echo e($approvedUser->created_at->format('d M Y')); ?></div>
                                                <small class="text-muted"><?php echo e($approvedUser->created_at->format('H:i')); ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="mgmp-empty-state py-5">
                        <i class="bx bx-user-check"></i>
                        <strong>Belum ada user approved</strong>
                        <small>Data user yang disetujui akan tampil di sini setelah proses approve dilakukan.</small>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-danger text-center">
        <h4>Akses Ditolak</h4>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script>
        $(document).ready(function () {
            $('.approve-btn').on('click', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');

                Swal.fire({
                    title: 'Approve Registration?',
                    text: `Approve registration for ${name}? Sistem akan membuat akun dan mengirim email kredensial login.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0e8549',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Approve',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $('<form>', {
                            'method': 'POST',
                            'action': '<?php echo e(url("admin/pending-registrations")); ?>/' + id + '/approve'
                        });

                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': '_token',
                            'value': '<?php echo e(csrf_token()); ?>'
                        }));

                        $('body').append(form);
                        form.submit();
                    }
                });
            });

            $('.reject-btn').on('click', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');

                Swal.fire({
                    title: 'Reject Registration?',
                    text: `Reject registration for ${name}? Tindakan ini akan menghapus antrean pendaftaran ini.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Reject',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $('<form>', {
                            'method': 'POST',
                            'action': '<?php echo e(url("admin/pending-registrations")); ?>/' + id + '/reject'
                        });

                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': '_token',
                            'value': '<?php echo e(csrf_token()); ?>'
                        }));

                        $('body').append(form);
                        form.submit();
                    }
                });
            });
        });
    </script>

    <style>
        .registration-stat-card {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbf9 100%);
        }

        .registration-stat-label {
            color: #6b7b75;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .registration-stat-number {
            color: #004b4c;
            font-size: 28px;
            font-weight: 800;
            line-height: 1.1;
            margin: 4px 0;
        }

        .registration-section-header {
            align-items: flex-start;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: space-between;
        }

        .registration-table thead th {
            white-space: nowrap;
        }

        .registration-approved-card {
            background: linear-gradient(180deg, #ffffff 0%, #f7fbf8 100%);
            border: 1px solid #e5eee9;
            border-radius: 18px;
            padding: 18px;
        }

        .registration-approved-avatar {
            align-items: center;
            background: linear-gradient(135deg, #004b4c, #0e8549);
            border-radius: 16px;
            color: #fff;
            display: inline-flex;
            font-size: 18px;
            font-weight: 800;
            height: 48px;
            justify-content: center;
            min-width: 48px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/admin/pending-registrations/index.blade.php ENDPATH**/ ?>