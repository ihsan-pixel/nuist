<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yayasan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'alamat',
        'latitude',
        'longitude',
        'map_link',
        'visi',
        'misi',
    ];

    // Relasi: satu yayasan punya banyak madrasah
    public function madrasahs()
    {
        return $this->hasMany(Madrasah::class);
    }
}
