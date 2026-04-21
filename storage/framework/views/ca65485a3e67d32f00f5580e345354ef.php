<div class="siswa-topbar">
    <div class="siswa-user">
        <div class="siswa-avatar">
            <?php echo e(strtoupper(substr($studentUser->name ?? 'S', 0, 1))); ?>

        </div>
        <div>
            <small><?php echo e($subtitle ?? 'Portal siswa digital'); ?></small>
            <strong><?php echo e($title ?? 'Menu Siswa'); ?></strong>
        </div>
    </div>
    <a href="<?php echo e(route('mobile.siswa.notifikasi')); ?>" class="ghost-btn" style="width:auto; padding:10px 12px;">
        <i class="bx bx-bell"></i>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($notifications ?? collect())->where('is_read', false)->count() > 0): ?>
            <?php echo e(($notifications ?? collect())->where('is_read', false)->count()); ?>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </a>
</div>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/siswa/partials/header.blade.php ENDPATH**/ ?>