<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DayMarker extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'scope_key',
        'madrasah_id',
        'class_name',
        'marker',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public static function scopeKeyGlobal(): string
    {
        return 'global';
    }

    public static function scopeKeySchool(int $madrasahId): string
    {
        return 'school:' . $madrasahId;
    }

    public static function scopeKeyClass(int $madrasahId, string $className): string
    {
        $className = strtolower(trim($className));
        return 'class:' . $madrasahId . ':' . $className;
    }

    public static function resolveMarker(Carbon|string $date, ?int $madrasahId, ?string $className): array
    {
        $date = $date instanceof Carbon ? $date->toDateString() : Carbon::parse($date)->toDateString();

        $keys = [];
        if ($madrasahId && $className) {
            $keys[] = static::scopeKeyClass($madrasahId, $className);
        }
        if ($madrasahId) {
            $keys[] = static::scopeKeySchool($madrasahId);
        }
        $keys[] = static::scopeKeyGlobal();

        $records = static::query()
            ->whereDate('date', $date)
            ->whereIn('scope_key', $keys)
            ->get()
            ->keyBy('scope_key');

        $record = null;
        foreach ($keys as $key) {
            if ($records->has($key)) {
                $record = $records->get($key);
                break;
            }
        }

        if (!$record) {
            return [
                'marker' => 'normal',
                'record' => null,
            ];
        }

        return [
            'marker' => $record->marker ?? 'normal',
            'record' => $record,
        ];
    }

    public static function markerLabel(string $marker): string
    {
        return match ($marker) {
            'libur' => 'Hari Libur',
            'ujian' => 'Hari Ujian',
            'kegiatan_khusus' => 'Hari Kegiatan Khusus',
            default => 'Hari Normal',
        };
    }
}
