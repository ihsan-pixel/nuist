@extends('layouts.mobile')

@section('title', 'Profil')
@section('subtitle', 'Informasi Personal')

@section('content')
@if(session('success'))
<div class="alert alert-success border-0 rounded-3 mb-4">
    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger border-0 rounded-3 mb-4">
    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
</div>
@endif

<!-- Profile Header -->
<div class="card mb-4 shadow-sm">
    <div class="card-body text-center py-4">
        <div class="avatar-lg mx-auto mb-3">
            <img src="{{ isset($user->avatar) ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 alt="" class="img-thumbnail rounded-circle">
        </div>
        <h5 class="mb-1">{{ $user->name }}</h5>
        <p class="text-muted mb-2">{{ $user->email }}</p>
        <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
    </div>
</div>

<!-- Personal Information -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="bx bx-user me-2"></i>Informasi Personal</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted">Nama Lengkap</span>
                    <span class="fw-medium">{{ $user->name }}</span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted">Email</span>
                    <span class="fw-medium">{{ $user->email }}</span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted">Role</span>
                    <span class="fw-medium">{{ ucfirst($user->role) }}</span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted">Madrasah</span>
                    <span class="fw-medium">{{ $user->madrasah?->name ?? 'Belum diatur' }}</span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted">Status Kepegawaian</span>
                    <span class="fw-medium">{{ $user->statusKepegawaian?->name ?? 'Belum diatur' }}</span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted">Ketugasan</span>
                    <span class="fw-medium">{{ $user->ketugasan ?? 'Belum diatur' }}</span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center py-2">
                    <span class="text-muted">Bergabung Sejak</span>
                    <span class="fw-medium">{{ $user->created_at ? $user->created_at->format('d M Y') : 'Tidak diketahui' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Account Settings -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="bx bx-cog me-2"></i>Pengaturan Akun</h6>
    </div>
    <div class="card-body">
        <div class="d-grid gap-2">
            @if(!$user->password_changed)
            <div class="alert alert-warning border-0 rounded-3">
                <i class="bx bx-info-circle me-2"></i>
                <strong>Password belum diubah!</strong> Anda menggunakan password default. Silakan ubah password untuk keamanan akun.
            </div>
            @endif

            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                <i class="bx bx-lock me-2"></i>Ubah Password
            </button>

            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changeAvatarModal">
                <i class="bx bx-camera me-2"></i>Ubah Foto Profil
            </button>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="bx bx-flash me-2"></i>Aksi Cepat</h6>
    </div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-6">
                <a href="{{ route('mobile.presensi') }}" class="btn btn-primary w-100 py-3">
                    <i class="bx bx-check-square d-block fs-4 mb-1"></i>
                    <small>Presensi</small>
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('mobile.jadwal') }}" class="btn btn-info w-100 py-3">
                    <i class="bx bx-calendar d-block fs-4 mb-1"></i>
                    <small>Jadwal</small>
                </a>
            </div>
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

@endsection
