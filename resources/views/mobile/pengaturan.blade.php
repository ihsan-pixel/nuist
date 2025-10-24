@extends('layouts.mobile')

@section('title', 'Pengaturan')
@section('subtitle', 'Pengaturan Akun')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        /* Reuse styling similar to profile view to keep mobile UI consistent */
        body { background: #f8f9fb; font-family: 'Poppins', sans-serif; font-size: 12px; }
        .card-custom { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 10px; overflow: hidden; }
        .card-header-custom { background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); color: #fff; padding: 12px; border-radius: 12px; }
        .avatar-lg { text-align: center; margin-bottom: 10px; }
        .avatar-lg img { width: 90px; height: 90px; border-radius: 50%; border: 3px solid #f1f3f4; }
        .item-row { display:flex; justify-content:space-between; align-items:center; padding:12px; border-bottom:1px solid #f1f3f4; }
        .item-label { color:#666; font-weight:600; }
        .item-value { color:#333; font-weight:700; text-align:right; max-width:65%; word-wrap:break-word; }
        .settings-btn { width:100%; display:flex; align-items:center; gap:10px; padding:10px; border-radius:8px; border:1px solid #e9ecef; background:#f8f9fa; color:#333; text-decoration:none; }
    </style>

    @if(session('success'))
    <div class="alert alert-success border-0 rounded-3 mb-3" style="background: rgba(25, 135, 84, 0.08); color: #198754; border-radius: 12px; padding: 10px;">
        <i class="bx bx-check-circle me-1"></i>{{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger border-0 rounded-3 mb-3" style="background: rgba(220, 53, 69, 0.06); color: #dc3545; border-radius: 12px; padding: 10px;">
        <i class="bx bx-error-circle me-1"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card-custom">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Pengaturan Akun</h6>
                <small>Kelola informasi akun Anda</small>
            </div>
            <img src="{{ isset($user->avatar) ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg') }}" class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
        <div class="p-3">
            <div class="avatar-lg text-center mb-2">
                <img src="{{ isset($user->avatar) ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg') }}" alt="Profile">
                <div class="mt-2">
                    <strong>{{ $user->name }}</strong>
                </div>
            </div>

            <div class="card-custom mb-2">
                <div class="item-row">
                    <div class="item-label">Email</div>
                    <div class="item-value">{{ $user->email }}</div>
                </div>
                <div class="item-row">
                    <div class="item-label">Nomor HP</div>
                    <div class="item-value">{{ $user->phone ?? 'Belum diatur' }}</div>
                </div>
            </div>

            <div class="card-custom mb-3">
                <div class="p-2">
                    <button class="settings-btn mb-2" data-bs-toggle="modal" data-bs-target="#editAccountModal">
                        <i class="bx bx-user"></i>
                        Ubah Email & Nomor HP
                    </button>

                    <button class="settings-btn mb-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="bx bx-lock"></i>
                        Ubah Password
                    </button>

                    <button class="settings-btn" data-bs-toggle="modal" data-bs-target="#changeAvatarModal">
                        <i class="bx bx-camera"></i>
                        Ubah Foto Profil
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Account Modal (email + phone) -->
    <div class="modal fade" id="editAccountModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Email & Nomor HP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('mobile.profile.update-account') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="0812xxxx" maxlength="20">
                        </div>
                        <small class="text-muted">Jika Anda mengubah email, verifikasi email mungkin diperlukan kembali.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('mobile.profile.update-password') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Avatar Modal -->
    <div class="modal fade" id="changeAvatarModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Foto Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('mobile.profile.update-avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Foto</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*" required>
                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
