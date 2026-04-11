<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Siswa extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'siswa';

    protected $fillable = [
        'madrasah_id',
        'nis',
        'nama_lengkap',
        'nama_orang_tua_wali',
        'email',
        'email_orang_tua_wali',
        'no_hp',
        'no_hp_orang_tua_wali',
        'kelas',
        'jurusan',
        'nama_madrasah',
        'alamat',
        'password',
        'is_active',
        'email_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }

    public function sppBills()
    {
        return $this->hasMany(SppSiswaBill::class);
    }

    public function sppTransactions()
    {
        return $this->hasMany(SppSiswaTransaction::class);
    }

    public function setNamaLengkapAttribute($value): void
    {
        $this->attributes['nama_lengkap'] = Str::upper(trim((string) $value));
    }

    public function setNamaOrangTuaWaliAttribute($value): void
    {
        $this->attributes['nama_orang_tua_wali'] = trim((string) $value);
    }

    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = Str::lower(trim((string) $value));
    }

    public function setEmailOrangTuaWaliAttribute($value): void
    {
        $this->attributes['email_orang_tua_wali'] = Str::lower(trim((string) $value));
    }

    public function setNoHpAttribute($value): void
    {
        $this->attributes['no_hp'] = trim((string) $value);
    }

    public function setNoHpOrangTuaWaliAttribute($value): void
    {
        $this->attributes['no_hp_orang_tua_wali'] = trim((string) $value);
    }

    public function setKelasAttribute($value): void
    {
        $this->attributes['kelas'] = Str::upper(trim((string) $value));
    }

    public function setJurusanAttribute($value): void
    {
        $this->attributes['jurusan'] = Str::upper(trim((string) $value));
    }

    public function setNamaMadrasahAttribute($value): void
    {
        $this->attributes['nama_madrasah'] = trim((string) $value);
    }

    public function setAlamatAttribute($value): void
    {
        $this->attributes['alamat'] = trim((string) $value);
    }
}
