<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('biometric_profiles')) {
            $userIds = DB::table('biometric_profiles')
                ->distinct()
                ->orderBy('user_id')
                ->pluck('user_id');

            foreach ($userIds as $userId) {
                $profiles = DB::table('biometric_profiles')
                    ->where('user_id', $userId)
                    ->orderByDesc('status')
                    ->orderByDesc('enrolled_at')
                    ->orderByDesc('id')
                    ->get();

                if ($profiles->count() <= 1) {
                    continue;
                }

                $keepId = $profiles->first()->id ?? null;
                if ($keepId === null) {
                    continue;
                }

                DB::table('biometric_profiles')
                    ->where('user_id', $userId)
                    ->where('id', '!=', $keepId)
                    ->delete();
            }
        }

        Schema::table('biometric_profiles', function (Blueprint $table) {
            $table->unique('user_id', 'biometric_profiles_user_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('biometric_profiles', function (Blueprint $table) {
            $table->dropUnique('biometric_profiles_user_id_unique');
        });
    }
};
