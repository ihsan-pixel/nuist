<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DpsMember extends Model
{
    use HasFactory;

    public const DEFAULT_UNSUR_OPTIONS = [
        "Pengurus LP. Ma'arif NU PWNU DIY",
        'Akademisi',
        'Pendiri Sekolah',
        'Tokoh Masyarakat',
    ];

    protected $fillable = [
        'madrasah_id',
        'user_id',
        'nama',
        'unsur',
        'periode',
    ];

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
