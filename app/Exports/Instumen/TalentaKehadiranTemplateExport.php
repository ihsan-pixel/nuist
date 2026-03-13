<?php

namespace App\Exports\Instumen;

use App\Models\TalentaMateri;
use App\Models\TalentaPeserta;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class TalentaKehadiranTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $pesertas = TalentaPeserta::with('user')->orderBy('kode_peserta')->get();
        $materis = TalentaMateri::orderBy('tanggal_materi')->orderBy('judul_materi')->get();

        return [
            new class implements FromArray, WithTitle, ShouldAutoSize {
                public function array(): array
                {
                    return [
                        ['tanggal', 'kode_peserta', 'materi_id', 'status_kehadiran', 'sesi', 'catatan'],
                        [now()->format('Y-m-d'), 'T-01.001', '1', 'hadir', '1,2,3,4', 'Hadir penuh'],
                        [now()->format('Y-m-d'), 'T-01.002', '1', 'telat', '1', 'Datang pukul 08.30'],
                    ];
                }

                public function title(): string
                {
                    return 'template_import';
                }
            },
            new class($pesertas) implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize {
                public function __construct(private $pesertas)
                {
                }

                public function collection()
                {
                    return $this->pesertas->map(function ($peserta) {
                        return [
                            'kode_peserta' => $peserta->kode_peserta,
                            'nama_peserta' => $peserta->user->name ?? '-',
                            'asal_sekolah' => $peserta->asal_sekolah,
                        ];
                    });
                }

                public function headings(): array
                {
                    return ['kode_peserta', 'nama_peserta', 'asal_sekolah'];
                }

                public function title(): string
                {
                    return 'referensi_peserta';
                }
            },
            new class($materis) implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize {
                public function __construct(private $materis)
                {
                }

                public function collection()
                {
                    return $this->materis->map(function ($materi) {
                        return [
                            'materi_id' => $materi->id,
                            'kode_materi' => $materi->kode_materi,
                            'judul_materi' => $materi->judul_materi,
                            'tanggal_materi' => optional($materi->tanggal_materi)->format('Y-m-d'),
                        ];
                    });
                }

                public function headings(): array
                {
                    return ['materi_id', 'kode_materi', 'judul_materi', 'tanggal_materi'];
                }

                public function title(): string
                {
                    return 'referensi_materi';
                }
            },
            new class implements FromArray, WithTitle, ShouldAutoSize {
                public function array(): array
                {
                    return [
                        ['panduan'],
                        ['1. Isi sheet template_import saja saat akan upload.'],
                        ['2. Kolom wajib: tanggal, kode_peserta, materi_id, status_kehadiran.'],
                        ['3. Kolom sesi diisi kombinasi sesi, contoh: 1,2 atau 1,2,3,4.'],
                        ['4. Status yang diperbolehkan: hadir, telat, izin, sakit, tidak_hadir, lainnya.'],
                        ['5. Format tanggal disarankan YYYY-MM-DD.'],
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
