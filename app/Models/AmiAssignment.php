<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmiAssignment extends Model
{
    use SoftDeletes;
    protected $fillable = ['ami_period_id', 'madrasah_id', 'auditor_id', 'assigned_by', 'role_in_team', 'is_lead', 'desk_review_at', 'visitasi_at', 'status'];

    public function period()
    {
        return $this->belongsTo(AmiPeriod::class, 'ami_period_id');
    }

    public function school()
    {
        return $this->belongsTo(Madrasah::class, 'madrasah_id');
    }
}
