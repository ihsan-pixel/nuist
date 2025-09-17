<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StatusKepegawaian;

class TenagaPendidik extends Model
{
    use HasFactory;

    protected $table = 'tenaga_pendidiks';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'tempat_lahir',
        'tanggal_lahir',
        'no_hp',
        'kartanu',
        'nip',
        'nuptk',
        'npk',
        'madrasah_id',
        'status_kepegawaian_id',
        'tmt',
        'ketugasan_id',
        'avatar',
        'alamat',
    ];

    protected $hidden = [
        'password',
    ];

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }

    public function statusKepegawaian()
    {
        return $this->belongsTo(StatusKepegawaian::class, 'status_kepegawaian_id');
    }
}
