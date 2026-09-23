<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppSiswaVirtualAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'madrasah_id', 'siswa_id', 'setting_id', 'tahun_ajaran', 'trx_id',
        'virtual_account', 'customer_name', 'customer_email', 'customer_phone',
        'trx_amount', 'expired_at', 'description', 'status', 'exported_at',
        'provider_payload', 'created_by',
    ];

    protected $casts = [
        'trx_amount' => 'decimal:2',
        'expired_at' => 'datetime',
        'exported_at' => 'datetime',
        'provider_payload' => 'array',
    ];

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function madrasah() { return $this->belongsTo(Madrasah::class); }
    public function setting() { return $this->belongsTo(SppSiswaSetting::class, 'setting_id'); }
}
