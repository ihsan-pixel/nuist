<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasNilai extends Model
{
    protected $table = 'tugas_nilai';

    protected $fillable = [
        'tugas_talenta_level1_id',
        'penilai_id',
        'nilai',
    ];

    protected $casts = [
        'nilai' => 'integer',
    ];

    public function tugas()
    {
        return $this->belongsTo(TugasTalentaLevel1::class, 'tugas_talenta_level1_id');
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }
}
