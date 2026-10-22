<?php

namespace App\Console\Commands;

use App\Models\GtkPendataan;
use App\Services\GtkMgmpSyncService;
use Illuminate\Console\Command;

class SyncGtkMgmp extends Command
{
    protected $signature = 'mgmp:sync-gtk
        {--dry-run : Periksa pemetaan tanpa mengubah anggota}
        {--create-missing-groups : Buat grup mata pelajaran yang dikenali tanpa pengelola}';

    protected $description = 'Sesuaikan anggota MGMP dengan nama MGMP pada pendataan GTK';

    public function handle(GtkMgmpSyncService $sync): int
    {
        if ($this->option('create-missing-groups')) {
            $created = $sync->createMissingGroups((bool) $this->option('dry-run'));
            foreach ($created as $name) {
                $this->info(($this->option('dry-run') ? 'Rencana grup baru: ' : 'Grup baru: ').$name);
            }
            if ($this->option('dry-run') && $created) {
                $this->comment('Pemetaan di bawah memakai grup yang sudah tersimpan; rencana grup baru belum diterapkan.');
            }
        }
        $processed = 0;
        $unmatched = 0;
        $missingNames = [];
        GtkPendataan::whereNotNull('nama_mgmp')->orderBy('id')->chunkById(200, function ($rows) use ($sync, &$processed, &$unmatched, &$missingNames) {
            foreach ($rows as $row) {
                // An empty legacy entry does not establish a replacement membership.
                if (trim((string) $row->nama_mgmp) === '') {
                    continue;
                }
                $missing = $sync->sync($row, (bool) $this->option('dry-run'));
                if ($missing) {
                    $unmatched++;
                    foreach (array_unique($missing) as $name) {
                        $missingNames[$name] = ($missingNames[$name] ?? 0) + 1;
                    }
                    $this->warn('User '.$row->user_id.': nama MGMP tidak cocok/ambigu: '.implode(', ', $missing));
                } else {
                    $processed++;
                }
            }
        });
        $this->info(($this->option('dry-run') ? 'Pemeriksaan' : 'Sinkronisasi').": {$processed} GTK cocok; {$unmatched} GTK perlu perbaikan nama.");
        if ($missingNames) {
            arsort($missingNames);
            $this->warn('Nama berikut belum mempunyai tujuan unik. Periksa grup yang belum tersedia, variasi nama, atau organisasi di luar MGMP. Keanggotaan GTK terkait tetap dipertahankan.');
            $this->table(['Nama belum cocok', 'Jumlah GTK'], collect($missingNames)->map(fn ($count, $name) => [$name, $count])->values()->all());
        }

        return $unmatched ? self::FAILURE : self::SUCCESS;
    }
}
