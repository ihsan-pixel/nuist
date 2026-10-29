<?php $__env->startSection('title', 'Pendataan GTK'); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<style>
    #gtk-schools-table td { vertical-align: middle; }
    #gtk-schools-table .gtk-school-name { min-width: 200px; white-space: normal; }
    #gtk-schools-table .gtk-status { min-width: 105px; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Pendataan GTK <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h4 class="card-title mb-2">Pendataan GTK</h4>
                <p class="text-muted mb-0">Kelola pendataan GTK dan unduh data seluruh sekolah atau per sekolah.</p>
            </div>
            <a href="<?php echo e(route('pendataan-gtk.export')); ?>" class="btn btn-success">
                <i class="bx bx-download me-1"></i> Export Excel Semua GTK
            </a>
        </div>

        <div class="d-flex flex-wrap gap-3 border rounded bg-light p-3 mb-4">
            <span><i class="bx bx-buildings text-primary me-1"></i> <strong><?php echo e($madrasahs->count()); ?></strong> sekolah</span>
            <span><i class="bx bx-group text-primary me-1"></i> <strong><?php echo e(number_format($madrasahs->sum('tenaga_pendidik_users_count'), 0, ',', '.')); ?></strong> tenaga pendidik</span>
        </div>

        <div class="table-responsive">
            <table id="gtk-schools-table" class="table table-bordered table-hover nowrap w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>SCOD</th>
                        <th>Nama Madrasah / Sekolah</th>
                        <th>Jumlah GTK</th>
                        <th>Kelengkapan Data</th>
                        <th>Scan Wajah</th>
                        <th>KTP</th>
                        <th>SK Pertama</th>
                        <th>SK Terakhir</th>
                        <th>Foto Resmi</th>
                        <th>Foto Bebas</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($madrasah->scod ?: '-'); ?></td>
                            <td class="gtk-school-name fw-semibold"><?php echo e($madrasah->name); ?></td>
                            <td class="text-center"><?php echo e($madrasah->tenaga_pendidik_users_count); ?></td>
                            <td class="gtk-status">
                                <div class="d-flex justify-content-between small mb-1"><span>Progress</span><strong><?php echo e($madrasah->gtk_completion_percentage); ?>%</strong></div>
                                <div class="progress" style="height: 6px"><div class="progress-bar <?php echo e($madrasah->gtk_completion_percentage === 100 ? 'bg-success' : 'bg-primary'); ?>" style="width:<?php echo e($madrasah->gtk_completion_percentage); ?>%"></div></div>
                            </td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['face' => 'Scan Wajah', 'ktp' => 'KTP', 'sk_awal' => 'SK Pertama', 'sk_akhir' => 'SK Terakhir', 'foto_resmi' => 'Foto Resmi', 'foto_bebas' => 'Foto Bebas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusKey => $statusLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <?php ($statusCount = $madrasah->gtk_status_counts[$statusKey] ?? 0); ?>
                                <td class="text-center gtk-status">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->tenaga_pendidik_users_count > 0 && $statusCount === $madrasah->tenaga_pendidik_users_count): ?>
                                        <span class="badge bg-success">Sudah</span>
                                    <?php elseif($statusCount > 0): ?>
                                        <span class="badge bg-warning text-dark"><?php echo e($statusCount); ?>/<?php echo e($madrasah->tenaga_pendidik_users_count); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Belum</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="<?php echo e(route('pendataan-gtk.show', $madrasah->id)); ?>" class="btn btn-primary btn-sm" aria-label="Lihat GTK <?php echo e($madrasah->name); ?>">
                                        <i class="bx bx-show me-1"></i> Detail GTK
                                    </a>
                                    <a href="<?php echo e(route('pendataan-gtk.export-school', $madrasah->id)); ?>" class="btn btn-success btn-sm" aria-label="Export Excel <?php echo e($madrasah->name); ?>">
                                        <i class="bx bx-download me-1"></i> Export Excel
                                    </a>
                                    <a href="<?php echo e(route('pendataan-gtk.export-school-pdfs', $madrasah)); ?>" class="btn btn-danger btn-sm" aria-label="Download semua PDF GTK <?php echo e($madrasah->name); ?>">
                                        <i class="bx bxs-file-archive me-1"></i> Semua PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script>
$(function () {
    $('#gtk-schools-table').DataTable({
        autoWidth: false,
        pageLength: 25,
        order: [[0, 'asc']],
        columnDefs: [{ targets: 11, orderable: false, searchable: false }],
        language: {
            search: 'Cari sekolah:',
            searchPlaceholder: 'Nama atau SCOD...',
            lengthMenu: 'Tampilkan _MENU_ sekolah',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ sekolah',
            infoEmpty: 'Belum ada sekolah',
            infoFiltered: '(disaring dari _MAX_ sekolah)',
            zeroRecords: 'Sekolah tidak ditemukan',
            emptyTable: 'Belum ada data sekolah',
            paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' }
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/masterdata/pendataan-gtk/index.blade.php ENDPATH**/ ?>