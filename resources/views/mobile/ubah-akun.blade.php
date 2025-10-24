@extends('layouts.mobile')

@section('title', 'Pengaturan')
@section('subtitle', 'Ubah Akun')

@section('content')
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

    <!-- Header -->
    <div class="settings-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Pengaturan</h6>
                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
            </div>
            <img src="{{ isset($user->avatar) ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
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
                <img src="{{ isset($user->avatar) ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                     alt="Current Avatar" id="current-avatar">
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
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Masukkan nomor HP">
                </div>
                <button type="submit" class="btn btn-primary w-100" id="save-profile-btn">
                    <i class="bx bx-save me-1"></i>Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.getElementById('avatar-input').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        // Preview the selected image
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('current-avatar').src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);

        // Auto-submit the form
        document.getElementById('avatar-form').submit();
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
