@extends('layouts.mobile')

@section('title', 'Bukti Pembayaran')

@section('content')
<div class="siswa-shell">
    @include('mobile.siswa.partials.styles')
    @include('mobile.siswa.partials.header', ['title' => 'Bukti Pembayaran', 'subtitle' => 'Dokumen transaksi siswa'])

    <section class="hero-card">
        <span class="hero-eyebrow"><i class="bx bx-badge-check"></i>Receipt pembayaran</span>
        <h4>Rp {{ number_format($selectedPayment->nominal_bayar, 0, ',', '.') }}</h4>
        <p class="mb-0">Dokumen transaksi ini menampilkan rincian pembayaran yang sudah tercatat pada sistem sekolah.</p>
        <div class="hero-meta">
            <span class="hero-chip"><i class="bx bx-receipt"></i>{{ $selectedTagihan->nomor_tagihan ?? '-' }}</span>
            <span class="hero-chip"><i class="bx bx-shield"></i>{{ ucfirst($selectedPayment->status_verifikasi) }}</span>
        </div>
    </section>

    <section class="receipt-card">
        <div class="section-title">
            <div>
                <h5>Receipt</h5>
                <p class="section-subtitle">Bukti transaksi pembayaran siswa</p>
            </div>
            <span class="pill {{ $selectedPayment->status_verifikasi === 'diverifikasi' ? 'pill-success' : ($selectedPayment->status_verifikasi === 'menunggu' ? 'pill-warning' : 'pill-danger') }}">{{ ucfirst($selectedPayment->status_verifikasi) }}</span>
        </div>
        <div class="detail-grid">
            <div class="detail-box">
                <small>No. transaksi</small>
                <strong>{{ $selectedPayment->nomor_transaksi }}</strong>
            </div>
            <div class="detail-box">
                <small>Invoice</small>
                <strong>{{ $selectedTagihan->nomor_tagihan ?? '-' }}</strong>
            </div>
            <div class="detail-box">
                <small>Metode</small>
                <strong>{{ strtoupper($selectedPayment->metode_pembayaran ?? '-') }}</strong>
            </div>
            <div class="detail-box">
                <small>Channel</small>
                <strong>{{ strtoupper($selectedPayment->payment_channel ?? '-') }}</strong>
            </div>
            <div class="detail-box">
                <small>VA BNI</small>
                <strong>{{ $selectedPayment->va_number ?? '-' }}</strong>
            </div>
            <div class="detail-box">
                <small>Waktu bayar</small>
                <strong>{{ optional($selectedPayment->tanggal_bayar)->translatedFormat('d M Y') ?? '-' }}</strong>
            </div>
            <div class="detail-box">
                <small>Nominal</small>
                <strong>Rp {{ number_format($selectedPayment->nominal_bayar, 0, ',', '.') }}</strong>
            </div>
            <div class="detail-box">
                <small>Sekolah</small>
                <strong>{{ $studentSchool->name ?? '-' }}</strong>
            </div>
        </div>
    </section>
</div>

@include('mobile.siswa.partials.nav')
@endsection
