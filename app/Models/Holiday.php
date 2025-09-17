<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $table = 'holidays';

    protected $fillable = [
        'date',
        'name',
        'type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Check if a given date is a holiday
     */
    public static function isHoliday($date)
    {
        return static::where('date', $date)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get holiday information for a given date
     */
    public static function getHoliday($date)
    {
        return static::where('date', $date)
            ->where('is_active', true)
            ->first();
    }
}
