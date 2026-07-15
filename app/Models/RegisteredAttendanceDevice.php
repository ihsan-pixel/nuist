<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisteredAttendanceDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'madrasah_id',
        'name',
        'device_type',
        'device_token_hash',
        'browser_fingerprint_hash',
        'allowed_ip_addresses',
        'is_active',
        'last_seen_at',
        'last_ip_address',
        'last_user_agent',
        'registered_by',
    ];

    protected $casts = [
        'allowed_ip_addresses' => 'array',
        'is_active' => 'boolean',
        'last_seen_at' => 'datetime',
    ];

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class, 'madrasah_id');
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'registered_device_id');
    }

    public function kioskLogs()
    {
        return $this->hasMany(AttendanceKioskLog::class, 'registered_device_id');
    }
}
