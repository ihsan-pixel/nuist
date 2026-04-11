<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppSiswaBill extends Model
{
    use HasFactory;

    protected $table = 'spp_siswa_bills';

    protected $fillable = [
        'siswa_id',
        'madrasah_id',
        'setting_id',
        'nomor_tagihan',
        'periode',
        'jatuh_tempo',
        'nominal',
        'denda',
        'total_tagihan',
        'status',
        'catatan',
    ];

    protected $casts = [
        'jatuh_tempo' => 'date',
        'nominal' => 'decimal:2',
        'denda' => 'decimal:2',
        'total_tagihan' => 'decimal:2',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }

    public function setting()
    {
        return $this->belongsTo(SppSiswaSetting::class, 'setting_id');
    }

    public function transactions()
    {
        return $this->hasMany(SppSiswaTransaction::class, 'bill_id');
    }
}
