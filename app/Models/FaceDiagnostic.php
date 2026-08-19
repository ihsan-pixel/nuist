<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class FaceDiagnostic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'actor_id',
        'source',
        'outcome',
        'stage',
        'reason',
        'device',
        'android_version',
        'browser',
        'gpu',
        'webgl',
        'tf_backend',
        'video_size',
        'ready_state',
        'camera_state',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
