<?php if (! $__env->hasRenderedOnce('e1f00f57-b180-4fe2-a064-7f768fcd42e2')): $__env->markAsRenderedOnce('e1f00f57-b180-4fe2-a064-7f768fcd42e2'); ?>
    <?php $__env->startPush('scripts'); ?>
        <link rel="stylesheet" href="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>">
        <script src="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof Swal === 'undefined') {
                    return;
                }

                const flashMessages = [
                    { icon: 'success', title: 'Berhasil', text: <?php echo json_encode(session('success'), 15, 512) ?> },
                    { icon: 'error', title: 'Terjadi kesalahan', text: <?php echo json_encode(session('error'), 15, 512) ?> },
                    { icon: 'warning', title: 'Perhatian', text: <?php echo json_encode(session('warning'), 15, 512) ?> },
                    { icon: 'info', title: 'Informasi', text: <?php echo json_encode(session('info'), 15, 512) ?> },
                ].filter((item) => item.text);

                const validationErrors = <?php echo json_encode($errors->all(), 15, 512) ?>;

                if (validationErrors.length) {
                    flashMessages.unshift({
                        icon: 'error',
                        title: 'Validasi gagal',
                        html: '<ul style="text-align:left;padding-left:18px;margin:0;">' + validationErrors.map((error) => `<li>${error}</li>`).join('') + '</ul>',
                    });
                }

                const showQueue = async () => {
                    for (const message of flashMessages) {
                        await Swal.fire({
                            confirmButtonColor: '#0e8549',
                            ...message,
                        });
                    }
                };

                showQueue();

                document.querySelectorAll('form[data-sk-swal-confirm]').forEach((form) => {
                    if (form.dataset.skSwalBound === '1') {
                        return;
                    }

                    form.dataset.skSwalBound = '1';

                    form.addEventListener('submit', function (event) {
                        event.preventDefault();

                        Swal.fire({
                            icon: form.dataset.skSwalIcon || 'warning',
                            title: form.dataset.skSwalTitle || 'Yakin melanjutkan?',
                            text: form.dataset.skSwalText || 'Tindakan ini tidak bisa dibatalkan.',
                            showCancelButton: true,
                            confirmButtonText: form.dataset.skSwalConfirmText || 'Ya, lanjutkan',
                            cancelButtonText: form.dataset.skSwalCancelText || 'Batal',
                            confirmButtonColor: '#0e8549',
                            cancelButtonColor: '#94a3b8',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/sk-yayasan/partials/sweet-alert.blade.php ENDPATH**/ ?>