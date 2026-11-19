<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubmission extends Model
{
    protected $fillable = [
        'submitted_by', 'madrasah_id', 'status', 'payload', 'review_note', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = ['payload' => 'array', 'reviewed_at' => 'datetime'];

    public function submitter() { return $this->belongsTo(User::class, 'submitted_by'); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function madrasah() { return $this->belongsTo(Madrasah::class); }
}
