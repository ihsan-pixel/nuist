<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPDBPendaftar extends Model
{
    use HasFactory;
    protected $table = 'ppdb_pendaftar';
    protected $fillable = [
        'ppdb_setting_id', 'nama_lengkap', 'nisn', 'asal_sekolah', 'jurusan_pilihan',
        'berkas_kk', 'berkas_ijazah', 'status'
    ];

    public function setting()
    {
        return $this->belongsTo(PPDBSetting::class, 'ppdb_setting_id');
    }
}
