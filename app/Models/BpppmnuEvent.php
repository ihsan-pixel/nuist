<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpppmnuEvent extends Model
{
    protected $fillable = ['name', 'type', 'description', 'organizer', 'person_in_charge', 'start_at', 'end_at', 'attendance_open_at', 'attendance_close_at', 'location_name', 'address', 'meeting_url', 'rundown', 'attachment', 'status', 'created_by', 'location_validation_enabled', 'latitude', 'longitude', 'location_radius_meters'];
    protected $casts = ['start_at' => 'datetime', 'end_at' => 'datetime', 'attendance_open_at' => 'datetime', 'attendance_close_at' => 'datetime', 'location_validation_enabled'=>'boolean','latitude'=>'float','longitude'=>'float'];

    public function invitations()
    {
        return $this->hasMany(BpppmnuEventInvitation::class, 'event_id');
    }

    public function attendances()
    {
        return $this->hasMany(BpppmnuEventAttendance::class, 'event_id');
    }

    public function qrTokens()
    {
        return $this->hasMany(BpppmnuEventQrToken::class, 'event_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isFinished(): bool
    {
        return now()->gt($this->end_at) && now()->gt($this->attendance_close_at);
    }

    public function isOpen(): bool
    {
        return $this->status === 'published' && now()->betweenIncluded($this->attendance_open_at, $this->attendance_close_at);
    }

    public function isLocked(): bool
    {
        return now()->gte($this->attendance_open_at) || $this->attendances()->exists();
    }
}
