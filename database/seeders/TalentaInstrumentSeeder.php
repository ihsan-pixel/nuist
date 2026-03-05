<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Question;

class TalentaInstrumentSeeder extends Seeder
{
    public function run()
    {
        // Assumption: scoring scale (best to worst) is:
        // C => 5, B => 4, A => 3, D => 2, E => 1
        // This was inferred from the instrument wording where C represents the strongest
        // statement (reflects values/identity) and E is the absence of the feature.
        $defaultMap = ['A' => 3, 'B' => 4, 'C' => 5, 'D' => 2, 'E' => 1];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('answers')->truncate();
        DB::table('school_scores')->truncate();
        DB::table('questions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $instrument = [
            'Struktur' => [
                'Apakah sekolah memiliki sistem layanan tertulis yang benar-benar dijalankan?',
                'Apakah pusat layanan mudah diakses dan tidak membingungkan pengguna?',
                'Apakah alur pelayanan meminimalkan birokrasi berlapis?',
                'Apakah terdapat sistem monitoring mutu layanan?',
                'Apakah regulasi dan panduan layanan dipahami oleh seluruh warga sekolah?',
            ],
            'Kompetensi' => [
                'Apakah guru-karyawan memahami standar layanan sekolah?',
                'Apakah terdapat pelatihan pelayanan secara berkala?',
                'Apakah personel mampu menangani keluhan tanpa konflik?',
                'Apakah staf mampu menjelaskan kebijakan secara jelas kepada pengguna?',
                'Apakah kompetensi pelayanan menjadi bagian dari evaluasi kinerja?',
            ],
            'Perilaku' => [
                'Apakah pelayanan diberikan cepat dan ramah secara konsisten?',
                'Apakah staf menerima kritik tanpa defensif?',
                'Apakah siswa dan orang tua merasa dihargai dalam layanan?',
                'Apakah pelayanan tetap baik dalam situasi tekanan atau krisis?',
                'Apakah perilaku melayani menjadi budaya sekolah?',
            ],
            'Keterpaduan' => [
                'Apakah unit-unit sekolah bekerja tanpa saling lempar tanggung jawab?',
                'Apakah layanan akademik dan nonakademik saling mendukung?',
                'Apakah penyelesaian masalah dilakukan sampai tuntas?',
                'Apakah komunikasi antar bagian berjalan lancar?',
                'Apakah layanan menunjukkan arah dan kesinambungan jangka panjang?',
            ],
        ];

        // Option texts per question are taken from the instrument. For simplicity each
        // question uses the same qualitative labels mapping (A..E) but textual
        // descriptions differ per-dimension/question as provided below.
        foreach ($instrument as $kategori => $questions) {
            foreach ($questions as $index => $qtext) {
                // Build choice_texts based on the instrument structure. These are
                // short descriptions for A..E per-question. The wording below is
                // the concise phrase seen in the provided instrument for each
                // response option for that question. Adjust as needed later.
                $choice_texts = [];

                // Provide mapping for each question by dimension and index
                switch ($kategori) {
                    case 'Struktur':
                        // For Struktur dimension the answers describe degree of systemic maturity
                        $choice_texts = [
                            'A' => 'Sistem tersedia sebagai dokumen administratif dan berfungsi untuk kepatuhan formal.',
                            'B' => 'Sistem dijalankan lintas unit dan menjadi acuan operasional bersama.',
                            'C' => 'Sistem mencerminkan nilai, visi, dan filosofi pelayanan sekolah.',
                            'D' => 'Sistem direvisi setelah terjadi krisis, teguran, atau masalah besar.',
                            'E' => 'Belum ada sistem.',
                        ];
                        break;
                    case 'Kompetensi':
                        $choice_texts = [
                            'A' => 'Pemahaman terbatas pada prosedur administratif formal.',
                            'B' => 'Memahami peran dan tanggung jawab lintas fungsi dalam pelayanan.',
                            'C' => 'Memahami filosofi melayani dan nilai yang mendasari layanan.',
                            'D' => 'Pemahaman diperkuat setelah terjadi kesalahan atau evaluasi khusus.',
                            'E' => 'Belum memahami standar layanan secara jelas.',
                        ];
                        break;
                    case 'Perilaku':
                        $choice_texts = [
                            'A' => 'Cepat dan ramah dalam batas kepatuhan prosedural.',
                            'B' => 'Konsisten cepat dan ramah lintas unit dan situasi.',
                            'C' => 'Cepat dan ramah sebagai kesadaran kolektif dan identitas sekolah.',
                            'D' => 'Cepat dan ramah terutama saat diawasi atau setelah evaluasi.',
                            'E' => 'Belum menunjukkan konsistensi dalam kecepatan dan keramahan.',
                        ];
                        break;
                    case 'Keterpaduan':
                        $choice_texts = [
                            'A' => 'Tugas dibagi secara administratif sesuai SOP formal.',
                            'B' => 'Koordinasi aktif dilakukan antar unit dalam menyelesaikan layanan.',
                            'C' => 'Solidaritas dan tanggung jawab kolektif menjadi budaya kerja.',
                            'D' => 'Koordinasi diperkuat setelah terjadi konflik atau kesalahan layanan.',
                            'E' => 'Sering terjadi saling lempar tanggung jawab.',
                        ];
                        break;
                }

                Question::create([
                    'kategori' => $kategori,
                    'pertanyaan' => $qtext,
                    'skor_ya' => 1,
                    'skor_tidak' => 0,
                    'choice_scores' => $defaultMap,
                    'choice_texts' => $choice_texts,
                ]);
            }
        }
    }
}
