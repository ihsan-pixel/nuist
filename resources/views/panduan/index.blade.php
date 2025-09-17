@extends('layouts.master')

@section('title') Panduan Penggunaan Aplikasi @endsection

@section('css')
<style>
.guide-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.guide-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0 !important;
    border-bottom: none;
}

.step-list {
    counter-reset: step-counter;
}

.step-list li {
    counter-increment: step-counter;
    margin-bottom: 10px;
    padding-left: 30px;
    position: relative;
}

.step-list li::before {
    content: counter(step-counter);
    position: absolute;
    left: 0;
    top: 0;
    background: #667eea;
    color: white;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    text-align: center;
    line-height: 20px;
    font-size: 12px;
    font-weight: bold;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Panduan Penggunaan Aplikasi @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bx bx-info-circle me-2"></i>
            Panduan ini membantu Anda memahami cara menggunakan fitur-fitur aplikasi berdasarkan peran Anda.
        </div>
    </div>
</div>

@php
    $userRole = auth()->user()->role;
@endphp

<!-- Dashboard -->
<div class="row">
    <div class="col-12">
        <div class="card guide-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-home-circle me-2"></i>Dashboard
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">Halaman utama aplikasi yang menampilkan ringkasan informasi penting.</p>
                <h6>Cara Mengakses:</h6>
                <ol class="step-list">
                    <li>Klik menu "Dashboard" di sidebar kiri.</li>
                    <li>Halaman akan menampilkan statistik dan informasi terkini.</li>
                </ol>
                <div class="alert alert-warning">
                    <strong>Tip:</strong> Dashboard diperbarui secara otomatis saat Anda login.
                </div>
            </div>
        </div>
    </div>
</div>

@if(in_array($userRole, ['super_admin', 'admin']))
<!-- Master Data -->
<div class="row">
    <div class="col-12">
        <div class="card guide-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-data me-2"></i>Master Data
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">Kelola data master seperti Admin, Madrasah/Sekolah, dan Tenaga Pendidik.</p>

                <h6>Data Admin:</h6>
                <ol class="step-list">
                    <li>Klik "Master Data" > "Data Admin".</li>
                    <li>Klik "Tambah Admin" untuk menambah data baru.</li>
                    <li>Isi formulir dan klik "Simpan".</li>
                    <li>Untuk import, gunakan template Excel dan klik "Import".</li>
                </ol>

                <h6>Data Madrasah/Sekolah:</h6>
                <ol class="step-list">
                    <li>Klik "Master Data" > "Data Madrasah/Sekolah".</li>
                    <li>Klik "Tambah Madrasah" untuk menambah data.</li>
                    <li>Isi detail madrasah termasuk lokasi dan map link.</li>
                    <li>Klik "Simpan" untuk menyimpan.</li>
                </ol>

                <h6>Data Tenaga Pendidik:</h6>
                <ol class="step-list">
                    <li>Klik "Master Data" > "Data Tenaga Pendidik".</li>
                    <li>Klik "Tambah Tenaga Pendidik" atau import dari Excel.</li>
                    <li>Pastikan data lengkap termasuk NIP, status kepegawaian, dll.</li>
                </ol>

                @if($userRole === 'super_admin')
                <h6>Data Status Kepegawaian:</h6>
                <ol class="step-list">
                    <li>Klik "Master Data" > "Data Status Kepegawaian".</li>
                    <li>Kelola status seperti PNS, Honorer, dll.</li>
                </ol>

                <h6>Data Tahun Pelajaran:</h6>
                <ol class="step-list">
                    <li>Klik "Master Data" > "Data Tahun Pelajaran".</li>
                    <li>Tambahkan tahun ajaran aktif.</li>
                </ol>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

@if($userRole === 'tenaga_pendidik')
<!-- Presensi -->
<div class="row">
    <div class="col-12">
        <div class="card guide-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-check-square me-2"></i>Presensi
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">Lakukan presensi harian sebagai tenaga pendidik.</p>
                <h6>Cara Melakukan Presensi:</h6>
                <ol class="step-list">
                    <li>Klik menu "Presensi" di sidebar.</li>
                    <li>Klik "Buat Presensi" untuk presensi masuk/keluar.</li>
                    <li>Pastikan lokasi GPS aktif dan dalam radius madrasah.</li>
                    <li>Klik "Simpan Presensi".</li>
                    <li>Untuk laporan, klik "Laporan Presensi".</li>
                </ol>
                <div class="alert alert-warning">
                    <strong>Penting:</strong> Pastikan izin lokasi browser diaktifkan.
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($userRole === 'super_admin')
<!-- Presensi Admin -->
<div class="row">
    <div class="col-12">
        <div class="card guide-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-check-square me-2"></i>Presensi Admin
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">Kelola pengaturan dan data presensi.</p>

                <h6>Pengaturan Presensi:</h6>
                <ol class="step-list">
                    <li>Klik "Presensi Admin" > "Pengaturan Presensi".</li>
                    <li>Atur jam masuk/keluar, radius presensi, dll.</li>
                    <li>Klik "Simpan Pengaturan".</li>
                </ol>

                <h6>Data Presensi:</h6>
                <ol class="step-list">
                    <li>Klik "Presensi Admin" > "Data Presensi".</li>
                    <li>Lihat dan filter data presensi semua pengguna.</li>
                    <li>Export data jika diperlukan.</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Pengembangan -->
<div class="row">
    <div class="col-12">
        <div class="card guide-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-history me-2"></i>Riwayat Pengembangan
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">Lihat timeline perkembangan aplikasi.</p>
                <h6>Cara Mengakses:</h6>
                <ol class="step-list">
                    <li>Klik menu "Riwayat Pengembangan".</li>
                    <li>Lihat statistik dan timeline update.</li>
                    <li>Gunakan filter untuk mencari berdasarkan tipe atau tanggal.</li>
                    <li>Klik "Sinkronisasi Migration" untuk update data.</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@elseif($userRole === 'admin')
<!-- Data Presensi -->
<div class="row">
    <div class="col-12">
        <div class="card guide-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-check-square me-2"></i>Data Presensi
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">Pantau data presensi tenaga pendidik di madrasah Anda.</p>
                <h6>Cara Mengakses:</h6>
                <ol class="step-list">
                    <li>Klik menu "Data Presensi".</li>
                    <li>Lihat daftar presensi dengan filter tanggal.</li>
                    <li>Periksa status presensi masing-masing tenaga pendidik.</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
