<?php

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Siswa::query()
            ->where(function ($query) {
                $query->whereNull('email')->orWhere('email', '');
            })
            ->whereNotNull('nisn')
            ->where('nisn', '!=', '')
            ->orderBy('id')
            ->eachById(function (Siswa $siswa) {
                $linkKey = 'S' . str_pad(strtoupper(base_convert((string) $siswa->id, 10, 36)), 5, '0', STR_PAD_LEFT);
                $user = User::query()->where('nuist_id', $linkKey)->where('role', 'siswa')->first();
                $email = strtolower(trim($siswa->nisn)) . '@nuist.id';

                if (!$user || $user->email === $email) {
                    return;
                }

                $conflict = User::query()
                    ->where('email', $email)
                    ->where('id', '!=', $user->id)
                    ->exists();

                if (!$conflict) {
                    $user->forceFill(['email' => $email])->save();
                }
            });
    }

    public function down(): void
    {
        // The previous generated address is not restored.
    }
};
