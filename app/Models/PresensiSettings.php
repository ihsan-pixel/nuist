<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiSettings extends Model
{
    use HasFactory;

    protected $table = 'presensi_settings';

    protected $fillable = [
        'status_kepegawaian_id',
        'waktu_mulai_presensi_masuk',
        'waktu_akhir_presensi_masuk',
        'waktu_mulai_presensi_pulang',
        'waktu_akhir_presensi_pulang',
    ];

    public function statusKepegawaian()
    {
        return $this->belongsTo(\App\Models\StatusKepegawaian::class, 'status_kepegawaian_id');
    }
}
