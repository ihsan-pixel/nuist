<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalMengajar extends Model
{
    use HasFactory;

    protected $table = 'jadwal_mengajar';

    protected $fillable = [
        'tenaga_pendidik_id',
        'madrasah_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'mata_pelajaran',
    ];

    public function tenagaPendidik()
    {
        return $this->belongsTo(TenagaPendidik::class);
    }

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }
}
