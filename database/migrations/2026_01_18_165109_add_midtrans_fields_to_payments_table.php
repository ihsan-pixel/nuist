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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('order_id')->nullable()->after('keterangan');
            $table->string('transaction_id')->nullable()->after('order_id');
            $table->string('payment_type')->nullable()->after('transaction_id');
            $table->json('response_midtrans')->nullable()->after('payment_type');
            $table->string('pdf_url')->nullable()->after('response_midtrans');
            $table->unsignedBigInteger('tagihan_id')->nullable()->after('pdf_url');
            $table->timestamp('paid_at')->nullable()->after('tagihan_id');

            $table->foreign('tagihan_id')->references('id')->on('tagihans')->onDelete('cascade');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['tagihan_id']);
            $table->dropIndex(['order_id']);
            $table->dropColumn(['order_id', 'transaction_id', 'payment_type', 'response_midtrans', 'pdf_url', 'tagihan_id', 'paid_at']);
        });
    }
};
