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
        // Add face data fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->json('face_data')->nullable()->after('avatar')->comment('Face recognition data (face descriptors)');
            $table->string('face_id')->nullable()->after('face_data')->comment('Unique face identifier');
            $table->timestamp('face_registered_at')->nullable()->after('face_id')->comment('When face was first registered');
            $table->boolean('face_verification_required')->default(true)->after('face_registered_at')->comment('Whether face verification is required for this user');
        });

        // Add face validation fields to presensis table
        Schema::table('presensis', function (Blueprint $table) {
            $table->string('face_id_used')->nullable()->after('device_info')->comment('Face ID used for this presensi');
            $table->decimal('face_similarity_score', 3, 2)->nullable()->after('face_id_used')->comment('Face similarity score (0.00-1.00)');
            $table->decimal('liveness_score', 3, 2)->nullable()->after('face_similarity_score')->comment('Liveness detection score (0.00-1.00)');
            $table->json('liveness_challenges')->nullable()->after('liveness_score')->comment('Liveness challenges performed');
            $table->boolean('face_verified')->default(false)->after('liveness_challenges')->comment('Whether face was successfully verified');
            $table->text('face_verification_notes')->nullable()->after('face_verified')->comment('Notes about face verification process');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'face_data',
                'face_id',
                'face_registered_at',
                'face_verification_required'
            ]);
        });

        Schema::table('presensis', function (Blueprint $table) {
            $table->dropColumn([
                'face_id_used',
                'face_similarity_score',
                'liveness_score',
                'liveness_challenges',
                'face_verified',
                'face_verification_notes'
            ]);
        });
    }
};
