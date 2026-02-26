<?php

namespace App\Exports\Instumen;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\TalentaPenilaianTrainer;
use App\Models\TalentaPemateri;

class PemateriSheetExport implements FromCollection, WithHeadings
{
    protected $pemateriId;

    public function __construct($pemateriId)
    {
        $this->pemateriId = $pemateriId;
    }

    public function headings(): array
    {
        return [
            'Evaluator',
            'Kualitas Materi',
            'Penyampaian',
            'Integrasi Kasus',
            'Penjelasan',
            'Umpan Balik',
            'Waktu',
            'Entri Count'
        ];
    }

    public function collection()
    {
        $entries = TalentaPenilaianTrainer::with('user')
            ->where('talenta_pemateri_id', $this->pemateriId)
            ->get()
            ->groupBy('user_id');

        $rows = collect();
        foreach ($entries as $userId => $group) {
            $user = $group->first()->user;
            $rows->push([
                $user ? $user->name : 'User ID:'.$userId,
                round($group->avg('kualitas_materi'),2),
                round($group->avg('penyampaian'),2),
                round($group->avg('integrasi_kasus'),2),
                round($group->avg('penjelasan'),2),
                round($group->avg('umpan_balik'),2),
                round($group->avg('waktu'),2),
                $group->count(),
            ]);
        }

        return $rows;
    }
}
