<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'nip')) {
                $table->string('nip', 50)->nullable();
            }
            if (! Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp', 30)->nullable();
            }
            if (! Schema::hasColumn('users', 'jabatan')) {
                $table->string('jabatan', 150)->nullable();
            }
            if (! Schema::hasColumn('users', 'instansi_asal')) {
                $table->string('instansi_asal')->nullable();
            }
            if (! Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    public function down(): void
    {
        // Existing deployments may already use these columns for other roles,
        // so they are intentionally retained during rollback.
    }
};
