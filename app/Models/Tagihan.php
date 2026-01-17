<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $fillable = [
        'madrasah_id',
        'tahun_anggaran',
        'nominal',
        'status',
        'jatuh_tempo',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'jatuh_tempo' => 'date',
    ];

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }
}
