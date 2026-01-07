@extends('layouts.mobile')

@section('title', 'Edit Laporan Akhir Tahun')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit Laporan Akhir Tahun {{ $laporan->tahun_pelaporan }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('mobile.laporan-akhir-tahun.update', $laporan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Data Kepala Sekolah</h5>
                                <div class="mb-3">
                                    <label for="nama_kepala_sekolah" class="form-label">Nama Kepala Sekolah <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_kepala_sekolah') is-invalid @enderror"
                                           id="nama_kepala_sekolah" name="nama_kepala_sekolah"
                                           value="{{ old('nama_kepala_sekolah', $laporan->nama_kepala_sekolah) }}" required>
                                    @error('nama_kepala_sekolah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nip" class="form-label">NIP</label>
                                    <input type="text" class="form-control @error('nip') is-invalid @enderror"
                                           id="nip" name="nip" value="{{ old('nip', $laporan->nip) }}">
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nuptk" class="form-label">NUPTK</label>
                                    <input type="text" class="form-control @error('nuptk') is-invalid @enderror"
                                           id="nuptk" name="nuptk" value="{{ old('nuptk', $laporan->nuptk) }}">
                                    @error('nuptk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5>Data Madrasah</h5>
                                <div class="mb-3">
                                    <label for="nama_madrasah" class="form-label">Nama Madrasah <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_madrasah') is-invalid @enderror"
                                           id="nama_madrasah" name="nama_madrasah"
                                           value="{{ old('nama_madrasah', $laporan->nama_madrasah) }}" required>
                                    @error('nama_madrasah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="alamat_madrasah" class="form-label">Alamat Madrasah <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat_madrasah') is-invalid @enderror"
                                              id="alamat_madrasah" name="alamat_madrasah" rows="3" required>{{ old('alamat_madrasah', $laporan->alamat_madrasah) }}</textarea>
                                    @error('alamat_madrasah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Data Statistik</h5>
                                <div class="mb-3">
                                    <label for="jumlah_guru" class="form-label">Jumlah Guru <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('jumlah_guru') is-invalid @enderror"
                                           id="jumlah_guru" name="jumlah_guru" min="0"
                                           value="{{ old('jumlah_guru', $laporan->jumlah_guru) }}" required>
                                    @error('jumlah_guru')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="jumlah_siswa" class="form-label">Jumlah Siswa <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('jumlah_siswa') is-invalid @enderror"
                                           id="jumlah_siswa" name="jumlah_siswa" min="0"
                                           value="{{ old('jumlah_siswa', $laporan->jumlah_siswa) }}" required>
                                    @error('jumlah_siswa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="jumlah_kelas" class="form-label">Jumlah Kelas <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('jumlah_kelas') is-invalid @enderror"
                                           id="jumlah_kelas" name="jumlah_kelas" min="0"
                                           value="{{ old('jumlah_kelas', $laporan->jumlah_kelas) }}" required>
                                    @error('jumlah_kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5>Informasi Laporan</h5>
                                <div class="mb-3">
                                    <label for="tanggal_laporan" class="form-label">Tanggal Laporan <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_laporan') is-invalid @enderror"
                                           id="tanggal_laporan" name="tanggal_laporan"
                                           value="{{ old('tanggal_laporan', $laporan->tanggal_laporan ? \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('Y-m-d') : date('Y-m-d')) }}" required>
                                    @error('tanggal_laporan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status Laporan</label>
                                    <br>
                                    <span class="badge bg-{{ $laporan->status === 'submitted' ? 'success' : ($laporan->status === 'approved' ? 'primary' : ($laporan->status === 'rejected' ? 'danger' : 'secondary')) }}">
                                        {{ ucfirst($laporan->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Target dan Capaian Section -->
                        <div class="row">
                            <div class="col-12">
                                <h5>1. TARGET UTAMA</h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="target_jumlah_siswa" class="form-label">Target Jumlah Siswa</label>
                                            <input type="number" class="form-control @error('target_jumlah_siswa') is-invalid @enderror"
                                                   id="target_jumlah_siswa" name="target_jumlah_siswa" min="0"
                                                   value="{{ old('target_jumlah_siswa', $laporan->target_jumlah_siswa) }}">
                                            @error('target_jumlah_siswa')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="capaian_jumlah_siswa" class="form-label">Capaian Jumlah Siswa</label>
                                            <input type="number" class="form-control @error('capaian_jumlah_siswa') is-invalid @enderror"
                                                   id="capaian_jumlah_siswa" name="capaian_jumlah_siswa" min="0"
                                                   value="{{ old('capaian_jumlah_siswa', $laporan->capaian_jumlah_siswa) }}">
                                            @error('capaian_jumlah_siswa')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="capaian_siswa_info" class="alert alert-info mt-1" style="display: none; font-size: 12px;"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="target_dana" class="form-label">Target Dana</label>
                                            <input type="text" class="form-control @error('target_dana') is-invalid @enderror"
                                                   id="target_dana" name="target_dana"
                                                   value="{{ old('target_dana', $laporan->target_dana) }}" placeholder="Rp 0">
                                            @error('target_dana')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="capaian_dana" class="form-label">Capaian Dana</label>
                                            <input type="text" class="form-control @error('capaian_dana') is-invalid @enderror"
                                                   id="capaian_dana" name="capaian_dana"
                                                   value="{{ old('capaian_dana', $laporan->capaian_dana) }}" placeholder="Rp 0">
                                            @error('capaian_dana')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="capaian_dana_info" class="alert alert-info mt-1" style="display: none; font-size: 12px;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="target_alumni" class="form-label">Target Alumni (%)</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control @error('target_alumni') is-invalid @enderror"
                                                       id="target_alumni" name="target_alumni"
                                                       value="{{ old('target_alumni', $laporan->target_alumni) }}" placeholder="0">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            @error('target_alumni')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="target_alumni_info" class="alert alert-info mt-1" style="display: none; font-size: 12px;"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="capaian_alumni" class="form-label">Capaian Alumni (%)</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control @error('capaian_alumni') is-invalid @enderror"
                                                       id="capaian_alumni" name="capaian_alumni"
                                                       value="{{ old('capaian_alumni', $laporan->capaian_alumni) }}" placeholder="0">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            @error('capaian_alumni')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="capaian_alumni_info" class="alert alert-info mt-1" style="display: none; font-size: 12px;"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="akreditasi" class="form-label">Akreditasi</label>
                                            <select class="form-control @error('akreditasi') is-invalid @enderror" id="akreditasi" name="akreditasi">
                                                <option value="">Pilih Akreditasi</option>
                                                <option value="Belum" {{ old('akreditasi', $laporan->akreditasi) == 'Belum' ? 'selected' : '' }}>Belum</option>
                                                <option value="C" {{ old('akreditasi', $laporan->akreditasi) == 'C' ? 'selected' : '' }}>C</option>
                                                <option value="B" {{ old('akreditasi', $laporan->akreditasi) == 'B' ? 'selected' : '' }}>B</option>
                                                <option value="A" {{ old('akreditasi', $laporan->akreditasi) == 'A' ? 'selected' : '' }}>A</option>
                                            </select>
                                            @error('akreditasi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="akreditasi_info" class="alert alert-info mt-1" style="display: none; font-size: 12px;"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="tahun_akreditasi" class="form-label">Tahun Akreditasi</label>
                                            <input type="number" class="form-control @error('tahun_akreditasi') is-invalid @enderror"
                                                   id="tahun_akreditasi" name="tahun_akreditasi" min="2000" max="{{ date('Y') + 10 }}"
                                                   value="{{ old('tahun_akreditasi', $laporan->tahun_akreditasi) }}">
                                            @error('tahun_akreditasi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="nilai_akreditasi" class="form-label">Nilai Akreditasi</label>
                                            <input type="number" class="form-control @error('nilai_akreditasi') is-invalid @enderror"
                                                   id="nilai_akreditasi" name="nilai_akreditasi" min="0" max="100" step="0.01"
                                                   value="{{ old('nilai_akreditasi', $laporan->nilai_akreditasi) }}">
                                            @error('nilai_akreditasi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Skor Field -->
                                <div class="mb-3">
                                    <label class="form-label"><strong>Total Skor</strong></label>
                                    <input type="text" class="form-control" id="total_skor" value="0" readonly style="font-weight: bold; background-color: #f8f9fa;">
                                    <small class="form-text text-muted">Total skor otomatis dari capaian siswa, dana, alumni, dan akreditasi</small>
                                    <div id="total_skor_info" class="alert alert-info mt-1" style="display: none; font-size: 12px;"></div>
                                </div>

                                <!-- Student Score Info -->
                                <div id="student_score_info" class="alert alert-info mt-1" style="display: none; font-size: 12px; margin-bottom: 12px;"></div>

                                <!-- Dana Score Info -->
                                <div id="dana_score_info" class="alert alert-info mt-1" style="display: none; font-size: 12px; margin-bottom: 12px;"></div>

                                <!-- Alumni Score Info -->
                                <div id="alumni_score_info" class="alert alert-info mt-1" style="display: none; font-size: 12px; margin-bottom: 12px;"></div>

                                <!-- Scoring Information -->
                                <div class="alert alert-light border">
                                    <h6 class="mb-2">Kategori Berdasarkan Jumlah Siswa</h6>
                                    <table class="table table-sm table-borderless mb-3">
                                        <tbody>
                                            <tr><td>9</td><td>Unggulan A</td><td>>1001</td></tr>
                                            <tr><td>8</td><td>Unggulan B</td><td>751 - 1000</td></tr>
                                            <tr><td>7</td><td>Mandiri A</td><td>501 - 750</td></tr>
                                            <tr><td>6</td><td>Mandiri B</td><td>251 - 500</td></tr>
                                            <tr><td>5</td><td>Pramandiri A</td><td>151 - 250</td></tr>
                                            <tr><td>4</td><td>Pramandiri B</td><td>101 - 150</td></tr>
                                            <tr><td>3</td><td>Rintisan A</td><td>61 - 100</td></tr>
                                            <tr><td>2</td><td>Rintisan B</td><td>20 - 60</td></tr>
                                            <tr><td>1</td><td>Posisi Zero</td><td>0 - 19</td></tr>
                                        </tbody>
                                    </table>

                                    <h6 class="mb-2">Kategori Berdasarkan Jumlah Dana (Juta)</h6>
                                    <table class="table table-sm table-borderless mb-3">
                                        <tbody>
                                            <tr><td>9</td><td>Unggulan A</td><td>>5001</td></tr>
                                            <tr><td>8</td><td>Unggulan B</td><td>3001-5000</td></tr>
                                            <tr><td>7</td><td>Mandiri A</td><td>2000 - 3000</td></tr>
                                            <tr><td>6</td><td>Mandiri B</td><td>1251 - 1999</td></tr>
                                            <tr><td>5</td><td>Pramandiri A</td><td>751 - 1250</td></tr>
                                            <tr><td>4</td><td>Pramandiri B</td><td>351 - 750</td></tr>
                                            <tr><td>3</td><td>Rintisan A</td><td>151 - 350</td></tr>
                                            <tr><td>2</td><td>Rintisan B</td><td>30 - 150</td></tr>
                                            <tr><td>1</td><td>Posisi Zero</td><td>0 - 29</td></tr>
                                        </tbody>
                                    </table>

                                    <h6 class="mb-2">Kategori Berdasarkan Keterserapan Lulusan</h6>
                                    <table class="table table-sm table-borderless">
                                        <tbody>
                                            <tr><td>9</td><td>Unggulan A</td><td>81 - 100%</td></tr>
                                            <tr><td>8</td><td>Unggulan B</td><td>66 - 80%</td></tr>
                                            <tr><td>7</td><td>Mandiri A</td><td>51 - 65%</td></tr>
                                            <tr><td>6</td><td>Mandiri B</td><td>35 - 50%</td></tr>
                                            <tr><td>5</td><td>Pramandiri A</td><td>20 - 34%</td></tr>
                                            <tr><td>4</td><td>Pramandiri B</td><td>10 - 19%</td></tr>
                                            <tr><td>3</td><td>Rintisan A</td><td>3 - 9%</td></tr>
                                            <tr><td>2</td><td>Rintisan B</td><td>1 - 2%</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <h5>Prestasi Madrasah <span class="text-danger">*</span></h5>
                                <div class="mb-3">
                                    <textarea class="form-control @error('prestasi_madrasah') is-invalid @enderror"
                                              id="prestasi_madrasah" name="prestasi_madrasah" rows="4" required>{{ old('prestasi_madrasah', $laporan->prestasi_madrasah) }}</textarea>
                                    @error('prestasi_madrasah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5>Kendala Utama <span class="text-danger">*</span></h5>
                                <div class="mb-3">
                                    <textarea class="form-control @error('kendala_utama') is-invalid @enderror"
                                              id="kendala_utama" name="kendala_utama" rows="4" required>{{ old('kendala_utama', $laporan->kendala_utama) }}</textarea>
                                    @error('kendala_utama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5>Program Kerja Tahun Depan <span class="text-danger">*</span></h5>
                                <div class="mb-3">
                                    <textarea class="form-control @error('program_kerja_tahun_depan') is-invalid @enderror"
                                              id="program_kerja_tahun_depan" name="program_kerja_tahun_depan" rows="4" required>{{ old('program_kerja_tahun_depan', $laporan->program_kerja_tahun_depan) }}</textarea>
                                    @error('program_kerja_tahun_depan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Anggaran</h5>
                                <div class="mb-3">
                                    <label for="anggaran_digunakan" class="form-label">Anggaran Digunakan (Rp)</label>
                                    <input type="number" class="form-control @error('anggaran_digunakan') is-invalid @enderror"
                                           id="anggaran_digunakan" name="anggaran_digunakan" min="0" step="1000"
                                           value="{{ old('anggaran_digunakan', $laporan->anggaran_digunakan) }}">
                                    @error('anggaran_digunakan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5>Saran dan Masukan</h5>
                                <div class="mb-3">
                                    <textarea class="form-control @error('saran_dan_masukan') is-invalid @enderror"
                                              id="saran_dan_masukan" name="saran_dan_masukan" rows="3">{{ old('saran_dan_masukan', $laporan->saran_dan_masukan) }}</textarea>
                                    @error('saran_dan_masukan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input @error('pernyataan_setuju') is-invalid @enderror"
                                           type="checkbox" id="pernyataan_setuju" name="pernyataan_setuju" value="1" {{ old('pernyataan_setuju', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pernyataan_setuju">
                                        <strong>Saya menyatakan bahwa data yang saya isi adalah benar dan dapat dipertanggungjawabkan. <span class="text-danger">*</span></strong>
                                    </label>
                                    @error('pernyataan_setuju')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('mobile.laporan-akhir-tahun.show', $laporan->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Laporan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format Rupiah for dana fields
    const danaFields = ['target_dana', 'capaian_dana'];

    danaFields.forEach(fieldName => {
        const input = document.getElementById(fieldName);
        if (input) {
            // Format initial value if it exists
            if (input.value && !input.value.includes('Rp ')) {
                formatRupiah(input);
            }

            input.addEventListener('focus', function() {
                // Remove formatting when focused for editing
                let value = this.value.replace(/[^\d]/g, '');
                this.value = value;
            });
            input.addEventListener('blur', function() {
                // Format when leaving the field
                formatRupiah(this);
            });
        }
    });

    // Format percentage for alumni fields
    const alumniFields = ['target_alumni', 'capaian_alumni'];

    alumniFields.forEach(fieldName => {
        const input = document.getElementById(fieldName);
        if (input) {
            // Format initial value if it exists
            if (input.value && !input.value.includes('%')) {
                formatPercentage(input);
            }

            input.addEventListener('focus', function() {
                // Remove formatting when focused for editing
                let value = this.value.replace(/[^\d]/g, '');
                this.value = value;
            });
            input.addEventListener('blur', function() {
                // Format when leaving the field
                formatPercentage(this);
            });
        }
    });

    // Initialize dynamic info for existing values
    updateSiswaInfo('capaian_jumlah_siswa', 'capaian_siswa_info');
    updateDanaInfo('capaian_dana', 'capaian_dana_info');
    updateAlumniInfo('capaian_alumni', 'capaian_alumni_info');
    updateAkreditasiInfo();

    // Initialize total score
    updateTotalSkor();

    // Add event listeners for dynamic updates
    document.getElementById('capaian_jumlah_siswa').addEventListener('input', function() {
        updateSiswaInfo('capaian_jumlah_siswa', 'capaian_siswa_info');
        updateTotalSkor();
    });
    document.getElementById('capaian_alumni').addEventListener('input', function() {
        updateAlumniInfo('capaian_alumni', 'capaian_alumni_info');
        updateTotalSkor();
    });
    document.getElementById('capaian_dana').addEventListener('blur', function() {
        updateDanaInfo('capaian_dana', 'capaian_dana_info');
        updateTotalSkor();
    });
    document.getElementById('akreditasi').addEventListener('change', function() {
        updateAkreditasiInfo();
        updateTotalSkor();
    });

    // Before form submission, remove Rupiah and percentage formatting
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            danaFields.forEach(fieldName => {
                const input = document.getElementById(fieldName);
                if (input && input.value.includes('Rp ')) {
                    input.value = input.value.replace(/[^\d]/g, '');
                }
            });
            alumniFields.forEach(fieldName => {
                const input = document.getElementById(fieldName);
                if (input && input.value.includes('%')) {
                    input.value = input.value.replace(/[^\d]/g, '');
                }
            });
        });
    }
});

// Format number to Rupiah
function formatRupiah(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if (value) {
        value = parseInt(value).toLocaleString('id-ID');
        input.value = 'Rp ' + value;
    } else {
        input.value = '';
    }
}

// Format number to percentage
function formatPercentage(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if (value) {
        input.value = value + '%';
    } else {
        input.value = '';
    }
}

// Function to update student count info
function updateSiswaInfo(inputId, infoId) {
    const input = document.getElementById(inputId);
    const info = document.getElementById(infoId);
    const value = parseInt(input.value) || 0;

    let skor = 1;
    let kategori = 'Posisi Zero';

    if (value > 1001) {
        skor = 9;
        kategori = 'Unggulan A';
    } else if (value >= 751) {
        skor = 8;
        kategori = 'Unggulan B';
    } else if (value >= 501) {
        skor = 7;
        kategori = 'Mandiri A';
    } else if (value >= 251) {
        skor = 6;
        kategori = 'Mandiri B';
    } else if (value >= 151) {
        skor = 5;
        kategori = 'Pramandiri A';
    } else if (value >= 101) {
        skor = 4;
        kategori = 'Pramandiri B';
    } else if (value >= 61) {
        skor = 3;
        kategori = 'Rintisan A';
    } else if (value >= 20) {
        skor = 2;
        kategori = 'Rintisan B';
    }

    if (value > 0) {
        info.textContent = `Skor: ${skor}, Kategori: ${kategori}`;
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }
}

// Function to update alumni percentage info
function updateAlumniInfo(inputId, infoId) {
    const input = document.getElementById(inputId);
    const info = document.getElementById(infoId);
    const value = parseInt(input.value.replace(/[^\d]/g, '')) || 0;

    let skor = 2;
    let kategori = 'Rintisan B';

    if (value >= 81) {
        skor = 9;
        kategori = 'Unggulan A';
    } else if (value >= 66) {
        skor = 8;
        kategori = 'Unggulan B';
    } else if (value >= 51) {
        skor = 7;
        kategori = 'Mandiri A';
    } else if (value >= 35) {
        skor = 6;
        kategori = 'Mandiri B';
    } else if (value >= 20) {
        skor = 5;
        kategori = 'Pramandiri A';
    } else if (value >= 10) {
        skor = 4;
        kategori = 'Pramandiri B';
    } else if (value >= 3) {
        skor = 3;
        kategori = 'Rintisan A';
    }

    if (value > 0) {
        info.textContent = `Skor: ${skor}, Kategori: ${kategori}`;
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }
}

// Function to update dana info
function updateDanaInfo(inputId, infoId) {
    const input = document.getElementById(inputId);
    const info = document.getElementById(infoId);
    const rawValue = parseInt(input.value.replace(/[^\d]/g, '')) || 0;
    const value = Math.floor(rawValue / 1000000); // Convert to millions

    let skor = 1;
    let kategori = 'Posisi Zero';

    if (value > 5001) {
        skor = 9;
        kategori = 'Unggulan A';
    } else if (value >= 3001) {
        skor = 8;
        kategori = 'Unggulan B';
    } else if (value >= 2000) {
        skor = 7;
        kategori = 'Mandiri A';
    } else if (value >= 1251) {
        skor = 6;
        kategori = 'Mandiri B';
    } else if (value >= 751) {
        skor = 5;
        kategori = 'Pramandiri A';
    } else if (value >= 351) {
        skor = 4;
        kategori = 'Pramandiri B';
    } else if (value >= 151) {
        skor = 3;
        kategori = 'Rintisan A';
    } else if (value >= 30) {
        skor = 2;
        kategori = 'Rintisan B';
    }

    if (rawValue > 0) {
        info.textContent = `Skor: ${skor}, Kategori: ${kategori}`;
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }
}

// Function to update accreditation info
function updateAkreditasiInfo() {
    const select = document.getElementById('akreditasi');
    const info = document.getElementById('akreditasi_info');
    const value = select.value;

    let skor = 1; // Default for "Belum"
    let kategori = 'Belum';

    if (value === 'A') {
        skor = 10;
        kategori = 'Unggulan A';
    } else if (value === 'B') {
        skor = 7;
        kategori = 'Mandiri A';
    } else if (value === 'C') {
        skor = 4;
        kategori = 'Rintisan A';
    }

    if (value) {
        info.textContent = `Skor: ${skor}, Kategori: ${kategori}`;
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }
}

    // Function to calculate and update total score
    function updateTotalSkor() {
        let totalSkor = 0;
        let skorTambahanSiswa = 0;
        let skorTambahanDana = 0;
        let skorTambahanAlumni = 0;

        // Get capaian siswa score (based on student count)
        const capaianSiswaInput = document.getElementById('capaian_jumlah_siswa');
        if (capaianSiswaInput) {
            const siswaValue = parseInt(capaianSiswaInput.value) || 0;
            if (siswaValue > 1001) totalSkor += 9;
            else if (siswaValue >= 751) totalSkor += 8;
            else if (siswaValue >= 501) totalSkor += 7;
            else if (siswaValue >= 251) totalSkor += 6;
            else if (siswaValue >= 151) totalSkor += 5;
            else if (siswaValue >= 101) totalSkor += 4;
            else if (siswaValue >= 61) totalSkor += 3;
            else if (siswaValue >= 20) totalSkor += 2;
            else if (siswaValue > 0) totalSkor += 1;
        }

        // Get capaian dana score (based on dana amount)
        const capaianDanaInput = document.getElementById('capaian_dana');
        if (capaianDanaInput) {
            const danaRawValue = parseInt(capaianDanaInput.value.replace(/[^\d]/g, '')) || 0;
            const danaValue = Math.floor(danaRawValue / 1000000); // Convert to millions
            if (danaValue > 5001) totalSkor += 9;
            else if (danaValue >= 3001) totalSkor += 8;
            else if (danaValue >= 2000) totalSkor += 7;
            else if (danaValue >= 1251) totalSkor += 6;
            else if (danaValue >= 751) totalSkor += 5;
            else if (danaValue >= 351) totalSkor += 4;
            else if (danaValue >= 151) totalSkor += 3;
            else if (danaValue >= 30) totalSkor += 2;
            else if (danaRawValue > 0) totalSkor += 1;
        }

        // Get capaian alumni score (based on alumni percentage)
        const capaianAlumniInput = document.getElementById('capaian_alumni');
        if (capaianAlumniInput) {
            const alumniValue = parseInt(capaianAlumniInput.value.replace(/[^\d]/g, '')) || 0;
            if (alumniValue >= 81) totalSkor += 9;
            else if (alumniValue >= 66) totalSkor += 8;
            else if (alumniValue >= 51) totalSkor += 7;
            else if (alumniValue >= 35) totalSkor += 6;
            else if (alumniValue >= 20) totalSkor += 5;
            else if (alumniValue >= 10) totalSkor += 4;
            else if (alumniValue >= 3) totalSkor += 3;
            else if (alumniValue >= 1) totalSkor += 2;
        }

        // Get skor tambahan siswa (additional score based on target vs achievement)
        const targetSiswaInput = document.getElementById('target_jumlah_siswa');
        const capaianSiswaInput2 = document.getElementById('capaian_jumlah_siswa');
        if (targetSiswaInput && capaianSiswaInput2 && targetSiswaInput.value.trim() !== '' && capaianSiswaInput2.value.trim() !== '') {
            const targetValue = parseInt(targetSiswaInput.value) || 0;
            const capaianValue = parseInt(capaianSiswaInput2.value) || 0;

            if (capaianValue < targetValue) {
                skorTambahanSiswa = 0; // turun
            } else if (capaianValue === targetValue) {
                skorTambahanSiswa = 1; // tetap
            } else if (capaianValue > targetValue) {
                skorTambahanSiswa = 2; // naik
            }

            totalSkor += skorTambahanSiswa;
        }

        // Get skor tambahan dana (additional score based on target vs achievement)
        const targetDanaInput = document.getElementById('target_dana');
        const capaianDanaInput2 = document.getElementById('capaian_dana');
        if (targetDanaInput && capaianDanaInput2 && targetDanaInput.value.trim() !== '' && capaianDanaInput2.value.replace(/[^\d]/g, '').trim() !== '') {
            const targetValue = parseInt(targetDanaInput.value.replace(/[^\d]/g, '')) || 0;
            const capaianValue = parseInt(capaianDanaInput2.value.replace(/[^\d]/g, '')) || 0;

            if (capaianValue < targetValue) {
                skorTambahanDana = 0; // turun
            } else if (capaianValue === targetValue) {
                skorTambahanDana = 1; // tetap
            } else if (capaianValue > targetValue) {
                skorTambahanDana = 2; // naik
            }

            totalSkor += skorTambahanDana;
        }

        // Get skor tambahan alumni (additional score based on target vs achievement)
        const targetAlumniInput = document.getElementById('target_alumni');
        const capaianAlumniInput2 = document.getElementById('capaian_alumni');
        if (targetAlumniInput && capaianAlumniInput2 && targetAlumniInput.value.replace(/[^\d]/g, '').trim() !== '' && capaianAlumniInput2.value.replace(/[^\d]/g, '').trim() !== '') {
            const targetValue = parseInt(targetAlumniInput.value.replace(/[^\d]/g, '')) || 0;
            const capaianValue = parseInt(capaianAlumniInput2.value.replace(/[^\d]/g, '')) || 0;

            if (capaianValue < targetValue) {
                skorTambahanAlumni = 0; // turun
            } else if (capaianValue === targetValue) {
                skorTambahanAlumni = 1; // tetap
            } else if (capaianValue > targetValue) {
                skorTambahanAlumni = 2; // naik
            }

            totalSkor += skorTambahanAlumni;
        }

        // Get akreditasi score
        const akreditasiSelect = document.getElementById('akreditasi');
        if (akreditasiSelect) {
            const akreditasiValue = akreditasiSelect.value;
            if (akreditasiValue === 'A') totalSkor += 10;
            else if (akreditasiValue === 'B') totalSkor += 7;
            else if (akreditasiValue === 'C') totalSkor += 4;
            else if (akreditasiValue === 'Belum') totalSkor += 1;
        }

        // Update total score field
        const totalSkorField = document.getElementById('total_skor');
        if (totalSkorField) {
            totalSkorField.value = totalSkor;
        }

        // Update student score info only if target and capaian are filled
        if (targetSiswaInput && capaianSiswaInput2 && targetSiswaInput.value.trim() !== '' && capaianSiswaInput2.value.trim() !== '') {
            updateStudentScoreInfo(skorTambahanSiswa);
        } else {
            const info = document.getElementById('student_score_info');
            info.style.display = 'none';
        }

        // Update dana score info only if target and capaian are filled
        if (targetDanaInput && capaianDanaInput2 && targetDanaInput.value.trim() !== '' && capaianDanaInput2.value.replace(/[^\d]/g, '').trim() !== '') {
            updateDanaScoreInfo(skorTambahanDana);
        } else {
            const info = document.getElementById('dana_score_info');
            info.style.display = 'none';
        }

        // Update alumni score info only if target and capaian are filled
        if (targetAlumniInput && capaianAlumniInput2 && targetAlumniInput.value.replace(/[^\d]/g, '').trim() !== '' && capaianAlumniInput2.value.replace(/[^\d]/g, '').trim() !== '') {
            updateAlumniScoreInfo(skorTambahanAlumni);
        } else {
            const info = document.getElementById('alumni_score_info');
            info.style.display = 'none';
        }

        // Update total score info
        updateTotalSkorInfo(totalSkor);
    }

    // Function to update student score info
    function updateStudentScoreInfo(siswaScore) {
        const info = document.getElementById('student_score_info');
        let scoreText = '';

        if (siswaScore === 0) {
            scoreText = 'Skor Siswa: 0 (turun)';
        } else if (siswaScore === 1) {
            scoreText = 'Skor Siswa: 1 (tetap)';
        } else if (siswaScore === 2) {
            scoreText = 'Skor Siswa: 2 (naik)';
        }

        if (scoreText) {
            info.textContent = scoreText;
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }
    }

    // Function to update dana score info
    function updateDanaScoreInfo(danaScore) {
        const info = document.getElementById('dana_score_info');
        let scoreText = '';

        if (danaScore === 0) {
            scoreText = 'Skor Dana: 0 (turun)';
        } else if (danaScore === 1) {
            scoreText = 'Skor Dana: 1 (tetap)';
        } else if (danaScore === 2) {
            scoreText = 'Skor Dana: 2 (naik)';
        }

        if (scoreText) {
            info.textContent = scoreText;
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }
    }

    // Function to update alumni score info
    function updateAlumniScoreInfo(alumniScore) {
        const info = document.getElementById('alumni_score_info');
        let scoreText = '';

        if (alumniScore === 0) {
            scoreText = 'Skor Alumni: 0 (turun)';
        } else if (alumniScore === 1) {
            scoreText = 'Skor Alumni: 1 (tetap)';
        } else if (alumniScore === 2) {
            scoreText = 'Skor Alumni: 2 (naik)';
        }

        if (scoreText) {
            info.textContent = scoreText;
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }
    }

    // Function to update total score info
    function updateTotalSkorInfo(totalSkor) {
        const info = document.getElementById('total_skor_info');
        let kategori = '';

        if (totalSkor >= 0 && totalSkor <= 5) {
            kategori = 'Sangat Lemah';
        } else if (totalSkor >= 6 && totalSkor <= 16) {
            kategori = 'Lemah';
        } else if (totalSkor >= 17 && totalSkor <= 25) {
            kategori = 'Rintisan';
        } else if (totalSkor >= 26 && totalSkor <= 33) {
            kategori = 'Cukup';
        } else if (totalSkor >= 34 && totalSkor <= 38) {
            kategori = 'Mandiri (Kuat)';
        } else if (totalSkor >= 39 && totalSkor <= 43) {
            kategori = 'Unggul';
        }

        if (kategori) {
            info.textContent = `Kategori: ${kategori}`;
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }
    }
</script>
