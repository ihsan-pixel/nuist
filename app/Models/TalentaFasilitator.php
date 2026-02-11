<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentaFasilitator extends Model
{
    use HasFactory;

    protected $table = 'talenta_fasilitator';

    protected $fillable = [
        'kode_fasilitator',
        'nama',
    ];

    public function materis()
    {
        return $this->belongsToMany(TalentaMateri::class, 'talenta_fasilitator_materi', 'talenta_fasilitator_id', 'talenta_materi_id');
    }
}
