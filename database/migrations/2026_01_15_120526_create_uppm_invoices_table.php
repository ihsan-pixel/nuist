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
        Schema::create('uppm_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uppm_school_data_id')->constrained('uppm_school_data')->onDelete('cascade');
            $table->string('no_invoice');
            $table->date('tanggal_invoice');
            $table->decimal('total_tagihan', 15, 2);
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue'])->default('draft');
            $table->text('rincian')->nullable(); // JSON string
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uppm_invoices');
    }
};
