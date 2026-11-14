<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [];

        if (! Schema::hasColumn('users', 'nip')) {
            $columns['nip'] = fn (Blueprint $table) => $table->string('nip', 50)->nullable();
        }
        if (! Schema::hasColumn('users', 'no_hp')) {
            $columns['no_hp'] = fn (Blueprint $table) => $table->string('no_hp', 30)->nullable();
        }
        if (! Schema::hasColumn('users', 'jabatan')) {
            $columns['jabatan'] = fn (Blueprint $table) => $table->string('jabatan', 150)->nullable();
        }
        if (! Schema::hasColumn('users', 'instansi_asal')) {
            $columns['instansi_asal'] = fn (Blueprint $table) => $table->string('instansi_asal')->nullable();
        }
        if (! Schema::hasColumn('users', 'is_active')) {
            $columns['is_active'] = fn (Blueprint $table) => $table->boolean('is_active')->default(true);
        }

        foreach ($columns as $column) {
            Schema::table('users', $column);
        }
    }

    public function down(): void
    {
        // These columns can be shared by other user roles and are retained safely.
    }
};
