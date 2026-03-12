<?php

namespace App\Exports\Instumen;

use App\Models\TalentaPemateri;
use App\Models\TalentaPenilaianTrainer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PemateriAllExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'Kode Pemateri',
            'Pemateri',
            'Evaluator',
            'Kualitas Materi',
            'Penyampaian',
            'Integrasi Kasus',
            'Penjelasan',
            'Umpan Balik',
            'Waktu',
            'Entri Count',
        ];
    }

    public function collection(): Collection
    {
        $pemateris = TalentaPemateri::orderBy('nama')->get();
        $rows = collect();

        foreach ($pemateris as $pemateri) {
            $entries = TalentaPenilaianTrainer::with('user')
                ->where('talenta_pemateri_id', $pemateri->id)
                ->get()
                ->groupBy('user_id');

            if ($entries->isEmpty()) {
                $rows->push([
                    $pemateri->kode_pemateri ?? '-',
                    $pemateri->nama ?? 'Pemateri ID:' . $pemateri->id,
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
                    $pemateri->kode_pemateri ?? '-',
                    $pemateri->nama ?? 'Pemateri ID:' . $pemateri->id,
                    $user ? $user->name : 'User ID:' . $userId,
                    round($group->avg('kualitas_materi'), 2),
                    round($group->avg('penyampaian'), 2),
                    round($group->avg('integrasi_kasus'), 2),
                    round($group->avg('penjelasan'), 2),
                    round($group->avg('umpan_balik'), 2),
                    round($group->avg('waktu'), 2),
                    $group->count(),
                ]);
            }
        }

        return $rows;
    }
}
