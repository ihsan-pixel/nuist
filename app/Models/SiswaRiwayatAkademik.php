<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiswaRiwayatAkademik extends Model
{
    protected $table = 'siswa_riwayat_akademik';

    protected $fillable = [
        'siswa_id',
        'madrasah_id',
        'tahun_ajaran',
        'semester',
        'jenjang_sekolah',
        'tingkat',
        'kelas',
        'jurusan',
        'status',
        'is_current',
        'sumber_data',
        'catatan',
        'updated_by',
    ];

    protected $casts = [
        'semester' => 'integer',
        'tingkat' => 'integer',
        'is_current' => 'boolean',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
