<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MgmpReport extends Model
{
    use HasFactory;

    protected $table = 'mgmp_reports';

    protected $fillable = [
        'mgmp_group_id',
        'created_by',
        'judul',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'deskripsi',
        'lokasi',
        'latitude',
        'longitude',
        'radius_meters',
        'materi',
        'hasil',
        'dokumentasi',
        'peserta',
        'jumlah_peserta'
    ];

    protected $casts = [
        'dokumentasi' => 'array',
        'peserta' => 'array',
        'tanggal' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function mgmpGroup()
    {
        return $this->belongsTo(MgmpGroup::class, 'mgmp_group_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances()
    {
        return $this->hasMany(MgmpAttendance::class, 'mgmp_report_id');
    }
}
