<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentaPenilaianTeknis extends Model
{
    use HasFactory;

    protected $table = 'talenta_penilaian_teknis';

    protected $fillable = [
        'talenta_layanan_teknis_id',
        'user_id',
        'kehadiran',
        'partisipasi',
        'disiplin',
        'kualitas_tugas',
        'pemahaman_materi',
        'implementasi_praktik',
    ];

    protected $casts = [
        'kehadiran' => 'integer',
        'partisipasi' => 'integer',
        'disiplin' => 'integer',
        'kualitas_tugas' => 'integer',
        'pemahaman_materi' => 'integer',
        'implementasi_praktik' => 'integer',
    ];

    public function layananTeknis()
    {
        return $this->belongsTo(TalentaLayananTeknis::class, 'talenta_layanan_teknis_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
