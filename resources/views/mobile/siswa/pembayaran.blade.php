@extends('layouts.mobile')

@section('title', 'Pembayaran Siswa')

@section('content')
<div class="siswa-shell">
    @include('mobile.siswa.partials.styles')
    @include('mobile.siswa.partials.header', ['title' => 'Pembayaran', 'subtitle' => 'Instruksi dan kanal pembayaran'])

    <section class="hero-card">
        <small>Pembayaran aktif</small>
        <h4>{{ $activeTagihan?->nomor_tagihan ?? 'Belum ada tagihan' }}</h4>
        <p class="mb-0">Gunakan halaman ini untuk melihat nominal, metode pembayaran yang tersedia, dan akses cepat ke detail invoice.</p>
    </section>

    <section class="section-card">
        <div class="section-title">
            <h5>Ringkasan pembayaran</h5>
            <span class="pill pill-info">{{ $activeTagihan?->status ? ucfirst(str_replace('_', ' ', $activeTagihan->status)) : 'Kosong' }}</span>
        </div>
        <div class="detail-grid">
            <div class="detail-box">
                <small>Periode</small>
                <strong>{{ $activeTagihan ? \Carbon\Carbon::createFromFormat('Y-m', $activeTagihan->periode)->translatedFormat('F Y') : '-' }}</strong>
            </div>
            <div class="detail-box">
                <small>Nominal</small>
                <strong>Rp {{ number_format($activeTagihan->total_tagihan ?? 0, 0, ',', '.') }}</strong>
            </div>
            <div class="detail-box">
                <small>Jatuh tempo</small>
                <strong>{{ optional($activeTagihan?->jatuh_tempo)->translatedFormat('d M Y') ?? '-' }}</strong>
            </div>
        </div>
        @if($activeTagihan)
            <div class="mt-3">
                <a href="{{ route('mobile.siswa.detail', $activeTagihan->id) }}" class="cta-btn">
                    <i class="bx bx-file-find"></i>Lihat detail pembayaran
                </a>
            </div>
        @endif
    </section>

    <section class="section-card">
        <div class="section-title">
            <h5>Metode pembayaran</h5>
        </div>
        <div class="list-item">
            <h6>Transfer / payment gateway</h6>
            <p>Siapkan nomor invoice dan nominal sesuai tagihan. Integrasi pembayaran dapat diarahkan ke gateway sekolah atau verifikasi admin.</p>
        </div>
        <div class="list-item">
            <h6>Konfirmasi manual ke admin</h6>
            <p>Setelah transfer, kirim bukti pembayaran melalui admin sekolah agar status tagihan diperbarui lebih cepat.</p>
        </div>
        <a href="{{ route('mobile.siswa.chat') }}" class="ghost-btn">
            <i class="bx bx-message-square-detail"></i>Hubungi admin sekolah
        </a>
    </section>
</div>

@include('mobile.siswa.partials.nav')
@endsection
