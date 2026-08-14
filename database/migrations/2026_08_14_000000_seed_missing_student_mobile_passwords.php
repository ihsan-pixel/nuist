<?php

use App\Models\Siswa;
use App\Services\StudentDefaultPasswordService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Provision credentials only for active students that can actually use
     * NISN login. Passwords already chosen by a student are preserved.
     */
    public function up(): void
    {
        $passwords = app(StudentDefaultPasswordService::class);

        Siswa::query()
            ->where('is_active', true)
            ->whereNotNull('nisn')
            ->where('nisn', '!=', '')
            ->where(function ($query) {
                $query->whereNull('password')->orWhere('password', '');
            })
            ->orderBy('id')
            ->eachById(fn (Siswa $siswa) => $passwords->ensurePassword($siswa));
    }

    public function down(): void
    {
        // Password hashes are intentionally not removed on rollback.
    }
};
