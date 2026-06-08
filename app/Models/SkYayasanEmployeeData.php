<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkYayasanEmployeeData extends Model
{
    use HasFactory;

    protected $table = 'sk_yayasan_employee_data';

    protected $fillable = [
        'user_id',
        'penilaian_kinerja',
        'keterangan',
    ];

    protected $casts = [
        'penilaian_kinerja' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
