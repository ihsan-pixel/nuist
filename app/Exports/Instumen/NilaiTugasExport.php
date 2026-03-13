<?php

namespace App\Exports\Instumen;

use App\Models\TugasNilai;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NilaiTugasExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $area;

    public function __construct(?string $area = null)
    {
        $this->area = $area;
    }

    public function headings(): array
    {
        return [
            'No',
            'Peserta / User',
            'Madrasah',
            'Kelompok',
            'Jenis Tugas',
            'Area',
            'Penilai',
            'Nilai',
            'Waktu Input',
        ];
    }

    public function collection(): Collection
    {
        $query = TugasNilai::with(['tugas.user.madrasah', 'tugas.kelompok', 'penilai']);

        if (!empty($this->area)) {
            $query->whereHas('tugas', function ($q) {
                $q->where('area', $this->area);
            });
        }

        return $query->orderBy('id', 'desc')->get()->values()->map(function ($nilai, $index) {
            $jenis = $nilai->tugas->jenis_tugas ?? null;
            $jenisLabel = '-';

            if ($jenis === 'on_site') {
                $jenisLabel = 'Tugas Onsite';
            } elseif ($jenis === 'terstruktur') {
                $jenisLabel = 'Tugas Terstruktur';
            } elseif ($jenis === 'kelompok') {
                $jenisLabel = 'Tugas Kelompok';
            } elseif (!empty($jenis)) {
                $jenisLabel = ucfirst($jenis);
            }

            return [
                $index + 1,
                $nilai->tugas->user->name ?? '-',
                $nilai->tugas->user->madrasah->name ?? '-',
                $nilai->tugas->kelompok->nama_kelompok ?? '-',
                $jenisLabel,
                $nilai->tugas->area ?? '-',
                $nilai->penilai->name ?? '-',
                $nilai->nilai ?? '-',
                optional($nilai->created_at)->format('Y-m-d H:i') ?? '-',
            ];
        });
    }
}
