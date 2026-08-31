<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceEnrollmentCapture extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'phase_key',
        'phase_label',
        'capture_index',
        'captured_image',
        'face_descriptor',
        'quality_score',
        'liveness_score',
        'metadata',
    ];

    protected $casts = [
        'face_descriptor' => 'array',
        'metadata' => 'array',
        'quality_score' => 'float',
        'liveness_score' => 'float',
    ];

    public function session()
    {
        return $this->belongsTo(FaceEnrollmentSession::class, 'session_id');
    }
}
