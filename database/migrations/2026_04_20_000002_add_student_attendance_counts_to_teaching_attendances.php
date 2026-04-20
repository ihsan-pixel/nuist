<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('teaching_class_student_counts')) {
            Schema::create('teaching_class_student_counts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('madrasahs')->cascadeOnDelete();
                $table->string('class_name');
                $table->unsignedInteger('total_students');
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->unique(['school_id', 'class_name'], 'teaching_class_students_unique');
            });
        }

        if (Schema::hasTable('teaching_attendances')) {
            $hasClassTotalStudents = Schema::hasColumn('teaching_attendances', 'class_total_students');
            $hasPresentStudents = Schema::hasColumn('teaching_attendances', 'present_students');
            $hasStudentAttendancePercentage = Schema::hasColumn('teaching_attendances', 'student_attendance_percentage');

            Schema::table('teaching_attendances', function (Blueprint $table) use (
                $hasClassTotalStudents,
                $hasPresentStudents,
                $hasStudentAttendancePercentage
            ) {
                if (!$hasClassTotalStudents) {
                    $table->unsignedInteger('class_total_students')->nullable()->after('materi');
                }

                if (!$hasPresentStudents) {
                    $table->unsignedInteger('present_students')->nullable()->after('class_total_students');
                }

                if (!$hasStudentAttendancePercentage) {
                    $table->decimal('student_attendance_percentage', 5, 2)->nullable()->after('present_students');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('teaching_attendances')) {
            $hasStudentAttendancePercentage = Schema::hasColumn('teaching_attendances', 'student_attendance_percentage');
            $hasPresentStudents = Schema::hasColumn('teaching_attendances', 'present_students');
            $hasClassTotalStudents = Schema::hasColumn('teaching_attendances', 'class_total_students');

            Schema::table('teaching_attendances', function (Blueprint $table) use (
                $hasStudentAttendancePercentage,
                $hasPresentStudents,
                $hasClassTotalStudents
            ) {
                if ($hasStudentAttendancePercentage) {
                    $table->dropColumn('student_attendance_percentage');
                }

                if ($hasPresentStudents) {
                    $table->dropColumn('present_students');
                }

                if ($hasClassTotalStudents) {
                    $table->dropColumn('class_total_students');
                }
            });
        }

        Schema::dropIfExists('teaching_class_student_counts');
    }
};
