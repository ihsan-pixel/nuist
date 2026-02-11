<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TalentaMateri extends Model
{
    use HasFactory;

    protected $table = 'talenta_materi';

    // Level constants
    const LEVEL_1 = 1;
    const LEVEL_2 = 2;

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'kode_materi',
        'judul_materi',
        'slug',
        'level_materi',
        'tanggal_materi',
        'status',
    ];

    protected $casts = [
        'tanggal_materi' => 'datetime',
        'level_materi' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($materi) {
            if (empty($materi->slug)) {
                $slug = Str::slug($materi->judul_materi);
                $count = static::where('slug', 'like', "$slug%")->count();
                $materi->slug = $count ? "{$slug}-{$count}" : $slug;
            }
        });

        static::updating(function ($materi) {
            if ($materi->isDirty('judul_materi') && empty($materi->slug)) {
                $slug = Str::slug($materi->judul_materi);
                $count = static::where('slug', 'like', "$slug%")->count();
                $materi->slug = $count ? "{$slug}-{$count}" : $slug;
            }
        });
    }

    public function pemateris()
    {
        return $this->belongsToMany(
            TalentaPemateri::class,
            'talenta_pemateri_materi',
            'talenta_materi_id',
            'talenta_pemateri_id'
        );
    }

    public function fasilitators()
    {
        return $this->belongsToMany(
            TalentaFasilitator::class,
            'talenta_fasilitator_materi',
            'talenta_materi_id',
            'talenta_fasilitator_id'
        );
    }

    // Helper methods
    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isLevel1()
    {
        return $this->level_materi === self::LEVEL_1;
    }

    public function isLevel2()
    {
        return $this->level_materi === self::LEVEL_2;
    }
}
