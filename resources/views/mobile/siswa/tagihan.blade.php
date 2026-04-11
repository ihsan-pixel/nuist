@extends('layouts.mobile')

@section('title', 'Tagihan Siswa')

@section('content')
<div class="siswa-shell">
    @include('mobile.siswa.partials.styles')
    @include('mobile.siswa.partials.header', ['title' => 'Tagihan', 'subtitle' => 'Daftar tagihan siswa'])

    <section class="section-card">
        <div class="section-title">
            <h5>Semua tagihan</h5>
            <span class="pill pill-info">{{ $tagihans->count() }} data</span>
        </div>
        @forelse($tagihans as $tagihan)
            <div class="list-item">
                <h6>{{ $tagihan->nomor_tagihan }}</h6>
                <p>SPP / Iuran Sekolah periode {{ \Carbon\Carbon::createFromFormat('Y-m', $tagihan->periode)->translatedFormat('F Y') }}</p>
                <div class="meta-row">
                    <span>{{ optional($tagihan->jatuh_tempo)->translatedFormat('d M Y') }}</span>
                    <strong>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</strong>
                </div>
                <div class="meta-row">
                    <span class="pill {{ $tagihan->status === 'lunas' ? 'pill-success' : ($tagihan->status === 'sebagian' ? 'pill-warning' : 'pill-danger') }}">{{ ucfirst(str_replace('_', ' ', $tagihan->status)) }}</span>
                    <a href="{{ route('mobile.siswa.detail', $tagihan->id) }}" class="ghost-btn" style="width:auto; padding:8px 12px;">Detail</a>
                </div>
            </div>
        @empty
            <div class="list-item">
                <h6>Belum ada tagihan</h6>
                <p>Daftar tagihan akan muncul di sini setelah dibuat oleh admin sekolah.</p>
            </div>
        @endforelse
    </section>
</div>

@include('mobile.siswa.partials.nav')
@endsection
