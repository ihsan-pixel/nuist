<?php $__env->startSection('title', 'Pengaturan'); ?>
<?php $__env->startSection('subtitle', 'Ubah Akun'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }

        .settings-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 12px;
            padding: 12px 10px;
            box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
            margin-bottom: 10px;
        }

        .settings-header h6 {
            font-weight: 600;
            font-size: 12px;
        }

        .settings-header h5 {
            font-size: 14px;
        }

        .settings-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
            overflow: hidden;
        }

        .section-header {
            background: #f8f9fa;
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .section-header h6 {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin: 0;
        }

        .section-content {
            padding: 12px;
        }

        .avatar-section {
            text-align: center;
            margin-bottom: 12px;
        }

        .avatar-section img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #e9ecef;
            margin-bottom: 12px;
        }

        .avatar-section .btn {
            border-radius: 8px;
            font-size: 12px;
            padding: 8px 16px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 10px 12px;
            font-size: 12px;
        }

        .form-control:focus {
            border-color: #556ee6;
            box-shadow: 0 0 0 0.2rem rgba(85, 110, 230, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 12px;
            font-size: 12px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        }

        .alert {
            border-radius: 8px;
            border: none;
            font-size: 11px;
            padding: 8px 12px;
            margin-bottom: 12px;
        }

        .alert-success {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .alert-warning {
            background: rgba(255, 193, 7, 0.1);
            color: #856404;
        }
    </style>

    <!-- Back Button -->
    <div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
        <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <span class="fw-bold" style="color: #004b4c; font-size: 12px;">Kembali</span>
    </div>

    <!-- Header -->
    <div class="settings-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Pengaturan</h6>
                <h5 class="fw-bold mb-0"><?php echo e($user->name); ?></h5>
            </div>
            <img src="<?php echo e(isset($user->avatar) ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg')); ?>"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success">
        <i class="bx bx-check-circle me-1"></i><?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-danger">
        <i class="bx bx-error-circle me-1"></i><?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <i class="bx bx-error-circle me-1"></i>
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- Avatar Section -->
    <div class="settings-section">
        <div class="section-header">
            <h6><i class="bx bx-camera me-2"></i>Foto Profil</h6>
        </div>
        <div class="section-content">
            <div class="avatar-section">
                <img src="<?php echo e(isset($user->avatar) ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg')); ?>"
                     alt="Current Avatar" id="current-avatar">
                <form action="<?php echo e(route('mobile.profile.update-avatar')); ?>" method="POST" enctype="multipart/form-data" id="avatar-form">
                    <?php echo csrf_field(); ?>
                    <input type="file" name="avatar" id="avatar-input" accept="image/*" style="display: none;">
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('avatar-input').click();">
                        <i class="bx bx-camera me-1"></i>Ubah Foto
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Profile Section -->
    <div class="settings-section">
        <div class="section-header">
            <h6><i class="bx bx-user me-2"></i>Ubah Profil</h6>
        </div>
        <div class="section-content">
            <form action="<?php echo e(route('mobile.profile.update-profile')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone" class="form-label">Nomor HP</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo e(old('phone', $user->no_hp)); ?>" placeholder="Masukkan nomor HP">
                </div>
                <div class="form-group">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?php echo e(old('tempat_lahir', $user->tempat_lahir)); ?>" placeholder="Masukkan tempat lahir">
                </div>
                <div class="form-group">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : '')); ?>">
                </div>
                <button type="submit" class="btn btn-primary w-100" id="save-profile-btn">
                    <i class="bx bx-save me-1"></i>Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <!-- Password Section -->
    <div class="settings-section">
        <div class="section-header">
            <h6><i class="bx bx-lock me-2"></i>Ubah Password</h6>
        </div>
        <div class="section-content">
            <form action="<?php echo e(route('mobile.profile.update-password')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="current_password" class="form-label">Password Lama</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required placeholder="Masukkan password lama">
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Minimal 8 karakter, kombinasi huruf besar, kecil, angka & simbol">
                    <div id="password-strength" class="mt-2">
                        <small id="password-strength-text" class="text-muted">Password harus mengandung huruf besar, huruf kecil, angka, dan simbol</small>
                        <div class="progress mt-1" style="height: 6px;">
                            <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%; background-color: #dc3545;"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password baru">
                </div>
                <button type="submit" class="btn btn-primary w-100" id="save-password-btn">
                    <i class="bx bx-save me-1"></i>Ubah Password
                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('avatar-input').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        // Preview the selected image
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('current-avatar').src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);

        // Auto-submit the form via AJAX
        const form = document.getElementById('avatar-form');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('success', data.message || 'Foto profil berhasil diperbarui');
                // Auto reload page after 1 second
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showMessage('error', data.message || 'Terjadi kesalahan saat mengunggah foto');
            }
        })
        .catch(error => {
            showMessage('error', 'Terjadi kesalahan saat mengunggah foto');
            console.error('Error:', error);
        });
    }
});

