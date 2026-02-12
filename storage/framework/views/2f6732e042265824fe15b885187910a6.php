<?php $__env->startSection('title'); ?> Dashboard MGMP - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> MGMP <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Dashboard MGMP <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<div class="row">
    <div class="col-lg-4 col-12">
        <!-- Profile / Welcome Card -->
        <div class="card border-0 shadow-sm hover-lift mb-3" style="border-radius: 12px; overflow: hidden;">
            <div class="p-4" style="background: linear-gradient(135deg, #0e8549 0%, #0b6b4d 100%); color: #fff;">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar-lg profile-user-wid mb-0">
                            <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle" style="margin-top:20px;">
                                <i class="mdi mdi-school fs-2"></i>
                            </div>
                        </div>
                    </div>
                    <div class="grow">
                        <h5 class="mb-1 text-white"><?php echo e(Str::title(Auth::user()->name ?? 'MGMP User')); ?></h5>
                        <p class="mb-1 text-white-50 small">MGMP NUIST • <?php echo e(Auth::user()->email ?? '-'); ?></p>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <small class="badge bg-white bg-opacity-10 text-white">Role: <?php echo e(Str::ucfirst(Auth::user()->role ?? 'mgmp')); ?></small>
                            <small class="badge bg-white bg-opacity-10 text-white">ID: <?php echo e(Auth::user()->nuist_id ?? '-'); ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <p class="text-muted small mb-2">Ringkasan cepat MGMP Anda</p>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded text-center">
                            <div class="text-muted small">Anggota</div>
                            <div class="h5 mb-0"><?php echo e($totalAnggota ?? 0); ?></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded text-center">
                            <div class="text-muted small">Kegiatan</div>
                            <div class="h5 mb-0"><?php echo e($totalKegiatan ?? 0); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-12">
        <!-- Top statistic cards -->
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md me-3">
                            <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                <i class="mdi mdi-account-group fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="text-muted small">Total Anggota</div>
                            <div class="h5 mb-0"><?php echo e($totalAnggota ?? 0); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md me-3">
                            <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                <i class="mdi mdi-calendar-check fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="text-muted small">Kegiatan</div>
                            <div class="h5 mb-0"><?php echo e($totalKegiatan ?? 0); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md me-3">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="mdi mdi-file-document fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="text-muted small">Laporan</div>
                            <div class="h5 mb-0"><?php echo e($totalLaporan ?? 0); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0">Aktivitas Terbaru</h6>
                            <small class="text-muted"><?php echo e(isset($recentActivities) ? $recentActivities->count() : 0); ?> items</small>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($recentActivities) && $recentActivities->count() > 0): ?>
                            <div class="list-group list-group-flush">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <div class="list-group-item px-0 py-2 border-0">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-light text-primary rounded-circle">
                                                    <i class="mdi mdi-calendar-check text-primary fs-5"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grow">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-1"><?php echo e($activity->title); ?></h6>
                                                <small class="text-muted"><?php echo e($activity->created_at->diffForHumans()); ?></small>
                                            </div>
                                            <p class="text-muted small mb-0"><?php echo e(Str::limit($activity->description, 120)); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="avatar-lg mx-auto mb-2">
                                    <div class="avatar-title bg-light text-muted rounded-circle">
                                        <i class="mdi mdi-information-outline fs-1"></i>
                                    </div>
                                </div>
                                <p class="text-muted small mb-0">Belum ada aktivitas. Buat laporan atau kegiatan untuk mengisi daftar ini.</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0">Laporan Terbaru</h6>
                            <a href="<?php echo e(route('mgmp.laporan')); ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($recentReports) && $recentReports->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Kegiatan</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <tr>
                                            <td><?php echo e($i + 1); ?></td>
                                            <td><?php echo e(Str::limit($r->name ?? $r->title ?? '-', 40)); ?></td>
                                            <td><?php echo e(isset($r->tanggal) ? \Carbon\Carbon::parse($r->tanggal)->format('d M Y') : '-'); ?></td>
                                        </tr>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <p class="text-muted small mb-0">Belum ada laporan terbaru.</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<style>
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mgmp/dashboard.blade.php ENDPATH**/ ?>