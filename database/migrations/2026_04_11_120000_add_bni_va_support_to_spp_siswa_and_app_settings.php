<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->boolean('bni_va_enabled')->default(false)->after('midtrans_is_production');
            $table->boolean('bni_va_mock_mode')->default(true)->after('bni_va_enabled');
            $table->string('bni_va_api_url')->nullable()->after('bni_va_mock_mode');
            $table->string('bni_va_client_id')->nullable()->after('bni_va_api_url');
            $table->string('bni_va_client_secret')->nullable()->after('bni_va_client_id');
            $table->string('bni_va_merchant_id')->nullable()->after('bni_va_client_secret');
            $table->string('bni_va_prefix', 20)->nullable()->after('bni_va_merchant_id');
            $table->string('bni_va_callback_token')->nullable()->after('bni_va_prefix');
        });

        Schema::table('spp_siswa_settings', function (Blueprint $table) {
            $table->string('payment_provider', 30)->default('manual')->after('denda_harian');
            $table->unsignedInteger('va_expired_hours')->default(24)->after('payment_provider');
            $table->text('payment_notes')->nullable()->after('catatan');
        });

        Schema::table('spp_siswa_transactions', function (Blueprint $table) {
            $table->string('external_order_id')->nullable()->after('nomor_transaksi');
            $table->string('external_transaction_id')->nullable()->after('external_order_id');
            $table->string('payment_channel', 50)->nullable()->after('metode_pembayaran');
            $table->string('va_number', 50)->nullable()->after('payment_channel');
            $table->timestamp('va_expired_at')->nullable()->after('va_number');
            $table->json('payment_payload')->nullable()->after('keterangan');

            $table->index('external_order_id', 'spp_siswa_transactions_external_order_idx');
            $table->index('va_number', 'spp_siswa_transactions_va_number_idx');
        });
    }

    public function down(): void
    {
        Schema::table('spp_siswa_transactions', function (Blueprint $table) {
            $table->dropIndex('spp_siswa_transactions_external_order_idx');
            $table->dropIndex('spp_siswa_transactions_va_number_idx');
            $table->dropColumn([
                'external_order_id',
                'external_transaction_id',
                'payment_channel',
                'va_number',
                'va_expired_at',
                'payment_payload',
            ]);
        });

        Schema::table('spp_siswa_settings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_provider',
                'va_expired_hours',
                'payment_notes',
            ]);
        });

        Schema::table('app_settings', function (Blueprint $table) {
            $table->dropColumn([
                'bni_va_enabled',
                'bni_va_mock_mode',
                'bni_va_api_url',
                'bni_va_client_id',
                'bni_va_client_secret',
                'bni_va_merchant_id',
                'bni_va_prefix',
                'bni_va_callback_token',
            ]);
        });
    }
};
