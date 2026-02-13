<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentaPenilaianPeserta extends Model
{
    use HasFactory;

    protected $table = 'talenta_penilaian_peserta';

    protected $fillable = [
        'talenta_peserta_id',
        'user_id',
        'kehadiran',
        'partisipasi',
        'disiplin',
        'tugas',
        'pemahaman',
        'praktik',
        'sikap',
    ];

    protected $casts = [
        'kehadiran' => 'integer',
        'partisipasi' => 'integer',
        'disiplin' => 'integer',
        'tugas' => 'integer',
        'pemahaman' => 'integer',
        'praktik' => 'integer',
        'sikap' => 'integer',
    ];

    public function peserta()
    {
        return $this->belongsTo(TalentaPeserta::class, 'talenta_peserta_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
