@extends('layouts.mobile')

@section('title', 'Ubah Akun')
@section('subtitle', 'Ubah Email & Nomor HP')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }

        .form-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 60px;
        }

        .form-section h5 {
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 500;
            color: #666;
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 10px 12px;
            font-size: 12px;
        }

        .form-control:focus {
            border-color: #004b4c;
            box-shadow: 0 0 0 0.2rem rgba(0, 75, 76, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 12px;
            font-weight: 600;
            width: 100%;
        }

        .btn-secondary {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            color: #333;
            border-radius: 8px;
            padding: 12px;
            font-size: 12px;
            font-weight: 600;
            width: 100%;
            margin-bottom: 10px;
        }

        .alert-custom {
            background: rgba(255, 193, 7, 0.1);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.2);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 11px;
            margin-bottom: 12px;
        }

        .alert-custom i {
            margin-right: 6px;
        }
    </style>

    @if(session('success'))
    <div class="alert alert-success border-0 rounded-3 mb-3" style="background: rgba(25, 135, 84, 0.1); color: #198754; border-radius: 12px; padding: 10px;">
        <i class="bx bx-check-circle me-1"></i>{{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger border-0 rounded-3 mb-3" style="background: rgba(220, 53, 69, 0.1); color: #dc3545; border-radius: 12px; padding: 10px;">
        <i class="bx bx-error-circle me-1"></i>{{ session('error') }}
    </div>
    @endif

    <div class="form-section">
        <h5><i class="bx bx-user me-2"></i>Ubah Data Akun</h5>

        <form action="{{ route('mobile.profile.update-account') }}" method="POST" id="updateAccountForm">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nomor HP</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->no_hp) }}" placeholder="0812xxxx" maxlength="20">
            </div>
            <div class="mb-3">
                <label class="form-label">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $user->tempat_lahir) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" maxlength="500">{{ old('alamat', $user->alamat) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Pendidikan Terakhir</label>
                <input type="text" name="pendidikan_terakhir" class="form-control" value="{{ old('pendidikan_terakhir', $user->pendidikan_terakhir) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Program Studi</label>
                <input type="text" name="program_studi" class="form-control" value="{{ old('program_studi', $user->program_studi) }}">
            </div>
            <small class="text-muted d-block mb-3">Jika Anda mengubah email, verifikasi email mungkin diperlukan kembali.</small>

            <button type="submit" class="btn btn-primary" id="submitBtn">Simpan Perubahan</button>
            <a href="{{ route('mobile.profile') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('updateAccountForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';
            }
        });
    }
});
</script>
