<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'teaching_schedule_id',
        'user_id',
        'tanggal',
        'waktu',
        'status',
        'latitude',
        'longitude',
        'lokasi',
        'materi',
        'class_total_students',
        'present_students',
        'student_attendance_percentage',
    ];

    public function teachingSchedule()
    {
        return $this->belongsTo(TeachingSchedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
