<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PPDBSetting extends Model
{
    use HasFactory;
    protected $table = 'ppdb_settings';
    protected $fillable = [
        'sekolah_id', 'slug', 'nama_sekolah', 'tahun', 'status', 'jadwal_buka', 'jadwal_tutup',
        'kuota_jurusan', 'periode_presensi_mulai', 'periode_presensi_selesai', 'kuota_total',
        'wajib_unggah_kk', 'syarat_tambahan',
        'email_kontak', 'telepon_kontak', 'alamat_kontak', 'jadwal_pengumuman', 'catatan_pengumuman',
        'visi', 'misi', 'fasilitas', 'prestasi', 'ekstrakurikuler', 'biaya_pendidikan', 'informasi_tambahan'
    ];

    protected $casts = [
        'jadwal_buka' => 'datetime',
        'jadwal_tutup' => 'datetime',
        'periode_presensi_mulai' => 'datetime',
        'periode_presensi_selesai' => 'datetime',
        'jadwal_pengumuman' => 'datetime',
        'kuota_jurusan' => 'array',
        'wajib_unggah_foto' => 'boolean',
        'wajib_unggah_ijazah' => 'boolean',
        'wajib_unggah_kk' => 'boolean',
    ];

    /**
     * Relasi ke Madrasah/Sekolah
     */
    public function sekolah()
    {
        return $this->belongsTo(Madrasah::class, 'sekolah_id');
    }

    /**
     * Relasi ke Pendaftar (plural)
     */
    public function pendaftars()
    {
        return $this->hasMany(PPDBPendaftar::class, 'ppdb_setting_id');
    }

    /**
     * Relasi ke Jalur
     */
    public function jalurs()
    {
        return $this->hasMany(PPDBJalur::class, 'ppdb_setting_id');
    }

    /**
     * Relasi ke Verifikasi
     */
    public function verifikasis()
    {
        return $this->hasMany(PPDBVerifikasi::class, 'ppdb_setting_id');
    }

    /**
     * Cek apakah pendaftaran masih buka
     */
    public function isPembukaan()
    {
        return $this->status === 'buka' && now()->isBetween($this->jadwal_buka, $this->jadwal_tutup);
    }

    /**
     * Cek apakah sudah dimulai
     */
    public function isStarted()
    {
        return now()->isAfter($this->jadwal_buka);
    }

    /**
     * Cek apakah sudah ditutup
     */
    public function isClosed()
    {
        return now()->isAfter($this->jadwal_tutup);
    }

    /**
     * Hitung sisa waktu dalam hari
     */
    public function remainingDays()
    {
        if ($this->isClosed()) {
            return 0;
        }
        return $this->jadwal_tutup->diffInDays(now());
    }

    /**
     * Hitung total pendaftar
     */
    public function totalPendaftar()
    {
        return $this->pendaftars()->count();
    }

    /**
     * Hitung pendaftar berdasarkan status
     */
    public function totalByStatus($status)
    {
        return $this->pendaftars()->where('status', $status)->count();
    }
}
