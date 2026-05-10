<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('izins') || !Schema::hasTable('presensis') || !Schema::hasTable('users')) {
            return;
        }

        DB::table('izins')
            ->join('users', 'users.id', '=', 'izins.user_id')
            ->where('izins.status', 'approved')
            ->where('izins.type', 'tugas_luar')
            ->select([
                'izins.id as id',
                'izins.user_id',
                'izins.tanggal',
                'izins.alasan',
                'izins.deskripsi_tugas',
                'izins.file_path',
                'izins.waktu_masuk',
                'izins.waktu_keluar',
                'izins.approved_by',
                'users.madrasah_id as user_madrasah_id',
                'users.status_kepegawaian_id as user_status_kepegawaian_id',
            ])
            ->orderBy('izins.id')
            ->chunkById(100, function ($izins): void {
                foreach ($izins as $izin) {
                    $tanggal = Carbon::parse($izin->tanggal)->toDateString();
                    $keterangan = trim((string) ($izin->alasan ?: $izin->deskripsi_tugas));
                    $existing = DB::table('presensis')
                        ->where('user_id', $izin->user_id)
                        ->whereDate('tanggal', $tanggal)
                        ->first();

                    $payload = [
                        'madrasah_id' => $izin->user_madrasah_id,
                        'waktu_masuk' => $izin->waktu_masuk,
                        'waktu_keluar' => $izin->waktu_keluar,
                        'status' => 'hadir',
                        'keterangan' => $keterangan,
                        'status_izin' => 'approved',
                        'approved_by' => $izin->approved_by,
                        'surat_izin_path' => $izin->file_path,
                        'status_kepegawaian_id' => $izin->user_status_kepegawaian_id,
                        'updated_at' => now(),
                    ];

                    if (!$existing) {
                        DB::table('presensis')->insert(array_merge($payload, [
                            'user_id' => $izin->user_id,
                            'tanggal' => $tanggal,
                            'created_at' => now(),
                        ]));

                        continue;
                    }

                    $hasNoAttendanceTime = empty($existing->waktu_masuk) && empty($existing->waktu_keluar);

                    if (
                        $existing->status === 'izin'
                        || ($existing->status === 'alpha' && $hasNoAttendanceTime)
                        || $hasNoAttendanceTime
                    ) {
                        DB::table('presensis')
                            ->where('id', $existing->id)
                            ->update($payload);

                        continue;
                    }

                    if ($existing->status === 'hadir') {
                        $updates = [
                            'status_izin' => 'approved',
                            'approved_by' => $izin->approved_by,
                            'surat_izin_path' => $izin->file_path,
                            'updated_at' => now(),
                        ];

                        if (empty($existing->waktu_masuk) && !empty($izin->waktu_masuk)) {
                            $updates['waktu_masuk'] = $izin->waktu_masuk;
                        }

                        if (empty($existing->waktu_keluar) && !empty($izin->waktu_keluar)) {
                            $updates['waktu_keluar'] = $izin->waktu_keluar;
                        }

                        if (empty($existing->keterangan) && $keterangan !== '') {
                            $updates['keterangan'] = $keterangan;
                        }

                        DB::table('presensis')
                            ->where('id', $existing->id)
                            ->update($updates);
                    }
                }
            }, 'izins.id', 'id');
    }

    public function down(): void
    {
        // Irreversible data correction.
    }
};
