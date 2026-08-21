<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmiPeriod extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'year', 'opens_at', 'closes_at', 'desk_review_at', 'visitasi_at', 'followup_deadline_at', 'status', 'settings'];

    protected $casts = [
        'opens_at' => 'date',
        'closes_at' => 'date',
        'desk_review_at' => 'date',
        'visitasi_at' => 'date',
        'followup_deadline_at' => 'date',
        'settings' => 'array',
    ];

    public function schools()
    {
        return $this->hasMany(AmiPeriodSchool::class);
    }
}
