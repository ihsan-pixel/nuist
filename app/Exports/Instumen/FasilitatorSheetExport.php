<?php

namespace App\Exports\Instumen;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use App\Models\TalentaPenilaianFasilitator;
use App\Models\TalentaFasilitator;

class FasilitatorSheetExport implements FromCollection, WithHeadings
{
    protected $fasilitatorId;

    public function __construct($fasilitatorId)
    {
        $this->fasilitatorId = $fasilitatorId;
    }

    public function headings(): array
    {
        return [
            'Evaluator',
            'Fasilitasi',
            'Pendampingan',
            'Respons',
            'Koordinasi',
            'Monitoring',
            'Waktu',
            'Entri Count'
        ];
    }

    public function collection()
    {
        $fasilitator = TalentaFasilitator::find($this->fasilitatorId);

        $entries = TalentaPenilaianFasilitator::with('user')
            ->where('talenta_fasilitator_id', $this->fasilitatorId)
            ->get()
            ->groupBy('user_id');

        $rows = collect();
        foreach ($entries as $userId => $group) {
            $user = $group->first()->user;
            $rows->push([
                $user ? $user->name : 'User ID:'.$userId,
                round($group->avg('fasilitasi'),2),
                round($group->avg('pendampingan'),2),
                round($group->avg('respons'),2),
                round($group->avg('koordinasi'),2),
                round($group->avg('monitoring'),2),
                round($group->avg('waktu'),2),
                $group->count(),
            ]);
        }

        return $rows;
    }
}
