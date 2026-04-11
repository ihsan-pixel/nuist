@extends('layouts.mobile')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="siswa-shell">
    @include('mobile.siswa.partials.styles')
    @include('mobile.siswa.partials.header', ['title' => 'Riwayat Pembayaran', 'subtitle' => 'Filter transaksi siswa'])

    <section class="section-card">
        <div class="section-title">
            <h5>🔍 Filter riwayat pembayaran</h5>
        </div>
        <form method="GET" class="filter-form">
            <select name="status" class="form-control">
                <option value="">Semua status</option>
                <option value="diverifikasi" {{ request('status') === 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select name="bulan" class="form-control">
                <option value="">Semua bulan</option>
                @for($bulan = 1; $bulan <= 12; $bulan++)
                    <option value="{{ $bulan }}" {{ (int) request('bulan') === $bulan ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
            <button class="cta-btn" type="submit" style="width:auto; padding:0 14px;">
                <i class="bx bx-search"></i>
            </button>
        </form>
    </section>

    <section class="list-card">
        <div class="section-title">
            <h5>Daftar transaksi</h5>
            <span class="pill pill-info">{{ $filteredPayments->count() }} hasil</span>
        </div>
        @forelse($filteredPayments as $payment)
            <div class="list-item">
                <h6>{{ $payment->nomor_transaksi }}</h6>
                <p>{{ $payment->bill?->nomor_tagihan ?? 'Tagihan tidak ditemukan' }}</p>
                <div class="meta-row">
                    <span>{{ optional($payment->tanggal_bayar)->translatedFormat('d M Y') ?? 'Belum dibayar' }}</span>
                    <strong>Rp {{ number_format($payment->nominal_bayar, 0, ',', '.') }}</strong>
                </div>
                <div class="meta-row">
                    <span class="pill {{ $payment->status_verifikasi === 'diverifikasi' ? 'pill-success' : ($payment->status_verifikasi === 'menunggu' ? 'pill-warning' : 'pill-danger') }}">{{ ucfirst($payment->status_verifikasi) }}</span>
                    <a href="{{ route('mobile.siswa.bukti', $payment->id) }}" class="ghost-btn" style="width:auto; padding:8px 12px;">Bukti</a>
                </div>
            </div>
        @empty
            <div class="list-item">
                <h6>Belum ada riwayat pembayaran</h6>
                <p>Transaksi yang sudah diproses akan muncul di sini dan bisa difilter per status atau bulan.</p>
            </div>
        @endforelse
    </section>
</div>

@include('mobile.siswa.partials.nav')
@endsection
