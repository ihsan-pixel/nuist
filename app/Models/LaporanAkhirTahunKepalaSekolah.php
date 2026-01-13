<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanAkhirTahunKepalaSekolah extends Model
{
    use HasFactory;

    protected $table = 'laporan_akhir_tahun_kepala_sekolah';

    protected $fillable = [
        'user_id',
        'nama_satpen',
        'alamat',
        'nama_kepala_sekolah_madrasah',
        'gelar',
        'tmt_ks_kamad_pertama',
        'tmt_ks_kamad_terakhir',
        'tahun_pelaporan',
        'nama_kepala_sekolah',
        // Step 2: Capaian Utama 3 Tahun Berjalan
        'jumlah_siswa_2023',
        'jumlah_siswa_2024',
        'jumlah_siswa_2025',
        'persentase_alumni_bekerja',
        'persentase_alumni_wirausaha',
        'persentase_alumni_tidak_terdeteksi',
        'bosnas_2023',
        'bosnas_2024',
        'bosnas_2025',
        'bosda_2023',
        'bosda_2024',
        'bosda_2025',
        'spp_bppp_lain_2023',
        'spp_bppp_lain_2024',
        'spp_bppp_lain_2025',
        'pendapatan_unit_usaha_2023',
        'pendapatan_unit_usaha_2024',
        'pendapatan_unit_usaha_2025',
        'status_akreditasi',
        'tanggal_akreditasi_mulai',
        'tanggal_akreditasi_berakhir',
        // Step 3: Layanan Pendidikan
        'model_layanan_pendidikan',
        'capaian_layanan_menonjol',
        'masalah_layanan_utama',
        // Step 4: SDM
        'pns_sertifikasi',
        'pns_non_sertifikasi',
        'gty_sertifikasi_inpassing',
        'gty_sertifikasi',
        'gty_non_sertifikasi',
        'gtt',
        'pty',
        'ptt',
        'jumlah_talenta',
        'nama_talenta',
        'alasan_talenta',
        'kondisi_guru',
        'masalah_sdm_utama',
        // Step 5: Keuangan
        'sumber_dana_utama',
        'kondisi_keuangan_akhir_tahun',
        'catatan_pengelolaan_keuangan',
        // Step 6: PPDB
        'metode_ppdb',
        'hasil_ppdb_tahun_berjalan',
        'masalah_utama_ppdb',
        // Step 7: Unggulan
        'nama_program_unggulan',
        'alasan_pemilihan_program',
        'target_unggulan',
        'kontribusi_unggulan',
        'sumber_biaya_program',
        'tim_program_unggulan',
        // Step 8: Refleksi
        'keberhasilan_terbesar_tahun_ini',
        'masalah_paling_berat_dihadapi',
        'keputusan_sulit_diambil',
        // Step 9: Risiko
        'risiko_terbesar_satpen_tahun_depan',
        'fokus_perbaikan_tahun_depan',
        // Step 10: Pernyataan
        'pernyataan_benar',
        'signature_data',
        // File attachments
        'lampiran_step_1',
        'lampiran_step_2',
        'lampiran_step_3',
        'lampiran_step_4',
        'lampiran_step_5',
        'lampiran_step_6',
        'lampiran_step_7',
        'lampiran_step_8',
        'lampiran_step_9',
        'lampiran_step_10',
    ];

    protected $casts = [
        'tmt_ks_kamad_pertama' => 'date',
        'tmt_ks_kamad_terakhir' => 'date',
        'tanggal_akreditasi_mulai' => 'date',
        'tanggal_akreditasi_berakhir' => 'date',
        'nama_talenta' => 'array',
        'alasan_talenta' => 'array',
        'kondisi_guru' => 'array',
        'masalah_sdm_utama' => 'array',
        'fokus_perbaikan_tahun_depan' => 'array',
        'pernyataan_benar' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
