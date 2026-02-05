<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talenta extends Model
{
    use HasFactory;

    protected $table = 'talenta';

    protected $fillable = [
        'user_id',
        'madrasah_id',
        'status',
        'tahun_pelaporan',
        // TPT Level 1
        'nomor_talenta_1',
        'skor_penilaian_1',
        'sertifikat_tpt_1',
        'produk_unggulan_1',
        // TPT Level 2
        'nomor_talenta_2',
        'skor_penilaian_2',
        'sertifikat_tpt_2',
        'produk_unggulan_2',
        // TPT Level 3
        'nomor_talenta_3',
        'skor_penilaian_3',
        'sertifikat_tpt_3',
        'produk_unggulan_3',
        // TPT Level 4
        'nomor_talenta_4',
        'skor_penilaian_4',
        'sertifikat_tpt_4',
        'produk_unggulan_4',
        // TPT Level 5
        'nomor_talenta_5',
        'skor_penilaian_5',
        'sertifikat_tpt_5',
        'produk_unggulan_5',
        // Pendidikan Kader
        'pkpnu_status',
        'pkpnu_sertifikat',
        'mknu_status',
        'mknu_sertifikat',
        'pmknu_status',
        'pmknu_sertifikat',
        // Proyeksi Diri
        'jabatan_saat_ini',
        'proyeksi_akademik',
        'proyeksi_jabatan_level2_umum',
        'proyeksi_jabatan_level2_khusus',
        'proyeksi_jabatan_level1',
        'proyeksi_jabatan_top_leader',
        'studi_lanjut',
        'leader_mgmp',
        'produk_ajar',
        'prestasi_kompetitif',
        // Data Diri - Personal
        'nama_lengkap_gelar',
        'nama_panggilan',
        'nomor_ktp',
        'nip_maarif',
        'nomor_talenta',
        'tempat_lahir',
        'tanggal_lahir',
        'email_aktif',
        'nomor_wa',
        'alamat_ktp',
        'alamat_tinggal',
        'link_fb',
        'link_tiktok',
        'link_instagram',
        'foto_resmi',
        'foto_bebas',
        'foto_keluarga',
        // Data Diri - Education
        'asal_sekolah',
        'ijazah_s1',
        'ijazah_s2',
        'ijazah_s3',
        // Data Diri - Income
        'level_pendapatan_internal',
        'pekerjaan_eksternal',
        'pendapatan_eksternal',
        // Data Diri - Work History
        'gtt_ptt_tanggal',
        'gtt_ptt_sk',
        'gty_tanggal',
        'gty_sk',
        'masa_kerja_lpmnu',
        'riwayat_jabatan_pemula',
        'riwayat_jabatan_terampil',
        'riwayat_jabatan_mahir',
        'riwayat_jabatan_ahli',
        // File attachments
        'lampiran_step_1',
        'lampiran_step_2',
        'lampiran_step_3',
        'lampiran_step_4',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'pernyataan_benar' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }
}
