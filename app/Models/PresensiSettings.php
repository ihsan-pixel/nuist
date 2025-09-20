<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiSettings extends Model
{
    use HasFactory;

    protected $table = 'presensi_settings';

    protected $fillable = [
        'waktu_mulai_presensi_masuk',
        'waktu_akhir_presensi_masuk',
        'waktu_mulai_presensi_pulang',
        'waktu_akhir_presensi_pulang',
        'singleton',
    ];

    protected $attributes = [
        'singleton' => true,
    ];
}
