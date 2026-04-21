<?php $__env->startSection('title', (isset($question) && $question->id) ? 'Edit Soal' : 'Tambah Soal'); ?>

<?php $__env->startSection('content'); ?>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<?php echo $__env->make('talenta.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>



<!-- Back button (no navbar) -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='<?php echo e(route('talenta.dashboard')); ?>'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #0f172a; margin-top: 20px;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #0f172a; font-size: 14px; margin-top: 20px">Kembali</span>
</div>

<section class="section-clean">
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0"><?php echo e(isset($question) && $question->id ? 'Edit Soal' : 'Tambah Soal'); ?></h2>
                <a href="<?php echo e(route('talenta.questions.index')); ?>" class="btn btn-outline-secondary">Kembali ke Daftar</a>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <li><?php echo e($err); ?></li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST" action="<?php echo e(isset($question) && $question->id ? route('talenta.questions.update', $question) : route('talenta.questions.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($question) && $question->id): ?> <?php echo method_field('PUT'); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">Dimensi / Kategori</label>
                            <select name="kategori" class="form-select">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Struktur','Kompetensi','Perilaku','Keterpaduan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($cat); ?>" <?php echo e((old('kategori', $question->kategori ?? '') == $cat) ? 'selected' : ''); ?>><?php echo e($cat); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pertanyaan</label>
                            <textarea name="pertanyaan" class="form-control" rows="4"><?php echo e(old('pertanyaan', $question->pertanyaan ?? '')); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <p class="small text-muted mb-2">Definisikan teks untuk pilihan A..E dan skor numeric untuk masing-masing pilihan. Contoh: A => teks, skor => 3</p>
                            </div>
                            <?php
                                $existing_texts = old('choice_texts', $question->choice_texts ?? []);
                                $existing_scores = old('choice_scores', $question->choice_scores ?? []);
                            ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['A','B','C','D','E']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pilihan <?php echo e($letter); ?> - Teks</label>
                                    <input type="text" name="choice_texts[<?php echo e($letter); ?>]" class="form-control" value="<?php echo e($existing_texts[$letter] ?? ''); ?>" placeholder="Teks jawaban untuk <?php echo e($letter); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pilihan <?php echo e($letter); ?> - Skor</label>
                                    <input type="number" name="choice_scores[<?php echo e($letter); ?>]" class="form-control" value="<?php echo e($existing_scores[$letter] ?? ''); ?>" placeholder="Skor numeric untuk <?php echo e($letter); ?>">
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top:90px;">
                <div class="card-body">
                    <h6 class="mb-2">Panduan</h6>
                    <p class="small text-muted">Masukkan pertanyaan sesuai dimensi. Pilihan A..E dan skor numeric akan digunakan untuk perhitungan School Level.</p>
                    <hr>
                    <p class="small text-muted mb-0">Kategori yang tersedia:</p>
                    <ul class="small">
                        <li>Struktur</li>
                        <li>Kompetensi</li>
                        <li>Perilaku</li>
                        <li>Keterpaduan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<?php echo $__env->make('talenta.partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/questions/form.blade.php ENDPATH**/ ?>