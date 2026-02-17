<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DetectTalentaPenilaianConstraints extends Command
{
    protected $signature = 'talenta:detect-penilaian-constraints';
    protected $description = 'Detect foreign keys and indexes that block changing unique index on talenta_penilaian_peserta';

    public function handle()
    {
        $this->info("Scanning database for references to `talenta_penilaian_peserta`...");

        $rows = DB::select(<<<'SQL'
            SELECT TABLE_NAME, CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
              AND REFERENCED_TABLE_NAME = 'talenta_penilaian_peserta'
            ORDER BY TABLE_NAME;
        SQL
        );

        if (empty($rows)) {
            $this->info('No foreign keys referencing talenta_penilaian_peserta found.');
            $this->info("Suggested ALTER statements:\n");
            $this->line("ALTER TABLE talenta_penilaian_peserta DROP INDEX talenta_penilaian_peserta_unique;");
            $this->line("ALTER TABLE talenta_penilaian_peserta ADD UNIQUE KEY talenta_penilaian_peserta_unique_v2 (talenta_peserta_id, user_id, materi_id);");
            return 0;
        }

        $this->info('Found the following referencing keys:');
        foreach ($rows as $r) {
            $this->line(" - Table: {$r->TABLE_NAME}, Constraint: {$r->CONSTRAINT_NAME}, Column: {$r->COLUMN_NAME} -> References {$r->REFERENCED_TABLE_NAME}({$r->REFERENCED_COLUMN_NAME})");
        }

        $this->info("\nTo allow changing the unique index you will need to drop or adjust these foreign keys first. Suggested SQL (review before running):\n");
        foreach ($rows as $r) {
            $this->line("-- On table {$r->TABLE_NAME}");
            $this->line("ALTER TABLE `{$r->TABLE_NAME}` DROP FOREIGN KEY `{$r->CONSTRAINT_NAME}`;");
        }

        $this->line("
-- Then alter the talenta_penilaian_peserta table (run after dropping foreign keys):");
        $this->line("ALTER TABLE talenta_penilaian_peserta DROP INDEX talenta_penilaian_peserta_unique;");
        $this->line("ALTER TABLE talenta_penilaian_peserta ADD UNIQUE KEY talenta_penilaian_peserta_unique_v2 (talenta_peserta_id, user_id, materi_id);");

        $this->info("\nAfter altering, re-create any foreign keys you dropped (reverse of above).");

        return 0;
    }
}
