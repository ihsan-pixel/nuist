<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PresensiSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = \App\Models\StatusKepegawaian::all();

        foreach ($statuses as $status) {
            \App\Models\PresensiSettings::updateOrCreate(
                ['status_kepegawaian_id' => $status->id],
                [
                    'waktu_mulai_presensi_masuk' => '06:30:00',
                    'waktu_akhir_presensi_masuk' => '07:00:00',
                    'waktu_mulai_presensi_pulang' => '13:00:00',
                    'waktu_akhir_presensi_pulang' => '15:00:00',
                ]
            );
        }
    }
}
