<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('academic_calendar_events')) {
            return;
        }

        $hasApprovalStatus = Schema::hasColumn('academic_calendar_events', 'approval_status');
        $hasApprovedBy = Schema::hasColumn('academic_calendar_events', 'approved_by');
        $hasApprovedAt = Schema::hasColumn('academic_calendar_events', 'approved_at');
        $hasApprovalNotes = Schema::hasColumn('academic_calendar_events', 'approval_notes');

        Schema::table('academic_calendar_events', function (Blueprint $table) use (
            $hasApprovalStatus,
            $hasApprovedBy,
            $hasApprovedAt,
            $hasApprovalNotes
        ) {
            if (!$hasApprovalStatus) {
                $table->string('approval_status', 20)->default('pending')->after('is_active');
            }

            if (!$hasApprovedBy) {
                $table->foreignId('approved_by')->nullable()->after('approval_status')->constrained('users')->nullOnDelete();
            }

            if (!$hasApprovedAt) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }

            if (!$hasApprovalNotes) {
                $table->text('approval_notes')->nullable()->after('approved_at');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('academic_calendar_events')) {
            return;
        }

        $hasApprovalNotes = Schema::hasColumn('academic_calendar_events', 'approval_notes');
        $hasApprovedAt = Schema::hasColumn('academic_calendar_events', 'approved_at');
        $hasApprovedBy = Schema::hasColumn('academic_calendar_events', 'approved_by');
        $hasApprovalStatus = Schema::hasColumn('academic_calendar_events', 'approval_status');

        Schema::table('academic_calendar_events', function (Blueprint $table) use (
            $hasApprovalNotes,
            $hasApprovedAt,
            $hasApprovedBy,
            $hasApprovalStatus
        ) {
            if ($hasApprovalNotes) {
                $table->dropColumn('approval_notes');
            }

            if ($hasApprovedAt) {
                $table->dropColumn('approved_at');
            }

            if ($hasApprovedBy) {
                $table->dropConstrainedForeignId('approved_by');
            }

            if ($hasApprovalStatus) {
                $table->dropColumn('approval_status');
            }
        });
    }
};
