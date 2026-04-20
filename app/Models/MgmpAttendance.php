<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MgmpAttendance extends Model
{
    use HasFactory;

    protected $table = 'mgmp_attendances';

    protected $fillable = [
        'mgmp_report_id',
        'mgmp_group_id',
        'user_id',
        'attended_at',
        'latitude',
        'longitude',
        'distance_meters',
        'selfie_path',
        'lokasi',
        'accuracy',
        'device_info',
        'location_readings',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'accuracy' => 'decimal:2',
        'location_readings' => 'array',
    ];

    public function report()
    {
        return $this->belongsTo(MgmpReport::class, 'mgmp_report_id');
    }

    public function mgmpGroup()
    {
        return $this->belongsTo(MgmpGroup::class, 'mgmp_group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
