<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->boolean('is_fake_location')->default(false)->after('lokasi')->comment('Flag untuk mendeteksi presensi dengan fake location');
            $table->json('fake_location_analysis')->nullable()->after('is_fake_location')->comment('Detail analisis fake location');
            $table->decimal('accuracy', 5, 2)->nullable()->after('fake_location_analysis')->comment('Akurasi GPS dalam meter');
            $table->decimal('altitude', 8, 2)->nullable()->after('accuracy')->comment('Ketinggian lokasi dalam meter');
            $table->decimal('speed', 5, 2)->nullable()->after('altitude')->comment('Kecepatan pergerakan dalam m/s');
            $table->string('device_info')->nullable()->after('speed')->comment('Informasi perangkat yang digunakan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropColumn(['is_fake_location', 'fake_location_analysis', 'accuracy', 'altitude', 'speed', 'device_info']);
        });
    }
};
