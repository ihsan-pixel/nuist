@extends('layouts.mobile')

@section('title', 'Detail Tagihan')

@section('content')
<div class="siswa-shell">
    @include('mobile.siswa.partials.styles')
    @include('mobile.siswa.partials.header', ['title' => 'Detail Tagihan', 'subtitle' => $selectedTagihan->nomor_invoice])

    <section class="section-card">
        <div class="section-title">
            <h5>Detail invoice</h5>
            <span class="pill {{ $selectedTagihan->status === 'lunas' ? 'pill-success' : ($selectedTagihan->status === 'pending' ? 'pill-warning' : 'pill-danger') }}">
                {{ ucfirst(str_replace('_', ' ', $selectedTagihan->status)) }}
            </span>
        </div>

        <div class="detail-grid">
            <div class="detail-box">
                <small>Nomor invoice</small>
                <strong>{{ $selectedTagihan->nomor_invoice }}</strong>
            </div>
            <div class="detail-box">
                <small>Jenis tagihan</small>
                <strong>{{ $selectedTagihan->jenis_tagihan ?? 'SPP / Iuran Sekolah' }}</strong>
            </div>
            <div class="detail-box">
                <small>Nominal</small>
                <strong>Rp {{ number_format($selectedTagihan->nominal, 0, ',', '.') }}</strong>
            </div>
            <div class="detail-box">
                <small>Jatuh tempo</small>
                <strong>{{ optional($selectedTagihan->jatuh_tempo)->translatedFormat('d M Y') }}</strong>
            </div>
        </div>

        @if($selectedTagihan->keterangan)
            <div class="list-item mt-3">
                <h6>Keterangan</h6>
                <p>{{ $selectedTagihan->keterangan }}</p>
            </div>
        @endif
    </section>

    <section class="section-card">
        <div class="section-title">
            <h5>Status pembayaran</h5>
        </div>
        @if($selectedPayment)
            <div class="list-item">
                <h6>{{ ucfirst($selectedPayment->status) }}</h6>
                <p>{{ optional($selectedPayment->paid_at)->translatedFormat('d M Y H:i') ?? 'Belum ada waktu pembayaran' }}</p>
                <div class="meta-row">
                    <span>{{ $selectedPayment->metode_pembayaran ?? 'Metode belum tercatat' }}</span>
                    <a href="{{ route('mobile.siswa.bukti', $selectedPayment->id) }}" class="ghost-btn" style="width:auto; padding:8px 12px;">Bukti pembayaran</a>
                </div>
            </div>
        @else
            <div class="list-item">
                <h6>Belum ada pembayaran</h6>
                <p>Tagihan ini belum memiliki transaksi yang tercatat.</p>
            </div>
        @endif
    </section>
</div>

@include('mobile.siswa.partials.nav')
@endsection
