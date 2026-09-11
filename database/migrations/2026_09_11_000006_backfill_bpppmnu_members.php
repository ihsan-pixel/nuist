<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void {
        DB::table('users')->where('is_bpppmnu_member', true)->orderBy('id')->eachById(function ($user) {
            DB::table('bpppmnu_members')->updateOrInsert(
                ['user_id' => $user->id],
                ['jabatan' => $user->bpppmnu_jabatan ?: $user->jabatan, 'instansi_asal' => $user->bpppmnu_instansi_asal ?: $user->instansi_asal, 'is_active' => (bool) $user->is_active, 'created_at' => now(), 'updated_at' => now()]
            );
        });
    }
    public function down(): void {}
};
