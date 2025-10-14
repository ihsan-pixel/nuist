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
        if (empty($row['school_id']) || empty($row['teacher_id']) || empty($row['day'])) {
            return null;
        }

        try {
            // Validate required fields
            $requiredFields = [
                'school_id', 'teacher_id', 'day', 'subject', 'class_name', 'start_time', 'end_time'
            ];

            foreach ($requiredFields as $field) {
                if (empty($row[$field])) {
                    throw new \Exception("Field {$field} is required but empty");
                }
            }

            // Validate day enum values
            $allowedDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            if (!in_array($row['day'], $allowedDays)) {
                throw new \Exception("Invalid day value: " . $row['day'] . ". Allowed values: " . implode(', ', $allowedDays));
            }

            // Validate school_id exists
            if (!Madrasah::find($row['school_id'])) {
                throw new \Exception("School ID {$row['school_id']} not found");
            }

            // Validate teacher_id exists and is tenaga_pendidik
            $teacher = User::where('id', $row['teacher_id'])->where('role', 'tenaga_pendidik')->first();
            if (!$teacher) {
                throw new \Exception("Teacher ID {$row['teacher_id']} not found or not a tenaga_pendidik");
            }

            // Validate time format
            if (!preg_match('/^\d{2}:\d{2}$/', $row['start_time']) || !preg_match('/^\d{2}:\d{2}$/', $row['end_time'])) {
                throw new \Exception("Invalid time format. Use HH:MM format");
            }

            if ($row['start_time'] >= $row['end_time']) {
                throw new \Exception("End time must be after start time");
            }

            // Check teacher schedule overlap
            $teacherOverlap = TeachingSchedule::where('teacher_id', $row['teacher_id'])
                ->where('day', $row['day'])
                ->where(function ($query) use ($row) {
                    $query->where('start_time', '<', $row['end_time'])
                          ->where('end_time', '>', $row['start_time']);
                })
                ->exists();

            if ($teacherOverlap) {
                throw new \Exception("Schedule overlap for teacher {$teacher->name} on {$row['day']}");
            }

            // Check class schedule overlap
            $classOverlap = TeachingSchedule::where('school_id', $row['school_id'])
                ->where('class_name', $row['class_name'])
                ->where('day', $row['day'])
                ->where(function ($query) use ($row) {
                    $query->where('start_time', '<', $row['end_time'])
                          ->where('end_time', '>', $row['start_time']);
                })
                ->exists();

            if ($classOverlap) {
                throw new \Exception("Schedule overlap for class {$row['class_name']} on {$row['day']}");
            }

            Log::info('Successfully importing teaching schedule for teacher: ' . $teacher->name . ' - ' . $row['subject']);

            return new TeachingSchedule([
                'school_id' => $row['school_id'],
                'teacher_id' => $row['teacher_id'],
                'day' => $row['day'],
                'subject' => $row['subject'],
                'class_name' => $row['class_name'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
                'created_by' => $this->createdBy,
            ]);

        } catch (\Exception $e) {
            $this->errors[] = 'Row error: ' . $e->getMessage();
            Log::error('Failed to import teaching schedule: ' . $e->getMessage());
            return null;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