// Handle profile form submission with AJAX
document.getElementById('save-profile-btn').addEventListener('click', function(e) {
    e.preventDefault();

    const form = document.querySelector('form[action*="update-profile"]');
    const formData = new FormData(form);

    // Show loading state
    const btn = this;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Menyimpan...';
    btn.disabled = true;

    // Submit form via AJAX
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Reset button
        btn.innerHTML = originalText;
        btn.disabled = false;

        if (data.success) {
            // Show success message
            showMessage('success', data.message || 'Profil berhasil diperbarui');
            // Auto reload page after 2 seconds
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            // Show error message
            showMessage('error', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        // Reset button
        btn.innerHTML = originalText;
        btn.disabled = false;
        showMessage('error', 'Terjadi kesalahan saat menyimpan');
        console.error('Error:', error);
    });
});

// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');

    let strength = 0;
    let feedback = [];

    // Length check
    if (password.length >= 8) {
        strength += 25;
    } else {
        feedback.push('Minimal 8 karakter');
    }

    // Lowercase check
    if (/[a-z]/.test(password)) {
        strength += 25;
    } else {
        feedback.push('Huruf kecil');
    }

    // Uppercase check
    if (/[A-Z]/.test(password)) {
        strength += 25;
    } else {
        feedback.push('Huruf besar');
    }

    // Number check
    if (/\d/.test(password)) {
        strength += 12.5;
    } else {
        feedback.push('Angka');
    }

    // Special character check
    if (/[@$!%*?&]/.test(password)) {
        strength += 12.5;
    } else {
        feedback.push('Simbol (@$!%*?&)');
    }

    // Update progress bar
    strengthBar.style.width = strength + '%';

    // Update color and text
    if (strength < 50) {
        strengthBar.style.backgroundColor = '#dc3545'; // Red
        strengthText.textContent = 'Lemah: ' + feedback.join(', ');
        strengthText.style.color = '#dc3545';
    } else if (strength < 75) {
        strengthBar.style.backgroundColor = '#ffc107'; // Yellow
        strengthText.textContent = 'Sedang: Perlu ' + feedback.join(', ');
        strengthText.style.color = '#ffc107';
    } else if (strength < 100) {
        strengthBar.style.backgroundColor = '#0d6efd'; // Blue
        strengthText.textContent = 'Kuat: Perlu ' + feedback.join(', ');
        strengthText.style.color = '#0d6efd';
    } else {
        strengthBar.style.backgroundColor = '#198754'; // Green
        strengthText.textContent = 'Sangat Kuat: Password memenuhi semua kriteria!';
        strengthText.style.color = '#198754';
    }
});

// Handle password form submission with AJAX
document.getElementById('save-password-btn').addEventListener('click', function(e) {
    e.preventDefault();

    const form = document.querySelector('form[action*="update-password"]');
    const formData = new FormData(form);

    // Show loading state
    const btn = this;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Mengubah...';
    btn.disabled = true;

    // Submit form via AJAX
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Reset button
        btn.innerHTML = originalText;
        btn.disabled = false;

        if (data.success) {
            // Show success SweetAlert
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message || 'Password berhasil diperbarui',
                confirmButtonColor: '#004b4c',
                confirmButtonText: 'OK'
            });
            // Clear form
            form.reset();
            // Reset password strength indicator
            document.getElementById('password-strength-bar').style.width = '0%';
            document.getElementById('password-strength-text').textContent = 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol';
            document.getElementById('password-strength-text').style.color = '#6c757d';
        } else {
            // Show error SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message || 'Terjadi kesalahan saat mengubah password',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        // Reset button
        btn.innerHTML = originalText;
        btn.disabled = false;
        // Show error SweetAlert
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan!',
            text: 'Terjadi kesalahan saat mengubah password',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK'
        });
        console.error('Error:', error);
    });
});

function showMessage(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());

    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'}`;
    alertDiv.innerHTML = `<i class="bx bx-${type === 'success' ? 'check' : 'error'}-circle me-1"></i>${message}`;

    // Insert at the top of the container
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);

    // Auto remove after 3 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/ubah-akun.blade.php ENDPATH**/ ?>