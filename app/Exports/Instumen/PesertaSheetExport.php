<?php

namespace App\Exports\Instumen;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\TalentaPenilaianPeserta;

class PesertaSheetExport implements FromCollection, WithHeadings
{
    protected $evaluatorId;
    protected $materiId;

    public function __construct($evaluatorId, $materiId = null)
    {
        $this->evaluatorId = $evaluatorId;
        $this->materiId = $materiId;
    }

    public function headings(): array
    {
        return [
            'Nomor Peserta',
            'Peserta',
            'Asal Sekolah',
            'Kehadiran',
            'Partisipasi',
            'Disiplin',
            'Tugas',
            'Pemahaman',
            'Praktik',
            'Sikap',
            'Entri Count'
        ];
    }

    public function collection()
    {
        $query = TalentaPenilaianPeserta::with(['peserta.user.madrasah','user'])
            ->where('user_id', $this->evaluatorId);

        if ($this->materiId && $this->materiId !== 'all') {
            $query->where('materi_id', $this->materiId);
        }

        $entries = $query->get()->groupBy('talenta_peserta_id');

        $rows = collect();
        foreach ($entries as $pesertaId => $group) {
            $peserta = $group->first()->peserta;
            $rows->push([
                $peserta?->kode_peserta ?? '-',
                $peserta ? ($peserta->nama ?? ($peserta->user?->name ?? 'ID:'.$pesertaId)) : 'ID:'.$pesertaId,
                $peserta?->nama_madrasah ?? $peserta?->asal_sekolah ?? '-',
                round($group->avg('kehadiran'),2),
                round($group->avg('partisipasi'),2),
                round($group->avg('disiplin'),2),
                round($group->avg('tugas'),2),
                round($group->avg('pemahaman'),2),
                round($group->avg('praktik'),2),
                round($group->avg('sikap'),2),
                $group->count(),
            ]);
        }

        return $rows;
    }
}
