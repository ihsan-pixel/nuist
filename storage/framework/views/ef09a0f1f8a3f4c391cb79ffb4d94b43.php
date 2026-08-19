<!-- JAVASCRIPT -->
<script src="<?php echo e(asset('build/libs/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/metismenu/metisMenu.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/simplebar/simplebar.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/node-waves/waves.min.js')); ?>"></script>

<script>
    // Script Change Password
    $('#change-password').on('submit', function(event) {
        event.preventDefault();
        var Id = $('#data_id').val();
        var current_password = $('#current-password').val();
        var password = $('#password').val();
        var password_confirm = $('#password-confirm').val();

        $('#current_passwordError').text('');
        $('#passwordError').text('');
        $('#password_confirmError').text('');

        $.ajax({
            url: "<?php echo e(url('update-password')); ?>" + "/" + Id,
            type: "POST",
            data: {
                "current_password": current_password,
                "password": password,
                "password_confirmation": password_confirm,
                "_token": "<?php echo e(csrf_token()); ?>",
            },
            success: function(response) {
                $('#current_passwordError').text('');
                $('#passwordError').text('');
                $('#password_confirmError').text('');

                if (response.isSuccess == false) {
                    $('#current_passwordError').text(response.Message);
                } else if (response.isSuccess == true) {
                    setTimeout(function() {
                        window.location.href = "<?php echo e(route('root')); ?>";
                    }, 1000);
                }
            },
            error: function(response) {
                $('#current_passwordError').text(response.responseJSON.errors.current_password);
                $('#passwordError').text(response.responseJSON.errors.password);
                $('#password_confirmError').text(response.responseJSON.errors.password_confirmation);
            }
        });
    });

</script>

<!-- Script tambahan yang didorong oleh <?php $__env->startPush('scripts'); ?> -->
<?php echo $__env->yieldPushContent('scripts'); ?>

<!-- Leaflet Core -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Leaflet Draw -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<!-- Script halaman yang menggunakan <?php $__env->startSection('script'); ?> -->
<?php echo $__env->yieldContent('script'); ?>   

<!-- App JS utama -->
<script src="<?php echo e(asset('build/js/app.js')); ?>"></script>

<!-- Script tambahan di paling bawah (jika ada) -->
<?php echo $__env->yieldContent('script-bottom'); ?>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/layouts/vendor-scripts.blade.php ENDPATH**/ ?>