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
        'polygon_koordinat_2',
        'enable_dual_polygon',
        'hari_kbm',
        'scod',
        // Profile fields
        'tagline',
        'deskripsi_singkat',
        'tahun_berdiri',
        'sejarah',
        'akreditasi',
        'nilai_nilai',
        'visi',
        'misi',
        'keunggulan',
        'fasilitas',
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
        'galeri_foto',
        'video_profile',
        'telepon',
        'email',
        'website',
        'jam_operasional_buka',
        'jam_operasional_tutup',
        'brosur_pdf',
        'faq',
        'alur_pendaftaran',
    // PPDB Settings fields
    'ppdb_status',
        'ppdb_jadwal_buka',
        'ppdb_jadwal_tutup',
        'ppdb_kuota_total',
        'ppdb_jadwal_pengumuman',
        'ppdb_kuota_jurusan',
        'ppdb_jalur',
        'ppdb_biaya_pendaftaran',
        'ppdb_catatan_pengumuman',
    // Note: ppdb contact fields moved to ppdb_pendaftar table. Keep madrasah defaults elsewhere if needed.
    ];

    protected $casts = [
        'misi' => 'array',
        'keunggulan' => 'array',
        'fasilitas' => 'array',
        'jurusan' => 'array',
        'prestasi' => 'array',
        'program_unggulan' => 'array',
        'ekstrakurikuler' => 'array',
        'testimoni' => 'array',
        'galeri_foto' => 'array',
        'faq' => 'array',
        'alur_pendaftaran' => 'array',
        'tahun_berdiri' => 'integer',
        'jumlah_siswa' => 'integer',
        'jumlah_guru' => 'integer',
        'jumlah_jurusan' => 'integer',
        'jumlah_sarana' => 'integer',
        'ppdb_jadwal_buka' => 'datetime',
        'ppdb_jadwal_tutup' => 'datetime',
        'ppdb_jadwal_pengumuman' => 'datetime',
        'ppdb_kuota_jurusan' => 'array',
        'ppdb_jalur' => 'array',
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

    // Relasi: satu madrasah punya banyak teaching schedules
    public function teachingSchedules()
    {
        return $this->hasMany(TeachingSchedule::class, 'school_id');
    }

    // Relasi: satu madrasah punya banyak teaching attendances melalui teaching schedules
    public function teachingAttendances()
    {
        return $this->hasManyThrough(TeachingAttendance::class, TeachingSchedule::class, 'school_id', 'teaching_schedule_id');
    }

    // Relasi: satu madrasah punya banyak PPDB settings
    public function ppdbSettings()
    {
        return $this->hasMany(PPDBSetting::class, 'sekolah_id');
    }
}
