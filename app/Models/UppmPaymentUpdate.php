<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UppmPaymentUpdate extends Model
{
    use HasFactory;

    public const PERIOD_JAN_JUN = 'jan_jun';
    public const PERIOD_JUL_DES = 'jul_des';

    public const PERIOD_LABELS = [
        self::PERIOD_JAN_JUN => 'Jan-Jun',
        self::PERIOD_JUL_DES => 'Jul-Des',
    ];

    protected $fillable = [
        'madrasah_id',
        'tahun_anggaran',
        'payment_period',
        'transfer_date',
        'amount',
        'note',
    ];

    protected $casts = [
        'tahun_anggaran' => 'integer',
        'transfer_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function madrasah(): BelongsTo
    {
        return $this->belongsTo(Madrasah::class);
    }

    public function getPaymentPeriodLabelAttribute(): string
    {
        return self::PERIOD_LABELS[$this->payment_period] ?? $this->payment_period;
    }
}
