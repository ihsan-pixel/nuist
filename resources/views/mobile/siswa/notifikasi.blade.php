@extends('layouts.mobile')

@section('title', 'Notifikasi Siswa')

@section('content')
<div class="siswa-shell">
    @include('mobile.siswa.partials.styles')
    @include('mobile.siswa.partials.header', ['title' => 'Notifikasi', 'subtitle' => 'Reminder dan update pembayaran'])

    <section class="list-card">
        <div class="section-title">
            <h5>Semua notifikasi</h5>
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
            <div class="list-item">
                <h6>Belum ada notifikasi</h6>
                <p>Notifikasi pembayaran dan reminder H-3 jatuh tempo akan tampil di sini.</p>
            </div>
        @endforelse
    </section>
</div>

@include('mobile.siswa.partials.nav')
@endsection
