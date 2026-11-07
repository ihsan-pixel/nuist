<?php $__env->startSection('title', 'Izin Jadwal Piket'); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<style>
.picket-page{position:relative;isolation:isolate;padding:8px 0 24px}.picket-page:before,.picket-page:after{content:"";position:fixed;z-index:-1;border-radius:50%;filter:blur(2px);pointer-events:none;animation:picket-float 12s ease-in-out infinite}.picket-page:before{width:280px;height:280px;right:-90px;top:150px;background:rgba(42,190,153,.14)}.picket-page:after{width:230px;height:230px;left:180px;bottom:-100px;background:rgba(98,158,255,.12);animation-direction:reverse}.picket-page .card{border:1px solid rgba(255,255,255,.86)!important;border-radius:22px!important;background:rgba(255,255,255,.7)!important;backdrop-filter:blur(18px);box-shadow:0 10px 30px rgba(32,70,78,.09)!important;overflow:hidden;animation:picket-in .7s both}.picket-page .card-body{padding:22px 24px}.picket-page .table-responsive{border:1px solid #e5edef;border-radius:16px;overflow-x:auto}.picket-page table.dataTable{table-layout:auto;min-width:900px}.picket-page table.dataTable thead th{background:#f1f6f6!important;color:#192d37;border:0;border-bottom:1px solid #e5edef;padding:13px 14px;font-size:11px;white-space:nowrap}.picket-page table.dataTable tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;white-space:normal;overflow-wrap:anywhere}.picket-page table.dataTable tbody tr{transition:background .18s ease,transform .18s ease}.picket-page table.dataTable tbody tr:hover{background:rgba(231,246,240,.45);transform:translateX(2px)}.picket-page .btn{border-radius:10px;transition:transform .18s ease,box-shadow .18s ease}.picket-page .btn:hover{transform:translateY(-2px);box-shadow:0 8px 16px rgba(32,70,78,.14)}.picket-page .btn-primary{background:linear-gradient(90deg,#0f766e,#14b8a6);border:0}.picket-page .badge{border-radius:999px}.picket-page .dt-buttons{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}.picket-page .dt-button{border:0!important;border-radius:8px!important;color:#fff!important}.picket-page .buttons-copy{background:linear-gradient(90deg,#0f766e,#14b8a6)!important}.picket-page .buttons-excel{background:linear-gradient(90deg,#2563eb,#60a5fa)!important}.picket-page .buttons-pdf{background:linear-gradient(90deg,#7c3aed,#a78bfa)!important}.picket-page .buttons-print{background:linear-gradient(90deg,#d97706,#fbbf24)!important}.picket-page .buttons-colvis{background:linear-gradient(90deg,#db2777,#f472b6)!important}.picket-page div.dt-button-collection{padding:10px!important;border-radius:16px!important;background:#f8fafb!important}.picket-page div.dt-button-collection button.dt-button{background:#fff!important;background-image:none!important;color:#192d37!important;border:0!important;border-radius:14px!important;width:100%!important;margin-bottom:6px;text-align:left}.picket-page .form-control,.picket-page .form-select{border:1px solid #dce7e9;border-radius:9px}@keyframes picket-float{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(-12px,18px,0)}}@keyframes picket-in{from{opacity:0;transform:translateY(18px) scale(.99);filter:blur(3px)}to{opacity:1;transform:none;filter:none}}@media(max-width:767px){.picket-page .card-body{padding:14px}}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Akademik <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Izin Jadwal Piket <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="picket-page"><div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div>
                    <h4 class="mb-1">Izin Jadwal Piket Semester</h4>
                    <p class="text-muted mb-0">
                        Kelola periode libur semester dan pengajuan jadwal piket untuk
                        <strong><?php echo e($school?->name ?? 'semua sekolah'); ?></strong>.
                    </p>
                </div>
                <div>
                    <a href="<?php echo e(route('picket-schedule-periods.create', $selectedSchoolId ? ['school_id' => $selectedSchoolId] : [])); ?>" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Tambah Periode
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(Auth::user()->role, ['super_admin', 'admin_yayasan'], true)): ?>
                    <form method="GET" action="<?php echo e(route('picket-schedule-periods.index')); ?>" class="row g-2 align-items-end mb-3">
                        <div class="col-md-6 col-lg-4">
                            <label class="form-label">Filter Sekolah</label>
                            <select name="school_id" class="form-select">
                                <option value="">Semua sekolah</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schoolOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($schoolOption->id); ?>" <?php if((string) $selectedSchoolId === (string) $schoolOption->id): echo 'selected'; endif; ?>>
                                        <?php echo e($schoolOption->name); ?>

                                    </option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Terapkan</button>
                        </div>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($periods->isEmpty()): ?>
                    <div class="text-center py-5">
                        <i class="bx bx-calendar-x display-5 text-muted"></i>
                        <h5 class="mt-3 mb-2">Belum ada periode jadwal piket</h5>
                        <p class="text-muted mb-0">Tambahkan periode libur semester agar guru dapat mengajukan hari piketnya.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(Auth::user()->role, ['super_admin', 'admin_yayasan'], true)): ?>
                                        <th>Sekolah</th>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <th>Periode</th>
                                    <th>Rentang Tanggal</th>
                                    <th>Pengajuan Guru</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(Auth::user()->role, ['super_admin', 'admin_yayasan'], true)): ?>
                                            <td><?php echo e($period->school->name ?? '-'); ?></td>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($period->name); ?></div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($period->description): ?>
                                                <small class="text-muted"><?php echo e(\Illuminate\Support\Str::limit($period->description, 110)); ?></small>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td><?php echo e($period->date_range_label); ?></td>
                                        <td>
                                            <div><span class="badge bg-primary"><?php echo e($period->submissions_count); ?></span> Total pengajuan</div>
                                            <div class="mt-1 d-flex flex-wrap gap-1">
                                                <span class="badge bg-warning text-dark">Pending: <?php echo e($period->submissions_pending_count); ?></span>
                                                <span class="badge bg-success">Approved: <?php echo e($period->submissions_approved_count); ?></span>
                                                <span class="badge bg-danger">Rejected: <?php echo e($period->submissions_rejected_count); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($period->is_active): ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('picket-schedule-periods.edit', $period)); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form action="<?php echo e(route('picket-schedule-periods.destroy', $period)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Hapus periode jadwal piket ini? Semua pengajuan guru pada periode ini juga akan ikut terhapus.')"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div></div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/jszip/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/pdfmake/build/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/pdfmake/build/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')); ?>"></script>

<script>
$(document).ready(function () {
    let table = $("#datatable-buttons").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        order: [],
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/picket-schedules/index.blade.php ENDPATH**/ ?>