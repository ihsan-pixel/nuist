@extends('layouts.mobile')

@section('title', 'Pengaturan')
@section('subtitle', 'Ubah Akun')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f6f8f7;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }

        .page-head {
            background: linear-gradient(135deg, #043F31 0%, #095341 100%);
            color: #fff;
            border-radius: 14px;
            padding: 12px 14px;
            box-shadow: 0 8px 18px rgba(4, 63, 49, 0.16);
            margin-bottom: 12px;
        }

        .page-head h6 {
            font-weight: 600;
            font-size: 11px;
            opacity: 0.85;
            margin-bottom: 2px;
        }

        .page-head h5 {
            font-size: 15px;
            margin-bottom: 0;
        }

        .settings-section {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
            margin-bottom: 12px;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .section-header {
            background: #f8fafc;
            padding: 10px 12px;
            border-bottom: 1px solid #edf1f3;
        }

        .section-header h6 {
            font-weight: 600;
            font-size: 13px;
            color: #1f2937;
            margin: 0;
        }

        .section-content {
            padding: 12px;
        }

        .avatar-section {
            text-align: center;
            margin-bottom: 10px;
        }

        .avatar-section img {
            width: 74px;
            height: 74px;
            border-radius: 50%;
            border: 3px solid rgba(4, 63, 49, 0.12);
            margin-bottom: 10px;
            object-fit: cover;
            background: #f8fafc;
        }

        .avatar-fallback {
            width: 74px;
            height: 74px;
            margin: 0 auto 10px;
            border-radius: 50%;
            border: 3px solid rgba(4, 63, 49, 0.12);
            background: #f8fafc;
            color: #043F31;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .avatar-fallback i {
            font-size: 34px;
        }

        .avatar-section .btn {
            border-radius: 10px;
            font-size: 12px;
            padding: 9px 14px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-label {
            font-size: 11px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            padding: 10px 12px;
            font-size: 12px;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: #095341;
            box-shadow: 0 0 0 0.16rem rgba(9, 83, 65, 0.12);
        }

        .btn-primary {
            background: linear-gradient(135deg, #043F31 0%, #095341 100%);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 10px 12px;
            font-size: 12px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #033529 0%, #074533 100%);
        }

        .alert {
            border-radius: 10px;
            border: none;
            font-size: 11px;
            padding: 8px 12px;
            margin-bottom: 10px;
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

    @php
        $avatarPath = $user->avatar ?? null;
        $hasCustomAvatar = $avatarPath
            && !str_contains($avatarPath, 'avatar-1.jpg')
            && \Illuminate\Support\Facades\Storage::disk('public')->exists($avatarPath);
    @endphp

    <!-- Back Button -->
    <div class="page-head">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Pengaturan</h6>
                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
            </div>
            @if($hasCustomAvatar)
                <img src="{{ asset('storage/' . $avatarPath) }}"
                     class="rounded-circle border border-white" width="36" height="36" alt="User">
            @else
                <div class="d-flex align-items-center justify-content-center rounded-circle border border-white"
                     style="width: 36px; height: 36px; background: rgba(255,255,255,0.12); color: #fff;">
                    <i class="bx bx-user" style="font-size: 20px;"></i>
                </div>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="bx bx-check-circle me-1"></i>{{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="bx bx-error-circle me-1"></i>{{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <i class="bx bx-error-circle me-1"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Avatar Section -->
    <div class="settings-section">
        <div class="section-header">
            <h6><i class="bx bx-camera me-2"></i>Foto Profil</h6>
        </div>
        <div class="section-content">
            <div class="avatar-section">
                @if($hasCustomAvatar)
                    <img src="{{ asset('storage/' . $avatarPath) }}"
                         alt="Current Avatar" id="current-avatar"
                         onerror="this.onerror=null; this.outerHTML='<div id=&quot;current-avatar&quot; class=&quot;avatar-fallback&quot;><i class=&quot;bx bx-user&quot;></i></div>';">
                @else
                    <div id="current-avatar" class="avatar-fallback">
                        <i class="bx bx-user"></i>
                    </div>
                @endif
                <form action="{{ route('mobile.profile.update-avatar') }}" method="POST" enctype="multipart/form-data" id="avatar-form">
                    @csrf
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
            <form action="{{ route('mobile.profile.update-profile') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="form-group">
                    <label for="phone" class="form-label">Nomor HP</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->no_hp) }}" placeholder="Masukkan nomor HP">
                </div>
                <div class="form-group">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $user->tempat_lahir) }}" placeholder="Masukkan tempat lahir">
                </div>
                <div class="form-group">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : '') }}">
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
            <form action="{{ route('mobile.profile.update-password') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="current_password" class="form-label">Password Lama</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required placeholder="Masukkan password lama">
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Minimal 8 karakter, kombinasi huruf besar, kecil, angka & simbol">
                    <div id="password-strength" class="mt-2">
                        <small id="password-strength-text" class="text-muted" style="font-size: 10px;">Password harus mengandung huruf besar, huruf kecil, angka, dan simbol</small>
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
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('avatar-input').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        // Preview the selected image
        const reader = new FileReader();
        reader.onload = function(e) {
            const currentAvatar = document.getElementById('current-avatar');
            if (currentAvatar.tagName === 'IMG') {
                currentAvatar.src = e.target.result;
            } else {
                currentAvatar.outerHTML = `<img src="${e.target.result}" alt="Current Avatar" id="current-avatar" class="avatar-preview-img">`;
            }
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
@endsection
