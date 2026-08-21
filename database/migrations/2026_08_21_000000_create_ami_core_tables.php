<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('ami_periods')) Schema::create('ami_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->year('year');
            $table->date('opens_at')->nullable();
            $table->date('closes_at')->nullable();
            $table->date('desk_review_at')->nullable();
            $table->date('visitasi_at')->nullable();
            $table->date('followup_deadline_at')->nullable();
            $table->string('status')->default('draft')->index();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        if (!Schema::hasTable('ami_instruments')) Schema::create('ami_instruments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_period_id')->nullable()->constrained('ami_periods')->nullOnDelete();
            $table->string('name');
            $table->string('code')->nullable()->index();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        if (!Schema::hasTable('ami_components')) Schema::create('ami_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_instrument_id')->constrained('ami_instruments')->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['ami_instrument_id', 'sort_order']);
        });

        if (!Schema::hasTable('ami_items')) Schema::create('ami_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_component_id')->constrained('ami_components')->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        if (!Schema::hasTable('ami_indicators')) Schema::create('ami_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_item_id')->constrained('ami_items')->cascadeOnDelete();
            $table->string('code')->index();
            $table->string('name');
            $table->longText('operational_definition')->nullable();
            $table->longText('fulfillment_criteria')->nullable();
            $table->longText('rubric')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('requires_evidence')->default(true)->index();
            $table->unsignedInteger('minimum_evidence_count')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        if (!Schema::hasTable('ami_indicator_criteria')) Schema::create('ami_indicator_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_indicator_id')->constrained('ami_indicators')->cascadeOnDelete();
            $table->string('label');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('score_value');
            $table->timestamps();
        });

        if (!Schema::hasTable('ami_rubrics')) Schema::create('ami_rubrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_indicator_id')->constrained('ami_indicators')->cascadeOnDelete();
            $table->string('label');
            $table->text('description');
            $table->unsignedTinyInteger('score_value');
            $table->timestamps();
        });

        if (!Schema::hasTable('ami_recommended_evidences')) Schema::create('ami_recommended_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_indicator_id')->constrained('ami_indicators')->cascadeOnDelete();
            $table->string('evidence_type');
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        if (!Schema::hasTable('ami_period_schools')) Schema::create('ami_period_schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_period_id')->constrained('ami_periods')->cascadeOnDelete();
            $table->foreignId('madrasah_id')->constrained('madrasahs')->cascadeOnDelete();
            $table->string('status')->default('draft')->index();
            $table->decimal('internal_index', 5, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['ami_period_id', 'madrasah_id'], 'ami_ps_unique');
        });

        if (!Schema::hasTable('ami_school_responses')) Schema::create('ami_school_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_period_school_id')->constrained('ami_period_schools')->cascadeOnDelete();
            $table->foreignId('ami_indicator_id')->constrained('ami_indicators')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('self_assessment_score')->nullable();
            $table->longText('school_performance_description')->nullable();
            $table->longText('internal_notes')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['ami_period_school_id', 'ami_indicator_id'], 'ami_sr_unique');
        });

        if (!Schema::hasTable('ami_evidences')) Schema::create('ami_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_school_response_id')->constrained('ami_school_responses')->cascadeOnDelete();
            $table->string('title');
            $table->string('evidence_type');
            $table->text('google_drive_url');
            $table->text('description')->nullable();
            $table->string('document_year')->nullable();
            $table->string('responsible_name')->nullable();
            $table->string('verification_status')->default('belum_diverifikasi')->index();
            $table->longText('auditor_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        if (!Schema::hasTable('ami_assignments')) Schema::create('ami_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_period_id')->constrained('ami_periods')->cascadeOnDelete();
            $table->foreignId('madrasah_id')->constrained('madrasahs')->cascadeOnDelete();
            $table->foreignId('auditor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
            $table->string('role_in_team')->default('auditor');
            $table->boolean('is_lead')->default(false)->index();
            $table->date('desk_review_at')->nullable();
            $table->date('visitasi_at')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        if (!Schema::hasTable('ami_auditor_scores')) Schema::create('ami_auditor_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_assignment_id')->constrained('ami_assignments')->cascadeOnDelete();
            $table->foreignId('ami_indicator_id')->constrained('ami_indicators')->cascadeOnDelete();
            $table->foreignId('auditor_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('score_value')->nullable();
            $table->longText('auditor_notes')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['ami_assignment_id', 'ami_indicator_id', 'auditor_id'], 'ami_scores_unique');
        });

        if (!Schema::hasTable('ami_verifications')) Schema::create('ami_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_auditor_score_id')->constrained('ami_auditor_scores')->cascadeOnDelete();
            $table->string('type');
            $table->string('source_info')->nullable();
            $table->longText('finding')->nullable();
            $table->date('verified_at')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });

        if (!Schema::hasTable('ami_clarifications')) Schema::create('ami_clarifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_assignment_id')->constrained('ami_assignments')->cascadeOnDelete();
            $table->foreignId('ami_indicator_id')->constrained('ami_indicators')->cascadeOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('target_school_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('question');
            $table->date('deadline_at')->nullable();
            $table->string('status')->default('open')->index();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });

        if (!Schema::hasTable('ami_clarification_responses')) Schema::create('ami_clarification_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_clarification_id')->constrained('ami_clarifications')->cascadeOnDelete();
            $table->foreignId('responded_by')->constrained('users')->cascadeOnDelete();
            $table->longText('answer');
            $table->text('additional_evidence_url')->nullable();
            $table->string('status')->default('submitted')->index();
            $table->timestamps();
        });

        if (!Schema::hasTable('ami_findings')) Schema::create('ami_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_assignment_id')->constrained('ami_assignments')->cascadeOnDelete();
            $table->foreignId('ami_component_id')->nullable()->constrained('ami_components')->nullOnDelete();
            $table->foreignId('ami_item_id')->nullable()->constrained('ami_items')->nullOnDelete();
            $table->foreignId('ami_indicator_id')->nullable()->constrained('ami_indicators')->nullOnDelete();
            $table->string('category')->index();
            $table->longText('description');
            $table->longText('supporting_evidence')->nullable();
            $table->longText('recommendation')->nullable();
            $table->string('priority')->default('sedang')->index();
            $table->timestamps();
        });

        if (!Schema::hasTable('ami_followups')) Schema::create('ami_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_finding_id')->constrained('ami_findings')->cascadeOnDelete();
            $table->foreignId('school_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('action_plan')->nullable();
            $table->string('pic_name')->nullable();
            $table->date('target_finish_at')->nullable();
            $table->string('status')->default('belum_ditindaklanjuti')->index();
            $table->longText('auditor_verification_notes')->nullable();
            $table->timestamps();
        });

        if (!Schema::hasTable('ami_followup_evidences')) Schema::create('ami_followup_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_followup_id')->constrained('ami_followups')->cascadeOnDelete();
            $table->string('title');
            $table->text('google_drive_url');
            $table->timestamps();
        });

        if (!Schema::hasTable('ami_followup_reviews')) Schema::create('ami_followup_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_followup_id')->constrained('ami_followups')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('pending')->index();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });

        if (!Schema::hasTable('ami_activity_logs')) Schema::create('ami_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('role')->nullable()->index();
            $table->string('action')->index();
            $table->string('entity_type')->nullable()->index();
            $table->unsignedBigInteger('entity_id')->nullable()->index();
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ami_activity_logs');
        Schema::dropIfExists('ami_followup_reviews');
        Schema::dropIfExists('ami_followup_evidences');
        Schema::dropIfExists('ami_followups');
        Schema::dropIfExists('ami_findings');
        Schema::dropIfExists('ami_clarification_responses');
        Schema::dropIfExists('ami_clarifications');
        Schema::dropIfExists('ami_verifications');
        Schema::dropIfExists('ami_auditor_scores');
        Schema::dropIfExists('ami_assignments');
        Schema::dropIfExists('ami_evidences');
        Schema::dropIfExists('ami_school_responses');
        Schema::dropIfExists('ami_period_schools');
        Schema::dropIfExists('ami_recommended_evidences');
        Schema::dropIfExists('ami_rubrics');
        Schema::dropIfExists('ami_indicator_criteria');
        Schema::dropIfExists('ami_indicators');
        Schema::dropIfExists('ami_items');
        Schema::dropIfExists('ami_components');
        Schema::dropIfExists('ami_instruments');
        Schema::dropIfExists('ami_periods');
    }
};
