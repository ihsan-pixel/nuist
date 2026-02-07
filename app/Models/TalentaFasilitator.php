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
        'materi_id',
    ];

    public function materi()
    {
        return $this->belongsTo(TalentaMateri::class, 'materi_id');
    }
}
