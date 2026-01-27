<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing tagihans where status is 'lunas' and jenis_pembayaran is null
        // Assume they are cash payments since we can't determine online from past data
        DB::table('tagihans')
            ->where('status', 'lunas')
            ->whereNull('jenis_pembayaran')
            ->update([
                'jenis_pembayaran' => 'cash',
                'metode_pembayaran' => null,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the update by setting jenis_pembayaran and metode_pembayaran back to null for lunas records
        DB::table('tagihans')
            ->where('status', 'lunas')
            ->where('jenis_pembayaran', 'cash')
            ->whereNull('metode_pembayaran')
            ->update([
                'jenis_pembayaran' => null,
                'metode_pembayaran' => null,
            ]);
    }
};
