@extends('layouts.master-without-nav')

@section('title', 'Verifikasi Pendaftar - ' . $ppdbSetting->nama_sekolah)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('ppdb.sekolah.dashboard') }}" class="text-blue-600 hover:text-blue-800 text-sm mb-2 inline-block">
                        ← Kembali ke Dashboard
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">Verifikasi Pendaftar</h1>
                    <p class="text-gray-600 mt-2">{{ $ppdbSetting->nama_sekolah }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Status:</p>
                    <p class="text-lg font-bold text-yellow-600">{{ $pendaftars->total() }} Menunggu Verifikasi</p>
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

        @if($pendaftars->isEmpty())
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Semua Pendaftar Terverifikasi</h3>
                <p class="text-gray-600">Tidak ada pendaftar yang menunggu verifikasi</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($pendaftars as $pendaftar)
                    <div class="bg-white rounded-lg shadow p-6">
                        <!-- Header Pendaftar -->
                        <div class="flex items-start justify-between mb-6 pb-4 border-b">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $pendaftar->nama_lengkap }}</h3>
                                <p class="text-gray-600 text-sm">No. Pendaftaran: <code class="bg-gray-100 px-2 py-1 rounded">{{ $pendaftar->nomor_pendaftaran }}</code></p>
                                <p class="text-gray-600 text-sm">NISN: {{ $pendaftar->nisn }}</p>
                            </div>
                            <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 font-semibold text-sm">
                                ⏳ Pending
                            </span>
                        </div>

                        <!-- Data Pendaftar -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-gray-600 text-sm">Asal Sekolah</p>
                                <p class="text-gray-900 font-semibold">{{ $pendaftar->asal_sekolah }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Pilihan Jurusan</p>
                                <p class="text-gray-900 font-semibold">{{ $pendaftar->jurusan_pilihan }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Jalur Pendaftaran</p>
                                <p class="text-gray-900 font-semibold">{{ $pendaftar->jalur->nama_jalur ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Tanggal Daftar</p>
                                <p class="text-gray-900 font-semibold">{{ $pendaftar->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Dokumen -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h4 class="font-semibold text-gray-900 mb-3">📄 Dokumen Pendaftar</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-2">Kartu Keluarga</p>
                                    @if($pendaftar->berkas_kk)
                                        <a href="{{ asset('storage/' . $pendaftar->berkas_kk) }}" target="_blank"
                                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                            <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM15.657 14.243a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM11 17a1 1 0 102 0v-1a1 1 0 10-2 0v1zM5.757 15.657a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM2 10a1 1 0 011-1h1a1 1 0 110 2H3a1 1 0 01-1-1zM5.757 4.343a1 1 0 00-1.414 1.414l.707.707a1 1 0 001.414-1.414l-.707-.707z"></path>
                                            </svg>
                                            Lihat File
                                        </a>
                                    @else
                                        <span class="text-gray-500 text-sm">Tidak ada file</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-2">Ijazah/SKHUN</p>
                                    @if($pendaftar->berkas_ijazah)
                                        <a href="{{ asset('storage/' . $pendaftar->berkas_ijazah) }}" target="_blank"
                                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                            <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM15.657 14.243a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM11 17a1 1 0 102 0v-1a1 1 0 10-2 0v1zM5.757 15.657a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM2 10a1 1 0 011-1h1a1 1 0 110 2H3a1 1 0 01-1-1zM5.757 4.343a1 1 0 00-1.414 1.414l.707.707a1 1 0 001.414-1.414l-.707-.707z"></path>
                                            </svg>
                                            Lihat File
                                        </a>
                                    @else
                                        <span class="text-gray-500 text-sm">Tidak ada file</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Verifikasi Form -->
                        <form method="POST" action="{{ route('ppdb.sekolah.verifikasi') }}" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status Verifikasi <span class="text-red-600">*</span>
                                </label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="status" value="verifikasi" required
                                               class="h-4 w-4 text-green-600">
                                        <span class="ml-2 text-gray-700">✅ Terverifikasi</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="status" value="tidak_lulus" required
                                               class="h-4 w-4 text-red-600">
                                        <span class="ml-2 text-gray-700">❌ Dokumen Tidak Lengkap / Ditolak</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Catatan Verifikasi
                                </label>
                                <textarea name="catatan" rows="3"
                                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Contoh: Dokumen lengkap, ijazah asli, KK sesuai..."></textarea>
                            </div>

                            <div class="flex gap-3 pt-4">
                                <button type="submit"
                                        class="flex-1 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">
                                    ✓ Proses Verifikasi
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($pendaftars->hasPages())
                <div class="mt-8">
                    {{ $pendaftars->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
