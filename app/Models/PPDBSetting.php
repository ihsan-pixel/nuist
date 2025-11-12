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
        'sekolah_id', 'slug', 'nama_sekolah', 'tahun', 'status', 'jadwal_buka', 'jadwal_tutup'
    ];

    protected $casts = [
        'jadwal_buka' => 'datetime',
        'jadwal_tutup' => 'datetime',
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
