<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TugasTalentaLevel1 extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'area',
        'jenis_tugas',
        'data',
        'file_path',
        'submitted_at',
    ];

    protected $casts = [
        'data' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessor to get decoded data
    public function getFormDataAttribute()
    {
        return json_decode($this->data, true);
    }
}
