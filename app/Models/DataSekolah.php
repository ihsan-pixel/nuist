<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSekolah extends Model
{
    use HasFactory;

    protected $table = 'data_sekolah';

    protected $fillable = [
        'madrasah_id',
        'tahun',
        'jumlah_siswa',
        'jumlah_pns_sertifikasi',
        'jumlah_pns_non_sertifikasi',
        'jumlah_gty_sertifikasi',
        'jumlah_gty_sertifikasi_inpassing',
        'jumlah_gty_non_sertifikasi',
        'jumlah_gtt',
        'jumlah_pty',
        'jumlah_ptt',
    ];

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }
}
