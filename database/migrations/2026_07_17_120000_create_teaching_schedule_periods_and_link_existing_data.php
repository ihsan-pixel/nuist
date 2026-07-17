<?php

use Carbon\CarbonImmutable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const SCHEDULE_PERIOD_FK = 'teaching_schedules_period_fk';
    private const CLASS_COUNT_PERIOD_FK = 'teaching_class_count_period_fk';

    public function up(): void
    {
        if (!Schema::hasTable('teaching_schedule_periods')) {
            Schema::create('teaching_schedule_periods', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('madrasahs')->cascadeOnDelete();
                $table->string('title');
                $table->string('school_year', 20);
                $table->string('semester', 20)->nullable();
                $table->date('start_date');
                $table->date('end_date');
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['school_id', 'start_date', 'end_date'], 'teaching_schedule_periods_school_dates_idx');
            });
        }

        if (Schema::hasTable('teaching_schedules') && !Schema::hasColumn('teaching_schedules', 'teaching_schedule_period_id')) {
            Schema::table('teaching_schedules', function (Blueprint $table) {
                $table->foreignId('teaching_schedule_period_id')
                    ->nullable()
                    ->after('school_id');

                $table->foreign('teaching_schedule_period_id', self::SCHEDULE_PERIOD_FK)
                    ->references('id')
                    ->on('teaching_schedule_periods')
                    ->nullOnDelete();

                $table->index(
                    ['school_id', 'teaching_schedule_period_id', 'day'],
                    'teaching_schedules_school_period_day_idx'
                );
            });
        }

        if (Schema::hasTable('teaching_class_student_counts')) {
            if (!Schema::hasColumn('teaching_class_student_counts', 'teaching_schedule_period_id')) {
                Schema::table('teaching_class_student_counts', function (Blueprint $table) {
                    $table->foreignId('teaching_schedule_period_id')
                        ->nullable()
                        ->after('school_id');

                    $table->foreign('teaching_schedule_period_id', self::CLASS_COUNT_PERIOD_FK)
                        ->references('id')
                        ->on('teaching_schedule_periods')
                        ->nullOnDelete();
                });
            }

            Schema::table('teaching_class_student_counts', function (Blueprint $table) {
                $table->dropUnique('teaching_class_students_unique');
            });

            Schema::table('teaching_class_student_counts', function (Blueprint $table) {
                $table->unique(
                    ['school_id', 'teaching_schedule_period_id', 'class_name'],
                    'teaching_class_students_period_unique'
                );
            });
        }

        $this->backfillExistingSchedulesAndClassCounts();
    }

    public function down(): void
    {
        if (Schema::hasTable('teaching_class_student_counts')) {
            Schema::table('teaching_class_student_counts', function (Blueprint $table) {
                $table->dropUnique('teaching_class_students_period_unique');
            });

            if (Schema::hasColumn('teaching_class_student_counts', 'teaching_schedule_period_id')) {
                Schema::table('teaching_class_student_counts', function (Blueprint $table) {
                    $table->dropForeign(self::CLASS_COUNT_PERIOD_FK);
                    $table->dropColumn('teaching_schedule_period_id');
                });
            }

            Schema::table('teaching_class_student_counts', function (Blueprint $table) {
                $table->unique(['school_id', 'class_name'], 'teaching_class_students_unique');
            });
        }

        if (Schema::hasTable('teaching_schedules') && Schema::hasColumn('teaching_schedules', 'teaching_schedule_period_id')) {
            Schema::table('teaching_schedules', function (Blueprint $table) {
                $table->dropIndex('teaching_schedules_school_period_day_idx');
                $table->dropForeign(self::SCHEDULE_PERIOD_FK);
                $table->dropColumn('teaching_schedule_period_id');
            });
        }

        Schema::dropIfExists('teaching_schedule_periods');
    }

    private function backfillExistingSchedulesAndClassCounts(): void
    {
        if (!Schema::hasTable('teaching_schedules') || !Schema::hasTable('teaching_class_student_counts')) {
            return;
        }

        $today = CarbonImmutable::now('Asia/Jakarta');
        $currentYear = (int) $today->format('Y');
        $currentMonth = (int) $today->format('n');
        $isGanjil = $currentMonth >= 7;

        $schoolYear = $isGanjil
            ? sprintf('%d/%d', $currentYear, $currentYear + 1)
            : sprintf('%d/%d', $currentYear - 1, $currentYear);

        $semester = $isGanjil ? 'ganjil' : 'genap';
        $semesterLabel = $isGanjil ? 'Semester Ganjil' : 'Semester Genap';
        $startDate = $isGanjil
            ? CarbonImmutable::create($currentYear, 7, 1, 0, 0, 0, 'Asia/Jakarta')
            : CarbonImmutable::create($currentYear, 1, 1, 0, 0, 0, 'Asia/Jakarta');
        $endDate = $isGanjil
            ? CarbonImmutable::create($currentYear, 12, 31, 0, 0, 0, 'Asia/Jakarta')
            : CarbonImmutable::create($currentYear, 6, 30, 0, 0, 0, 'Asia/Jakarta');

        $schoolIds = collect(DB::table('teaching_schedules')->select('school_id')->pluck('school_id'))
            ->merge(DB::table('teaching_class_student_counts')->select('school_id')->pluck('school_id'))
            ->filter()
            ->unique()
            ->values();

        foreach ($schoolIds as $schoolId) {
            $periodId = DB::table('teaching_schedule_periods')
                ->where('school_id', $schoolId)
                ->where('school_year', $schoolYear)
                ->where('semester', $semester)
                ->whereDate('start_date', $startDate->toDateString())
                ->whereDate('end_date', $endDate->toDateString())
                ->value('id');

            if (!$periodId) {
                $creatorId = DB::table('teaching_schedules')
                    ->where('school_id', $schoolId)
                    ->whereNotNull('created_by')
                    ->value('created_by')
                    ?: DB::table('teaching_class_student_counts')
                        ->where('school_id', $schoolId)
                        ->whereNotNull('created_by')
                        ->value('created_by');

                $periodId = DB::table('teaching_schedule_periods')->insertGetId([
                    'school_id' => $schoolId,
                    'title' => 'Jadwal Mengajar ' . $semesterLabel,
                    'school_year' => $schoolYear,
                    'semester' => $semester,
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'created_by' => $creatorId,
                    'updated_by' => $creatorId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('teaching_schedules')
                ->where('school_id', $schoolId)
                ->whereNull('teaching_schedule_period_id')
                ->update(['teaching_schedule_period_id' => $periodId]);

            DB::table('teaching_class_student_counts')
                ->where('school_id', $schoolId)
                ->whereNull('teaching_schedule_period_id')
                ->update(['teaching_schedule_period_id' => $periodId]);
        }
    }
};
