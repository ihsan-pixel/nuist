<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sk_yayasan_employee_data')) {
            Schema::create('sk_yayasan_employee_data', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
                $table->decimal('penilaian_kinerja', 5, 2)->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasColumn('users', 'penilaian_kinerja') || Schema::hasColumn('users', 'sk_yayasan_keterangan')) {
            DB::table('users')
                ->select('id', 'penilaian_kinerja', 'sk_yayasan_keterangan')
                ->where(function ($query) {
                    $query->whereNotNull('penilaian_kinerja')
                        ->orWhereNotNull('sk_yayasan_keterangan');
                })
                ->orderBy('id')
                ->chunkById(200, function ($users) {
                    foreach ($users as $user) {
                        DB::table('sk_yayasan_employee_data')->updateOrInsert(
                            ['user_id' => $user->id],
                            [
                                'penilaian_kinerja' => $user->penilaian_kinerja,
                                'keterangan' => $user->sk_yayasan_keterangan,
                                'updated_at' => now(),
                                'created_at' => now(),
                            ]
                        );
                    }
                });
        }

        Schema::table('users', function (Blueprint $table) {
            $columns = [];

            foreach (['penilaian_kinerja', 'sk_yayasan_keterangan'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'penilaian_kinerja')) {
                $table->decimal('penilaian_kinerja', 5, 2)->nullable()->after('mengajar');
            }

            if (!Schema::hasColumn('users', 'sk_yayasan_keterangan')) {
                $table->text('sk_yayasan_keterangan')->nullable()->after('penilaian_kinerja');
            }
        });

        Schema::dropIfExists('sk_yayasan_employee_data');
    }
};
