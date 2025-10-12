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
        'kabupaten',
        'alamat',
        'latitude',
        'longitude',
        'map_link',
        'logo',
        'polygon_koordinat',
        'hari_kbm',
        'scod',
    ];

    // Relasi: satu madrasah punya banyak admin
    public function admins()
    {
        return $this->hasMany(User::class, 'madrasah_id');
    }

    // Relasi: satu madrasah punya banyak tenaga pendidik (dari users table dengan role tenaga_pendidik)
    public function tenagaPendidikUsers()
    {
        return $this->hasMany(User::class, 'madrasah_id')->where('role', 'tenaga_pendidik');
    }

    // Relasi lama ke TenagaPendidik model (jika masih diperlukan)
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
