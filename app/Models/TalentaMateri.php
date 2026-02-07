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
}
