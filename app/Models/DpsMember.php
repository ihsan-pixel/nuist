<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DpsMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'madrasah_id',
        'nama',
        'unsur',
        'periode',
    ];

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }
}

