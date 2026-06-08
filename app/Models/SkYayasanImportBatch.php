<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkYayasanImportBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'madrasah_id',
        'uploaded_by',
        'reviewed_by',
        'status',
        'original_filename',
        'stored_path',
        'total_rows',
        'valid_rows',
        'invalid_rows',
        'headings_valid',
        'missing_headings',
        'unexpected_headings',
        'payload_rows',
        'matched_user_ids',
        'review_notes',
        'uploaded_at',
        'reviewed_at',
        'synced_at',
    ];

    protected $casts = [
        'headings_valid' => 'boolean',
        'missing_headings' => 'array',
        'unexpected_headings' => 'array',
        'payload_rows' => 'array',
        'matched_user_ids' => 'array',
        'uploaded_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'synced_at' => 'datetime',
    ];

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class, 'madrasah_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function rows()
    {
        return $this->hasMany(SkYayasanImportRow::class, 'batch_id')->orderBy('row_number');
    }
}
