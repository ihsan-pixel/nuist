<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentaKelompok extends Model
{
    use HasFactory;

    protected $table = 'talenta_kelompoks';

    protected $fillable = [
        'nama_kelompok',
    ];

    /**
     * Get the pesertas for the kelompok.
     */
    public function pesertas()
    {
        return $this->belongsToMany(TalentaPeserta::class, 'talenta_kelompok_peserta', 'talenta_kelompok_id', 'talenta_peserta_id');
    }
}
