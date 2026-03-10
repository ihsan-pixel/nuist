<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class TalentaPemateri extends Model
{
    use HasFactory;

    protected $table = 'talenta_pemateri';

    protected $fillable = [
        'kode_pemateri',
        'nama',
        'user_id',
    ];

    public function materis()
    {
        return $this->belongsToMany(
            TalentaMateri::class,
            'talenta_pemateri_materi',
            'talenta_pemateri_id',
            'talenta_materi_id'
        );
    }

    /**
     * Pemateri mungkin terhubung ke user (user_id).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
