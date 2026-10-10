<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bpppmnu_event_guest_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('bpppmnu_events')->restrictOnDelete();
            $table->string('name', 255);
            $table->string('organization', 255)->nullable();
            $table->text('phone')->nullable();
            $table->timestamps();
            $table->index(['event_id', 'name']);
        });

        Schema::table('bpppmnu_event_guest_attendances', function (Blueprint $table) {
            $table->foreignId('guest_invitation_id')->nullable()->after('event_id')
                ->constrained('bpppmnu_event_guest_invitations')->restrictOnDelete();
            $table->unique(['event_id', 'guest_invitation_id'], 'bpppmnu_guest_invitation_attendance_unique');
        });
    }

    public function down(): void
    {
        Schema::table('bpppmnu_event_guest_attendances', function (Blueprint $table) {
            $table->dropUnique('bpppmnu_guest_invitation_attendance_unique');
            $table->dropConstrainedForeignId('guest_invitation_id');
        });
        Schema::dropIfExists('bpppmnu_event_guest_invitations');
    }
};
