@extends('layouts.mobile')

@section('title', 'Detail Tagihan')

@section('content')
<div class="siswa-shell">
    @include('mobile.siswa.partials.styles')
    @include('mobile.siswa.partials.header', ['title' => 'Detail Tagihan', 'subtitle' => $selectedTagihan->nomor_tagihan])

    @if($errors->has('bni_va'))
        <section class="section-card"><div class="list-item"><h6>BNI VA</h6><p>{{ $errors->first('bni_va') }}</p></div></section>
    @endif

    <section class="section-card">
        <div class="section-title">
            <h5>Detail invoice</h5>
            <span class="pill {{ $selectedTagihan->status === 'lunas' ? 'pill-success' : ($selectedTagihan->status === 'sebagian' ? 'pill-warning' : 'pill-danger') }}">
                {{ ucfirst(str_replace('_', ' ', $selectedTagihan->status)) }}
            </span>
        </div>

        <div class="detail-grid">
            <div class="detail-box">
                <small>Nomor invoice</small>
                <strong>{{ $selectedTagihan->nomor_tagihan }}</strong>
            </div>
            <div class="detail-box">
                <small>Periode</small>
                <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $selectedTagihan->periode)->translatedFormat('F Y') }}</strong>
            </div>
            <div class="detail-box">
                <small>Nominal</small>
                <strong>Rp {{ number_format($selectedTagihan->nominal, 0, ',', '.') }}</strong>
            </div>
            <div class="detail-box">
                <small>Total tagihan</small>
                <strong>Rp {{ number_format($selectedTagihan->total_tagihan, 0, ',', '.') }}</strong>
            </div>
            <div class="detail-box">
                <small>Jatuh tempo</small>
                <strong>{{ optional($selectedTagihan->jatuh_tempo)->translatedFormat('d M Y') }}</strong>
            </div>
        </div>

        @if($selectedTagihan->catatan)
            <div class="list-item mt-3">
                <h6>Catatan</h6>
                <p>{{ $selectedTagihan->catatan }}</p>
            </div>
        @endif
    </section>

    <section class="section-card">
        <div class="section-title">
            <h5>Status pembayaran</h5>
        </div>
        @if($selectedPayment)
            <div class="list-item">
                <h6>{{ ucfirst($selectedPayment->status_verifikasi) }}</h6>
                <p>{{ optional($selectedPayment->tanggal_bayar)->translatedFormat('d M Y') ?? 'Belum ada waktu pembayaran' }}</p>
                <div class="meta-row">
                    <span>{{ $selectedPayment->metode_pembayaran ?? 'Metode belum tercatat' }}</span>
                    <a href="{{ route('mobile.siswa.bukti', $selectedPayment->id) }}" class="ghost-btn" style="width:auto; padding:8px 12px;">Bukti pembayaran</a>
                </div>
                @if($selectedPayment->payment_channel === 'bni_va' && $selectedPayment->va_number)
                    <div class="meta-row mt-2">
                        <span>VA BNI: {{ $selectedPayment->va_number }}</span>
                        <span>{{ optional($selectedPayment->va_expired_at)->translatedFormat('d M Y H:i') ?? '-' }}</span>
                    </div>
                @endif
            </div>
        @else
            <div class="list-item">
                <h6>Belum ada pembayaran</h6>
                <p>Tagihan ini belum memiliki transaksi yang tercatat.</p>
            </div>
            @if(($selectedTagihan->setting->payment_provider ?? 'manual') === 'bni_va')
                <a href="{{ route('mobile.siswa.billing', $selectedTagihan->id) }}" class="cta-btn">
                    <i class="bx bx-printer"></i>Cetak Billing & Terbitkan VA
                </a>
            @endif
        @endif
    </section>
</div>

@include('mobile.siswa.partials.nav')
@endsection
