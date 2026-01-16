<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UppmSchoolData extends Model
{
    protected $fillable = [
        'madrasah_id',
        'tahun_anggaran',
        'jumlah_siswa',
        'jumlah_pns_sertifikasi',
        'jumlah_pns_non_sertifikasi',
        'jumlah_gty_sertifikasi',
        'jumlah_gty_sertifikasi_inpassing',
        'jumlah_gty_non_sertifikasi',
        'jumlah_gtt',
        'jumlah_pty',
        'jumlah_ptt',
        'jumlah_karyawan_tetap',
        'jumlah_karyawan_tidak_tetap',
        'total_nominal',
        'status_pembayaran',
        'nominal_dibayar',
    ];

    protected $casts = [
        'tahun_anggaran' => 'integer',
        'jumlah_siswa' => 'integer',
        'jumlah_guru_tetap' => 'integer',
        'jumlah_guru_tidak_tetap' => 'integer',
        'jumlah_guru_pns' => 'integer',
        'jumlah_guru_pppk' => 'integer',
        'jumlah_karyawan_tetap' => 'integer',
        'jumlah_karyawan_tidak_tetap' => 'integer',
        'total_nominal' => 'decimal:2',
        'nominal_dibayar' => 'decimal:2',
    ];

    public function madrasah(): BelongsTo
    {
        return $this->belongsTo(Madrasah::class);
    }
}
