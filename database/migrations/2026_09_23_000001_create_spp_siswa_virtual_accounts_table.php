<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spp_siswa_virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('madrasah_id')->index();
            // The legacy `siswa.id` column is not indexed consistently on existing
            // installations, so keep this indexed without a database-level FK.
            $table->unsignedBigInteger('siswa_id')->index();
            $table->unsignedBigInteger('setting_id')->nullable()->index();
            $table->string('tahun_ajaran', 20);
            $table->string('trx_id', 100)->unique();
            $table->string('virtual_account', 20)->unique();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone', 32)->nullable();
            $table->decimal('trx_amount', 15, 2)->default(0);
            $table->dateTime('expired_at');
            $table->string('description');
            $table->enum('status', ['draft', 'exported', 'active', 'expired', 'disabled'])->default('draft');
            $table->timestamp('exported_at')->nullable();
            $table->json('provider_payload')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();

            $table->unique(['siswa_id', 'tahun_ajaran'], 'spp_siswa_va_student_year_unique');
            $table->index(['madrasah_id', 'tahun_ajaran', 'status'], 'spp_siswa_va_scope_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spp_siswa_virtual_accounts');
    }
};
