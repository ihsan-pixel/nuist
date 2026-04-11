<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppSiswaTransaction extends Model
{
    use HasFactory;

    protected $table = 'spp_siswa_transactions';

    protected $fillable = [
        'bill_id',
        'siswa_id',
        'madrasah_id',
        'nomor_transaksi',
        'external_order_id',
        'external_transaction_id',
        'tanggal_bayar',
        'nominal_bayar',
        'metode_pembayaran',
        'payment_channel',
        'va_number',
        'va_expired_at',
        'status_verifikasi',
        'keterangan',
        'payment_payload',
        'created_by',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'nominal_bayar' => 'decimal:2',
        'va_expired_at' => 'datetime',
        'payment_payload' => 'array',
    ];

    public function bill()
    {
        return $this->belongsTo(SppSiswaBill::class, 'bill_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
