<?php

namespace App\Exports\Instumen;

use App\Models\TalentaPenilaianFasilitator;
use App\Models\TalentaFasilitator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FasilitatorAllExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'Kode Fasilitator',
            'Fasilitator',
            'Evaluator',
            'Fasilitasi',
            'Pendampingan',
            'Respons',
            'Koordinasi',
            'Monitoring',
            'Waktu',
            'Entri Count',
        ];
    }

    public function collection(): Collection
    {
        $fasilitators = TalentaFasilitator::orderBy('nama')->get();
        $rows = collect();

        foreach ($fasilitators as $fasilitator) {
            $entries = TalentaPenilaianFasilitator::with('user')
                ->where('talenta_fasilitator_id', $fasilitator->id)
                ->get()
                ->groupBy('user_id');

            if ($entries->isEmpty()) {
                $rows->push([
                    $fasilitator->kode_fasilitator ?? '-',
                    $fasilitator->nama ?? 'Fasilitator ID:' . $fasilitator->id,
                    '-',
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    0,
                ]);

                continue;
            }

            foreach ($entries as $userId => $group) {
                $user = $group->first()->user;
                $rows->push([
                    $fasilitator->kode_fasilitator ?? '-',
                    $fasilitator->nama ?? 'Fasilitator ID:' . $fasilitator->id,
                    $user ? $user->name : 'User ID:' . $userId,
                    round($group->avg('fasilitasi'), 2),
                    round($group->avg('pendampingan'), 2),
                    round($group->avg('respons'), 2),
                    round($group->avg('koordinasi'), 2),
                    round($group->avg('monitoring'), 2),
                    round($group->avg('waktu'), 2),
                    $group->count(),
                ]);
            }
        }

        return $rows;
    }
}
