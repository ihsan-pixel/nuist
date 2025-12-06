<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simfoni extends Model
{
    use HasFactory;

    protected $table = 'simfoni';

    protected $fillable = [
        // A. DATA SK
        'user_id',
        'nama_lengkap_gelar',
        'gelar',
        'tempat_lahir',
        'tanggal_lahir',
        'nuptk',
        'kartanu',
        'nipm',
        'nik',
        'tmt',
        'strata_pendidikan',
        'pt_asal',
        'tahun_lulus',
        'program_studi',

        // B. RIWAYAT KERJA
        'status_kerja',
        'tanggal_sk_pertama',
        'nomor_sk_pertama',
        'nomor_sertifikasi_pendidik',
        'riwayat_kerja_sebelumnya',

        // C. KEAHLIAN DAN DATA LAIN
        'keahlian',
        'kedudukan_lpm',
        'prestasi',
        'tahun_sertifikasi_impassing',
        'no_hp',
        'email',
        'status_pernikahan',
        'alamat_lengkap',

        // D. DATA KEUANGAN/KESEJAHTERAAN
        'bank',
        'nomor_rekening',
        'gaji_sertifikasi',
        'gaji_pokok',
        'honor_lain',
        'penghasilan_lain',
        'penghasilan_pasangan',
        'total_penghasilan',
        'masa_kerja',
        'kategori_penghasilan',

        // E. STATUS KEKADERAN
        'status_kader_diri',
        'pendidikan_kader',
        'status_kader_ayah',
        'status_kader_ibu',
        'status_kader_pasangan',

        // F. DATA KELUARGA
        'nama_ayah',
        'nama_ibu',
        'nama_pasangan',
        'jumlah_anak',

        // G. PROYEKSI KE DEPAN
        'akan_kuliah_s2',
        'akan_mendaftar_pns',
        'akan_mendaftar_pppk',
        'akan_mengikuti_ppg',
        'akan_menulis_buku_modul_riset',
        'akan_mengikuti_seleksi_diklat_cakep',
        'akan_membimbing_riset_prestasi_siswa',
        'akan_masuk_tim_unggulan_sekolah_madrasah',
        'akan_kompetisi_pimpinan_level_ii',
        'akan_aktif_mengikuti_pelatihan',
        'akan_aktif_mgmp_mkk',
        'akan_mengikuti_pendidikan_kader_nu',
        'akan_aktif_membantu_kegiatan_lembaga',
        'akan_aktif_mengikuti_kegiatan_nu',
        'akan_aktif_ikut_zis_kegiatan_sosial',
        'akan_mengembangkan_unit_usaha_satpen',
        'akan_bekerja_disiplin_produktif',
        'akan_loyal_nu_aktif_masyarakat',
        'akan_bersedia_dipindah_satpen_lain',
        'skor_proyeksi',
        'pernyataan_setuju',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the simfoni record
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
