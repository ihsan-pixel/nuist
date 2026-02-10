<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentaPemateri extends Model
{
    use HasFactory;

    protected $table = 'talenta_pemateri';

    protected $fillable = [
        'kode_pemateri',
        'nama',
        'materi_id',
    ];

    public function materi()
    {
        return $this->belongsTo(TalentaMateri::class, 'materi_id');
    }

    /**
     * Get the materi title (accessor).
     */
    public function getJudulMateriAttribute()
    {
        return $this->materi ? $this->materi->judul_materi : null;
    }
}
