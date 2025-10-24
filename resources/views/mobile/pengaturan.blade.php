@extends('layouts.mobile')

@section('title', 'Pengaturan')
@section('subtitle', 'Pengaturan Akun')

@section('content')
<div class="container py-3" style="max-width:420px; margin:auto;">
    <style>
        /* Clean, consistent mobile settings styles (aligned with profile view) */
        body { background: #f8f9fb; font-family: 'Poppins', sans-serif; font-size: 13px; }
        .card-custom { background: #fff; border-radius: 12px; box-shadow: 0 6px 18px rgba(14,133,73,0.06); margin-bottom: 12px; overflow: hidden; }
        .card-header-custom { background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); color: #fff; padding: 14px; }
        .card-header-custom h6 { margin:0; font-size:14px; font-weight:700; }
        .card-header-custom small { opacity:0.95; }

        .avatar-lg { text-align:center; margin-bottom:8px; }
        .avatar-lg img { width:88px; height:88px; border-radius:50%; border:3px solid rgba(241,243,244,0.9); object-fit:cover; }

        .info-row { display:flex; justify-content:space-between; align-items:center; padding:12px 14px; border-bottom:1px solid #f1f3f4; }
        .info-label { color:#6c757d; font-weight:600; }
        .info-value { color:#333; font-weight:700; text-align:right; max-width:65%; word-wrap:break-word; }

        .settings-list { padding:10px; }
        .settings-item { display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:10px; background:#fbfcfd; border:1px solid #eef2f3; margin-bottom:10px; cursor:pointer; transition:all .15s ease; }
        .settings-item:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(14,133,73,0.06); }
        .settings-item .left { display:flex; align-items:center; gap:12px; }
        .settings-item .title { font-weight:600; color:#263238; }
        .settings-item .subtitle { font-size:12px; color:#6c757d; }
        .settings-item i.chev { color:#bfc8c6; font-size:20px; }

        .btn-ghost { background:transparent; border:none; padding:0; }
        @media (max-width:420px) { .container { padding-left:10px; padding-right:10px; } }
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
                <h6>Pengaturan Akun</h6>
                <small>Kelola informasi akun Anda</small>
            </div>
            <img src="{{ isset($user->avatar) ? asset('storage/' . $user->avatar) : asset('build/images/users/avatar-11.jpg') }}" class="rounded-circle border border-white" width="40" height="40" alt="User">
        </div>
        <div class="p-3">
            <div class="avatar-lg">
                <img src="{{ isset($user->avatar) ? asset('storage/' . $user->avatar) : asset('build/images/users/avatar-11.jpg') }}" alt="Profile">
                <div class="mt-2">
                    <strong style="font-size:15px">{{ $user->name }}</strong>
                    <div style="font-size:12px;color:#6c757d">{{ $user->email }}</div>
                </div>
            </div>

            <div class="card-custom mb-2">
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nomor HP</div>
                    <div class="info-value">{{ $user->phone ?? 'Belum diatur' }}</div>
                </div>
            </div>

            <div class="settings-list">
                <button type="button" class="settings-item btn-ghost" data-bs-toggle="modal" data-bs-target="#editAccountModal" aria-label="Ubah Email & Nomor HP">
                    <div class="left">
                        <i class="bx bx-user" style="font-size:20px;color:#0e8549"></i>
                        <div>
                            <div class="title">Ubah Email & Nomor HP</div>
                            <div class="subtitle">Perbarui alamat email dan nomor telepon Anda</div>
                        </div>
                    </div>
                    <i class="bx bx-chevron-right chev"></i>
                </button>

                <button type="button" class="settings-item btn-ghost" data-bs-toggle="modal" data-bs-target="#changePasswordModal" aria-label="Ubah Password">
                    <div class="left">
                        <i class="bx bx-lock" style="font-size:20px;color:#f59e0b"></i>
                        <div>
                            <div class="title">Ubah Password</div>
                            <div class="subtitle">Ganti kata sandi lama Anda</div>
                        </div>
                    </div>
                    <i class="bx bx-chevron-right chev"></i>
                </button>

                <button type="button" class="settings-item btn-ghost" data-bs-toggle="modal" data-bs-target="#changeAvatarModal" aria-label="Ubah Foto Profil">
                    <div class="left">
                        <i class="bx bx-camera" style="font-size:20px;color:#06b6d4"></i>
                        <div>
                            <div class="title">Ubah Foto Profil</div>
                            <div class="subtitle">Unggah foto profil baru (max 2MB)</div>
                        </div>
                    </div>
                    <i class="bx bx-chevron-right chev"></i>
                </button>
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
