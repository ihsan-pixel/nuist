<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPDBVerifikasi extends Model
{
    use HasFactory;

    protected $table = 'ppdb_verifikasis';
    protected $fillable = [
        'ppdb_setting_id',
        'ppdb_pendaftar_id',
        'status',
        'catatan',
        'diverifikasi_oleh',
        'diverifikasi_tanggal',
    ];

    protected $casts = [
        'diverifikasi_tanggal' => 'datetime',
    ];

    /**
     * Relasi ke PPDBSetting
     */
    public function ppdbSetting()
    {
        return $this->belongsTo(PPDBSetting::class, 'ppdb_setting_id');
    }

    /**
     * Relasi ke PPDBPendaftar
     */
    public function pendaftar()
    {
        return $this->belongsTo(PPDBPendaftar::class, 'ppdb_pendaftar_id');
    }

    /**
     * Relasi ke User (verifikator)
     */
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}
