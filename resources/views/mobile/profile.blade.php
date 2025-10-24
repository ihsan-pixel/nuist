@extends('layouts.mobile')

@section('title', 'Profil')
@section('subtitle', 'Informasi Personal')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }

        .profile-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 12px;
            padding: 12px 10px;
            box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
            margin-bottom: 10px;
        }

        .profile-header h6 {
            font-weight: 600;
            font-size: 12px;
        }

        .profile-header h5 {
            font-size: 14px;
        }

        .profile-avatar {
            background: #fff;
            border-radius: 12px;
            padding: 20px 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
            text-align: center;
        }

        .profile-avatar img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #e9ecef;
            margin-bottom: 12px;
        }

        .profile-avatar h5 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 4px;
            color: #333;
        }

        .profile-avatar p {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }

        .role-badge {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .info-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
            overflow: hidden;
        }

        .info-header {
            background: #f8f9fa;
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .info-header h6 {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin: 0;
        }

        .info-content {
            padding: 12px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }

        .info-value {
            font-size: 12px;
            color: #333;
            font-weight: 600;
            text-align: right;
            max-width: 60%;
            word-wrap: break-word;
        }

        .settings-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 60px;
        }

        .settings-header {
            background: #f8f9fa;
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .settings-header h6 {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin: 0;
        }

        .settings-content {
            padding: 12px;
        }

        .settings-button {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 10px;
            text-decoration: none;
            color: #333;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 8px;
            transition: all 0.2s;
        }

        .settings-button:hover {
            background: #e9ecef;
            color: #333;
        }

        .settings-button i {
            font-size: 16px;
            margin-right: 8px;
        }

        .quick-actions {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 60px;
        }

        .quick-actions-header {
            background: #f8f9fa;
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .quick-actions-header h6 {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin: 0;
        }

        .quick-actions-content {
            padding: 12px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .action-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            border-radius: 8px;
            padding: 12px 8px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 500;
            text-align: center;
            transition: all 0.2s;
        }

        .action-button:hover {
            background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
            color: white;
            transform: translateY(-1px);
        }

        .action-button i {
            font-size: 20px;
            margin-bottom: 4px;
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

    <!-- Header -->
    <div class="profile-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Profil</h6>
                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
            </div>
            <img src="{{ isset($user->avatar) ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div>

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

    <!-- Profile Avatar Section -->
    <div class="profile-avatar">
       <img src="{{ isset($user->avatar) ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg') }}"
           alt="Profile Picture">
        <h5>{{ $user->name }}</h5>
        <p>{{ $user->email }}</p>
        <span class="role-badge">{{ ucfirst($user->role) }}</span>
    </div>

    <!-- Personal Information -->
    <div class="info-section">
        <div class="info-header">
            <h6><i class="bx bx-user me-2"></i>Informasi Personal</h6>
        </div>
        <div class="info-content">
            <div class="info-item">
                <span class="info-label">Nama Lengkap</span>
                <span class="info-value">{{ $user->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $user->email }}</span>
            </div>
            {{-- <div class="info-item">
                <span class="info-label">Role</span>
                <span class="info-value">{{ ucfirst($user->role) }}</span>
            </div> --}}
            <div class="info-item">
                <span class="info-label">Madrasah</span>
                <span class="info-value">{{ $user->madrasah?->name ?? 'Belum diatur' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Status Kepegawaian</span>
                <span class="info-value">{{ $user->statusKepegawaian?->name ?? 'Belum diatur' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Ketugasan</span>
                <span class="info-value">{{ $user->ketugasan ?? 'Belum diatur' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Bergabung Sejak</span>
                <span class="info-value">{{ $user->created_at ? $user->created_at->format('d M Y') : 'Tidak diketahui' }}</span>
            </div>
        </div>
    </div>

    <!-- Account Settings -->
    <div class="settings-section">
        <div class="settings-header">
            <h6><i class="bx bx-cog me-2"></i>Pengaturan Akun</h6>
        </div>
        <div class="settings-content">
            @if(!$user->password_changed)
            <div class="alert-custom">
                <i class="bx bx-info-circle"></i>
                <strong>Password belum diubah!</strong> Anda menggunakan password default. Silakan ubah password untuk keamanan akun.
            </div>
            @endif

            <a href="{{ route('mobile.ubah-akun') }}" class="settings-button">
                <i class="bx bx-cog"></i>
                Pengaturan Akun
            </a>

        </div>
    </div>



@endsection
