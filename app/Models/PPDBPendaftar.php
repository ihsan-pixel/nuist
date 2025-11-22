<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPDBPendaftar extends Model
{
    use HasFactory;
    protected $table = 'ppdb_pendaftar';
    protected $fillable = [
        'ppdb_setting_id',
        'ppdb_jalur_id',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'nisn',
        'asal_sekolah',
        'ppdb_nomor_whatsapp_siswa',
        'ppdb_nomor_whatsapp_wali',
        'ppdb_email_siswa',
        'jurusan_pilihan',
        'berkas_kk',
        'berkas_ijazah',
        'status',
        'nomor_pendaftaran',
        'nilai',
        'ranking',
        'catatan_verifikasi',
        'diverifikasi_oleh',
        'diverifikasi_tanggal',
        'diseleksi_oleh',
        'diseleksi_tanggal',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'diverifikasi_tanggal' => 'datetime',
        'diseleksi_tanggal' => 'datetime',
    ];

    /**
     * Relasi ke PPDBSetting
     */
    public function ppdbSetting()
    {
        return $this->belongsTo(PPDBSetting::class, 'ppdb_setting_id');
    }

    /**
     * Relasi ke PPDBJalur
     */
    public function jalur()
    {
        return $this->belongsTo(PPDBJalur::class, 'ppdb_jalur_id');
    }

    /**
     * Relasi ke User (verifikator)
     */
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    /**
     * Relasi ke User (penyeleksi)
     */
    public function penyeleksi()
    {
        return $this->belongsTo(User::class, 'diseleksi_oleh');
    }

    /**
     * Relasi ke Verifikasi (jika ada tabel terpisah)
     */
    public function verifikasis()
    {
        return $this->hasMany(PPDBVerifikasi::class, 'ppdb_pendaftar_id');
    }

    /**
     * Scope untuk pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk verifikasi
     */
    public function scopeVerifikasi($query)
    {
        return $query->where('status', 'verifikasi');
    }

    /**
     * Scope untuk lulus
     */
    public function scopeLulus($query)
    {
        return $query->where('status', 'lulus');
    }

    /**
     * Scope untuk tidak lulus
     */
    public function scopeTidakLulus($query)
    {
        return $query->where('status', 'tidak_lulus');
    }

    /**
     * Cek apakah sudah diverifikasi
     */
    public function isVerified()
    {
        return $this->status !== 'pending';
    }

    /**
     * Cek apakah hasil seleksi sudah keluar
     */
    public function isSelected()
    {
        return in_array($this->status, ['lulus', 'tidak_lulus']);
    }

    /**
     * Generate nomor kartu pendaftaran
     */
    public function getKartuPendaftaranAttribute()
    {
        return $this->nomor_pendaftaran ?? 'N/A';
    }
}
