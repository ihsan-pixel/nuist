<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teaching_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('teaching_schedules', 'class_names')) {
                $table->json('class_names')->nullable()->after('class_name');
            }
        });

        DB::table('teaching_schedules')
            ->whereNull('class_names')
            ->orderBy('id')
            ->chunkById(200, function ($schedules) {
                foreach ($schedules as $schedule) {
                    $className = trim((string) ($schedule->class_name ?? ''));
                    DB::table('teaching_schedules')
                        ->where('id', $schedule->id)
                        ->update([
                            'class_names' => $className !== '' ? json_encode([$className], JSON_UNESCAPED_UNICODE) : null,
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('teaching_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('teaching_schedules', 'class_names')) {
                $table->dropColumn('class_names');
            }
        });
    }
};
