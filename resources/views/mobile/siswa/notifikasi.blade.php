@extends('layouts.mobile')

@section('title', 'Notifikasi Siswa')

@section('content')
<div class="siswa-shell">
    @include('mobile.siswa.partials.styles')
    @include('mobile.siswa.partials.header', ['title' => 'Notifikasi', 'subtitle' => 'Reminder dan update pembayaran'])

    <section class="hero-card">
        <span class="hero-eyebrow"><i class="bx bx-bell"></i>Pusat notifikasi</span>
        <h4>{{ $notifications->count() }} update masuk</h4>
        <p class="mb-0">Semua reminder pembayaran dan pemberitahuan terbaru sekolah dikumpulkan di satu tempat.</p>
    </section>

    <section class="list-card">
        <div class="section-title">
            <div>
                <h5>Semua notifikasi</h5>
                <p class="section-subtitle">Reminder jatuh tempo dan pembaruan pembayaran</p>
            </div>
            <span class="pill pill-info">{{ $notifications->count() }} item</span>
        </div>
        @forelse($notifications as $notification)
            <div class="notif-item">
                <div class="notif-icon">
                    <i class="bx {{ $notification->type === 'reminder' ? 'bx-time-five' : 'bx-bell' }}"></i>
                </div>
                <div>
                    <h6 class="mb-1">{{ $notification->title }}</h6>
                    <p class="mb-1">{{ $notification->message }}</p>
                    <small>{{ optional($notification->created_at)->diffForHumans() }}</small>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bx bx-bell-off"></i>
                <h6>Belum ada notifikasi</h6>
                <p>Notifikasi pembayaran dan reminder H-3 jatuh tempo akan tampil di sini.</p>
            </div>
        @endforelse
    </section>
</div>

@include('mobile.siswa.partials.nav')
@endsection
