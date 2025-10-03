<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TenagaPendidik;

class Madrasah extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'alamat',
        'latitude',
        'longitude',
        'map_link',
        'logo',
        'polygon_koordinat',
    ];

    // Relasi: satu madrasah punya banyak admin
    public function admins()
    {
        return $this->hasMany(User::class, 'madrasah_id');
    }

    // Relasi: satu madrasah punya banyak tenaga pendidik
    public function tenagaPendidik()
    {
        return $this->hasMany(TenagaPendidik::class, 'madrasah_id');
    }

    // Relasi: satu madrasah belongs to satu yayasan
    public function yayasan()
    {
        return $this->belongsTo(Yayasan::class);
    }
}
