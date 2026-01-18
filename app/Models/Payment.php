<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'madrasah_id',
        'tahun_anggaran',
        'nominal',
        'metode_pembayaran',
        'status',
        'keterangan',
        'tagihan_id',
        'order_id',
        'transaction_id',
        'payment_type',
        'response_midtrans',
        'pdf_url',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }
}
