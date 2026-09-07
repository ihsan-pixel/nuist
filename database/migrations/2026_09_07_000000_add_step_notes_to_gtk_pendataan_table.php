<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gtk_pendataan', function (Blueprint $table) {
            $table->text('catatan_step_1')->nullable();
            $table->text('catatan_step_2')->nullable();
            $table->text('catatan_step_3')->nullable();
            $table->text('catatan_step_4')->nullable();
            $table->text('catatan_step_5')->nullable();
            $table->text('keterangan_sk')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('gtk_pendataan', function (Blueprint $table) {
            $table->dropColumn([
                'catatan_step_1',
                'catatan_step_2',
                'catatan_step_3',
                'catatan_step_4',
                'catatan_step_5',
                'keterangan_sk',
            ]);
        });
    }
};
