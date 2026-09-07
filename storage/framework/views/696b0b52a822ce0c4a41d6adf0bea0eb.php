<?php $__env->startSection('title', 'Pendataan GTK'); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Pendataan GTK <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php $__env->startSection('css'); ?>
<style>
    .school-card {
        border: 1px solid #e9edf4;
        border-radius: 1rem;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        transition: transform .15s ease, box-shadow .15s ease;
        height: 100%;
    }
    .school-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .75rem 1.5rem rgba(15, 23, 42, .08);
    }
</style>
<?php $__env->stopSection(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h4 class="card-title mb-1">Pendataan GTK</h4>
                        <p class="text-muted mb-0">Pilih sekolah untuk melihat seluruh tenaga pendidik dan melengkapi data GTK.</p>
                    </div>
                    <span class="badge bg-primary fs-6"><?php echo e($madrasahs->count()); ?> sekolah</span>
                </div>

                <div class="row g-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="school-card p-3">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <img src="<?php echo e($madrasah->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($madrasah->logo) ? asset('storage/' . $madrasah->logo) : asset('build/images/logo-light.png')); ?>" alt="<?php echo e($madrasah->name); ?>" class="rounded-3 border" style="width:64px;height:64px;object-fit:cover;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-start justify-content-between gap-2">
                                            <div>
                                                <div class="fw-semibold"><?php echo e($madrasah->name); ?></div>
                                                <div class="text-muted small"><?php echo e($madrasah->kabupaten ?: 'Kabupaten belum diisi'); ?></div>
                                            </div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->scod): ?>
                                                <span class="badge bg-soft-primary text-primary"><?php echo e($madrasah->scod); ?></span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div class="text-muted small mt-2"><?php echo e(\Illuminate\Support\Str::limit($madrasah->alamat ?: 'Alamat belum tersedia', 90)); ?></div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    <a href="<?php echo e(route('pendataan-gtk.show', $madrasah->id)); ?>" class="btn btn-primary btn-sm">
                                        Lihat GTK / Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/masterdata/pendataan-gtk/index.blade.php ENDPATH**/ ?>