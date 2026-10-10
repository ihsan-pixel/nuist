<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpppmnuEventGuestInvitation extends Model
{
    protected $fillable = ['event_id', 'name', 'organization', 'phone'];

    protected $casts = ['phone' => 'encrypted'];

    public function event()
    {
        return $this->belongsTo(BpppmnuEvent::class, 'event_id');
    }

    public function attendance()
    {
        return $this->hasOne(BpppmnuEventGuestAttendance::class, 'guest_invitation_id');
    }
}
