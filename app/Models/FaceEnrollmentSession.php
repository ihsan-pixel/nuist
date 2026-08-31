<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceEnrollmentSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_uuid',
        'user_id',
        'school_id',
        'operator_user_id',
        'status',
        'active_phase',
        'face_descriptor',
        'face_data',
        'quality_score',
        'liveness_score',
        'completed_at',
        'metadata',
    ];

    protected $casts = [
        'face_descriptor' => 'array',
        'face_data' => 'array',
        'metadata' => 'array',
        'quality_score' => 'float',
        'liveness_score' => 'float',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function captures()
    {
        return $this->hasMany(FaceEnrollmentCapture::class, 'session_id');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_user_id');
    }
}
