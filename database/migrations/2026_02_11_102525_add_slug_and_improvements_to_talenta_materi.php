<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('talenta_materi', function (Blueprint $table) {
            // Add slug column only if it doesn't exist
            if (!Schema::hasColumn('talenta_materi', 'slug')) {
                $table->string('slug')->nullable()->after('judul_materi');
            }

            // Change level_materi to integer
            $table->integer('level_materi')->change();

            // Add status enum
            $table->enum('status', ['draft', 'published', 'archived'])->default('published')->after('tanggal_materi');

            // Add indexes for performance
            $table->index('level_materi');
            $table->index('tanggal_materi');
            $table->index('status');
        });

        // Populate slugs for existing records with anti-duplicate logic
        $materis = DB::table('talenta_materi')->whereNull('slug')->orWhere('slug', '')->get();
        foreach ($materis as $materi) {
            $baseSlug = Str::slug($materi->judul_materi);
            $slug = $baseSlug;
            $count = 1;
            while (DB::table('talenta_materi')->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }
            DB::table('talenta_materi')->where('id', $materi->id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talenta_materi', function (Blueprint $table) {
            $table->dropIndex(['level_materi']);
            $table->dropIndex(['slug']);
            $table->dropIndex(['tanggal_materi']);
            $table->dropIndex(['status']);

            $table->dropColumn(['slug', 'status']);

            // Revert level_materi back to string (assuming it was string before)
            $table->string('level_materi')->change();
        });
    }
};
