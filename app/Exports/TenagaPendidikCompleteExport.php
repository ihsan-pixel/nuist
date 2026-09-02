<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TenagaPendidikCompleteExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        private readonly Collection $tenagaPendidiks
    ) {
    }

    public function headings(): array
    {
        return [
            'No',
            'SCOD',
            'NUist ID',
            'Nama dan Gelar',
            'Asal Sekolah',
            'Tempat, Tanggal Lahir',
            'NUPTK',
            'NIPM',
            'Kartanu',
            'TMT',
            'Pendidikan Terakhir',
            'Tahun Lulus',
            'Program Studi',
        ];
    }

    public function collection(): Collection
    {
        return $this->tenagaPendidiks->values()->map(function (User $user, int $index) {
            $namaDanGelar = trim(implode(' ', array_filter([
                $user->name,
                $user->gelar,
            ])));

            return [
                'no' => $index + 1,
                'scod' => $user->madrasah?->scod ?: '-',
                'nuist_id' => $user->nuist_id ?: '-',
                'nama_dan_gelar' => $namaDanGelar !== '' ? $namaDanGelar : '-',
                'asal_sekolah' => $user->madrasah?->name ?: '-',
                'tempat_tanggal_lahir' => $this->formatTempatTanggalLahir($user),
                'nuptk' => $user->nuptk ?: '-',
                'nipm' => $user->nip ?: '-',
                'kartanu' => $user->kartanu ?: '-',
                'tmt' => $this->formatDate($user->tmt),
                'pendidikan_terakhir' => $user->pendidikan_terakhir ?: '-',
                'tahun_lulus' => $user->tahun_lulus ?: '-',
                'program_studi' => $user->program_studi ?: '-',
            ];
        });
    }

    private function formatTempatTanggalLahir(User $user): string
    {
        $tempatLahir = trim((string) ($user->tempat_lahir ?: '-'));
        $tanggalLahir = $this->formatDate($user->tanggal_lahir);

        if ($tempatLahir === '-') {
            return $tanggalLahir === '-' ? '-' : $tanggalLahir;
        }

        return $tempatLahir . ', ' . $tanggalLahir;
    }

    private function formatDate(mixed $value): string
    {
        if (empty($value)) {
            return '-';
        }

        try {
            return Carbon::parse($value)->locale('id')->translatedFormat('j F Y');
        } catch (\Throwable) {
            return (string) $value;
        }
    }
}
