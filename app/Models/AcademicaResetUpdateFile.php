<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicaResetUpdateFile extends Model
{
    use HasFactory;

    protected $table = 'academica_reset_update_files';

    protected $fillable = [
        'academica_reset_update_id',
        'original_name',
        'path',
        'mime',
    ];

    public function resetUpdate()
    {
        return $this->belongsTo(AcademicaResetUpdate::class, 'academica_reset_update_id');
    }
}
