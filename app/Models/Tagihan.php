<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $fillable = [
        'madrasah_id',
        'tahun_anggaran',
        'nominal',
        'nominal_dibayar',
        'status',
        'jatuh_tempo',
        'keterangan',
        'nomor_invoice',
        'tanggal_pembayaran',
        'jenis_tagihan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'nominal_dibayar' => 'decimal:2',
        'jatuh_tempo' => 'date',
        'tanggal_pembayaran' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tagihan) {
            if (!$tagihan->nomor_invoice) {
                $madrasah = Madrasah::find($tagihan->madrasah_id);
                if ($madrasah) {
                    $uniqueCode = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
                    $tagihan->nomor_invoice = 'INV-' . $madrasah->scod . '-' . $tagihan->tahun_anggaran . '-' . $uniqueCode;
                }
            }
        });
    }

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }
}
