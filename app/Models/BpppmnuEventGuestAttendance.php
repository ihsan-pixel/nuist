<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpppmnuEventGuestAttendance extends Model
{
    protected $fillable = [
        'event_id', 'guest_invitation_id', 'guest_name', 'guest_phone', 'guest_organization', 'attended_at',
        'method', 'confirmation_code', 'request_fingerprint', 'ip_hash', 'user_agent',
        'latitude', 'longitude', 'distance_meters',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
        'guest_phone' => 'encrypted',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function event()
    {
        return $this->belongsTo(BpppmnuEvent::class, 'event_id');
    }

    public function invitation()
    {
        return $this->belongsTo(BpppmnuEventGuestInvitation::class, 'guest_invitation_id');
    }
}
