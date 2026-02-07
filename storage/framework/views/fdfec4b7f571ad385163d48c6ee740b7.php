<?php $__env->startSection('title', 'Kelengkapan Data Madrasah'); ?>

<?php $__env->startSection('content'); ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kabupatenOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kabupaten): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0 text-white">Kabupaten: <?php echo e($kabupaten); ?></h4>
                <a href="<?php echo e(route('admin.data_madrasah.export', ['kabupaten' => $kabupaten])); ?>" class="btn btn-success btn-sm">
                    Export Excel
                </a>
            </div>
            <div class="card-body">
                <table id="datatable-<?php echo e(Str::slug($kabupaten)); ?>" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>SCOD</th>
                            <th>Nama Madrasah</th>
                            <th>Alamat</th>
                            <th>Logo</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Map Link</th>
                            <th>Polygon (koordinat)</th>
                            <th>Hari KBM</th>
                            <th>Status Guru</th>
                            <th>Kelengkapan (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs[$kabupaten] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td><?php echo e($madrasah->scod); ?></td>
                            <td><?php echo e($madrasah->name); ?></td>
                            <td style="font-size: 20px; text-align: center;"><?php echo $madrasah->field_status['alamat'] ?? '❌'; ?></td>
                            <td style="font-size: 20px; text-align: center;"><?php echo $madrasah->field_status['logo'] ?? '❌'; ?></td>
                            <td style="font-size: 20px; text-align: center;"><?php echo $madrasah->field_status['latitude'] ?? '❌'; ?></td>
                            <td style="font-size: 20px; text-align: center;"><?php echo $madrasah->field_status['longitude'] ?? '❌'; ?></td>
                            <td style="font-size: 20px; text-align: center;"><?php echo $madrasah->field_status['map_link'] ?? '❌'; ?></td>
                            <td style="font-size: 20px; text-align: center;">
                                <?php echo $madrasah->field_status['polygon_koordinat'] ?? '❌'; ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->enable_dual_polygon && $madrasah->field_status['polygon_koordinat_2'] === '✅'): ?>
                                    <br><small class="text-success">+ Dual</small>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td style="font-size: 20px; text-align: center;"><?php echo $madrasah->field_status['hari_kbm'] ?? '❌'; ?></td>
                            <td style="font-size: 20px; text-align: center;"><?php echo $madrasah->field_status['status_guru'] ?? '❌'; ?></td>
                            <td style="font-weight: bold; text-align: center;"><?php echo e($madrasah->completeness_percentage); ?>%</td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

<!-- Table for Jumlah Tenaga Pendidik -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0">Jumlah Tenaga Pendidik</h4>
            </div>
            <div class="card-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kabupatenOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kabupaten): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <h5 class="mt-4 mb-3"><?php echo e($kabupaten); ?></h5>
                <table id="datatable-tenaga-<?php echo e(Str::slug($kabupaten)); ?>" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2" class="text-center align-middle">SCOD</th>
                            <th rowspan="2" class="text-center align-middle">Nama Sekolah/Madrasah</th>
                            <th colspan="10" class="text-center">Jumlah Tenaga Pendidik</th>
                        </tr>
                        <tr>
                            <th class="text-center">PNS Sertifikasi</th>
                            <th class="text-center">PNS Non Sertifikasi</th>
                            <th class="text-center">GTY Sertifikasi Inpassing</th>
                            <th class="text-center">GTY Sertifikasi</th>
                            <th class="text-center">GTY</th>
                            <th class="text-center">GTT</th>
                            <th class="text-center">PTY</th>
                            <th class="text-center">PTT</th>
                            <th class="text-center">Tidak Diketahui</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tenagaPendidikData[$kabupaten] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td class="text-center"><?php echo e($data['scod']); ?></td>
                            <td><?php echo e($data['name']); ?></td>
                            <td class="text-center"><?php echo e($data['pns_sertifikasi']); ?></td>
                            <td class="text-center"><?php echo e($data['pns_non_sertifikasi']); ?></td>
                            <td class="text-center"><?php echo e($data['gty_sertifikasi_inpassing']); ?></td>
                            <td class="text-center"><?php echo e($data['gty_sertifikasi']); ?></td>
                            <td class="text-center"><?php echo e($data['gty']); ?></td>
                            <td class="text-center"><?php echo e($data['gtt']); ?></td>
                            <td class="text-center"><?php echo e($data['pty']); ?></td>
                            <td class="text-center"><?php echo e($data['ptt']); ?></td>
                            <td class="text-center"><?php echo e($data['tidak_diketahui']); ?></td>
                            <td class="text-center font-weight-bold"><?php echo e($data['total']); ?></td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        <?php $__currentLoopData = $kabupatenOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kabupaten): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        $('#datatable-<?php echo e(Str::slug($kabupaten)); ?>').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'pdf', 'print', 'colvis'
            ],
            responsive: true,
            order: [[0, 'asc']]
        });
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/admin/data_madrasah.blade.php ENDPATH**/ ?>