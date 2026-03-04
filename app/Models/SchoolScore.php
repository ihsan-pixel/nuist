<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolScore extends Model
{
    use HasFactory;

    protected $table = 'school_scores';

    protected $fillable = [
        'school_id','submitted_by','layanan','tata_kelola','jumlah_siswa','jumlah_penghasilan','jumlah_prestasi','jumlah_talenta','total_skor','level_sekolah'
    ];

    public function school()
    {
        return $this->belongsTo(\App\Models\Madrasah::class, 'school_id');
    }

    public function submittedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'submitted_by');
    }
}
