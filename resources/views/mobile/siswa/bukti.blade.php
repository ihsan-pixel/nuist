@extends('layouts.mobile')

@section('title', 'Bukti Pembayaran')

@section('content')
<div class="siswa-shell">
    @include('mobile.siswa.partials.styles')
    @include('mobile.siswa.partials.header', ['title' => 'Bukti Pembayaran', 'subtitle' => 'Dokumen transaksi siswa'])

    <section class="receipt-card">
        <div class="section-title">
            <h5>Receipt</h5>
            <span class="pill {{ $selectedPayment->status === 'success' ? 'pill-success' : ($selectedPayment->status === 'pending' ? 'pill-warning' : 'pill-danger') }}">{{ ucfirst($selectedPayment->status) }}</span>
        </div>
        <div class="detail-grid">
            <div class="detail-box">
                <small>Order ID</small>
                <strong>{{ $selectedPayment->order_id ?? 'PAY-' . $selectedPayment->id }}</strong>
            </div>
            <div class="detail-box">
                <small>Invoice</small>
                <strong>{{ $selectedTagihan->nomor_invoice ?? '-' }}</strong>
            </div>
            <div class="detail-box">
                <small>Metode</small>
                <strong>{{ strtoupper($selectedPayment->metode_pembayaran ?? '-') }}</strong>
            </div>
            <div class="detail-box">
                <small>Waktu bayar</small>
                <strong>{{ optional($selectedPayment->paid_at)->translatedFormat('d M Y H:i') ?? '-' }}</strong>
            </div>
            <div class="detail-box">
                <small>Nominal</small>
                <strong>Rp {{ number_format($selectedPayment->nominal, 0, ',', '.') }}</strong>
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
