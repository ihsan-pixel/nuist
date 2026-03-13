<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('talenta_kehadiran_peserta')) {
            return;
        }

        if (! Schema::hasColumn('talenta_kehadiran_peserta', 'materi_id')) {
            Schema::table('talenta_kehadiran_peserta', function (Blueprint $table) {
                $table->unsignedBigInteger('materi_id')->nullable()->after('talenta_peserta_id');
            });
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("
                ALTER TABLE talenta_kehadiran_peserta
                MODIFY status_kehadiran ENUM('hadir', 'telat', 'izin', 'sakit', 'tidak_hadir', 'lainnya')
                NOT NULL
            ");
        }

        $foreignKeys = collect(DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'talenta_kehadiran_peserta'
              AND COLUMN_NAME = 'materi_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        "));

        if ($foreignKeys->isEmpty()) {
            Schema::table('talenta_kehadiran_peserta', function (Blueprint $table) {
                $table->foreign('materi_id', 'talenta_kehadiran_peserta_materi_id_foreign')
                    ->references('id')
                    ->on('talenta_materi')
                    ->nullOnDelete();
            });
        }

        $indexes = collect(DB::select("SHOW INDEX FROM talenta_kehadiran_peserta WHERE Key_name = 'talenta_kehadiran_peserta_unique'"));
        if ($indexes->isNotEmpty()) {
            Schema::table('talenta_kehadiran_peserta', function (Blueprint $table) {
                $table->dropUnique('talenta_kehadiran_peserta_unique');
            });
        }

        Schema::table('talenta_kehadiran_peserta', function (Blueprint $table) {
            $table->unique(['tanggal', 'talenta_peserta_id', 'materi_id'], 'talenta_kehadiran_peserta_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('talenta_kehadiran_peserta')) {
            return;
        }

        $indexes = collect(DB::select("SHOW INDEX FROM talenta_kehadiran_peserta WHERE Key_name = 'talenta_kehadiran_peserta_unique'"));
        if ($indexes->isNotEmpty()) {
            Schema::table('talenta_kehadiran_peserta', function (Blueprint $table) {
                $table->dropUnique('talenta_kehadiran_peserta_unique');
            });
        }

        if (Schema::hasColumn('talenta_kehadiran_peserta', 'materi_id')) {
            $foreignKeys = collect(DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'talenta_kehadiran_peserta'
                  AND COLUMN_NAME = 'materi_id'
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            "));

            if ($foreignKeys->isNotEmpty()) {
                Schema::table('talenta_kehadiran_peserta', function (Blueprint $table) {
                    $table->dropForeign('talenta_kehadiran_peserta_materi_id_foreign');
                });
            }

            Schema::table('talenta_kehadiran_peserta', function (Blueprint $table) {
                $table->dropColumn('materi_id');
            });
        }

        Schema::table('talenta_kehadiran_peserta', function (Blueprint $table) {
            $table->unique(['tanggal', 'talenta_peserta_id'], 'talenta_kehadiran_peserta_unique');
        });
    }
};
