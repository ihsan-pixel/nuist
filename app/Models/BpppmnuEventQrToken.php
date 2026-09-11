<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpppmnuEventQrToken extends Model
{
    protected $fillable = ['event_id', 'token_hash', 'expires_at', 'revoked_at'];

    protected $hidden = ['token_hash'];

    protected $casts = ['expires_at' => 'datetime', 'revoked_at' => 'datetime'];

    public function event()
    {
        return $this->belongsTo(BpppmnuEvent::class, 'event_id');
    }
}
