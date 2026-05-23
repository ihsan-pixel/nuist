<?php $__env->startSection('title'); ?> Jadwal Mengajar <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" />
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">

<style>
.schedule-page {
    display: grid;
    gap: 1.5rem;
}

.page-hero {
    border: 1px solid rgba(13, 110, 253, 0.12);
    border-radius: 1rem;
    background: linear-gradient(135deg, #ffffff 0%, #f4f8ff 100%);
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
}

.hero-icon {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0d6efd 0%, #3b82f6 100%);
    color: #fff;
    font-size: 1.75rem;
    box-shadow: 0 12px 24px rgba(13, 110, 253, 0.22);
}

.summary-card,
.teacher-card,
.empty-state {
    border: 1px solid #e9edf5;
    border-radius: 1rem;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
}

.summary-card {
    background: #fff;
}

.summary-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.teacher-card {
    background: #fff;
    overflow: hidden;
}

.teacher-card-header {
    padding: 1.25rem 1.25rem 1rem;
    border-bottom: 1px solid #eef2f7;
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
}

.teacher-avatar {
    width: 52px;
    height: 52px;
    background: #e8f1ff;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0d6efd;
    font-weight: bold;
    font-size: 18px;
}

.schedule-list {
    padding: 1rem 1.25rem 1.25rem;
}

.schedule-item {
    border: 1px solid #edf1f7;
    border-radius: 0.9rem;
    padding: 1rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
}

.schedule-item:hover {
    border-color: rgba(13, 110, 253, 0.2);
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
    transform: translateY(-1px);
}

.meta-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.45rem 0.75rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.meta-badge-day {
    color: #0f766e;
    background: #e6fffb;
}

.meta-badge-time {
    color: #9a3412;
    background: #fff1e6;
}

.schedule-subject {
    font-size: 1rem;
    font-weight: 600;
    color: #0f172a;
}

.info-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.5rem 0.75rem;
    border-radius: 0.75rem;
    background: #f8fafc;
    color: #475569;
    border: 1px solid #edf2f7;
    font-size: 0.875rem;
}

.empty-state {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
}

.btn-action {
    border-radius: 999px;
    padding: 0.45rem 0.9rem;
    font-size: 0.75rem;
    white-space: nowrap;
}

