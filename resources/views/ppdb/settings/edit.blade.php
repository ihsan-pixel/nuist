@extends('layouts.master')

@section('title', 'Edit Pengaturan PPDB - ' . $ppdbSetting->nama_sekolah)

@push('css')
<style>
    .settings-form {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .form-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #004b4c;
    }

    .section-title {
        color: #004b4c;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #004b4c;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #004b4c;
        box-shadow: 0 0 0 0.2rem rgba(0, 75, 76, 0.25);
    }

    .status-toggle {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .status-indicator {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-buka {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .status-tutup {
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
    }

    .quota-jurusan-container {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        background: white;
        margin-top: 1rem;
    }

    .quota-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.5rem;
        padding: 0.5rem;
        background: #f8f9fa;
        border-radius: 6px;
    }

    .quota-item input {
        flex: 1;
    }

    .quota-item .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }

    .btn-add-quota {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    .btn-add-quota:hover {
        background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        color: white;
    }

    .checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #004b4c;
    }

    .btn-submit {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 75, 76, 0.3);
        color: white;
    }

    .btn-cancel {
        background: #6c757d;
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        margin-right: 1rem;
    }

    .btn-cancel:hover {
        background: #5a6268;
        color: white;
    }

    .help-text {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .required-field::after {
        content: ' *';
        color: #dc3545;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .settings-form {
            padding: 1rem;
        }

        .form-section {
            padding: 1rem;
        }

        .status-toggle {
            flex-direction: column;
            align-items: flex-start;
        }

        .checkbox-group {
            grid-template-columns: 1fr;
        }

        .btn-submit, .btn-cancel {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .btn-cancel {
            margin-right: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="mdi mdi-cog-outline me-2 text-primary"></i>
                Edit Pengaturan PPDB
            </h2>
            <p class="text-muted mb-0">{{ $ppdbSetting->nama_sekolah }} - Tahun {{ $ppdbSetting->tahun }}</p>
        </div>
        <a href="{{ route('ppdb.settings.index') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('ppdb.settings.update', $ppdbSetting->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Status dan Jadwal PPDB -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="mdi mdi-calendar-clock"></i>Status dan Jadwal PPDB
            </h3>

            <div class="form-group">
                <label for="status" class="form-label required-field">Status PPDB</label>
                <div class="status-toggle">
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="tutup" {{ old('status', $ppdbSetting->status) == 'tutup' ? 'selected' : '' }}>Tutup</option>
                        <option value="buka" {{ old('status', $ppdbSetting->status) == 'buka' ? 'selected' : '' }}>Buka</option>
                    </select>
                    <div class="status-indicator status-{{ old('status', $ppdbSetting->status) }}"></div>
                    <small class="text-muted">Status akan berubah otomatis berdasarkan jadwal yang ditentukan</small>
                </div>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jadwal_buka" class="form-label required-field">Jadwal Buka</label>
                        <input type="datetime-local" class="form-control @error('jadwal_buka') is-invalid @enderror"
                               id="jadwal_buka" name="jadwal_buka"
                               value="{{ old('jadwal_buka', $ppdbSetting->jadwal_buka ? $ppdbSetting->jadwal_buka->format('Y-m-d\TH:i') : '') }}" required>
                        @error('jadwal_buka')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jadwal_tutup" class="form-label required-field">Jadwal Tutup</label>
                        <input type="datetime-local" class="form-control @error('jadwal_tutup') is-invalid @enderror"
                               id="jadwal_tutup" name="jadwal_tutup"
                               value="{{ old('jadwal_tutup', $ppdbSetting->jadwal_tutup ? $ppdbSetting->jadwal_tutup->format('Y-m-d\TH:i') : '') }}" required>
                        @error('jadwal_tutup')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Kuota Pendaftar -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="mdi mdi-account-group"></i>Kuota Pendaftar
            </h3>

            <div class="form-group">
                <label for="kuota_total" class="form-label required-field">Kuota Total</label>
                <input type="number" class="form-control @error('kuota_total') is-invalid @enderror"
                       id="kuota_total" name="kuota_total" value="{{ old('kuota_total', $ppdbSetting->kuota_total) }}" min="1" required>
                <div class="help-text">Total kuota pendaftar untuk semua jurusan</div>
                @error('kuota_total')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kuota per Jurusan</label>
                <div class="help-text">Tentukan kuota khusus untuk setiap jurusan (opsional)</div>

                <div id="quota-jurusan-container" class="quota-jurusan-container">
                    @php $kuotaJurusan = old('kuota_jurusan', $ppdbSetting->kuota_jurusan ?? []); @endphp
                    @if(is_array($kuotaJurusan) && count($kuotaJurusan) > 0)
                        @foreach($kuotaJurusan as $jurusan => $kuota)
                            <div class="quota-item">
                                <input type="text" class="form-control" name="kuota_jurusan[{{ $jurusan }}]" value="{{ $kuota }}" placeholder="Nama Jurusan">
                                <input type="number" class="form-control" name="kuota_jurusan[{{ $jurusan }}]" value="{{ $kuota }}" placeholder="Kuota" min="0">
                                <button type="button" class="btn btn-danger btn-sm remove-quota">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                    <div class="quota-item">
                        <input type="text" class="form-control" name="kuota_jurusan[]" placeholder="Nama Jurusan">
                        <input type="number" class="form-control" name="kuota_jurusan[]" placeholder="Kuota" min="0">
                        <button type="button" class="btn btn-danger btn-sm remove-quota">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>

                <button type="button" class="btn btn-add-quota" id="add-quota-btn">
                    <i class="mdi mdi-plus me-1"></i>Tambah Kuota Jurusan
                </button>
                @error('kuota_jurusan.*')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Periode Presensi -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="mdi mdi-clipboard-check"></i>Periode Presensi
            </h3>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="periode_presensi_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control @error('periode_presensi_mulai') is-invalid @enderror"
                               id="periode_presensi_mulai" name="periode_presensi_mulai"
                               value="{{ old('periode_presensi_mulai', $ppdbSetting->periode_presensi_mulai ? $ppdbSetting->periode_presensi_mulai->format('Y-m-d') : '') }}">
                        @error('periode_presensi_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="periode_presensi_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control @error('periode_presensi_selesai') is-invalid @enderror"
                               id="periode_presensi_selesai" name="periode_presensi_selesai"
                               value="{{ old('periode_presensi_selesai', $ppdbSetting->periode_presensi_selesai ? $ppdbSetting->periode_presensi_selesai->format('Y-m-d') : '') }}">
                        @error('periode_presensi_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Persyaratan Upload -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="mdi mdi-file-upload"></i>Persyaratan Upload
            </h3>

            <div class="form-group">
                <label class="form-label">Dokumen yang Wajib Diupload</label>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" id="wajib_unggah_foto" name="wajib_unggah_foto" value="1"
                               {{ old('wajib_unggah_foto', $ppdbSetting->wajib_unggah_foto) ? 'checked' : '' }}>
                        <label for="wajib_unggah_foto">Foto</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="wajib_unggah_ijazah" name="wajib_unggah_ijazah" value="1"
                               {{ old('wajib_unggah_ijazah', $ppdbSetting->wajib_unggah_ijazah) ? 'checked' : '' }}>
                        <label for="wajib_unggah_ijazah">Ijazah</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="wajib_unggah_kk" name="wajib_unggah_kk" value="1"
                               {{ old('wajib_unggah_kk', $ppdbSetting->wajib_unggah_kk) ? 'checked' : '' }}>
                        <label for="wajib_unggah_kk">Kartu Keluarga</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="syarat_tambahan" class="form-label">Syarat Tambahan</label>
                <textarea class="form-control @error('syarat_tambahan') is-invalid @enderror"
                          id="syarat_tambahan" name="syarat_tambahan" rows="3"
                          placeholder="Syarat tambahan yang harus dipenuhi calon siswa">{{ old('syarat_tambahan', $ppdbSetting->syarat_tambahan) }}</textarea>
                @error('syarat_tambahan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Kontak dan Informasi -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="mdi mdi-phone"></i>Kontak dan Informasi
            </h3>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="telepon_kontak" class="form-label">Telepon Kontak</label>
                        <input type="text" class="form-control @error('telepon_kontak') is-invalid @enderror"
                               id="telepon_kontak" name="telepon_kontak"
                               value="{{ old('telepon_kontak', $ppdbSetting->telepon_kontak) }}"
                               placeholder="(021) 1234567">
                        @error('telepon_kontak')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email_kontak" class="form-label">Email Kontak</label>
                        <input type="email" class="form-control @error('email_kontak') is-invalid @enderror"
                               id="email_kontak" name="email_kontak"
                               value="{{ old('email_kontak', $ppdbSetting->email_kontak) }}"
                               placeholder="info@madrasah.com">
                        @error('email_kontak')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="alamat_kontak" class="form-label">Alamat Kontak</label>
                <textarea class="form-control @error('alamat_kontak') is-invalid @enderror"
                          id="alamat_kontak" name="alamat_kontak" rows="2"
                          placeholder="Alamat lengkap untuk informasi PPDB">{{ old('alamat_kontak', $ppdbSetting->alamat_kontak) }}</textarea>
                @error('alamat_kontak')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Pengumuman Hasil -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="mdi mdi-bullhorn"></i>Pengumuman Hasil
            </h3>

            <div class="form-group">
                <label for="jadwal_pengumuman" class="form-label">Jadwal Pengumuman</label>
                <input type="datetime-local" class="form-control @error('jadwal_pengumuman') is-invalid @enderror"
                       id="jadwal_pengumuman" name="jadwal_pengumuman"
                       value="{{ old('jadwal_pengumuman', $ppdbSetting->jadwal_pengumuman ? $ppdbSetting->jadwal_pengumuman->format('Y-m-d\TH:i') : '') }}">
                @error('jadwal_pengumuman')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="catatan_pengumuman" class="form-label">Catatan Pengumuman</label>
                <textarea class="form-control @error('catatan_pengumuman') is-invalid @enderror"
                          id="catatan_pengumuman" name="catatan_pengumuman" rows="3"
                          placeholder="Catatan tambahan untuk pengumuman hasil PPDB">{{ old('catatan_pengumuman', $ppdbSetting->catatan_pengumuman) }}</textarea>
                @error('catatan_pengumuman')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Informasi Sekolah -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="mdi mdi-school"></i>Informasi Sekolah
            </h3>

            <div class="form-group">
                <label for="visi" class="form-label">Visi Sekolah</label>
                <textarea class="form-control @error('visi') is-invalid @enderror"
                          id="visi" name="visi" rows="3"
                          placeholder="Masukkan visi sekolah">{{ old('visi', $ppdbSetting->visi) }}</textarea>
                @error('visi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="misi" class="form-label">Misi Sekolah</label>
                <textarea class="form-control @error('misi') is-invalid @enderror"
                          id="misi" name="misi" rows="4"
                          placeholder="Masukkan misi sekolah">{{ old('misi', $ppdbSetting->misi) }}</textarea>
                @error('misi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="fasilitas" class="form-label">Fasilitas Sekolah</label>
                <textarea class="form-control @error('fasilitas') is-invalid @enderror"
                          id="fasilitas" name="fasilitas" rows="4"
                          placeholder="Jelaskan fasilitas yang tersedia di sekolah">{{ old('fasilitas', $ppdbSetting->fasilitas) }}</textarea>
                @error('fasilitas')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="prestasi" class="form-label">Prestasi Sekolah</label>
                <textarea class="form-control @error('prestasi') is-invalid @enderror"
                          id="prestasi" name="prestasi" rows="4"
                          placeholder="Sebutkan prestasi-prestasi yang telah diraih sekolah">{{ old('prestasi', $ppdbSetting->prestasi) }}</textarea>
                @error('prestasi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="ekstrakurikuler" class="form-label">Ekstrakurikuler</label>
                <textarea class="form-control @error('ekstrakurikuler') is-invalid @enderror"
                          id="ekstrakurikuler" name="ekstrakurikuler" rows="4"
                          placeholder="Jelaskan kegiatan ekstrakurikuler yang tersedia">{{ old('ekstrakurikuler', $ppdbSetting->ekstrakurikuler) }}</textarea>
                @error('ekstrakurikuler')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="biaya_pendidikan" class="form-label">Biaya Pendidikan</label>
                <textarea class="form-control @error('biaya_pendidikan') is-invalid @enderror"
                          id="biaya_pendidikan" name="biaya_pendidikan" rows="4"
                          placeholder="Informasi biaya pendidikan dan keuangan">{{ old('biaya_pendidikan', $ppdbSetting->biaya_pendidikan) }}</textarea>
                @error('biaya_pendidikan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="informasi_tambahan" class="form-label">Informasi Tambahan</label>
                <textarea class="form-control @error('informasi_tambahan') is-invalid @enderror"
                          id="informasi_tambahan" name="informasi_tambahan" rows="4"
                          placeholder="Informasi tambahan lainnya yang perlu diketahui calon siswa">{{ old('informasi_tambahan', $ppdbSetting->informasi_tambahan) }}</textarea>
                @error('informasi_tambahan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="settings-form">
            <div class="d-flex justify-content-end">
                <a href="{{ route('ppdb.settings.index') }}" class="btn btn-cancel">
                    <i class="mdi mdi-close me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-submit">
                    <i class="mdi mdi-content-save me-1"></i>Simpan Pengaturan
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status indicator update
    const statusSelect = document.getElementById('status');
    const statusIndicator = document.querySelector('.status-indicator');

    function updateStatusIndicator() {
        const status = statusSelect.value;
        statusIndicator.className = 'status-indicator status-' + status;
    }

    statusSelect.addEventListener('change', updateStatusIndicator);
    updateStatusIndicator();

    // Add quota jurusan functionality
    const addQuotaBtn = document.getElementById('add-quota-btn');
    const quotaContainer = document.getElementById('quota-jurusan-container');

    addQuotaBtn.addEventListener('click', function() {
        const quotaItem = document.createElement('div');
        quotaItem.className = 'quota-item';
        quotaItem.innerHTML = `
            <input type="text" class="form-control" name="kuota_jurusan[]" placeholder="Nama Jurusan">
            <input type="number" class="form-control" name="kuota_jurusan[]" placeholder="Kuota" min="0">
            <button type="button" class="btn btn-danger btn-sm remove-quota">
                <i class="mdi mdi-minus"></i>
            </button>
        `;
        quotaContainer.appendChild(quotaItem);
    });

    // Remove quota functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-quota') || e.target.closest('.remove-quota')) {
            const quotaItem = e.target.closest('.quota-item');
            if (quotaItem) {
                quotaItem.remove();
            }
        }
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Clean up empty quota inputs
        const quotaInputs = form.querySelectorAll('input[name="kuota_jurusan[]"]');
        quotaInputs.forEach(input => {
            if (!input.value.trim()) {
                input.remove();
            }
        });
    });
});
</script>
@endpush
@endsection
