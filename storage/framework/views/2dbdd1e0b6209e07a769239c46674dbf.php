<div class="card" style="border:2px solid #dc3545">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submissionClosed ?? false): ?>
        <h3 style="color:#dc3545">
            Batas waktu pengiriman tugas telah berakhir
        </h3>

        <p>
            Pengiriman file untuk semua materi sudah ditutup dan tidak dapat disimpan lagi ke database.
        </p>
    <?php else: ?>
        <h3 style="color:#dc3545">
            Materi <?php echo e($nama); ?> belum terlaksana
        </h3>

        <p>
            Akan dilaksanakan:
            <strong><?php echo e($tanggal->format('d F Y')); ?></strong>
        </p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/partials/warning.blade.php ENDPATH**/ ?>