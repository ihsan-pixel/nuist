<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingClassActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'class_name',
        'start_date',
        'end_date',
        'activity_type',
        'description',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(Madrasah::class, 'school_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isActiveOnDate(string $dateYmd): bool
    {
        $start = optional($this->start_date)->toDateString();
        $end = optional($this->end_date)->toDateString();

        if (!$start || !$end) {
            return false;
        }

        return $start <= $dateYmd && $end >= $dateYmd;
    }
}

