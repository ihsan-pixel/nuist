<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\TugasNilai;
use App\Models\TalentaKelompok;

class TugasTalentaLevel1 extends Model
{
    use HasFactory;

    protected $table = 'tugas_talenta_level1';
    // optional tapi bagus kalau nama tabel tidak jamak standar

    protected $fillable = [
        'user_id',
        'kelompok_id',
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

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(TalentaKelompok::class, 'kelompok_id');
    }

    public function nilai()
    {
        return $this->hasMany(TugasNilai::class, 'tugas_talenta_level1_id');
    }
}
