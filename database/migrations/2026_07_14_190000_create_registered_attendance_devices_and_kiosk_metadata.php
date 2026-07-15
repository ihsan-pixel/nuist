<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registered_attendance_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('madrasah_id')->constrained('madrasahs')->cascadeOnDelete();
            $table->string('name');
            $table->string('device_type', 50)->default('school_kiosk');
            $table->string('device_token_hash', 191)->unique();
            $table->string('browser_fingerprint_hash', 191)->nullable()->index();
            $table->json('allowed_ip_addresses')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_seen_at')->nullable();
            $table->string('last_ip_address', 64)->nullable();
            $table->text('last_user_agent')->nullable();
            $table->foreignId('registered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['madrasah_id', 'is_active']);
        });

        Schema::create('attendance_kiosk_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registered_device_id')->nullable()->constrained('registered_attendance_devices')->nullOnDelete();
            $table->foreignId('madrasah_id')->nullable()->constrained('madrasahs')->nullOnDelete();
            $table->foreignId('operator_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('target_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 80);
            $table->string('status', 40)->default('success');
            $table->string('ip_address', 64)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('payload_snapshot')->nullable();
            $table->timestamps();

            $table->index(['madrasah_id', 'action']);
            $table->index(['registered_device_id', 'created_at']);
        });

        Schema::table('presensis', function (Blueprint $table) {
            $table->string('attendance_channel', 50)->nullable()->after('status_kepegawaian_id');
            $table->foreignId('registered_device_id')->nullable()->after('attendance_channel')->constrained('registered_attendance_devices')->nullOnDelete();
            $table->foreignId('recorded_by_user_id')->nullable()->after('registered_device_id')->constrained('users')->nullOnDelete();
            $table->string('source_ip_address', 64)->nullable()->after('recorded_by_user_id');

            $table->index('attendance_channel');
        });
    }

    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropForeign(['registered_device_id']);
            $table->dropForeign(['recorded_by_user_id']);
            $table->dropIndex(['attendance_channel']);
            $table->dropColumn([
                'attendance_channel',
                'registered_device_id',
                'recorded_by_user_id',
                'source_ip_address',
            ]);
        });

        Schema::dropIfExists('attendance_kiosk_logs');
        Schema::dropIfExists('registered_attendance_devices');
    }
};
