@extends('layouts.master-without-nav')

@section('title', 'Hasil Saya')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
@include('talenta.partials.hero')
@include('talenta.navbar')

<section class="section-clean">
<div class="container">
    <h2>Hasil Saya</h2>
    @if($schoolScore)
        <ul>
            <li>Skor Layanan: {{ $schoolScore->layanan }}</li>
            <li>Skor Tata Kelola: {{ $schoolScore->tata_kelola }}</li>
            <li>Skor Jumlah Siswa: {{ $schoolScore->jumlah_siswa }}</li>
            <li>Skor Jumlah Penghasilan: {{ $schoolScore->jumlah_penghasilan }}</li>
            <li>Skor Jumlah Prestasi: {{ $schoolScore->jumlah_prestasi }}</li>
            <li>Skor Jumlah Talenta: {{ $schoolScore->jumlah_talenta }}</li>
            <li>Total Skor: {{ $schoolScore->total_skor }}</li>
            <li>Level Sekolah: {{ $schoolScore->level_sekolah }}</li>
        </ul>
    @else
        <p>Belum ada hasil assessment untuk sekolah Anda.</p>
    @endif
</div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
