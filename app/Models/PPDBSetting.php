<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPDBSetting extends Model
{
    use HasFactory;
    protected $table = 'ppdb_settings';
    protected $fillable = [
        'sekolah_id', 'slug', 'nama_sekolah', 'tahun', 'status', 'jadwal_buka', 'jadwal_tutup'
    ];

    public function pendaftar()
    {
        return $this->hasMany(PPDBPendaftar::class, 'ppdb_setting_id');
    }

    public function jalur()
    {
        return $this->hasMany(PPDBJalur::class, 'ppdb_setting_id');
    }
}
