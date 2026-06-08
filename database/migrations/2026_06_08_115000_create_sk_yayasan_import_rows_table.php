<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sk_yayasan_import_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('sk_yayasan_import_batches')->cascadeOnDelete();
            $table->unsignedInteger('row_number');
            $table->string('excel_no')->nullable();
            $table->string('source_nuist_id')->nullable();
            $table->string('source_nama')->nullable();
            $table->string('source_gelar')->nullable();
            $table->string('source_tempat_lahir')->nullable();
            $table->string('source_tanggal_lahir')->nullable();
            $table->string('source_nip_maarif')->nullable();
            $table->string('source_nuptk')->nullable();
            $table->string('source_nomor_kartanu')->nullable();
            $table->string('source_tmt_pertama')->nullable();
            $table->string('source_masa_kerja')->nullable();
            $table->string('source_pendidikan_terakhir')->nullable();
            $table->string('source_tahun_lulus')->nullable();
            $table->string('source_program_studi')->nullable();
            $table->string('source_mapel_tugas')->nullable();
            $table->string('source_penilaian_kinerja')->nullable();
            $table->text('source_keterangan')->nullable();
            $table->foreignId('matched_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('matched_name')->nullable();
            $table->boolean('is_valid')->default(false);
            $table->string('status_label')->nullable();
            $table->json('validation_errors')->nullable();
            $table->json('user_payload')->nullable();
            $table->json('sk_payload')->nullable();
            $table->timestamps();
        });

        if (Schema::hasTable('sk_yayasan_import_batches') && Schema::hasColumn('sk_yayasan_import_batches', 'payload_rows')) {
            DB::table('sk_yayasan_import_batches')
                ->select('id', 'payload_rows')
                ->whereNotNull('payload_rows')
                ->orderBy('id')
                ->chunkById(100, function ($batches) {
                    foreach ($batches as $batch) {
                        $rows = json_decode($batch->payload_rows, true);

                        if (!is_array($rows) || empty($rows)) {
                            continue;
                        }

                        $inserts = [];

                        foreach ($rows as $row) {
                            $sourceColumns = $row['source_columns'] ?? [];

                            $inserts[] = [
                                'batch_id' => $batch->id,
                                'row_number' => (int) ($row['row_number'] ?? 0),
                                'excel_no' => $sourceColumns['No'] ?? null,
                                'source_nuist_id' => $sourceColumns['NUIST ID'] ?? null,
                                'source_nama' => $sourceColumns['Nama'] ?? null,
                                'source_gelar' => $sourceColumns['Gelar'] ?? null,
                                'source_tempat_lahir' => $sourceColumns['Tempat Lahir'] ?? null,
                                'source_tanggal_lahir' => $sourceColumns['Tanggal Lahir'] ?? null,
                                'source_nip_maarif' => $sourceColumns["NIP Ma'arif"] ?? null,
                                'source_nuptk' => $sourceColumns['NUPTK'] ?? null,
                                'source_nomor_kartanu' => $sourceColumns['Nomor Kartanu'] ?? null,
                                'source_tmt_pertama' => $sourceColumns['TMT Pertama'] ?? null,
                                'source_masa_kerja' => $sourceColumns['Masa Kerja'] ?? null,
                                'source_pendidikan_terakhir' => $sourceColumns['Pendidikan Terakhir'] ?? null,
                                'source_tahun_lulus' => $sourceColumns['Tahun Lulus'] ?? null,
                                'source_program_studi' => $sourceColumns['Program Studi'] ?? null,
                                'source_mapel_tugas' => $sourceColumns['Mapel/Tugas yang Diampu'] ?? null,
                                'source_penilaian_kinerja' => $sourceColumns['Penilaian Kinerja'] ?? null,
                                'source_keterangan' => $sourceColumns['Keterangan'] ?? null,
                                'matched_user_id' => $row['user_id'] ?? null,
                                'matched_name' => $row['matched_name'] ?? null,
                                'is_valid' => (bool) ($row['is_valid'] ?? false),
                                'status_label' => $row['status_label'] ?? null,
                                'validation_errors' => json_encode($row['errors'] ?? []),
                                'user_payload' => json_encode($row['user_payload'] ?? []),
                                'sk_payload' => json_encode($row['sk_payload'] ?? []),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }

                        if (!empty($inserts)) {
                            DB::table('sk_yayasan_import_rows')->insert($inserts);
                        }
                    }
                });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sk_yayasan_import_rows');
    }
};
