<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GtkPendataan extends Model
{
    protected $table = 'gtk_pendataan';

    protected $fillable = [
        'user_id',
        'nik',
        'gol_darah',
        'email_aktif',
        'tmt_sk_pertama',
        'tmt_sk_terakhir',
        'nomor_sk_pertama',
        'tahun_sk_pertama',
        'gaji_satpen',
        'nomor_sertifikasi_pendidik',
        'gaji_sertifikasi',
        'tunjangan_rerata_bulanan',
        'nama_mgmp',
        'produk_kerja_kolaboratif',
    ];

    protected $casts = [
        'tmt_sk_pertama' => 'date',
        'tmt_sk_terakhir' => 'date',
        'tahun_sk_pertama' => 'integer',
        'gaji_satpen' => 'decimal:2',
        'gaji_sertifikasi' => 'decimal:2',
        'tunjangan_rerata_bulanan' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
