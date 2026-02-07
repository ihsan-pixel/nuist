<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentaPeserta extends Model
{
    use HasFactory;

    protected $table = 'talenta_peserta';

    protected $fillable = [
        'kode_peserta',
        'user_id',
        'asal_sekolah',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
