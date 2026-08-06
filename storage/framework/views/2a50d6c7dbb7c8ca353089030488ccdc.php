<?php $__env->startSection('title', 'Rekap School Level'); ?>

<?php $__env->startSection('content'); ?>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<?php echo $__env->make('talenta.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<!-- Back button (no navbar) -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='<?php echo e(route('talenta.dashboard')); ?>'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #0f172a; margin-top: 20px;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #0f172a; font-size: 14px; margin-top:20px">Kembali</span>
</div>

<section class="section-clean">
<div class="container">
    <div class="row">
        <div class="col-lg-9">
            <h2 class="mb-3">Rekap Instrumen Deteksi Model Layanan Sekolah</h2>

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-1">Rata-rata Struktur</h6>
                            <h3 class="mb-0"><?php echo e($avgStruktur ?? 0); ?></h3>
                            <p class="small text-muted mb-0">dari <?php echo e($totalSchools ?? 0); ?> Talenta</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-1">Rata-rata Kompetensi</h6>
                            <h3 class="mb-0"><?php echo e($avgKompetensi ?? 0); ?></h3>
                            <p class="small text-muted mb-0">dari <?php echo e($totalSchools ?? 0); ?> Talenta</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-1">Rata-rata Perilaku</h6>
                            <h3 class="mb-0"><?php echo e($avgPerilaku ?? 0); ?></h3>
                            <p class="small text-muted mb-0">dari <?php echo e($totalSchools ?? 0); ?> Talenta</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-1">Rata-rata Keterpaduan</h6>
                            <h3 class="mb-0"><?php echo e($avgKeterpaduan ?? 0); ?></h3>
                            <p class="small text-muted mb-0">dari <?php echo e($totalSchools ?? 0); ?> Talenta</p>
                        </div>
                    </div>
                </div>
            </div>

            

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Sekolah</th>
                                    <th>Struktur</th>
                                    <th>Kompetensi</th>
                                    <th>Perilaku</th>
                                    <th>Keterpaduan</th>
                                    <th>Total Nilai</th>
                                    <th>Level</th>
                                    <th style="width:120px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr>
                                    <td>
                                        <?php echo e(optional($s->school)->nama ?? 'N/A'); ?>

                                        <div class="small text-muted">Pengirim: <?php echo e(optional($s->submittedBy)->name ?? '-'); ?></div>
                                    </td>
                                    <td><?php echo e($s->struktur ?? $s->layanan ?? 0); ?></td>
                                    <td><?php echo e($s->kompetensi ?? $s->tata_kelola ?? 0); ?></td>
                                    <td><?php echo e($s->perilaku ?? $s->jumlah_siswa ?? 0); ?></td>
                                    <td><?php echo e($s->keterpaduan ?? $s->jumlah_penghasilan ?? 0); ?></td>
                                    <td><?php echo e($s->total_skor); ?></td>
                                    <td><?php echo e($s->level_sekolah); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('talenta.rekap.pdf', $s->id)); ?>" target="_blank" class="btn btn-sm btn-secondary">PDF View</a>
                                    </td>
                                </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3"><?php echo e($scores->links()); ?></div>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm border-0 sticky-top" style="top:90px;">
                <div class="card-body">
                    <h6 class="mb-2">Ringkasan</h6>
                    <p class="small text-muted mb-2">Total Sekolah: <strong><?php echo e($scores->total() ?? $scores->count()); ?></strong></p>
                    <p class="small text-muted mb-1">Distribusi Level:</p>
                    <ul class="small list-unstyled">
                        <li class="mb-1">A: <strong><?php echo e(\App\Models\SchoolScore::where('level_sekolah','A')->count()); ?></strong></li>
                        <li class="mb-1">B: <strong><?php echo e(\App\Models\SchoolScore::where('level_sekolah','B')->count()); ?></strong></li>
                        <li class="mb-1">C: <strong><?php echo e(\App\Models\SchoolScore::where('level_sekolah','C')->count()); ?></strong></li>
                        <li class="mb-1">D: <strong><?php echo e(\App\Models\SchoolScore::where('level_sekolah','D')->count()); ?></strong></li>
                    </ul>
                    <p class="small text-muted">Gunakan tombol <strong>PDF View</strong> untuk melihat rekap lengkap per sekolah dan mencetaknya.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<?php echo $__env->make('talenta.partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/rekap/index.blade.php ENDPATH**/ ?>