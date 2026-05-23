<?php $__env->startSection('title', 'Ubah Password'); ?>
<?php $__env->startSection('subtitle', 'Update Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            background-color: #f8f9fb;
        }

        .form-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #333;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            font-size: 13px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background: #fff;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #004b4c;
            box-shadow: 0 0 0 0.2rem rgba(0, 75, 76, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #003838 0%, #0a6b3d 100%);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle .toggle-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            font-size: 18px;
        }

        .alert-custom {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.2);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 11px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .alert-custom i {
            margin-right: 8px;
            font-size: 16px;
        }

        .alert-success-custom {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
            border: 1px solid rgba(25, 135, 84, 0.2);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 11px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .alert-success-custom i {
            margin-right: 8px;
            font-size: 16px;
        }

        .back-link {
            display: flex;
            align-items: center;
            color: #004b4c;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 16px;
        }

        .back-link:hover {
            color: #003838;
        }

        .back-link i {
            font-size: 18px;
            margin-right: 4px;
        }
    </style>

    <!-- Back Link -->
    <a href="<?php echo e(route('mobile.pengurus.profile')); ?>" class="back-link">
        <i class="bx bx-arrow-back"></i>
        Kembali ke Profil
    </a>

    <!-- Header -->
    <div class="text-center mb-4">
        <h5 class="fw-bold text-dark mb-1" style="font-size: 18px;">Ubah Password</h5>
        <small class="text-muted" style="font-size: 12px;">Perbarui password akun Anda</small>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
    <div class="alert-custom">
        <i class="bx bx-error-circle"></i><?php echo e(session('error')); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="alert-success-custom">
        <i class="bx bx-check-circle"></i><?php echo e(session('success')); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Password Form -->
    <div class="form-card">
        <form action="<?php echo e(route('mobile.pengurus.update-password')); ?>" method="POST" id="passwordForm">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label class="form-label" for="password_lama">Password Lama</label>
                <div class="password-toggle">
                    <input type="password" name="password_lama" id="password_lama" class="form-control" placeholder="Masukkan password lama" required>
                    <i class="bx bx-eye toggle-icon" onclick="togglePassword('password_lama')"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password_baru">Password Baru</label>
                <div class="password-toggle">
                    <input type="password" name="password_baru" id="password_baru" class="form-control" placeholder="Masukkan password baru (min. 6 karakter)" required minlength="6">
                    <i class="bx bx-eye toggle-icon" onclick="togglePassword('password_baru')"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password_baru_confirmation">Konfirmasi Password Baru</label>
                <div class="password-toggle">
                    <input type="password" name="password_baru_confirmation" id="password_baru_confirmation" class="form-control" placeholder="Konfirmasi password baru" required>
                    <i class="bx bx-eye toggle-icon" onclick="togglePassword('password_baru_confirmation')"></i>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bx bx-save me-2"></i>
                Simpan Password
            </button>
        </form>
    </div>
</div>

<script>
    // Toggle password visibility
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.parentElement.querySelector('.toggle-icon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bx-eye');
            icon.classList.add('bx-eye-off');
        } else {
            input.type = 'password';
            icon.classList.remove('bx-eye-off');
            icon.classList.add('bx-eye');
        }
    }

    // Form validation
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        const passwordBaru = document.getElementById('password_baru').value;
        const konfirmasi = document.getElementById('password_baru_confirmation').value;

        if (passwordBaru !== konfirmasi) {
            e.preventDefault();
            Swal.fire({
                title: 'Error',
                text: 'Password baru dan konfirmasi password tidak cocok.',
                icon: 'error',
                confirmButtonColor: '#004b4c'
            });
            return;
        }

        if (passwordBaru.length < 6) {
            e.preventDefault();
            Swal.fire({
                title: 'Error',
                text: 'Password baru minimal 6 karakter.',
                icon: 'error',
                confirmButtonColor: '#004b4c'
            });
            return;
        }
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.mobile-pengurus', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/pengurus/ubah-password.blade.php ENDPATH**/ ?>