<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppSiswaSetting extends Model
{
    use HasFactory;

    protected $table = 'spp_siswa_settings';

    protected $fillable = [
        'madrasah_id',
        'tahun_ajaran',
        'nominal_spp',
        'tanggal_jatuh_tempo',
        'denda_harian',
        'is_active',
        'catatan',
    ];

    protected $casts = [
        'nominal_spp' => 'decimal:2',
        'denda_harian' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }

    public function bills()
    {
        return $this->hasMany(SppSiswaBill::class, 'setting_id');
    }
}
