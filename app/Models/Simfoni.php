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
