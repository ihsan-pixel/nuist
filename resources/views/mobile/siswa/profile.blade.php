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
                <small>Role</small>
                <strong>{{ ucfirst(str_replace('_', ' ', $studentUser->role ?? 'siswa')) }}</strong>
            </div>
            <div class="detail-box">
                <small>No. HP</small>
                <strong>{{ $studentUser->no_hp ?? '-' }}</strong>
            </div>
            <div class="detail-box">
                <small>Madrasah</small>
                <strong>{{ $studentSchool->name ?? '-' }}</strong>
            </div>
            <div class="detail-box">
                <small>Alamat</small>
                <strong>{{ $studentUser->alamat ?? '-' }}</strong>
            </div>
        </div>
    </section>
</div>

@include('mobile.siswa.partials.nav')
@endsection
