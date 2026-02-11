<div id="<?php echo e($config['slug']); ?>"
     class="area-content <?php echo e($first ? 'active' : ''); ?>">

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($materi->tanggal_materi > now()): ?>

        <?php echo $__env->make('talenta.partials.warning', [
            'nama' => $config['name'],
            'tanggal' => $materi->tanggal_materi
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


    
    <div class="sub-tabs">

        <button class="sub-tab-btn active"
                onclick="openSubTab(event,'<?php echo e($config['slug']); ?>-onsite')">
            On Site
        </button>

        <button class="sub-tab-btn"
                onclick="openSubTab(event,'<?php echo e($config['slug']); ?>-terstruktur')">
            Terstruktur
        </button>

        <button class="sub-tab-btn"
                onclick="openSubTab(event,'<?php echo e($config['slug']); ?>-kelompok')">
            Kelompok
        </button>

    </div>


    <?php echo $__env->make('talenta.partials.forms.onsite', ['config' => $config], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('talenta.partials.forms.terstruktur', ['config' => $config], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('talenta.partials.forms.kelompok', ['config' => $config], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</div>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/partials/area.blade.php ENDPATH**/ ?>