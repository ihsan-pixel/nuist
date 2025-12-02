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
        'kabupaten',
        'alamat',
        'tagline',
        'akreditasi',
        'tahun_berdiri',
        'telepon',
        'email',
        'website',
        'deskripsi_singkat',
        'sejarah',
        'nilai_nilai',
        'visi',
        'misi',
        'fasilitas',
        'keunggulan',
        'jurusan',
        'prestasi',
        'program_unggulan',
        'ekstrakurikuler',
        'testimoni',
        'kepala_sekolah_nama',
        'kepala_sekolah_gelar',
        'kepala_sekolah_sambutan',
        'kepala_sekolah_foto',
        'jumlah_siswa',
        'jumlah_guru',
        'jumlah_jurusan',
        'jumlah_sarana',
        'video_profile',
        'brosur_pdf',
        'galeri_foto',
        'link_video_youtube',
        'logo',
        'name',
        'faq',
        'alur_pendaftaran',
        'facebook',
        'instagram',
        'youtube',
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
    ];

    protected $casts = [
        'ppdb_jadwal_buka' => 'datetime',
        'ppdb_jadwal_tutup' => 'datetime',
        'ppdb_jadwal_pengumuman' => 'datetime',
        'ppdb_kuota_jurusan' => 'array',
        'ppdb_jalur' => 'array',
        'jurusan' => 'array',
        'galeri_foto' => 'array',
        'misi' => 'array',
        'fasilitas' => 'array',
        'keunggulan' => 'array',
        'prestasi' => 'array',
        'program_unggulan' => 'array',
        'ekstrakurikuler' => 'array',
        'testimoni' => 'array',
        'faq' => 'array',
        'alur_pendaftaran' => 'array',
    ];

    // Relasi ke Madrasah
    public function sekolah()
    {
        return $this->belongsTo(Madrasah::class, 'sekolah_id');
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

    /**
     * Check if PPDB is currently open
     */
    public function isPembukaan()
    {
        return $this->ppdb_status === 'buka' &&
               $this->ppdb_jadwal_buka &&
               $this->ppdb_jadwal_tutup &&
               now()->between($this->ppdb_jadwal_buka, $this->ppdb_jadwal_tutup);
    }

    /**
     * Check if PPDB is about to start (within 7 days before opening)
     */
    public function isStarted()
    {
        return $this->ppdb_status === 'buka' &&
               $this->ppdb_jadwal_buka &&
               now()->lt($this->ppdb_jadwal_buka) &&
               now()->diffInDays($this->ppdb_jadwal_buka) <= 7;
    }

    /**
     * Get total number of pendaftar
     */
    public function totalPendaftar()
    {
        return $this->pendaftars()->count();
    }

    /**
     * Relasi ke PPDB Jalur
     */
    public function jalurs()
    {
        return $this->hasMany(PPDBJalur::class, 'ppdb_setting_id');
    }
}
