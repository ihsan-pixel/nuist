<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'face_data')) {
                $table->longText('face_data')->nullable()->after('avatar');
            }

            if (!Schema::hasColumn('users', 'face_id')) {
                $table->string('face_id')->nullable()->after('face_data');
            }

            if (!Schema::hasColumn('users', 'face_registered_at')) {
                $table->timestamp('face_registered_at')->nullable()->after('face_id');
            }

            if (!Schema::hasColumn('users', 'face_verification_required')) {
                $table->boolean('face_verification_required')->default(false)->after('face_registered_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];

            foreach (['face_verification_required', 'face_registered_at', 'face_id', 'face_data'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $columnsToDrop[] = $column;
                }
            }

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
