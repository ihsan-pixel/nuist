<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentaPenilaianFasilitator extends Model
{
    use HasFactory;

    protected $table = 'talenta_penilaian_fasilitator';

    protected $fillable = [
        'talenta_fasilitator_id',
        'user_id',
        'fasilitasi',
        'pendampingan',
        'respons',
        'koordinasi',
        'monitoring',
        'waktu',
    ];

    protected $casts = [
        'fasilitasi' => 'integer',
        'pendampingan' => 'integer',
        'respons' => 'integer',
        'koordinasi' => 'integer',
        'monitoring' => 'integer',
        'waktu' => 'integer',
    ];

    public function fasilitator()
    {
        return $this->belongsTo(TalentaFasilitator::class, 'talenta_fasilitator_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
