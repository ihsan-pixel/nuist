<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiometricProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'enrollment_uuid',
        'engine',
        'model',
        'model_version',
        'dimension',
        'embedding',
        'samples',
        'quality_score',
        'liveness_score',
        'source',
        'status',
        'metadata',
        'enrolled_at',
    ];

    protected $casts = [
        'embedding' => 'array',
        'samples' => 'array',
        'metadata' => 'array',
        'quality_score' => 'float',
        'liveness_score' => 'float',
        'enrolled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
