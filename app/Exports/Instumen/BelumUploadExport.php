<?php

namespace App\Exports\Instumen;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use App\Models\TugasTalentaLevel1;
use App\Models\TalentaPeserta;
use App\Models\TalentaKelompok;

class BelumUploadExport implements FromCollection, WithHeadings
{
    protected $jenis;
    protected $area;

    public function __construct($jenis, $area = null)
    {
        $this->jenis = $jenis;
        $this->area = $area;
    }

    public function headings(): array
    {
        return [
            'Kode Peserta',
            'Nama Peserta',
            'Email',
            'Kelompok',
            'Asal Sekolah / Madrasah',
            'Area (filter)',
            'Keterangan'
        ];
    }

    public function collection()
    {
        $rows = collect();

        if ($this->jenis === 'kelompok') {
            // For kelompok tasks, find kelompok that have NOT uploaded
            $submittedKelompokIds = TugasTalentaLevel1::where('jenis_tugas', 'kelompok')
                ->when($this->area, fn($q) => $q->where('area', $this->area))
                ->whereNotNull('kelompok_id')
                ->pluck('kelompok_id')
                ->unique()
                ->filter();

            $kelompoks = TalentaKelompok::with('users')
                ->when($submittedKelompokIds->isNotEmpty(), fn($q) => $q->whereNotIn('id', $submittedKelompokIds))
                ->get();

            foreach ($kelompoks as $kelompok) {
                foreach ($kelompok->users as $user) {
                    $peserta = TalentaPeserta::where('user_id', $user->id)->first();
                    $rows->push([
                        $peserta->kode_peserta ?? '',
                        $user->name ?? '',
                        $user->email ?? '',
                        $kelompok->nama_kelompok ?? '',
                        $peserta ? ($peserta->nama_madrasah ?? $peserta->asal_sekolah) : '',
                        $this->area ?? '',
                        'Kelompok belum mengunggah tugas'
                    ]);
                }
            }
        } else {
            // Individual tasks: find users who haven't uploaded a tugas for the given jenis and area
            $submittedUserIds = TugasTalentaLevel1::where('jenis_tugas', $this->jenis)
                ->when($this->area, fn($q) => $q->where('area', $this->area))
                ->whereNotNull('user_id')
                ->pluck('user_id')
                ->unique()
                ->filter();

            $pesertas = TalentaPeserta::with('user','madrasah')
                ->when($submittedUserIds->isNotEmpty(), fn($q) => $q->whereNotIn('user_id', $submittedUserIds))
                ->get();

            foreach ($pesertas as $peserta) {
                $rows->push([
                    $peserta->kode_peserta ?? '',
                    $peserta->user->name ?? '',
                    $peserta->user->email ?? '',
                    '', // kelompok
                    $peserta->nama_madrasah ?? $peserta->asal_sekolah ?? '',
                    $this->area ?? '',
                    'Belum mengunggah tugas'
                ]);
            }
        }

        return $rows;
    }
}
