@extends('layouts.app')

@section('title', 'Dashboard PPDB - ' . $ppdbSetting->nama_sekolah)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard PPDB</h1>
                    <p class="text-gray-600 mt-2">{{ $ppdbSetting->nama_sekolah }} | Tahun {{ $ppdbSetting->tahun }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Status Pendaftaran:</p>
                    <span class="inline-block px-4 py-2 rounded-full font-bold
                        {{ $ppdbSetting->status === 'buka' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($ppdbSetting->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                ❌ {{ session('error') }}
            </div>
        @endif

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Pendaftar</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistik['total_pendaftar'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10h.01M11 10h.01M7 10h.01M6 20h12a2 2 0 002-2V9a2 2 0 00-2-2H6a2 2 0 00-2 2v9a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Pending</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $statistik['pending'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="h-6 w-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 11-2 0 1 1 0 012 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Diverifikasi</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $statistik['verifikasi'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-2.77 3.066 3.066 0 00-3.58 3.03A3.066 3.066 0 006.267 3.455zm9.8 6.67a2.391 2.391 0 11-4.782 0 2.391 2.391 0 014.782 0zm5.574.016l.057.177.229.529a5.408 5.408 0 01-3.9 7.05 3.41 3.41 0 11-6.772-1.142 2.265 2.265 0 00-.938-.743l-.231-.529-.057-.177A8.364 8.364 0 0110 3.318a8.37 8.37 0 016.641 13.368z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Lulus</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $statistik['lulus'] }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Tidak Lulus</p>
                        <p class="text-3xl font-bold text-red-600 mt-2">{{ $statistik['tidak_lulus'] }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="h-6 w-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('ppdb.sekolah.verifikasi') }}"
               class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow cursor-pointer border-l-4 border-yellow-500">
                <h3 class="text-lg font-semibold text-gray-900">📋 Verifikasi Data</h3>
                <p class="text-gray-600 text-sm mt-2">Periksa dan verifikasi dokumen pendaftar</p>
                <span class="inline-block mt-4 text-yellow-600 font-semibold">
                    {{ $statistik['pending'] }} menunggu verifikasi →
                </span>
            </a>

            <a href="{{ route('ppdb.sekolah.seleksi') }}"
               class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow cursor-pointer border-l-4 border-blue-500">
                <h3 class="text-lg font-semibold text-gray-900">⭐ Seleksi & Nilai</h3>
                <p class="text-gray-600 text-sm mt-2">Input nilai dan ranking pendaftar</p>
                <span class="inline-block mt-4 text-blue-600 font-semibold">
                    {{ $statistik['verifikasi'] }} siap diseleksi →
                </span>
            </a>

            <a href="{{ route('ppdb.sekolah.export') }}"
               class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow cursor-pointer border-l-4 border-green-500">
                <h3 class="text-lg font-semibold text-gray-900">📊 Export Data</h3>
                <p class="text-gray-600 text-sm mt-2">Download data pendaftar ke Excel/PDF</p>
                <span class="inline-block mt-4 text-green-600 font-semibold">
                    Export {{ $statistik['total_pendaftar'] }} data →
                </span>
            </a>
        </div>

        <!-- Jadwal PPDB Info -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">📅 Jadwal Pendaftaran</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="border-l-4 border-green-600 pl-4">
                    <p class="text-gray-600 text-sm">Mulai</p>
                    <p class="text-2xl font-bold text-green-700">{{ $ppdbSetting->jadwal_buka->format('d M Y') }}</p>
                    <p class="text-gray-500 text-xs">{{ $ppdbSetting->jadwal_buka->format('H:i') }}</p>
                </div>
                <div class="border-l-4 border-red-600 pl-4">
                    <p class="text-gray-600 text-sm">Berakhir</p>
                    <p class="text-2xl font-bold text-red-700">{{ $ppdbSetting->jadwal_tutup->format('d M Y') }}</p>
                    <p class="text-gray-500 text-xs">{{ $ppdbSetting->jadwal_tutup->format('H:i') }}</p>
                </div>
                <div class="border-l-4 border-blue-600 pl-4">
                    <p class="text-gray-600 text-sm">Sisa Waktu</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $ppdbSetting->remainingDays() }} hari</p>
                    <p class="text-gray-500 text-xs">Dari hari ini</p>
                </div>
            </div>
        </div>

        <!-- Footer Help -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">💡 Bantuan</h3>
            <p class="text-blue-800 mb-4">Alur pendaftaran: Calon Peserta mengisi form → Verifikasi data → Seleksi → Hasil</p>
            <p class="text-blue-700 text-sm">Hubungi LP. Ma'arif untuk bantuan teknis: ppdb@nuist.id</p>
        </div>
    </div>
</div>
@endsection
