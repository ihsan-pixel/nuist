<?php $__env->startSection('title', 'Detail Nilai Sekolah'); ?>

<?php $__env->startSection('content'); ?>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<?php echo $__env->make('talenta.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('talenta.partials.hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('talenta.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="section-clean">
<div class="container">
    <h2>Detail Nilai Sekolah</h2>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-1">Skor per Dimensi</h6>
                    <ul class="mb-0 small">
                        <li>Struktur: <strong><?php echo e($score->struktur ?? $score->layanan ?? 0); ?></strong></li>
                        <li>Kompetensi: <strong><?php echo e($score->kompetensi ?? $score->tata_kelola ?? 0); ?></strong></li>
                        <li>Perilaku: <strong><?php echo e($score->perilaku ?? $score->jumlah_siswa ?? 0); ?></strong></li>
                        <li>Keterpaduan: <strong><?php echo e($score->keterpaduan ?? $score->jumlah_penghasilan ?? 0); ?></strong></li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">Total Skor: <strong><?php echo e($score->total_skor); ?></strong></p>
                    <p class="mb-0">Level Sekolah: <strong><?php echo e($score->level_sekolah); ?></strong></p>
                    <p class="small text-muted mb-0">Pengirim: <?php echo e(optional($score->submittedBy)->name ?? '-'); ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6>Informasi Sekolah</h6>
                    <p class="mb-0"><strong><?php echo e(optional($score->school)->nama ?? 'N/A'); ?></strong></p>
                    <p class="small text-muted mb-0">ID Sekolah: <?php echo e($score->school_id ?? '-'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mt-3">Jawaban per Pertanyaan</h5>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($answers) && $answers->count()): ?>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width:60px">No</th>
                                <th>Pertanyaan</th>
                                <th style="width:90px">Jawaban</th>
                                <th style="width:120px">Teks Pilihan</th>
                                <th style="width:90px">Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ans): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td style="max-width:440px"><?php echo e(optional($ans->question)->pertanyaan ?? '-'); ?></td>
                                    <td><?php echo e(strtoupper($ans->jawaban ?? '-')); ?></td>
                                    <td class="small text-muted"><?php echo e($ans->choice_text ?? (optional($ans->question)->choice_texts[$ans->jawaban ?? ''] ?? '-')); ?></td>
                                    <td><?php echo e($ans->skor ?? '-'); ?></td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Tidak ada jawaban tersimpan untuk sekolah ini.</div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
</section>

<?php echo $__env->make('talenta.partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/rekap/detail.blade.php ENDPATH**/ ?>