<?php

namespace App\Exports\Instumen;

use App\Models\TalentaPenilaianPeserta;
use App\Models\TalentaPeserta;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class PesertaAllExport implements WithMultipleSheets
{
    protected $materiId;

    public function __construct($materiId = null)
    {
        $this->materiId = $materiId;
    }

    public function sheets(): array
    {
        return [
            new PesertaRekapSheet($this->materiId),
            new PesertaDetailFasilitatorSheet($this->materiId),
        ];
    }
}

class PesertaRekapSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $materiId;

    public function __construct($materiId = null)
    {
        $this->materiId = $materiId;
    }

    public function headings(): array
    {
        return [
            'Peserta',
            'Asal Sekolah',
            'Kehadiran',
            'Partisipasi',
            'Disiplin',
            'Tugas',
            'Pemahaman',
            'Praktik',
            'Sikap',
            'Entri Count',
        ];
    }

    public function collection(): Collection
    {
        $fields = ['kehadiran', 'partisipasi', 'disiplin', 'tugas', 'pemahaman', 'praktik', 'sikap'];
        $pesertas = TalentaPeserta::with('user.madrasah')->orderBy('id')->get();

        return $pesertas->map(function ($peserta) use ($fields) {
            $query = TalentaPenilaianPeserta::where('talenta_peserta_id', $peserta->id);

            if ($this->materiId && $this->materiId !== 'all') {
                $query->where('materi_id', $this->materiId);
            }

            $entries = $query->get();
            $row = [
                $peserta->nama ?? $peserta->user?->name ?? 'ID:' . $peserta->id,
                $peserta->nama_madrasah ?? $peserta->asal_sekolah ?? '-',
            ];

            foreach ($fields as $field) {
                $avg = $entries->avg($field);
                $row[] = $avg !== null ? round($avg, 2) : null;
            }

            $row[] = $entries->count();

            return $row;
        });
    }

    public function title(): string
    {
        return 'Rekap Peserta';
    }
}

class PesertaDetailFasilitatorSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $materiId;

    public function __construct($materiId = null)
    {
        $this->materiId = $materiId;
    }

    public function headings(): array
    {
        return [
            'Fasilitator/Evaluator',
            'Email',
            'Peserta',
            'Asal Sekolah',
            'Kehadiran',
            'Partisipasi',
            'Disiplin',
            'Tugas',
            'Pemahaman',
            'Praktik',
            'Sikap',
            'Entri Count',
        ];
    }

    public function collection(): Collection
    {
        $fields = ['kehadiran', 'partisipasi', 'disiplin', 'tugas', 'pemahaman', 'praktik', 'sikap'];
        $query = TalentaPenilaianPeserta::with(['user', 'peserta.user.madrasah']);

        if ($this->materiId && $this->materiId !== 'all') {
            $query->where('materi_id', $this->materiId);
        }

        $entries = $query->get()->groupBy('user_id');
        $rows = collect();

        foreach ($entries as $evaluatorId => $evaluatorEntries) {
            $evaluator = $evaluatorEntries->first()->user;
            $byPeserta = $evaluatorEntries->groupBy('talenta_peserta_id');

            foreach ($byPeserta as $pesertaId => $group) {
                $peserta = $group->first()->peserta;
                $row = [
                    $evaluator?->name ?? 'User ID:' . $evaluatorId,
                    $evaluator?->email ?? '-',
                    $peserta?->nama ?? $peserta?->user?->name ?? 'ID:' . $pesertaId,
                    $peserta?->nama_madrasah ?? $peserta?->asal_sekolah ?? '-',
                ];

                foreach ($fields as $field) {
                    $avg = $group->avg($field);
                    $row[] = $avg !== null ? round($avg, 2) : null;
                }

                $row[] = $group->count();
                $rows->push($row);
            }
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Detail Fasilitator';
    }
}
