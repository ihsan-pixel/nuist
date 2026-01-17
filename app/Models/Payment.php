<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'madrasah_id',
        'tahun_anggaran',
        'nominal',
        'metode_pembayaran',
        'status',
        'keterangan',
        'payment_data',
        'paid_at',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'payment_data' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Relationship with Madrasah
     */
    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }
}
