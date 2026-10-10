<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bpppmnu_events', function (Blueprint $table) {
            $table->string('attendance_access_mode', 20)->default('registered')->after('status');
            $table->string('public_name_verification', 20)->default('none')->after('attendance_access_mode');
            $table->boolean('guest_phone_required')->default(false)->after('public_name_verification');
            $table->boolean('guest_organization_required')->default(false)->after('guest_phone_required');
            $table->unsignedInteger('capacity')->nullable()->after('guest_organization_required');
        });

        Schema::create('bpppmnu_event_guest_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('bpppmnu_events')->restrictOnDelete();
            $table->string('guest_name', 255);
            $table->text('guest_phone')->nullable();
            $table->string('guest_organization', 255)->nullable();
            $table->dateTime('attended_at');
            $table->string('method', 30)->default('public_qr_guest');
            $table->string('confirmation_code', 32)->unique();
            $table->string('request_fingerprint', 64);
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('distance_meters')->nullable();
            $table->timestamps();
            $table->unique(['event_id', 'request_fingerprint'], 'bpppmnu_guest_request_unique');
            $table->index(['event_id', 'attended_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bpppmnu_event_guest_attendances');
        Schema::table('bpppmnu_events', function (Blueprint $table) {
            $table->dropColumn([
                'attendance_access_mode', 'public_name_verification', 'guest_phone_required',
                'guest_organization_required', 'capacity',
            ]);
        });
    }
};
