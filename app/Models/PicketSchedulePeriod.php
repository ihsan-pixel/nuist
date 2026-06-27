<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicketSchedulePeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'start_date',
        'end_date',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(Madrasah::class, 'school_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function submissions()
    {
        return $this->hasMany(PicketScheduleSubmission::class, 'picket_schedule_period_id');
    }

    public function getDateRangeLabelAttribute(): string
    {
        $start = Carbon::parse($this->start_date, 'Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY');
        $end = Carbon::parse($this->end_date, 'Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY');

        return $start === $end ? $start : $start . ' - ' . $end;
    }

    public function containsDate(Carbon|string $date): bool
    {
        $date = $date instanceof Carbon ? $date->copy()->startOfDay() : Carbon::parse($date, 'Asia/Jakarta')->startOfDay();

        return $date->betweenIncluded(
            Carbon::parse($this->start_date, 'Asia/Jakarta')->startOfDay(),
            Carbon::parse($this->end_date, 'Asia/Jakarta')->startOfDay(),
        );
    }
}
