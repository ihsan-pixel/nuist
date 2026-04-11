@extends('layouts.mobile')

@section('title', 'Profil Siswa')

@section('content')
<div class="siswa-shell">
    @include('mobile.siswa.partials.styles')
    @include('mobile.siswa.partials.header', ['title' => 'Profil Siswa', 'subtitle' => 'Informasi akun dan sekolah'])

    <section class="section-card profile-card">
        <div class="siswa-avatar">{{ strtoupper(substr($studentUser->name ?? 'S', 0, 1)) }}</div>
        <h5 class="mb-1">{{ $studentUser->name }}</h5>
        <p class="text-soft mb-0">{{ $studentUser->email }}</p>
    </section>

    <section class="section-card">
        <div class="detail-grid">
            <div class="detail-box">
                <small>NIS</small>
                <strong>{{ $studentRecord->nis ?? '-' }}</strong>
            </div>
            <div class="detail-box">
                <small>Role</small>
                <strong>{{ ucfirst(str_replace('_', ' ', $studentUser->role ?? 'siswa')) }}</strong>
            </div>
            <div class="detail-box">
                <small>Kelas</small>
                <strong>{{ $studentRecord->kelas ?? '-' }}</strong>
            </div>
            <div class="detail-box">
                <small>Jurusan</small>
                <strong>{{ $studentRecord->jurusan ?? '-' }}</strong>
            </div>
            <div class="detail-box">
                <small>No. HP</small>
                <strong>{{ $studentRecord->no_hp ?? $studentUser->no_hp ?? '-' }}</strong>
            </div>
            <div class="detail-box">
                <small>Madrasah</small>
                <strong>{{ $studentSchool->name ?? '-' }}</strong>
            </div>
            <div class="detail-box">
                <small>Alamat</small>
                <strong>{{ $studentRecord->alamat ?? $studentUser->alamat ?? '-' }}</strong>
            </div>
        </div>
    </section>

    <section class="section-card">
        <form id="siswaLogoutForm" action="{{ route('logout') }}" method="POST" style="text-align:center;">
            @csrf
            <button id="siswaLogoutButton" type="button" class="ghost-btn" style="width:auto; padding:9px 14px; background: linear-gradient(135deg, #dc3545 0%, #a61e2f 100%); color:#fff; margin:0 auto; display:inline-flex; justify-content:center; align-items:center;">
                <i class="bx bx-log-out"></i>Logout
            </button>
        </form>
    </section>
</div>

@include('mobile.siswa.partials.nav')
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var logoutButton = document.getElementById('siswaLogoutButton');
    var logoutForm = document.getElementById('siswaLogoutForm');

    if (!logoutButton || !logoutForm) return;

    logoutButton.addEventListener('click', function () {
        Swal.fire({
            title: 'Yakin ingin logout?',
            text: 'Sesi login Anda akan diakhiri.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, logout',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#a61e2f',
            cancelButtonColor: '#6c757d'
        }).then(function (result) {
            if (result.isConfirmed) {
                logoutForm.submit();
            }
        });
    });
});
</script>
@endsection
