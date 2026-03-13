<?php

namespace App\Exports\Instumen;

use App\Models\TalentaPemateri;
use App\Models\TugasNilai;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StatusPenilaianMateriExport implements FromCollection, WithHeadings, ShouldAutoSize
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
            'Pemateri',
            'Materi yang Diampu',
            'Status',
            'Jumlah Entri Nilai',
        ];
    }

    public function collection(): Collection
    {
        $pemateris = TalentaPemateri::with(['materis', 'user'])->get();

        return $pemateris->values()->map(function ($pemateri, $index) {
            $count = 0;

            if (!empty($pemateri->user_id)) {
                $query = TugasNilai::where('penilai_id', $pemateri->user_id)
                    ->whereHas('tugas', function ($q) {
                        if (!empty($this->area)) {
                            $q->where('area', $this->area);
                        }
                    });

                $count = $query->count();
            }

            $materi = ($pemateri->materis && $pemateri->materis->isNotEmpty())
                ? $pemateri->materis->map(function ($item) {
                    return $item->judul_materi ?? $item->kode_materi ?? '-';
                })->implode(', ')
                : '-';

            return [
                $index + 1,
                $pemateri->nama ?? $pemateri->user->name ?? '-',
                $materi,
                $count > 0 ? 'Sudah' : 'Belum',
                $count,
            ];
        });
    }
}
