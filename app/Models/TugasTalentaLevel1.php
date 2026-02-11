<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class TugasTalentaLevel1 extends Model
{
    use HasFactory;

    protected $table = 'tugas_talenta_level1';
    // optional tapi bagus kalau nama tabel tidak jamak standar

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

    /* ================= RELATION ================= */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
