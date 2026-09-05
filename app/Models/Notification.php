<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::created(function (self $notification): void {
            $user = $notification->user;
            if (!$user) {
                return;
            }

            $data = is_array($notification->data) ? $notification->data : [];
            $data['notification_id'] = $notification->id;
            $data['type'] = $notification->type;

            app(\App\Services\FcmPushService::class)->sendToUser(
                $user,
                (string) $notification->title,
                (string) $notification->message,
                $data
            );
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
