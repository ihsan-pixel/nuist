<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('day_markers')) {
            return;
        }

        Schema::create('day_markers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('scope_key'); // global | school:{id} | class:{id}:{class_name_lower}
            $table->foreignId('madrasah_id')->nullable()->constrained('madrasahs')->nullOnDelete();
            $table->string('class_name')->nullable();
            $table->enum('marker', ['normal', 'libur', 'ujian', 'kegiatan_khusus'])->default('normal');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['scope_key', 'date'], 'day_markers_scope_date_unique');
            $table->index(['date', 'scope_key'], 'day_markers_date_scope_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('day_markers');
    }
};

