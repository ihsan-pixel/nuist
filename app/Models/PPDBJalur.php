<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPDBJalur extends Model
{
    use HasFactory;

    protected $table = 'ppdb_jalurs';
    protected $fillable = [
        'ppdb_setting_id',
        'nama_jalur',
        'keterangan',
        'urutan',
    ];

    /**
     * Relasi ke PPDBSetting
     */
    public function ppdbSetting()
    {
        return $this->belongsTo(PPDBSetting::class, 'ppdb_setting_id');
    }

    /**
     * Relasi ke Pendaftar yang memilih jalur ini
     */
    public function pendaftars()
    {
        return $this->hasMany(PPDBPendaftar::class, 'ppdb_jalur_id');
    }

    /**
     * Hitung total pendaftar via jalur ini
     */
    public function totalPendaftar()
    {
        return $this->pendaftars()->count();
    }

    /**
     * Hitung total lulus via jalur ini
     */
    public function totalLulus()
    {
        return $this->pendaftars()->where('status', 'lulus')->count();
    }
}
