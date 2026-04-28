@extends('layouts.mobile')

@section('title', 'Tagihan Siswa')

@section('content')
<div class="siswa-shell">
    @include('mobile.siswa.partials.styles')
    @include('mobile.siswa.partials.header', ['title' => 'Tagihan', 'subtitle' => 'Daftar tagihan siswa'])

    <section class="hero-card">
        <span class="hero-eyebrow"><i class="bx bx-receipt"></i>Monitoring tagihan</span>
        <h4>{{ $tagihans->count() }} invoice tercatat</h4>
        <p class="mb-0">Lihat seluruh tagihan, status pembayaran, dan akses cepat ke detail atau billing Virtual Account.</p>
    </section>

    <section class="section-card">
        <div class="section-title">
            <div>
                <h5>Semua tagihan</h5>
                <p class="section-subtitle">Daftar invoice yang dibuat oleh sekolah</p>
            </div>
            <span class="pill pill-info">{{ $tagihans->count() }} data</span>
        </div>
        @forelse($tagihans as $tagihan)
            <div class="list-item">
                <div class="list-kicker"><i class="bx bx-calendar"></i>{{ \Carbon\Carbon::createFromFormat('Y-m', $tagihan->periode)->translatedFormat('F Y') }}</div>
                <h6>{{ $tagihan->nomor_tagihan }}</h6>
                <p>{{ $tagihan->jenis_tagihan ?? 'SPP' }} periode {{ \Carbon\Carbon::createFromFormat('Y-m', $tagihan->periode)->translatedFormat('F Y') }}</p>
                <div class="meta-row">
                    <span>{{ optional($tagihan->jatuh_tempo)->translatedFormat('d M Y') }}</span>
                    <strong>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</strong>
                </div>
                <div class="meta-row">
                    <span class="pill {{ $tagihan->status === 'lunas' ? 'pill-success' : ($tagihan->status === 'sebagian' ? 'pill-warning' : 'pill-danger') }}">{{ ucfirst(str_replace('_', ' ', $tagihan->status)) }}</span>
                    <div style="display:flex; gap:8px;">
                        <a href="{{ route('mobile.siswa.detail', $tagihan->id) }}" class="ghost-btn" style="width:auto; padding:8px 12px;">Detail</a>
                        @if(($tagihan->setting->payment_provider ?? 'manual') === 'bni_va' && $tagihan->status !== 'lunas')
                            <a href="{{ route('mobile.siswa.billing', $tagihan->id) }}" class="ghost-btn" style="width:auto; padding:8px 12px;">Billing</a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bx bx-folder-open"></i>
                <h6>Belum ada tagihan</h6>
                <p>Daftar tagihan akan muncul di sini setelah dibuat oleh admin sekolah.</p>
            </div>
        @endforelse
    </section>
</div>

@include('mobile.siswa.partials.nav')
@endsection
