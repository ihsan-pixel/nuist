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
        'tanggal_bayar',
        'nominal_bayar',
        'metode_pembayaran',
        'status_verifikasi',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'nominal_bayar' => 'decimal:2',
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
