@extends('layouts.master-without-nav')

@section('title', 'Formulir PPDB ' . $ppdbSetting->nama_sekolah)

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-8">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ route('ppdb.index') }}" class="text-blue-600 hover:text-blue-800">PPDB</a>
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                <a href="{{ route('ppdb.sekolah', $ppdbSetting->slug) }}" class="text-blue-600 hover:text-blue-800">{{ $ppdbSetting->nama_sekolah }}</a>
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                <span class="text-gray-600">Formulir Pendaftaran</span>
            </div>
        </div>

        <!-- Header Card -->
        <div class="bg-gradient-to-r from-green-700 to-blue-700 text-white rounded-lg shadow-lg p-8 mb-8">
            <h1 class="text-3xl font-bold mb-2">Formulir Pendaftaran</h1>
            <p class="text-green-100">{{ $ppdbSetting->nama_sekolah }}</p>
        </div>

        <!-- Alert Messages -->
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="text-red-800 font-semibold mb-2">⚠️ Terjadi Kesalahan</h3>
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-green-800 font-semibold">✅ {{ session('success') }}</p>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="{{ route('ppdb.store', $ppdbSetting->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-gray-700">Langkah 1 dari 3: Data Pribadi</h3>
                        <span class="text-xs text-gray-500">33%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 33%"></div>
                    </div>
                </div>

                <!-- Section 1: Data Pribadi -->
                <div>
                    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b-2 border-green-600">👤 Data Pribadi</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('nama_lengkap') border-red-500 @enderror"
                                   placeholder="Nama lengkap sesuai KTP/Akte Kelahiran" required>
                            @error('nama_lengkap')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    NISN <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="nisn" value="{{ old('nisn') }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('nisn') border-red-500 @enderror"
                                       placeholder="Nomor Induk Siswa Nasional" required>
                                @error('nisn')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Asal Sekolah <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('asal_sekolah') border-red-500 @enderror"
                                       placeholder="Nama sekolah asal" required>
                                @error('asal_sekolah')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Pilihan Jalur & Jurusan -->
                <div>
                    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b-2 border-blue-600">🎯 Pilihan Program</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jalur Pendaftaran <span class="text-red-600">*</span>
                            </label>
                            <select name="ppdb_jalur_id"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ppdb_jalur_id') border-red-500 @enderror"
                                    required>
                                <option value="">-- Pilih Jalur Pendaftaran --</option>
                                @forelse($jalurs as $jalur)
                                    <option value="{{ $jalur->id }}" {{ old('ppdb_jalur_id') == $jalur->id ? 'selected' : '' }}>
                                        {{ $jalur->nama_jalur }}
                                        @if($jalur->keterangan)
                                            - {{ $jalur->keterangan }}
                                        @endif
                                    </option>
                                @empty
                                    <option disabled>Tidak ada jalur tersedia</option>
                                @endforelse
                            </select>
                            @error('ppdb_jalur_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pilihan Jurusan <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="jurusan_pilihan" value="{{ old('jurusan_pilihan') }}"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jurusan_pilihan') border-red-500 @enderror"
                                   placeholder="Contoh: IPA, IPS, Teknik Komputer" required>
                            @error('jurusan_pilihan')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Upload Berkas -->
                <div>
                    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b-2 border-orange-600">📄 Upload Berkas</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Kartu Keluarga (KK) <span class="text-red-600">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-500 transition-colors cursor-pointer"
                                 onclick="document.getElementById('berkas_kk').click()">
                                <svg class="h-12 w-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <p class="text-gray-700 font-medium">Klik untuk memilih file</p>
                                <p class="text-gray-500 text-sm">PDF, JPG, atau PNG (Maksimal 2MB)</p>
                            </div>
                            <input type="file" id="berkas_kk" name="berkas_kk" accept=".pdf,.jpg,.jpeg,.png"
                                   class="hidden @error('berkas_kk') border-red-500 @enderror" required>
                            <span id="berkas_kk_name" class="text-sm text-gray-600 mt-2 block"></span>
                            @error('berkas_kk')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Ijazah/SKHUN <span class="text-red-600">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-500 transition-colors cursor-pointer"
                                 onclick="document.getElementById('berkas_ijazah').click()">
                                <svg class="h-12 w-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <p class="text-gray-700 font-medium">Klik untuk memilih file</p>
                                <p class="text-gray-500 text-sm">PDF, JPG, atau PNG (Maksimal 2MB)</p>
                            </div>
                            <input type="file" id="berkas_ijazah" name="berkas_ijazah" accept=".pdf,.jpg,.jpeg,.png"
                                   class="hidden @error('berkas_ijazah') border-red-500 @enderror" required>
                            <span id="berkas_ijazah_name" class="text-sm text-gray-600 mt-2 block"></span>
                            @error('berkas_ijazah')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-blue-800 text-sm">
                        <span class="font-semibold">ℹ️ Penting:</span> Semua data yang Anda isi harus sesuai dengan dokumen asli. Periksa kembali sebelum mengirim.
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4 pt-4">
                    <a href="{{ route('ppdb.sekolah', $ppdbSetting->slug) }}"
                       class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors text-center">
                        ← Kembali
                    </a>
                    <button type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-lg hover:from-green-700 hover:to-green-800 transition-colors">
                        ✓ Kirim Pendaftaran
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Section -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">❓ Butuh Bantuan?</h3>
            <p class="text-gray-700 mb-4">Jika Anda mengalami kendala dalam proses pendaftaran, silakan hubungi panitia PPDB kami:</p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="https://wa.me/6281234567890" target="_blank"
                   class="flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371 0-.57 0-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004c-1.022 0-2.031.193-3.008.566-.93.361-1.764.87-2.456 1.563-.691.692-1.201 1.526-1.562 2.457-.375.98-.568 1.987-.568 3.008 0 1.019.193 2.031.568 3.008.361.931.871 1.765 1.562 2.457s1.526 1.201 2.456 1.562c.977.375 1.986.568 3.008.568 1.022 0 2.031-.193 3.008-.568.93-.361 1.764-.87 2.456-1.562.691-.692 1.201-1.526 1.562-2.457.375-.977.568-1.986.568-3.008 0-1.022-.193-2.031-.568-3.008-.361-.931-.871-1.765-1.562-2.457-.691-.692-1.526-1.201-2.456-1.562-.977-.375-1.986-.568-3.008-.568z"/></svg>
                    WhatsApp Kami
                </a>
                <a href="mailto:ppdb@nuist.id"
                   class="flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                    Email Kami
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle file upload display
    document.getElementById('berkas_kk').addEventListener('change', function(e) {
        document.getElementById('berkas_kk_name').textContent = this.files[0]?.name || '';
    });

    document.getElementById('berkas_ijazah').addEventListener('change', function(e) {
        document.getElementById('berkas_ijazah_name').textContent = this.files[0]?.name || '';
    });
</script>
@endsection
