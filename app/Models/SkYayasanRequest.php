<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkYayasanRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'madrasah_id',
        'employee_id',
        'submitted_by',
        'reviewed_by',
        'template_id',
        'request_number',
        'request_type',
        'employment_category',
        'effective_start_date',
        'effective_end_date',
        'current_status',
        'submission_notes',
        'review_notes',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'effective_start_date' => 'date',
        'effective_end_date' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class, 'madrasah_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function template()
    {
        return $this->belongsTo(SkYayasanTemplate::class, 'template_id');
    }

    public function document()
    {
        return $this->hasOne(SkYayasanDocument::class, 'request_id');
    }
}
