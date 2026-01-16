<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UppmSetting extends Model
{
    protected $fillable = [
        'tahun_anggaran',
        'nominal_siswa',
        'nominal_pns_sertifikasi',
        'nominal_pns_non_sertifikasi',
        'nominal_gty_sertifikasi',
        'nominal_gty_sertifikasi_inpassing',
        'nominal_gty_non_sertifikasi',
        'nominal_gtt',
        'nominal_pty',
        'nominal_ptt',
        'nominal_karyawan_tetap',
        'nominal_karyawan_tidak_tetap',
        'jatuh_tempo',
        'skema_pembayaran',
        'aktif',
        'catatan',
        'format_invoice',
    ];

    protected $casts = [
        'tahun_anggaran' => 'integer',
        'nominal_siswa' => 'decimal:2',
        'nominal_guru_tetap' => 'decimal:2',
        'nominal_guru_tidak_tetap' => 'decimal:2',
        'nominal_guru_pns' => 'decimal:2',
        'nominal_guru_pppk' => 'decimal:2',
        'nominal_karyawan_tetap' => 'decimal:2',
        'nominal_karyawan_tidak_tetap' => 'decimal:2',
        'jatuh_tempo' => 'date',
        'aktif' => 'boolean',
    ];
}
