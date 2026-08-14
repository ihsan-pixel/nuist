<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('email_nuist', 255)->nullable()->unique()->after('email');
        });

        // Do not alter siswa.email: this stores the personal email supplied
        // by the school. The new field is a separate system identity.
        DB::table('siswa')
            ->whereNotNull('nisn')
            ->where('nisn', '!=', '')
            ->orderBy('id')
            ->eachById(function (object $siswa) {
                DB::table('siswa')
                    ->where('id', $siswa->id)
                    ->update(['email_nuist' => strtolower(trim($siswa->nisn)) . '@nuist.id']);
            });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropUnique(['email_nuist']);
            $table->dropColumn('email_nuist');
        });
    }
};
