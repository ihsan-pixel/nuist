<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentaKelompok extends Model
{
    use HasFactory;

    protected $table = 'talenta_kelompoks';

    protected $fillable = [
        'nama_kelompok',
    ];

    /**
     * Get the users for the kelompok.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'talenta_kelompok_peserta', 'talenta_kelompok_id', 'user_id');
    }
}
