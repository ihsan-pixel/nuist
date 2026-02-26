<?php

namespace App\Exports\Instumen;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\TalentaPenilaianTeknis;
use App\Models\TalentaLayananTeknis;

class TeknisSheetExport implements FromCollection, WithHeadings
{
    protected $layananId;

    public function __construct($layananId)
    {
        $this->layananId = $layananId;
    }

    public function headings(): array
    {
        return [
            'Evaluator',
            'Kehadiran',
            'Partisipasi',
            'Disiplin',
            'Kualitas Tugas',
            'Pemahaman Materi',
            'Implementasi Praktik',
            'Entri Count'
        ];
    }

    public function collection()
    {
        $entries = TalentaPenilaianTeknis::with('user')
            ->where('talenta_layanan_teknis_id', $this->layananId)
            ->get()
            ->groupBy('user_id');

        $rows = collect();
        foreach ($entries as $userId => $group) {
            $user = $group->first()->user;
            $rows->push([
                $user ? $user->name : 'User ID:'.$userId,
                round($group->avg('kehadiran'),2),
                round($group->avg('partisipasi'),2),
                round($group->avg('disiplin'),2),
                round($group->avg('kualitas_tugas'),2),
                round($group->avg('pemahaman_materi'),2),
                round($group->avg('implementasi_praktik'),2),
                $group->count(),
            ]);
        }

        return $rows;
    }
}
