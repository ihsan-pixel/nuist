<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpppmnuEventInvitation extends Model
{
    protected $fillable = ['event_id', 'user_id'];

    public function event()
    {
        return $this->belongsTo(BpppmnuEvent::class, 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->hasOne(BpppmnuEventAttendance::class, 'event_id', 'event_id')
            ->whereColumn('user_id', 'bpppmnu_event_invitations.user_id');
    }
}
