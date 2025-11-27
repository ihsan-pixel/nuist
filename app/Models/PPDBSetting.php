<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPDBSetting extends Model
{
    use HasFactory;

    protected $table = 'ppdb_settings';

    protected $fillable = [
        'sekolah_id',
        'slug',
        'nama_sekolah',
        'tahun',
        'status',
        'jadwal_buka',
        'jadwal_tutup',
        // PPDB specific fields
        'ppdb_status',
        'ppdb_jadwal_buka',
        'ppdb_jadwal_tutup',
        'ppdb_kuota_total',
        'ppdb_jadwal_pengumuman',
        'ppdb_kuota_jurusan',
        'ppdb_jalur',
        'ppdb_biaya_pendaftaran',
        'ppdb_catatan_pengumuman',
        // Additional fields
        'visi',
        'misi',
        'fasilitas',
        'prestasi',
        'ekstrakurikuler',
        'biaya_pendidikan',
        'informasi_tambahan',
    ];

    protected $casts = [
        'ppdb_jadwal_buka' => 'datetime',
        'ppdb_jadwal_tutup' => 'datetime',
        'ppdb_jadwal_pengumuman' => 'datetime',
        'ppdb_kuota_jurusan' => 'array',
        'ppdb_jalur' => 'array',
        'jurusan' => 'array',
        'galeri_foto' => 'array',
    ];

    // Relasi ke Madrasah
    public function sekolah()
    {
        return $this->belongsTo(Madrasah::class, 'sekolah_id');
    }

    // Relasi ke PPDB Jalur
    public function jalurs()
    {
        return $this->hasMany(PPDBJalur::class, 'ppdb_setting_id');
    }

    // Relasi ke PPDB Pendaftar
    public function pendaftars()
    {
        return $this->hasMany(PPDBPendaftar::class, 'ppdb_setting_id');
    }

    // Relasi ke PPDB Verifikasi
    public function verifikasis()
    {
        return $this->hasMany(PPDBVerifikasi::class, 'ppdb_setting_id');
    }
}
