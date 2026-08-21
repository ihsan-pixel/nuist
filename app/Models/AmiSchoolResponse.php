<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmiSchoolResponse extends Model
{
    use SoftDeletes;
    protected $fillable = ['ami_period_school_id', 'ami_indicator_id', 'user_id', 'self_assessment_score', 'school_performance_description', 'internal_notes', 'status', 'submitted_at'];
    protected $casts = ['submitted_at' => 'datetime'];

    public function periodSchool()
    {
        return $this->belongsTo(AmiPeriodSchool::class, 'ami_period_school_id');
    }
}
