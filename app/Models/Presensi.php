<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensis';

    protected $fillable = [
        'user_id',
        'tanggal',
        'waktu_masuk',
        'waktu_keluar',
        'latitude',
        'longitude',
        'lokasi',
        'is_fake_location',
        'fake_location_analysis',
        'is_fake_location_keluar',
        'fake_location_analysis_keluar',
        'accuracy',
        'altitude',
        'speed',
        'device_info',
        'location_readings',
        'status',
        'keterangan',
        'surat_izin_path',
        'status_izin',
        'approved_by',
        'status_kepegawaian_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_masuk' => 'datetime:H:i',
        'waktu_keluar' => 'datetime:H:i',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_fake_location' => 'boolean',
        'fake_location_analysis' => 'array',
        'is_fake_location_keluar' => 'boolean',
        'fake_location_analysis_keluar' => 'array',
        'accuracy' => 'decimal:2',
        'altitude' => 'decimal:2',
        'speed' => 'decimal:2',
        'location_readings' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function madrasah()
    {
        return $this->hasOneThrough(Madrasah::class, User::class, 'id', 'id', 'user_id', 'madrasah_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function statusKepegawaian()
    {
        return $this->belongsTo(\App\Models\StatusKepegawaian::class, 'status_kepegawaian_id');
    }
}
