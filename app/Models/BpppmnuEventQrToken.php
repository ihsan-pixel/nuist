<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpppmnuEventQrToken extends Model
{
    protected $fillable = ['event_id', 'token_hash', 'raw_token', 'expires_at', 'claimed_at', 'revoked_at'];

    protected $hidden = ['token_hash', 'raw_token'];

    protected $casts = [
        'raw_token' => 'encrypted',
        'expires_at' => 'datetime',
        'claimed_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(BpppmnuEvent::class, 'event_id');
    }
}
