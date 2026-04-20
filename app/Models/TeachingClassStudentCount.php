<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingClassStudentCount extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'class_name',
        'total_students',
        'created_by',
        'updated_by',
    ];

    public function school()
    {
        return $this->belongsTo(Madrasah::class, 'school_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
