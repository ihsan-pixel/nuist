@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="mdi mdi-cog-outline me-2 text-primary"></i>
                Pengaturan PPDB
            </h2>
            <p class="text-muted mb-0">Konfigurasi pengaturan PPDB untuk {{ $madrasah->name }}</p>
        </div>
        <a href="{{ route('ppdb.lp.dashboard') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left me-1"></i>Kembali ke Dashboard
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

    <form action="{{ route('ppdb.lp.update-ppdb-settings', $madrasah->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Status PPDB -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-toggle-switch me-2"></i>Status PPDB
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="ppdb_status" class="form-label">Status PPDB</label>
                    <div class="d-flex align-items-center gap-3">
                        <select class="form-select @error('ppdb_status') is-invalid @enderror" id="ppdb_status" name="ppdb_status" style="max-width: 200px;">
                            <option value="tutup" {{ old('ppdb_status', $ppdbSetting->ppdb_status ?? 'tutup') == 'tutup' ? 'selected' : '' }}>Tutup</option>
                            <option value="buka" {{ old('ppdb_status', $ppdbSetting->ppdb_status ?? 'tutup') == 'buka' ? 'selected' : '' }}>Buka</option>
                        </select>
                        <div class="status-indicator status-{{ old('ppdb_status', $ppdbSetting->ppdb_status ?? 'tutup') }}" style="width: 20px; height: 20px; border-radius: 50%; display: inline-block;"></div>
                        <small class="text-muted">Status akan berubah otomatis berdasarkan jadwal</small>
                        @if(old('ppdb_status', $ppdbSetting->ppdb_status ?? 'tutup') == 'buka')
                            <div class="mt-3 p-3 bg-light rounded border">
                                <div class="row align-items-center">
                                    <div class="col-md-8 text-center">
                                        <label class="form-label fw-semibold mb-2 text-center">Link PPDB:</label>
                                        <p></p>
                                        <p></p>
                                        <p></p>
                                        <p></p>
                                        <div class="text-center">
                                            <span class="text-primary fw-bold">{{ url('/ppdb/' . $madrasah->name) }}</span>
                                            <div class="mt-2">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary"
                                                    onclick="window.copyToClipboard(event, {{ json_encode(url('/ppdb/' . $madrasah->name)) }})">
                                                    <i class="mdi mdi-content-copy me-1"></i>Salin Link
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <label class="form-label fw-semibold mb-2">QR Code:</label>
                                        <div class="qr-code-container p-2 border rounded bg-white d-inline-block">
                                            <img id="qr-code-image" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/ppdb/' . $madrasah->name)) }}"
                                                 alt="QR Code untuk PPDB"
                                                 style="width: 80px; height: 80px;"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <div id="qr-fallback" style="display: none; width: 80px; height: 80px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px;"></div>
                                        </div>
                                        <small class="text-muted d-block mt-1">Scan untuk akses PPDB</small>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-1" onclick="downloadQRCode()">
                                            <i class="mdi mdi-download me-1"></i>Download QR
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    @error('ppdb_status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Jadwal PPDB -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-calendar me-2"></i>Jadwal PPDB
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ppdb_jadwal_buka" class="form-label">Jadwal Buka PPDB</label>
                            <input type="datetime-local" class="form-control @error('ppdb_jadwal_buka') is-invalid @enderror"
                                   id="ppdb_jadwal_buka" name="ppdb_jadwal_buka"
                                   value="{{ old('ppdb_jadwal_buka', $ppdbSetting->ppdb_jadwal_buka ? $ppdbSetting->ppdb_jadwal_buka->format('Y-m-d\TH:i') : '') }}">
                            @error('ppdb_jadwal_buka')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ppdb_jadwal_tutup" class="form-label">Jadwal Tutup PPDB</label>
                            <input type="datetime-local" class="form-control @error('ppdb_jadwal_tutup') is-invalid @enderror"
                                   id="ppdb_jadwal_tutup" name="ppdb_jadwal_tutup"
                                   value="{{ old('ppdb_jadwal_tutup', $ppdbSetting->ppdb_jadwal_tutup ? $ppdbSetting->ppdb_jadwal_tutup->format('Y-m-d\TH:i') : '') }}">
                            @error('ppdb_jadwal_tutup')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ppdb_jadwal_pengumuman" class="form-label">Jadwal Pengumuman</label>
                            <input type="datetime-local" class="form-control @error('ppdb_jadwal_pengumuman') is-invalid @enderror"
                                   id="ppdb_jadwal_pengumuman" name="ppdb_jadwal_pengumuman"
                                   value="{{ old('ppdb_jadwal_pengumuman', $ppdbSetting->ppdb_jadwal_pengumuman ? $ppdbSetting->ppdb_jadwal_pengumuman->format('Y-m-d\TH:i') : '') }}">
                            @error('ppdb_jadwal_pengumuman')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kuota PPDB -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-sigma me-2"></i>Kuota PPDB
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="ppdb_kuota_total" class="form-label">Kuota Total</label>
                    <input type="number" class="form-control @error('ppdb_kuota_total') is-invalid @enderror"
                                   id="ppdb_kuota_total" name="ppdb_kuota_total"
                                   value="{{ old('ppdb_kuota_total', $ppdbSetting->ppdb_kuota_total ?? '') }}" readonly>
                    <small class="text-muted">Total kuota pendaftar untuk semua jurusan (otomatis dihitung dari kuota jurusan)</small>
                    @error('ppdb_kuota_total')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kuota per Jurusan</label>
                    <small class="text-muted d-block mb-3">Kuota per jurusan otomatis mengikuti jurusan yang sudah diisi di profil madrasah.</small>
                    <div id="ppdb-quota-jurusan-container">
                        @php
                            $jurusanArray = old('jurusan', $ppdbSetting->jurusan ?? []);
                            $kuotaJurusan = old('ppdb_kuota_jurusan', $ppdbSetting->ppdb_kuota_jurusan ?? []);
                        @endphp
                        @if(is_array($jurusanArray) && count($jurusanArray) > 0)
                            @foreach($jurusanArray as $index => $jurusan)
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" value="{{ is_array($jurusan) ? $jurusan['nama'] : $jurusan }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control kuota-jurusan-input" name="ppdb_kuota_jurusan[{{ is_array($jurusan) ? $jurusan['nama'] : $jurusan }}]" value="{{ $kuotaJurusan[ is_array($jurusan) ? $jurusan['nama'] : $jurusan ] ?? '' }}" placeholder="Kuota" min="0" onchange="updateKuotaTotal()">
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-info">
                                <i class="mdi mdi-information me-2"></i>Isi jurusan terlebih dahulu di profil madrasah untuk mengatur kuota per jurusan.
                            </div>
                        @endif
                    </div>
                    <script>
                    function updateKuotaTotal() {
                        let total = 0;
                        document.querySelectorAll('.kuota-jurusan-input').forEach(function(input) {
                            let val = parseInt(input.value);
                            if (!isNaN(val)) total += val;
                        });
                        let totalInput = document.getElementById('ppdb_kuota_total');
                        if (totalInput) totalInput.value = total;
                    }
                    document.addEventListener('DOMContentLoaded', function() {
                        updateKuotaTotal();
                        document.querySelectorAll('.kuota-jurusan-input').forEach(function(input) {
                            input.addEventListener('input', updateKuotaTotal);
                        });
                    });
                    </script>
                    @error('ppdb_kuota_jurusan.*')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Jalur Pendaftaran -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-routes me-2"></i>Jalur Pendaftaran
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Pilih Jalur Pendaftaran yang Akan Diaktifkan</label>
                    <small class="text-muted d-block mb-3">Centang jalur yang ingin diaktifkan untuk PPDB ini.</small>
                    @php
                        // Get unique jalur options from existing PPDB settings
                        $jalurOptions = \App\Models\PPDBSetting::whereNotNull('ppdb_jalur')
                            ->where('ppdb_jalur', '!=', '[]')
                            ->pluck('ppdb_jalur')
                            ->flatten()
                            ->unique()
                            ->filter()
                            ->values()
                            ->toArray();

                        // If no existing jalur, provide default options
                        if (empty($jalurOptions)) {
                            $jalurOptions = ['Jalur Prestasi', 'Jalur Reguler', 'Jalur Afirmasi', 'Jalur Perpindahan', 'Jalur Khusus'];
                        }

                        // Define descriptions for each jalur
                        $jalurDescriptions = [
                            'Jalur Prestasi' => 'Untuk siswa yang memiliki prestasi akademik atau non-akademik yang luar biasa',
                            'Jalur Reguler' => 'Jalur pendaftaran standar untuk semua calon siswa',
                            'Jalur Afirmasi' => 'Jalur khusus untuk siswa dari keluarga kurang mampu atau daerah tertinggal',
                            'Jalur Perpindahan' => 'Untuk siswa yang pindah dari sekolah lain',
                            'Jalur Khusus' => 'Jalur untuk kategori siswa tertentu seperti atlet atau siswa berbakat'
                        ];

                        $selectedJalur = old('ppdb_jalur', $ppdbSetting->ppdb_jalur ?? []);
                    @endphp
                    <div class="row">
                        @foreach($jalurOptions as $jalur)
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="ppdb_jalur[]" value="{{ $jalur }}" id="jalur_{{ str_replace(' ', '_', $jalur) }}"
                                           {{ in_array($jalur, $selectedJalur) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="jalur_{{ str_replace(' ', '_', $jalur) }}">
                                        {{ $jalur }}
                                    </label>
                                    <br>
                                    <small class="text-muted">{{ $jalurDescriptions[$jalur] ?? '' }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('ppdb_jalur.*')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Biaya Pendaftaran -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-cash me-2"></i>Informasi Biaya
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="ppdb_biaya_pendaftaran" class="form-label">Biaya Pendaftaran</label>
                    <textarea class="form-control @error('ppdb_biaya_pendaftaran') is-invalid @enderror"
                              id="ppdb_biaya_pendaftaran" name="ppdb_biaya_pendaftaran" rows="3"
                              placeholder="Informasi biaya pendaftaran PPDB">{{ old('ppdb_biaya_pendaftaran', $ppdbSetting->ppdb_biaya_pendaftaran ?? '') }}</textarea>
                    @error('ppdb_biaya_pendaftaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Catatan Pengumuman -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-note-text me-2"></i>Catatan Pengumuman
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="ppdb_catatan_pengumuman" class="form-label">Catatan Pengumuman</label>
                    <textarea class="form-control @error('ppdb_catatan_pengumuman') is-invalid @enderror"
                              id="ppdb_catatan_pengumuman" name="ppdb_catatan_pengumuman" rows="3"
                              placeholder="Catatan tambahan untuk pengumuman hasil PPDB">{{ old('ppdb_catatan_pengumuman', $ppdbSetting->ppdb_catatan_pengumuman ?? '') }}</textarea>
                    @error('ppdb_catatan_pengumuman')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('ppdb.lp.dashboard') }}" class="btn btn-secondary">
                        <i class="mdi mdi-close me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save me-1"></i>Simpan Pengaturan PPDB
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Array input management
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-array-item') || e.target.closest('.add-array-item')) {
            const button = e.target.classList.contains('add-array-item') ? e.target : e.target.closest('.add-array-item');
            const targetId = button.getAttribute('data-target');
            const container = document.getElementById(targetId);

            const newItem = document.createElement('div');
            newItem.className = 'array-input-item mb-2';
            newItem.innerHTML = `
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" name="${targetId.replace('-container', '[]')}" placeholder="${getPlaceholderText(targetId)}">
                    <button type="button" class="btn btn-danger btn-remove-array remove-array-item">
                        <i class="mdi mdi-minus"></i>
                    </button>
                </div>
            `;

            // Insert before the last item (which should be empty)
            const items = container.querySelectorAll('.array-input-item');
            if (items.length > 0) {
                container.insertBefore(newItem, items[items.length - 1]);
            } else {
                container.appendChild(newItem);
            }
        }

        if (e.target.classList.contains('remove-array-item') || e.target.closest('.remove-array-item')) {
            const item = e.target.closest('.array-input-item');
            if (item) {
                item.remove();
            }
        }
    });

    function getPlaceholderText(containerId) {
        const placeholders = {
            'ppdb-jalur-container': 'Contoh: Jalur Prestasi, Jalur Reguler'
        };
        return placeholders[containerId] || '';
    }

    // Copy to clipboard function
    window.copyToClipboard = function(event, text) {
        navigator.clipboard.writeText(text).then(function() {
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="mdi mdi-check me-1"></i>Berhasil Disalin!';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-success');

            setTimeout(function() {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 2000);
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
        });
    }

    // Download QR Code function
    window.downloadQRCode = function() {
        const qrImage = document.getElementById('qr-code-image');
        if (qrImage && qrImage.src) {
            const link = document.createElement('a');
            link.href = qrImage.src;
            link.download = 'qr-code-ppdb-{{ $madrasah->name }}.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
});
</script>
@endpush
@endsection
