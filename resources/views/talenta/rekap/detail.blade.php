@extends('layouts.master-without-nav')

@section('title', 'Detail Nilai Sekolah')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
@include('talenta.partials.hero')
@include('talenta.navbar')

<section class="section-clean">
<div class="container">
    <h2>Detail Nilai Sekolah</h2>
    <ul>
        <li>Skor Layanan: {{ $score->layanan }}</li>
        <li>Skor Tata Kelola: {{ $score->tata_kelola }}</li>
        <li>Skor Jumlah Siswa: {{ $score->jumlah_siswa }}</li>
        <li>Skor Jumlah Penghasilan: {{ $score->jumlah_penghasilan }}</li>
        <li>Skor Jumlah Prestasi: {{ $score->jumlah_prestasi }}</li>
        <li>Skor Jumlah Talenta: {{ $score->jumlah_talenta }}</li>
        <li>Total Skor: {{ $score->total_skor }}</li>
        <li>Level Sekolah: {{ $score->level_sekolah }}</li>
    </ul>
</div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
