<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'teaching_schedule_period_id',
        'teacher_id',
        'day',
        'subject',
        'class_name',
        'class_names',
        'start_time',
        'end_time',
        'created_by',
    ];

    protected $casts = [
        'class_names' => 'array',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function period()
    {
        return $this->belongsTo(TeachingSchedulePeriod::class, 'teaching_schedule_period_id');
    }

    public function school()
    {
        return $this->belongsTo(Madrasah::class, 'school_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teachingAttendances()
    {
        return $this->hasMany(TeachingAttendance::class);
    }

    public static function normalizeClassNames(mixed $classNames, ?string $fallbackClassName = null): array
    {
        $items = [];

        if (is_array($classNames)) {
            $items = $classNames;
        } elseif (is_string($classNames) && trim($classNames) !== '') {
            $items = preg_split('/[\r\n,;]+/', $classNames) ?: [];
        } elseif ($fallbackClassName !== null && trim($fallbackClassName) !== '') {
            $items = [$fallbackClassName];
        }

        $normalized = [];
        foreach ($items as $item) {
            $value = trim((string) $item);
            if ($value === '') {
                continue;
            }

            $key = mb_strtolower($value);
            if (!array_key_exists($key, $normalized)) {
                $normalized[$key] = $value;
            }
        }

        return array_values($normalized);
    }

    public static function formatClassNames(array $classNames): string
    {
        return implode(', ', self::normalizeClassNames($classNames));
    }

    public function resolvedClassNames(): array
    {
        return self::normalizeClassNames($this->class_names, $this->getRawOriginal('class_name'));
    }

    public function classNameLabel(): string
    {
        $label = self::formatClassNames($this->resolvedClassNames());

        if ($label !== '') {
            return $label;
        }

        return trim((string) $this->getRawOriginal('class_name'));
    }

    public function overlapsClassNames(array $classNames): bool
    {
        $current = collect($this->resolvedClassNames())
            ->map(fn ($item) => mb_strtolower(trim((string) $item)))
            ->filter()
            ->values();
        $incoming = collect(self::normalizeClassNames($classNames))
            ->map(fn ($item) => mb_strtolower(trim((string) $item)))
            ->filter()
            ->values();

        return $current->intersect($incoming)->isNotEmpty();
    }
}
