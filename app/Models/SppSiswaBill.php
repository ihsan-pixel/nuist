<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SppSiswaBill extends Model
{
    use HasFactory;

    protected $table = 'spp_siswa_bills';

    protected $fillable = [
        'siswa_id',
        'madrasah_id',
        'setting_id',
        'jenis_tagihan',
        'nomor_tagihan',
        'periode',
        'jatuh_tempo',
        'nominal',
        'total_tagihan',
        'status',
        'catatan',
    ];

    protected $casts = [
        'jatuh_tempo' => 'date',
        'nominal' => 'decimal:2',
        'total_tagihan' => 'decimal:2',
    ];

    public static function makeBillNumber(Siswa $siswa, Carbon $periode, string $jenisTagihan = 'SPP'): string
    {
        $prefix = Str::upper(Str::slug($jenisTagihan, ''));
        $prefix = $prefix !== '' ? Str::limit($prefix, 8, '') : 'TAGIHAN';

        return sprintf(
            '%s-%s-%s-%04d',
            $prefix,
            $periode->format('Ym'),
            $siswa->id,
            self::query()->whereYear('created_at', now()->year)->count() + 1
        );
    }

    public function setJenisTagihanAttribute($value): void
    {
        $this->attributes['jenis_tagihan'] = filled($value) ? trim((string) $value) : 'SPP';
    }

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

    public function getOutstandingAmountAttribute(): float
    {
        $verifiedAmount = (float) $this->transactions()
            ->where('status_verifikasi', 'diverifikasi')
            ->sum('nominal_bayar');

        return max(0, (float) $this->total_tagihan - $verifiedAmount);
    }
}
