@extends('layouts.mobile')

@section('title', 'Buat Laporan Akhir Tahun')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Buat Laporan Akhir Tahun</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('mobile.laporan-akhir-tahun.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Data Kepala Sekolah</h5>
                                <div class="mb-3">
                                    <label for="nama_kepala_sekolah" class="form-label">Nama Kepala Sekolah <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_kepala_sekolah') is-invalid @enderror"
                                           id="nama_kepala_sekolah" name="nama_kepala_sekolah"
                                           value="{{ old('nama_kepala_sekolah', $data['nama_kepala_sekolah'] ?? '') }}" required>
                                    @error('nama_kepala_sekolah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nip" class="form-label">NIP</label>
                                    <input type="text" class="form-control @error('nip') is-invalid @enderror"
                                           id="nip" name="nip" value="{{ old('nip', $data['nip'] ?? '') }}">
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nuptk" class="form-label">NUPTK</label>
                                    <input type="text" class="form-control @error('nuptk') is-invalid @enderror"
                                           id="nuptk" name="nuptk" value="{{ old('nuptk', $data['nuptk'] ?? '') }}">
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
                                           value="{{ old('nama_madrasah', $data['nama_madrasah'] ?? '') }}" required>
                                    @error('nama_madrasah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="alamat_madrasah" class="form-label">Alamat Madrasah <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat_madrasah') is-invalid @enderror"
                                              id="alamat_madrasah" name="alamat_madrasah" rows="3" required>{{ old('alamat_madrasah', $data['alamat_madrasah'] ?? '') }}</textarea>
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
                                           value="{{ old('jumlah_guru') }}" required>
                                    @error('jumlah_guru')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="jumlah_siswa" class="form-label">Jumlah Siswa <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('jumlah_siswa') is-invalid @enderror"
                                           id="jumlah_siswa" name="jumlah_siswa" min="0"
                                           value="{{ old('jumlah_siswa') }}" required>
                                    @error('jumlah_siswa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="jumlah_kelas" class="form-label">Jumlah Kelas <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('jumlah_kelas') is-invalid @enderror"
                                           id="jumlah_kelas" name="jumlah_kelas" min="0"
                                           value="{{ old('jumlah_kelas') }}" required>
                                    @error('jumlah_kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5>Informasi Laporan</h5>
                                <div class="mb-3">
                                    <label for="tahun_pelaporan" class="form-label">Tahun Pelaporan <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('tahun_pelaporan') is-invalid @enderror"
                                           id="tahun_pelaporan" name="tahun_pelaporan" min="2020" max="{{ date('Y') + 1 }}"
                                           value="{{ old('tahun_pelaporan', $data['tahun_pelaporan'] ?? date('Y')) }}" required>
                                    @error('tahun_pelaporan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tanggal_laporan" class="form-label">Tanggal Laporan <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_laporan') is-invalid @enderror"
                                           id="tanggal_laporan" name="tanggal_laporan"
                                           value="{{ old('tanggal_laporan', date('Y-m-d')) }}" required>
                                    @error('tanggal_laporan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <h5>Prestasi Madrasah <span class="text-danger">*</span></h5>
                                <div class="mb-3">
                                    <textarea class="form-control @error('prestasi_madrasah') is-invalid @enderror"
                                              id="prestasi_madrasah" name="prestasi_madrasah" rows="4"
                                              placeholder="Jelaskan prestasi-prestasi yang telah dicapai oleh madrasah selama tahun ini..." required>{{ old('prestasi_madrasah') }}</textarea>
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
                                              id="kendala_utama" name="kendala_utama" rows="4"
                                              placeholder="Jelaskan kendala-kendala utama yang dihadapi selama tahun ini..." required>{{ old('kendala_utama') }}</textarea>
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
                                              id="program_kerja_tahun_depan" name="program_kerja_tahun_depan" rows="4"
                                              placeholder="Jelaskan program-program kerja yang akan dilaksanakan pada tahun depan..." required>{{ old('program_kerja_tahun_depan') }}</textarea>
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
                                           value="{{ old('anggaran_digunakan') }}">
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
                                              id="saran_dan_masukan" name="saran_dan_masukan" rows="3"
                                              placeholder="Berikan saran dan masukan untuk pengembangan madrasah...">{{ old('saran_dan_masukan') }}</textarea>
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
                                           type="checkbox" id="pernyataan_setuju" name="pernyataan_setuju" value="1" {{ old('pernyataan_setuju') ? 'checked' : '' }}>
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
                                    <a href="{{ route('mobile.laporan-akhir-tahun.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Laporan
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
