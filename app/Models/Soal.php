<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'soals';

    protected $fillable = [
        'materi_slug',
        'jenis',
        'kelompok',
        'pertanyaan',
        'instruksi',
        'urut',
    ];

    // simple ordering scope
    public function scopeOrdered($query)
    {
        return $query->orderBy('urut')->orderBy('id');
    }
}
