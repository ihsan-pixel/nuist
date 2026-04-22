<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Izin extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'tanggal_selesai',
        'hari_presensi',
        'hari_tidak_presensi',
        'type',
        'alasan',
        'deskripsi_tugas',
        'lokasi_tugas',
        'file_path',
        'file_name',
        'waktu_masuk',
        'waktu_keluar',
        'status',
        'approved_by',
        'approved_at',
        'approval_notes',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_selesai' => 'date',
        'hari_presensi' => 'array',
        'hari_tidak_presensi' => 'array',
        'waktu_masuk' => 'datetime:H:i',
        'waktu_keluar' => 'datetime:H:i',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
