@extends('layouts.mobile')

@section('title', 'Detail Laporan Akhir Tahun')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Detail Laporan Akhir Tahun {{ $laporan->tahun_pelaporan }}</h4>
                        <div>
                            <a href="{{ route('mobile.laporan-akhir-tahun.edit', $laporan->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('mobile.laporan-akhir-tahun.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Data Kepala Sekolah</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Nama Kepala Sekolah</strong></td>
                                    <td>: {{ $laporan->nama_kepala_sekolah }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NIP</strong></td>
                                    <td>: {{ $laporan->nip ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NUPTK</strong></td>
                                    <td>: {{ $laporan->nuptk ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Data Madrasah</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Nama Madrasah</strong></td>
                                    <td>: {{ $laporan->nama_madrasah }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat</strong></td>
                                    <td>: {{ $laporan->alamat_madrasah }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Data Statistik</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Jumlah Guru</strong></td>
                                    <td>: {{ $laporan->jumlah_guru }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Siswa</strong></td>
                                    <td>: {{ $laporan->jumlah_siswa }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Kelas</strong></td>
                                    <td>: {{ $laporan->jumlah_kelas }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Status Laporan</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Tahun Pelaporan</strong></td>
                                    <td>: {{ $laporan->tahun_pelaporan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>: <span class="badge bg-{{ $laporan->status === 'submitted' ? 'success' : ($laporan->status === 'approved' ? 'primary' : ($laporan->status === 'rejected' ? 'danger' : 'secondary')) }}">{{ ucfirst($laporan->status) }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Laporan</strong></td>
                                    <td>: {{ $laporan->tanggal_laporan ? \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d/m/Y') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h5>Prestasi Madrasah</h5>
                            <p class="text-justify">{{ $laporan->prestasi_madrasah }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h5>Kendala Utama</h5>
                            <p class="text-justify">{{ $laporan->kendala_utama }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h5>Program Kerja Tahun Depan</h5>
                            <p class="text-justify">{{ $laporan->program_kerja_tahun_depan }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Anggaran</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Anggaran Digunakan</strong></td>
                                    <td>: Rp {{ number_format($laporan->anggaran_digunakan ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($laporan->saran_dan_masukan)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5>Saran dan Masukan</h5>
                                <p class="text-justify">{{ $laporan->saran_dan_masukan }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
