<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bpppmnu_events', function (Blueprint $table) {
            $table->id();
            foreach (['name', 'type', 'organizer', 'person_in_charge', 'location_name'] as $field) {
                $table->string($field, 255);
            }
            $table->text('description');
            foreach (['start_at', 'end_at', 'attendance_open_at', 'attendance_close_at'] as $field) {
                $table->dateTime($field);
            }
            foreach (['address', 'meeting_url', 'rundown', 'attachment'] as $field) {
                $table->text($field)->nullable();
            }
            $table->string('status', 20)->default('draft');
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->index(['status', 'start_at']);
        });
        Schema::create('bpppmnu_event_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('bpppmnu_events')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['event_id', 'user_id'], 'bpppmnu_invitation_unique');
        });
        Schema::create('bpppmnu_event_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('bpppmnu_events')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->dateTime('attended_at');
            $table->string('method', 20)->default('qr');
            $table->timestamps();
            $table->unique(['event_id', 'user_id'], 'bpppmnu_attendance_unique');
            $table->foreign(['event_id', 'user_id'], 'bpppmnu_attendance_invitation_fk')
                ->references(['event_id', 'user_id'])->on('bpppmnu_event_invitations')->restrictOnDelete();
        });
        Schema::create('bpppmnu_event_qr_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('bpppmnu_events')->restrictOnDelete();
            $table->string('token_hash', 64)->unique();
            $table->dateTime('expires_at');
            $table->dateTime('revoked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bpppmnu_event_qr_tokens');
        Schema::dropIfExists('bpppmnu_event_attendances');
        Schema::dropIfExists('bpppmnu_event_invitations');
        Schema::dropIfExists('bpppmnu_events');
    }
};
