<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceKioskLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'registered_device_id',
        'madrasah_id',
        'operator_user_id',
        'target_user_id',
        'action',
        'status',
        'ip_address',
        'user_agent',
        'payload_snapshot',
    ];

    protected $casts = [
        'payload_snapshot' => 'array',
    ];

    public function device()
    {
        return $this->belongsTo(RegisteredAttendanceDevice::class, 'registered_device_id');
    }

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class, 'madrasah_id');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_user_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
