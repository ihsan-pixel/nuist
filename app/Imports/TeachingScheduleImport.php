<?php

namespace App\Imports;

use App\Models\TeachingSchedule;
use App\Models\User;
use App\Models\Madrasah;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeachingScheduleImport implements ToModel, WithHeadingRow
{
    protected $createdBy;
    protected $errors = [];

    public function __construct($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['school_scod']) || empty($row['teacher_nuist_id']) || empty($row['day'])) {
            return null;
        }

        try {
            // Validate required fields
            $requiredFields = [
                'school_scod', 'teacher_nuist_id', 'day', 'subject', 'class_name', 'start_time', 'end_time'
            ];

            foreach ($requiredFields as $field) {
                if (empty($row[$field])) {
                    throw new \Exception("Kolom {$field} wajib diisi");
                }
            }

            // Validate day enum values
            $allowedDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            if (!in_array($row['day'], $allowedDays)) {
                throw new \Exception("Hari '{$row['day']}' tidak valid. Hari yang diperbolehkan: " . implode(', ', $allowedDays));
            }

            // Validate school_scod exists
            $madrasah = Madrasah::where('scod', $row['school_scod'])->first();
            if (!$madrasah) {
                throw new \Exception("Kode SCOD Madrasah '{$row['school_scod']}' tidak ditemukan");
            }

            // Validate teacher_nuist_id exists and is tenaga_pendidik
            $teacher = User::where('nuist_id', $row['teacher_nuist_id'])->where('role', 'tenaga_pendidik')->first();
            if (!$teacher) {
                throw new \Exception("NUist ID Guru '{$row['teacher_nuist_id']}' tidak ditemukan atau bukan tenaga pendidik");
            }

            // Validate time format
            if (!preg_match('/^\d{2}:\d{2}$/', $row['start_time']) || !preg_match('/^\d{2}:\d{2}$/', $row['end_time'])) {
                throw new \Exception("Format waktu tidak valid. Gunakan format JJ:MM");
            }

            if ($row['start_time'] >= $row['end_time']) {
                throw new \Exception("Jam selesai harus lebih besar dari jam mulai");
            }

            // Check teacher schedule overlap
            $teacherOverlap = TeachingSchedule::where('teacher_id', $teacher->id)
                ->where('day', $row['day'])
                ->where(function ($query) use ($row) {
                    $query->where('start_time', '<', $row['end_time'])
                          ->where('end_time', '>', $row['start_time']);
                })
                ->exists();

            if ($teacherOverlap) {
                throw new \Exception("Jadwal guru {$teacher->name} bentrok pada hari {$row['day']}");
            }

            // Check class schedule overlap
            $classOverlap = TeachingSchedule::where('school_id', $madrasah->id)
                ->where('class_name', $row['class_name'])
                ->where('day', $row['day'])
                ->where(function ($query) use ($row) {
                    $query->where('start_time', '<', $row['end_time'])
                          ->where('end_time', '>', $row['start_time']);
                })
                ->exists();

            if ($classOverlap) {
                throw new \Exception("Jadwal kelas {$row['class_name']} bentrok pada hari {$row['day']}");
            }

            Log::info('Successfully importing teaching schedule for teacher NUist ID: ' . $teacher->nuist_id . ' (' . $teacher->name . ') - ' . $row['subject']);

            return new TeachingSchedule([
                'school_id' => $madrasah->id,
                'teacher_id' => $teacher->id,
                'day' => $row['day'],
                'subject' => $row['subject'],
                'class_name' => $row['class_name'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
                'created_by' => $this->createdBy,
            ]);

        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            Log::error('Failed to import teaching schedule: ' . $e->getMessage());
            return null;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
