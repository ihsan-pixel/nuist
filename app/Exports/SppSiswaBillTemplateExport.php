<?php

namespace App\Exports;

use App\Models\Madrasah;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class SppSiswaBillTemplateExport implements WithMultipleSheets
{
    public function __construct(private readonly ?Madrasah $madrasah = null)
    {
    }

    public function sheets(): array
    {
        $students = $this->madrasah
            ? Siswa::query()
                ->where('madrasah_id', $this->madrasah->id)
                ->orderBy('nama_lengkap')
                ->get(['nis', 'nama_lengkap', 'kelas', 'jurusan'])
            : collect();

        return [
            new class($students) implements FromArray, WithTitle, ShouldAutoSize {
                public function __construct(private readonly Collection $students)
                {
                }

                public function array(): array
                {
                    $sampleNis = $this->students->first()?->nis ?? '20250001';

                    return [
                        ['nis', 'jenis_tagihan', 'periode', 'jatuh_tempo', 'nominal', 'status', 'catatan'],
                        [$sampleNis, 'SPP', now()->format('Y-m'), now()->startOfMonth()->addDays(9)->format('Y-m-d'), 250000, 'belum_lunas', 'Tagihan SPP bulan ini'],
                        [$sampleNis, 'UANG GEDUNG', now()->format('Y-m'), now()->startOfMonth()->addDays(14)->format('Y-m-d'), 1000000, 'belum_lunas', 'Contoh tagihan selain SPP'],
                    ];
                }

                public function title(): string
                {
                    return 'template_import';
                }
            },
            new class($students) implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize {
                public function __construct(private readonly Collection $students)
                {
                }

                public function collection(): Collection
                {
                    return $this->students->map(fn (Siswa $siswa) => [
                        'nis' => $siswa->nis,
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'kelas' => $siswa->kelas,
                        'jurusan' => $siswa->jurusan,
                    ]);
                }

                public function headings(): array
                {
                    return ['nis', 'nama_lengkap', 'kelas', 'jurusan'];
                }

                public function title(): string
                {
                    return 'referensi_siswa';
                }
            },
            new class implements FromArray, WithTitle, ShouldAutoSize {
                public function array(): array
                {
                    return [
                        ['panduan'],
                        ['1. Isi sheet template_import saat akan upload.'],
                        ['2. Kolom wajib: nis, jenis_tagihan, periode, jatuh_tempo, nominal.'],
                        ['3. jenis_tagihan bebas diisi operator sekolah, contoh: SPP, UANG GEDUNG, SERAGAM, KEGIATAN.'],
                        ['4. periode menggunakan format YYYY-MM, contoh: 2026-04.'],
                        ['5. jatuh_tempo menggunakan format YYYY-MM-DD, contoh: 2026-04-10.'],
                        ['6. status boleh kosong atau salah satu: belum_lunas, sebagian, lunas.'],
                        ['7. Baris dengan siswa, periode, dan jenis_tagihan yang sudah ada akan dilewati.'],
                    ];
                }

                public function title(): string
                {
                    return 'panduan';
                }
            },
        ];
    }
}
