<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataTenagaPendidik extends Model
{
    use HasFactory;

    protected $table = 'data_tenaga_pendidik';

    protected $fillable = [
        'madrasah_id',
        'tahun',
        'status_kepegawaian_id',
        'jumlah',
    ];

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }

    public function statusKepegawaian()
    {
        return $this->belongsTo(StatusKepegawaian::class);
    }
}
