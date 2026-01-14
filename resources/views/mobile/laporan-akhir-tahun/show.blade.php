@extends('layouts.mobile')

@section('title', 'Detail Laporan Akhir Tahun')

@section('content')
<link rel="stylesheet" href="{{ asset('css/mobile/laporan-akhir-tahun-create.css') }}">

<style>
    body {
        background: #f8f9fb url('/images/bg.png') no-repeat center center;
        background-size: cover;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
    }
</style>

<!-- Header -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='{{ route('mobile.laporan-akhir-tahun.index') }}'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #ffffff;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #ffffff; font-size: 12px;">Kembali</span>
</div>

<div class="simfoni-header" style="margin-top: -10px;">
    <h4>HASIL LAPORAN AKHIR TAHUN</h4>
    <p>Kepala Sekolah/Madrasah</p>
</div>

<!-- Report Container -->
<div class="form-container">
    <!-- Report Header -->
    <div class="info-group" style="margin-bottom: 24px;">
        <div class="report-info-grid">
            <div class="info-item">
                <label>Tahun Pelaporan</label>
                <span>{{ $laporan->tahun_pelaporan }}</span>
            </div>
            <div class="info-item">
                <label>Status Laporan</label>
                <span class="badge bg">Berhasil Terkirim</span>
            </div>
            <div class="info-item">
                <label>Tanggal Laporan</label>
                <span>{{ $laporan->created_at ? \Carbon\Carbon::parse($laporan->created_at)->format('d/m/Y') : '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Information Sections -->
    <div class="info-grid">
        <!-- Step 1: Identitas Satpen -->
        <div class="info-group">
            <h5 class="info-group-title">1. Identitas Satpen</h5>
            <div class="info-row">
                <span class="info-label">Nama Satpen</span>
                <span class="info-value">{{ $laporan->nama_satpen ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Alamat</span>
                <span class="info-value">{{ $laporan->alamat ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama Kepala Sekolah/Madrasah</span>
                <span class="info-value">{{ $laporan->nama_kepala_sekolah_madrasah ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Gelar</span>
                <span class="info-value">{{ $laporan->gelar ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">TMT KS/Kamad Pertama</span>
                <span class="info-value">{{ $laporan->tmt_ks_kamad_pertama ? \Carbon\Carbon::parse($laporan->tmt_ks_kamad_pertama)->format('d/m/Y') : '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">TMT KS/Kamad Terakhir</span>
                <span class="info-value">{{ $laporan->tmt_ks_kamad_terakhir ? \Carbon\Carbon::parse($laporan->tmt_ks_kamad_terakhir)->format('d/m/Y') : '-' }}</span>
            </div>
            @if($laporan->lampiran_step_1)
                <div class="info-row">
                    <span class="info-label">Lampiran</span>
                    <span class="info-value">
                        <a href="{{ asset($laporan->lampiran_step_1) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-file"></i> Lihat File
                        </a>
                    </span>
                </div>
            @endif
        </div>

        <!-- Step 2: Capaian Utama 3 Tahun Berjalan -->
        <div class="info-group">
            <h5 class="info-group-title">2. Capaian Utama 3 Tahun Berjalan</h5>
            <div class="info-row">
                <span class="info-label">Jumlah Siswa 2023</span>
                <span class="info-value">{{ $laporan->jumlah_siswa_2023 ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jumlah Siswa 2024</span>
                <span class="info-value">{{ $laporan->jumlah_siswa_2024 ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jumlah Siswa 2025</span>
                <span class="info-value">{{ $laporan->jumlah_siswa_2025 ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Persentase Alumni Bekerja</span>
                <span class="info-value">{{ $laporan->persentase_alumni_bekerja ? $laporan->persentase_alumni_bekerja . '%' : '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Persentase Alumni Wirausaha</span>
                <span class="info-value">{{ $laporan->persentase_alumni_wirausaha ? $laporan->persentase_alumni_wirausaha . '%' : '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Persentase Alumni Tidak Terdeteksi</span>
                <span class="info-value">{{ $laporan->persentase_alumni_tidak_terdeteksi ? $laporan->persentase_alumni_tidak_terdeteksi . '%' : '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">BOSNAS 2023</span>
                <span class="info-value currency">{{ $laporan->bosnas_2023 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">BOSNAS 2024</span>
                <span class="info-value currency">{{ $laporan->bosnas_2024 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">BOSNAS 2025</span>
                <span class="info-value currency">{{ $laporan->bosnas_2025 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">BOSDA 2023</span>
                <span class="info-value currency">{{ $laporan->bosda_2023 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">BOSDA 2024</span>
                <span class="info-value currency">{{ $laporan->bosda_2024 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">BOSDA 2025</span>
                <span class="info-value currency">{{ $laporan->bosda_2025 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">SPP/BPPP/Lain 2023</span>
                <span class="info-value currency">{{ $laporan->spp_bppp_lain_2023 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">SPP/BPPP/Lain 2024</span>
                <span class="info-value currency">{{ $laporan->spp_bppp_lain_2024 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">SPP/BPPP/Lain 2025</span>
                <span class="info-value currency">{{ $laporan->spp_bppp_lain_2025 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Pendapatan Unit Usaha 2023</span>
                <span class="info-value currency">{{ $laporan->pendapatan_unit_usaha_2023 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Pendapatan Unit Usaha 2024</span>
                <span class="info-value currency">{{ $laporan->pendapatan_unit_usaha_2024 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Pendapatan Unit Usaha 2025</span>
                <span class="info-value currency">{{ $laporan->pendapatan_unit_usaha_2025 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status Akreditasi</span>
                <span class="info-value">{{ $laporan->status_akreditasi ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Akreditasi Mulai</span>
                <span class="info-value">{{ $laporan->tanggal_akreditasi_mulai ? \Carbon\Carbon::parse($laporan->tanggal_akreditasi_mulai)->format('d/m/Y') : '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Akreditasi Berakhir</span>
                <span class="info-value">{{ $laporan->tanggal_akreditasi_berakhir ? \Carbon\Carbon::parse($laporan->tanggal_akreditasi_berakhir)->format('d/m/Y') : '-' }}</span>
            </div>
            @if($laporan->lampiran_step_2)
                <div class="info-row">
                    <span class="info-label">Lampiran</span>
                    <span class="info-value">
                        <a href="{{ asset($laporan->lampiran_step_2) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-file"></i> Lihat File
                        </a>
                    </span>
                </div>
            @endif
        </div>

        <!-- Step 3: Layanan Pendidikan -->
        <div class="info-group">
            <h5 class="info-group-title">3. Layanan Pendidikan</h5>
            <div class="info-row">
                <span class="info-label">Model Layanan Pendidikan</span>
                <span class="info-value">{{ $laporan->model_layanan_pendidikan ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Capaian Layanan Menonjol</span>
                <span class="info-value">{{ $laporan->capaian_layanan_menonjol ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Masalah Layanan Utama</span>
                <span class="info-value">{{ $laporan->masalah_layanan_utama ?? '-' }}</span>
            </div>
            @if($laporan->lampiran_step_3)
                <div class="info-row">
                    <span class="info-label">Lampiran</span>
                    <span class="info-value">
                        <a href="{{ asset($laporan->lampiran_step_3) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-file"></i> Lihat File
                        </a>
                    </span>
                </div>
            @endif
        </div>

        <!-- Step 4: SDM -->
        <div class="info-group">
            <h5 class="info-group-title">4. Sumber Daya Manusia (SDM)</h5>
            <div class="info-row">
                <span class="info-label">PNS Sertifikasi</span>
                <span class="info-value">{{ $laporan->pns_sertifikasi ?? 0 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">PNS Non Sertifikasi</span>
                <span class="info-value">{{ $laporan->pns_non_sertifikasi ?? 0 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">GTY Sertifikasi Inpassing</span>
                <span class="info-value">{{ $laporan->gty_sertifikasi_inpassing ?? 0 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">GTY Sertifikasi</span>
                <span class="info-value">{{ $laporan->gty_sertifikasi ?? 0 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">GTY Non Sertifikasi</span>
                <span class="info-value">{{ $laporan->gty_non_sertifikasi ?? 0 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">GTT</span>
                <span class="info-value">{{ $laporan->gtt ?? 0 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">PTY</span>
                <span class="info-value">{{ $laporan->pty ?? 0 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">PTT</span>
                <span class="info-value">{{ $laporan->ptt ?? 0 }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jumlah Talenta</span>
                <span class="info-value">{{ $laporan->jumlah_talenta ?? 0 }}</span>
            </div>
            @if($laporan->nama_talenta && $laporan->alasan_talenta)
                <div class="info-row">
                    {{-- <span class="info-label">Talenta</span> --}}
                    <span class="info-value">
                        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                            <thead>
                                <tr style="background-color: #f8f9fa;">
                                    <th style="border: 1px solid #dee2e6; padding: 8px; text-align: left; width: 50%;">Nama</th>
                                    <th style="border: 1px solid #dee2e6; padding: 8px; text-align: left; width: 50%;">Alasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $namaTalenta = is_array(json_decode($laporan->nama_talenta, true)) ? json_decode($laporan->nama_talenta, true) : [$laporan->nama_talenta];
                                    $alasanTalenta = is_array(json_decode($laporan->alasan_talenta, true)) ? json_decode($laporan->alasan_talenta, true) : [$laporan->alasan_talenta];
                                    $maxCount = max(count($namaTalenta), count($alasanTalenta));
                                @endphp
                                @for ($i = 0; $i < $maxCount; $i++)
                                    <tr>
                                        <td style="border: 1px solid #dee2e6; padding: 8px; text-align: left;">{{ $namaTalenta[$i] ?? '-' }}</td>
                                        <td style="border: 1px solid #dee2e6; padding: 8px; text-align: left;">{{ $alasanTalenta[$i] ?? '-' }}</td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </span>
                </div>
            @endif
            @if($laporan->kondisi_guru)
                <div class="info-row">
                    <span class="info-label">Kondisi Guru & Karyawan</span>
                </div>
                <div class="info-row">
                    {{-- <span class="info-label">Kondisi Guru</span> --}}
                    <span class="info-value">
                        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                            <thead>
                                <tr style="background-color: #f8f9fa;">
                                    <th style="border: 1px solid #dee2e6; padding: 8px; text-align: left; width: 50%;">Nama</th>
                                    <th style="border: 1px solid #dee2e6; padding: 8px; text-align: left; width: 50%;">Kondisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $kondisiGuru = json_decode($laporan->kondisi_guru, true);
                                    $userIds = array_keys($kondisiGuru);
                                    $users = \App\Models\User::whereIn('id', $userIds)->pluck('name', 'id');
                                @endphp
                                @foreach($kondisiGuru as $userId => $kondisi)
                                    <tr>
                                        <td style="border: 1px solid #dee2e6; padding: 8px; text-align:left;">{{ $users[$userId] ?? 'User ID: ' . $userId }}</td>
                                        <td style="border: 1px solid #dee2e6; padding: 8px; text-align:left;">{{ $kondisi }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </span>
                </div>
            @endif
            @if($laporan->masalah_sdm_utama)
                <div class="info-row">
                    <span class="info-label">Masalah SDM Utama</span>
                    <span class="info-value">{{ is_array(json_decode($laporan->masalah_sdm_utama, true)) ? implode(', ', json_decode($laporan->masalah_sdm_utama, true)) : $laporan->masalah_sdm_utama }}</span>
                </div>
            @endif
            @if($laporan->lampiran_step_4)
                <div class="info-row">
                    <span class="info-label">Lampiran</span>
                    <span class="info-value">
                        <a href="{{ asset($laporan->lampiran_step_4) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-file"></i> Lihat File
                        </a>
                    </span>
                </div>
            @endif
        </div>

        <!-- Step 5: Keuangan -->
        <div class="info-group">
            <h5 class="info-group-title">5. Keuangan</h5>
            <div class="info-row">
                <span class="info-label">Sumber Dana Utama</span>
                <span class="info-value">{{ $laporan->sumber_dana_utama ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kondisi Keuangan Akhir Tahun</span>
                <span class="info-value">{{ $laporan->kondisi_keuangan_akhir_tahun ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Catatan Pengelolaan Keuangan</span>
                <span class="info-value">{{ $laporan->catatan_pengelolaan_keuangan ?? '-' }}</span>
            </div>
            @if($laporan->lampiran_step_5)
                <div class="info-row">
                    <span class="info-label">Lampiran</span>
                    <span class="info-value">
                        <a href="{{ asset($laporan->lampiran_step_5) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-file"></i> Lihat File
                        </a>
                    </span>
                </div>
            @endif
        </div>

        <!-- Step 6: PPDB -->
        <div class="info-group">
            <h5 class="info-group-title">6. PPDB</h5>
            <div class="info-row">
                <span class="info-label">Metode PPDB</span>
                <span class="info-value">{{ $laporan->metode_ppdb ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Hasil PPDB Tahun Berjalan</span>
                <span class="info-value">{{ $laporan->hasil_ppdb_tahun_berjalan ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Masalah Utama PPDB</span>
                <span class="info-value">{{ $laporan->masalah_utama_ppdb ?? '-' }}</span>
            </div>
        </div>

        <!-- Step 7: Unggulan -->
        <div class="info-group">
            <h5 class="info-group-title">7. Program Unggulan</h5>
            <div class="info-row">
                <span class="info-label">Nama Program Unggulan</span>
                <span class="info-value">{{ $laporan->nama_program_unggulan ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Alasan Pemilihan Program</span>
                <span class="info-value">{{ $laporan->alasan_pemilihan_program ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Target Unggulan</span>
                <span class="info-value">{{ $laporan->target_unggulan ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kontribusi Unggulan</span>
                <span class="info-value">{{ $laporan->kontribusi_unggulan ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Sumber Biaya Program</span>
                <span class="info-value">{{ $laporan->sumber_biaya_program ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tim Program Unggulan</span>
                <span class="info-value">{{ $laporan->tim_program_unggulan ?? '-' }}</span>
            </div>
            @if($laporan->lampiran_step_7)
                <div class="info-row">
                    <span class="info-label">Lampiran</span>
                    <span class="info-value">
                        <a href="{{ asset($laporan->lampiran_step_7) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-file"></i> Lihat File
                        </a>
                    </span>
                </div>
            @endif
        </div>

        <!-- Step 8: Refleksi -->
        <div class="info-group">
            <h5 class="info-group-title">8. Refleksi</h5>
            <div class="info-row">
                <span class="info-label">Keberhasilan Terbesar Tahun Ini</span>
                <span class="info-value">{{ $laporan->keberhasilan_terbesar_tahun_ini ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Masalah Paling Berat Dihadapi</span>
                <span class="info-value">{{ $laporan->masalah_paling_berat_dihadapi ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Keputusan Sulit Diambil</span>
                <span class="info-value">{{ $laporan->keputusan_sulit_diambil ?? '-' }}</span>
            </div>
            @if($laporan->lampiran_step_8)
                <div class="info-row">
                    <span class="info-label">Lampiran</span>
                    <span class="info-value">
                        <a href="{{ asset($laporan->lampiran_step_8) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-file"></i> Lihat File
                        </a>
                    </span>
                </div>
            @endif
        </div>

        <!-- Step 9: Risiko -->
        <div class="info-group">
            <h5 class="info-group-title">9. Risiko</h5>
            <div class="info-row">
                <span class="info-label">Risiko Terbesar Satpen Tahun Depan</span>
                <span class="info-value">{{ $laporan->risiko_terbesar_satpen_tahun_depan ?? '-' }}</span>
            </div>
            @if($laporan->fokus_perbaikan_tahun_depan)
                <div class="info-row">
                    <span class="info-label">Fokus Perbaikan Tahun Depan</span>
                    <span class="info-value">{{ is_array(json_decode($laporan->fokus_perbaikan_tahun_depan, true)) ? implode(', ', json_decode($laporan->fokus_perbaikan_tahun_depan, true)) : $laporan->fokus_perbaikan_tahun_depan }}</span>
                </div>
            @endif
            @if($laporan->lampiran_step_9)
                <div class="info-row">
                    <span class="info-label">Lampiran</span>
                    <span class="info-value">
                        <a href="{{ asset($laporan->lampiran_step_9) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-file"></i> Lihat File
                        </a>
                    </span>
                </div>
            @endif
        </div>

        <!-- Step 10: Pernyataan -->
        <div class="info-group">
            <h5 class="info-group-title">10. Pernyataan</h5>
            <div class="info-row">
                <span class="info-label">Pernyataan Benar</span>
                <span class="info-value">{{ $laporan->pernyataan_benar ? 'Ya' : 'Tidak' }}</span>
            </div>
            @if($laporan->signature_data)
                <div class="info-row">
                    <span class="info-label">Tanda Tangan</span>
                    <span class="info-value">
                        <img src="{{ $laporan->signature_data }}" alt="Tanda Tangan" style="max-width: 200px; max-height: 100px; border: 1px solid #ddd; border-radius: 4px;">
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('mobile.laporan-akhir-tahun.edit', $laporan->id) }}" class="btn btn-primary">
            <i class="bx bx-edit"></i>
            Edit Laporan
        </a>
        <a href="{{ route('mobile.laporan-akhir-tahun.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i>
            Kembali ke Daftar
        </a>
    </div>
</div>
@endsection
