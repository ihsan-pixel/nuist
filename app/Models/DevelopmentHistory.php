<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DevelopmentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'version',
        'development_date',
        'migration_file',
        'details'
    ];

    protected $casts = [
        'development_date' => 'date',
        'details' => 'array'
    ];

    /**
     * Get development histories ordered by date (newest first)
     */
    public static function getTimelineData()
    {
        return self::orderBy('development_date', 'desc')
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Get development histories grouped by year
     */
    public static function getGroupedByYear()
    {
        return self::orderBy('development_date', 'desc')
                   ->get()
                   ->groupBy(function($item) {
                       return Carbon::parse($item->development_date)->year;
                   });
    }

    /**
     * Get development histories by type
     */
    public static function getByType($type)
    {
        return self::where('type', $type)
                   ->orderBy('development_date', 'desc')
                   ->get();
    }

    /**
     * Get type badge class for UI
     */
    public function getTypeBadgeClass()
    {
        $classes = [
            'migration' => 'bg-primary',
            'feature' => 'bg-success',
            'update' => 'bg-info',
            'bugfix' => 'bg-warning',
            'enhancement' => 'bg-secondary'
        ];

        return $classes[$this->type] ?? 'bg-light';
    }

    /**
     * Get type icon for UI
     */
    public function getTypeIcon()
    {
        $icons = [
            'migration' => 'bx-data',
            'feature' => 'bx-plus-circle',
            'update' => 'bx-refresh',
            'bugfix' => 'bx-wrench',
            'enhancement' => 'bx-trending-up'
        ];

        return $icons[$this->type] ?? 'bx-circle';
    }

    /**
     * Format development date for display
     */
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->development_date)->format('d M Y');
    }

    /**
     * Get relative time for development date
     */
    public function getRelativeDateAttribute()
    {
        return Carbon::parse($this->development_date)->diffForHumans();
    }
}
