<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmiPeriodSchool extends Model
{
    use SoftDeletes;

    protected $fillable = ['ami_period_id', 'madrasah_id', 'status', 'internal_index'];
    protected $casts = ['internal_index' => 'decimal:2'];

    public function period()
    {
        return $this->belongsTo(AmiPeriod::class, 'ami_period_id');
    }

    public function school()
    {
        return $this->belongsTo(Madrasah::class, 'madrasah_id');
    }
}
