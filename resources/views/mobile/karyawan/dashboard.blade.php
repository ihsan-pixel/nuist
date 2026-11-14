@extends('layouts.mobile')
@section('title', 'Presensi Karyawan')
@section('content')
<main class="container py-3" style="max-width:520px">
    <div class="d-flex justify-content-between align-items-center mb-4"><div><small class="text-muted">Selamat datang</small><h4 class="mb-0">{{ auth()->user()->name }}</h4></div><span class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"><i class="bx bx-user"></i></span></div>
    <div class="card border-0 shadow-sm mb-3"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><small class="text-muted">Status jaringan</small><h6 class="mb-0"><i class="bx bx-wifi text-success me-1"></i>Periksa Wi-Fi kantor</h6></div><span class="badge bg-success-subtle text-success">Siap</span></div></div></div>
    <div class="card border-0 shadow-sm text-center mb-3"><div class="card-body py-4"><div class="rounded-circle border border-3 border-primary mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:150px;height:150px"><i class="bx bx-scan text-primary" style="font-size:64px"></i></div><h5>Presensi dengan wajah</h5><p class="text-muted small">Pastikan wajah terlihat jelas dan terhubung ke jaringan kantor.</p><a href="{{ route('mobile.presensi') }}" class="btn btn-primary btn-lg w-100"><i class="bx bx-face me-1"></i>Scan Wajah</a></div></div>
    <div class="card border-0 shadow-sm"><div class="card-body"><h6>Presensi hari ini</h6><div class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Masuk</span><strong>Belum tercatat</strong></div><div class="d-flex justify-content-between pt-2"><span class="text-muted">Pulang</span><strong>Belum tercatat</strong></div></div></div>
</main>
@endsection
