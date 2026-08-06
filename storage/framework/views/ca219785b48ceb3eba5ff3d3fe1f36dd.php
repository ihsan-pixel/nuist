<?php $__env->startSection('title', 'Isi Assessment - School Level'); ?>

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
        <h2 class="section-title">Instrumen Deteksi Model Layanan Sekolah</h2>
        <p class="section-subtitle">Jawab pertanyaan berikut sesuai pada sekolah Anda!</p>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="alert alert-success mt-3"><?php echo e(session('success')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form id="assessmentForm" method="POST" action="<?php echo e(route('talenta.assessment.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="row">
                <div class="col-12 col-lg-9">

                    <?php $currentKategori = null; $cardOpen = false; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentKategori !== $q->kategori): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cardOpen): ?>
                                </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="card shadow-sm mb-4 border-0">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0" style="text-transform: capitalize;"><?php echo e(str_replace('_',' ', $q->kategori)); ?></h5>
                                </div>
                                <div class="card-body">
                            <?php $cardOpen = true; $currentKategori = $q->kategori; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="mb-3 question-block" data-qid="<?php echo e($q->id); ?>">
                            <label class="form-label fw-semibold"><?php echo e($q->pertanyaan); ?></label>
                            <div class="d-flex flex-column gap-2">
                                <?php
                                    $choices = $q->choice_texts ?? ['A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E'];
                                ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['A','B','C','D','E']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answers[<?php echo e($q->id); ?>]" id="answer_<?php echo e($q->id); ?>_<?php echo e($letter); ?>" value="<?php echo e($letter); ?>" required <?php if(isset($existingAnswers[$q->id]) && strtoupper($existingAnswers[$q->id]) == $letter): ?> checked <?php endif; ?>>
                                        <label class="form-check-label" for="answer_<?php echo e($q->id); ?>_<?php echo e($letter); ?>"><strong><?php echo e($letter); ?>.</strong> <?php echo e($choices[$letter] ?? $letter); ?></label>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cardOpen): ?>
                        </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="d-flex justify-content-center mt-3">
                        <button id="submitBtn" type="submit" class="btn btn-primary btn-lg px-5">Simpan Jawaban</button>
                    </div>

                </div>

                <div class="col-12 col-lg-3">
                    <div class="card shadow-sm mb-4 border-0 sticky-top" style="top: 90px;">
                        <div class="card-body">
                            <h6 class="mb-2">Ringkasan Jawaban</h6>
                            <p class="text-muted small mb-1">Terjawab: <span id="answeredCount">0</span> / <span id="totalCount">0</span></p>
                            <div class="progress mb-2" style="height:10px;">
                                <div id="answeredProgress" class="progress-bar bg-primary" role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="small text-muted">Pastikan semua pertanyaan dijawab sebelum menyimpan untuk hasil yang akurat.</p>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</section>

<?php echo $__env->make('talenta.partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionBlocks = document.querySelectorAll('.question-block');
    const totalCountEl = document.getElementById('totalCount');
    const answeredCountEl = document.getElementById('answeredCount');
    const answeredProgress = document.getElementById('answeredProgress');
    const form = document.getElementById('assessmentForm');

    const total = questionBlocks.length;
    if (totalCountEl) totalCountEl.textContent = total;

    function update() {
        let answered = 0;
        questionBlocks.forEach(qb => {
            const qid = qb.dataset.qid;
            const checked = qb.querySelector(`input[name="answers[${qid}]"]:checked`);
            if (checked) answered++;
        });
        if (answeredCountEl) answeredCountEl.textContent = answered;
        const pct = total ? Math.round((answered / total) * 100) : 0;
        if (answeredProgress) {
            answeredProgress.style.width = pct + '%';
            answeredProgress.setAttribute('aria-valuenow', pct);
        }
    }

    questionBlocks.forEach(qb => {
        qb.querySelectorAll('input[type=radio]').forEach(r => r.addEventListener('change', update));
    });

    update();

    if (form) {
        form.addEventListener('submit', function(e) {
            const answered = parseInt(answeredCountEl.textContent || 0, 10);
            if (answered < total) {
                e.preventDefault();
                if (confirm(`Masih ada ${total - answered} pertanyaan yang belum dijawab. Lanjutkan dan kirim jawaban?`)) {
                    form.submit();
                }
            }
        });
    }
});
</script>
<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/assessment/fill.blade.php ENDPATH**/ ?>