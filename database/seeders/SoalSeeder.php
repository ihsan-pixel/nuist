<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Soal;
use App\Models\TalentaMateri;

class SoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $materis = TalentaMateri::take(3)->get();
        if ($materis->isEmpty()) {
            return;
        }

        foreach ($materis as $m) {
            Soal::create([
                'materi_slug' => $m->slug,
                'jenis' => 'on_site',
                'pertanyaan' => "Contoh soal untuk {$m->judul_materi}: Jelaskan poin utama materi ini dalam 3-5 kalimat.",
                'instruksi' => 'Tuliskan jawabannya pada berkas PDF/Word Anda lalu unggah di bagian On Site.',
                'urut' => 1,
            ]);

            Soal::create([
                'materi_slug' => $m->slug,
                'jenis' => 'terstruktur',
                'pertanyaan' => "Tugas terstruktur untuk {$m->judul_materi}: Buat ringkasan dan contoh penerapan.",
                'instruksi' => null,
                'urut' => 1,
            ]);
        }
    }
}
