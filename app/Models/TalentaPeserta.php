<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentaPeserta extends Model
{
    use HasFactory;

    protected $table = 'talenta_peserta';

    protected $fillable = [
        'kode_peserta',
        'user_id',
        'asal_sekolah',
    ];

    /**
     * Get the user associated with the peserta.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get the madrasah through user relationship.
     */
    public function madrasah()
    {
        return $this->hasOneThrough(
            Madrasah::class,
            User::class,
            'id',
            'id',
            'user_id',
            'madrasah_id'
        );
    }

    /**
     * Penilaian entries for this peserta.
     */
    public function penilaian()
    {
        return $this->hasMany(\App\Models\TalentaPenilaianPeserta::class, 'talenta_peserta_id');
    }

    /**
     * Latest penilaian (one) for display convenience.
     */
    public function latestPenilaian()
    {
        // requires Laravel 8+ latestOfMany support
        return $this->hasOne(\App\Models\TalentaPenilaianPeserta::class, 'talenta_peserta_id')->latestOfMany();
    }

    /**
     * Get user's name (accessor).
     */
    public function getNamaAttribute()
    {
        return $this->user ? $this->user->name : null;
    }

    /**
     * Get user's email (accessor).
     */
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : null;
    }

    /**
     * Get madrasah name (accessor).
     */
    public function getNamaMadrasahAttribute()
    {
        return $this->user && $this->user->madrasah ? $this->user->madrasah->name : $this->asal_sekolah;
    }
}