@media (max-width: 767.98px) {
    .teacher-card-header,
    .schedule-list {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $totalTeachers = $grouped->count();
    $totalSchedules = $grouped->flatten()->count();
?>

<div class="schedule-page">
    <div class="card page-hero mb-0">
        <div class="card-body p-4 p-lg-4">
            <div class="row align-items-center g-3">
                <div class="col-auto">
                    <div class="hero-icon">
                        <i class="bx bx-calendar"></i>
                    </div>
                </div>
                <div class="col">
                    <h4 class="card-title mb-1">Daftar Jadwal Mengajar</h4>
                    <p class="text-muted mb-0">Kelola jadwal mengajar tenaga pendidik dengan tampilan yang lebih ringkas dan mudah dipindai.</p>
                </div>
                <div class="col-12 col-lg-auto">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role !== 'tenaga_pendidik'): ?>
                        <a href="<?php echo e(route('teaching-schedules.create')); ?>" class="btn btn-primary rounded-pill px-3">
                            <i class="bx bx-plus me-1"></i>Tambah Jadwal
                        </a>
                        <?php if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin' || Auth::user()->role === 'pengurus'): ?>
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bx bx-upload me-1"></i>Import Jadwal
                        </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card summary-card h-100 mb-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="summary-icon bg-primary-subtle text-primary">
                        <i class="bx bx-group"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Guru</div>
                        <h4 class="mb-0"><?php echo e($totalTeachers); ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-card h-100 mb-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="summary-icon bg-success-subtle text-success">
                        <i class="bx bx-book-content"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Jadwal</div>
                        <h4 class="mb-0"><?php echo e($totalSchedules); ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-card h-100 mb-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="summary-icon bg-warning-subtle text-warning">
                        <i class="bx bx-calendar-week"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Status</div>
                        <h6 class="mb-0"><?php echo e($totalSchedules ? 'Data jadwal tersedia' : 'Belum ada data jadwal'); ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mb-0" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bx-check-circle fs-4 me-3"></i>
            <div><?php echo e(session('success')); ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mb-0" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bx-error-circle fs-4 me-3"></i>
            <div><?php echo e(session('error')); ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($grouped->isEmpty()): ?>
        <div class="card empty-state mb-0">
            <div class="card-body text-center py-5">
                <div class="avatar-xl mx-auto mb-4">
                    <div class="avatar-title bg-light rounded-circle">
                        <i class="bx bx-calendar-x fs-1 text-muted"></i>
                    </div>
                </div>
                <h5 class="text-muted mb-2">Belum ada jadwal mengajar</h5>
                <p class="text-muted mb-0">Belum ada data jadwal mengajar yang terdaftar dalam sistem.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacherName => $schedules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div class="col-12 col-xl-6">
                <div class="card teacher-card h-100 mb-0">
                    <div class="teacher-card-header">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="teacher-avatar">
                                    <?php echo e(strtoupper(substr($teacherName, 0, 1))); ?>

                                </div>
                                <div>
                                    <h5 class="mb-1 text-primary"><?php echo e($teacherName); ?></h5>
                                    <div class="text-muted small"><?php echo e(count($schedules)); ?> jadwal mengajar</div>
                                </div>
                            </div>
                            <span class="badge bg-light text-dark border"><?php echo e(count($schedules)); ?> entri</span>
                        </div>
                    </div>

                    <div class="schedule-list d-grid gap-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="schedule-item">
                            <div class="d-flex flex-column flex-lg-row align-items-lg-start justify-content-between gap-3">
                                <div class="grow">
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <span class="meta-badge meta-badge-day">
                                            <i class="bx bx-calendar"></i><?php echo e($schedule->day); ?>

                                        </span>
                                        <span class="meta-badge meta-badge-time">
                                            <i class="bx bx-time-five"></i><?php echo e($schedule->start_time); ?> - <?php echo e($schedule->end_time); ?>

                                        </span>
                                    </div>
                                    <div class="schedule-subject mb-2"><?php echo e($schedule->subject); ?></div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="info-chip">
                                            <i class="bx bx-group"></i><?php echo e($schedule->class_name); ?>

                                        </span>
                                        <span class="info-chip">
                                            <i class="bx bx-building-house"></i><?php echo e($schedule->school->name ?? 'N/A'); ?>

                                        </span>
                                    </div>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role !== 'tenaga_pendidik'): ?>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="<?php echo e(route('teaching-schedules.edit', $schedule->id)); ?>" class="btn btn-warning btn-action">
                                        <i class="bx bx-edit"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-danger btn-action delete-btn"
                                            data-id="<?php echo e($schedule->id); ?>"
                                            data-name="<?php echo e($schedule->subject); ?> - <?php echo e($schedule->class_name); ?>">
                                        <i class="bx bx-trash"></i> Hapus
                                    </button>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Jadwal Mengajar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('import_errors')): ?>
                <div class="alert alert-danger">
                    <strong>Import gagal dengan <?php echo e(count(session('import_errors'))); ?> error(s):</strong>
                    <ul class="mt-2 mb-0">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = session('import_errors'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <li><?php echo e($error); ?></li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </ul>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <li><?php echo e($error); ?></li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </ul>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="row">
                    <div class="col-md-8">
                        <h5>Panduan Import Jadwal Mengajar</h5>
                        <div class="alert alert-info">
                            <h6><i class="bx bx-info-circle"></i> Instruksi:</h6>
                            <ol>
                                <li>Unduh template file Excel dengan mengklik tombol "Unduh Template" di bawah ini.</li>
                                <li>Buka file template menggunakan Microsoft Excel atau aplikasi spreadsheet lainnya.</li>
                                <li>Isi data jadwal mengajar sesuai dengan format yang telah ditentukan.</li>
                                <li>Simpan file dalam format Excel (.xlsx/.xls).</li>
                                <li>Upload file yang telah diisi menggunakan form di bawah ini.</li>
                            </ol>
                        </div>

                        <h6>Format Data yang Diperlukan:</h6>
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Kolom</th>
                                    <th>Tipe Data</th>
                                    <th>Keterangan</th>
                                    <th>Wajib</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>school_scod</code></td>
                                    <td>Angka</td>
                                    <td>Kode SCOD Madrasah (lihat di master data madrasah)</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>teacher_nuist_id</code></td>
                                    <td>Angka</td>
                                    <td>NUist ID guru (lihat di master data tenaga pendidik)</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>day</code></td>
                                    <td>Teks</td>
                                    <td>Hari: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>subject</code></td>
                                    <td>Teks</td>
                                    <td>Mata pelajaran</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>class_name</code></td>
                                    <td>Teks</td>
                                    <td>Nama kelas (contoh: Kelas 1A, Kelas 2B)</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>start_time</code></td>
                                    <td>Jam (HH:MM)</td>
                                    <td>Jam mulai mengajar (contoh: 08:00)</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>end_time</code></td>
                                    <td>Jam (HH:MM)</td>
                                    <td>Jam selesai mengajar (contoh: 09:00)</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="alert alert-warning">
                            <h6><i class="bx bx-error"></i> Catatan Penting:</h6>
                            <ul>
                                <li>Pastikan Kode SCOD Madrasah dan NUist ID Guru sudah terdaftar dalam sistem.</li>
                                <li>Jam selesai harus lebih besar dari jam mulai.</li>
                                <li>Sistem akan mengecek konflik jadwal otomatis (guru yang sama pada hari dan jam yang sama).</li>
                                <li>Data yang tidak valid akan dilewati dengan pesan error.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="bx bx-upload"></i> Upload File</h6>
                            </div>
                            <div class="card-body">
                                <form action="<?php echo e(route('teaching-schedules.process-import')); ?>" method="POST" enctype="multipart/form-data" id="importForm">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label for="file" class="form-label">Pilih File Import</label>
                                        <input type="file" class="form-control" id="file" name="file" accept=".csv,.xlsx,.xls" required>
                                        <div class="form-text">Format: CSV, Excel (.xlsx, .xls) - Maksimal 10MB</div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-upload"></i> Import Data
                                        </button>
                                        <a href="<?php echo e(asset('template/teaching_schedule_import_template.xlsx')); ?>" class="btn btn-outline-secondary" download>
                                            <i class="bx bx-download"></i> Unduh Template
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bx bx-link"></i> Link Penting</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><a href="<?php echo e(route('madrasah.index')); ?>" target="_blank">Lihat Data Madrasah</a></li>
                                    <li><a href="<?php echo e(route('tenaga-pendidik.index')); ?>" target="_blank">Lihat Data Tenaga Pendidik</a></li>
                                    <li><a href="<?php echo e(route('teaching-schedules.index')); ?>">Kembali ke Daftar Jadwal</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script-bottom'); ?>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function() {
    <?php if(session('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Import Gagal',
            text: '<?php echo e(session("error")); ?>',
            confirmButtonText: 'Tutup'
        });
    <?php endif; ?>

    <?php if(session('import_errors')): ?>
        var errorList = '';
        <?php $__currentLoopData = session('import_errors'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            errorList += '<?php echo e($error); ?>\n';
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        Swal.fire({
            icon: 'error',
            title: 'Import Gagal',
            html: '<div style="text-align: left; white-space: pre-line;">' + errorList + '</div>',
            confirmButtonText: 'Tutup'
        });
    <?php endif; ?>

    <?php if(session('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '<?php echo e(session("success")); ?>',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>

    // SweetAlert for delete confirmation
    $('.delete-btn').on('click', function() {
        var scheduleId = $(this).data('id');
        var scheduleName = $(this).data('name');

        Swal.fire({
            title: 'Yakin hapus?',
            text: 'Jadwal "' + scheduleName + '" akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit form
                var form = $('<form>', {
                    'method': 'POST',
                    'action': '<?php echo e(route("teaching-schedules.destroy", ":id")); ?>'.replace(':id', scheduleId)
                });
                form.append('<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">');
                form.append('<input type="hidden" name="_method" value="DELETE">');
                $('body').append(form);
                form.submit();
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/teaching-schedules/index.blade.php ENDPATH**/ ?>