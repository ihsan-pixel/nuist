<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentaMateri extends Model
{
    use HasFactory;

    protected $table = 'talenta_materi';

    protected $fillable = [
        'kode_materi',
        'judul_materi',
        'level_materi',
        'tanggal_materi',
    ];

    protected $casts = [
        'tanggal_materi' => 'date',
    ];

    public function pemateris()
    {
        return $this->belongsToMany(
            TalentaPemateri::class,
            'talenta_pemateri_materi',
            'talenta_materi_id',
            'talenta_pemateri_id'
        );
    }

    public function fasilitators()
    {
        return $this->belongsToMany(
            TalentaFasilitator::class,
            'talenta_fasilitator_materi',
            'talenta_materi_id',
            'talenta_fasilitator_id'
        );
    }
}
