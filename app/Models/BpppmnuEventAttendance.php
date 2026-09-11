<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpppmnuEventAttendance extends Model
{
    protected $fillable = ['event_id', 'user_id', 'attended_at', 'method'];

    protected $casts = ['attended_at' => 'datetime'];

    public function event()
    {
        return $this->belongsTo(BpppmnuEvent::class, 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
