<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentaPenilaianTrainer extends Model
{
    use HasFactory;

    protected $table = 'talenta_penilaian_trainer';

    protected $fillable = [
        'talenta_pemateri_id',
        'user_id',
        'kualitas_materi',
        'penyampaian',
        'integrasi_kasus',
        'penjelasan',
        'umpan_balik',
        'waktu',
    ];

    protected $casts = [
        'kualitas_materi' => 'integer',
        'penyampaian' => 'integer',
        'integrasi_kasus' => 'integer',
        'penjelasan' => 'integer',
        'umpan_balik' => 'integer',
        'waktu' => 'integer',
    ];

    public function pemateri()
    {
        return $this->belongsTo(TalentaPemateri::class, 'talenta_pemateri_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
