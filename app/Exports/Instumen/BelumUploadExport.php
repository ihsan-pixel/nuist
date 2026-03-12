<?php

namespace App\Exports\Instumen;

use App\Models\TalentaKelompok;
use App\Models\TalentaMateri;
use App\Models\TalentaPeserta;
use App\Models\TugasTalentaLevel1;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BelumUploadExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $jenis;
    protected $area;
    protected $materis;
    protected $jenisList;
    protected $jenisLabels;

    public function __construct($jenis = null, $area = null)
    {
        $this->jenis = $jenis;
        $this->area = $area;
        $this->jenisLabels = [
            'on_site' => 'On Site',
            'terstruktur' => 'Terstruktur',
            'kelompok' => 'Kelompok',
        ];
        $this->jenisList = empty($jenis)
            ? ['on_site', 'terstruktur', 'kelompok']
            : [$jenis];
        $this->materis = TalentaMateri::where('status', TalentaMateri::STATUS_PUBLISHED)
            ->where('level_materi', TalentaMateri::LEVEL_1)
            ->when($area, fn($query) => $query->where('slug', $area))
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        $headings = [
            'Kode Peserta',
            'Nama Peserta',
            'Email',
            'Sekolah / Madrasah',
            'Kelompok',
            'Total Tugas Belum Upload',
        ];

        foreach ($this->materis as $materi) {
            $headings[] = sprintf(
                '%s - %s (%s)',
                $materi->kode_materi ?? ('M-' . $materi->id),
                $materi->judul_materi,
                $materi->slug
            );
        }

        $headings[] = 'Tugas Belum Terinput';

        return $headings;
    }

    public function collection()
    {
        $materiSlugs = $this->materis->pluck('slug')->toArray();
        $pesertas = TalentaPeserta::with(['user.madrasah'])
            ->orderBy('kode_peserta')
            ->get();

        $kelompokByUser = [];
        $allKelompokIds = [];
        TalentaKelompok::with('users:id')
            ->get()
            ->each(function ($kelompok) use (&$kelompokByUser, &$allKelompokIds) {
                foreach ($kelompok->users as $user) {
                    $kelompokByUser[$user->id] = $kelompokByUser[$user->id] ?? [];
                    $kelompokByUser[$user->id][] = [
                        'id' => $kelompok->id,
                        'nama' => $kelompok->nama_kelompok,
                    ];
                    $allKelompokIds[$kelompok->id] = $kelompok->id;
                }
            });

        $userIds = $pesertas->pluck('user_id')->filter()->unique()->values()->all();
        $kelompokIds = array_values($allKelompokIds);

        $existingTugas = TugasTalentaLevel1::query()
            ->when(!empty($materiSlugs), fn($query) => $query->whereIn('area', $materiSlugs))
            ->when(!empty($this->jenisList), fn($query) => $query->whereIn('jenis_tugas', $this->jenisList))
            ->where(function ($query) use ($userIds, $kelompokIds) {
                if (!empty($userIds)) {
                    $query->whereIn('user_id', $userIds);
                }

                if (!empty($kelompokIds)) {
                    $query->orWhereIn('kelompok_id', $kelompokIds);
                }
            })
            ->get();

        $userSubmissionMap = [];
        $kelompokSubmissionMap = [];
        foreach ($existingTugas as $tugas) {
            if (!empty($tugas->user_id)) {
                $userSubmissionMap[$tugas->user_id][$tugas->area][$tugas->jenis_tugas] = true;
            }

            if (!empty($tugas->kelompok_id)) {
                $kelompokSubmissionMap[$tugas->kelompok_id][$tugas->area][$tugas->jenis_tugas] = true;
            }
        }

        $rows = collect();
        foreach ($pesertas as $peserta) {
            $user = $peserta->user;
            if (!$user) {
                continue;
            }

            $kelompokEntries = collect($kelompokByUser[$user->id] ?? []);
            $kelompokNames = $kelompokEntries->pluck('nama')->filter()->unique()->values();
            $kelompokIdsForUser = $kelompokEntries->pluck('id')->filter()->unique()->values();

            $missingTaskCount = 0;
            $materiCells = [];
            $missingSummary = [];

            foreach ($this->materis as $materi) {
                $missingJenis = [];

                foreach ($this->jenisList as $jenisTugas) {
                    $isUploaded = false;
                    $missingLabel = $this->jenisLabels[$jenisTugas] ?? $jenisTugas;

                    if ($jenisTugas === 'kelompok') {
                        if ($kelompokIdsForUser->isNotEmpty()) {
                            foreach ($kelompokIdsForUser as $kelompokId) {
                                if (!empty($kelompokSubmissionMap[$kelompokId][$materi->slug][$jenisTugas])) {
                                    $isUploaded = true;
                                    break;
                                }
                            }
                        } else {
                            $missingLabel = 'Kelompok belum ditetapkan';
                        }
                    } else {
                        $isUploaded = !empty($userSubmissionMap[$user->id][$materi->slug][$jenisTugas]);
                    }

                    if (!$isUploaded) {
                        $missingJenis[] = $missingLabel;
                    }
                }

                $missingTaskCount += count($missingJenis);
                $materiCells[] = empty($missingJenis) ? 'Lengkap' : implode(', ', $missingJenis);

                if (!empty($missingJenis)) {
                    $missingSummary[] = ($materi->kode_materi ?? ('M-' . $materi->id)) . ': ' . implode(', ', $missingJenis);
                }
            }

            if ($missingTaskCount === 0) {
                continue;
            }

            $row = [
                $peserta->kode_peserta ?? '-',
                $user->name ?? '-',
                $user->email ?? '-',
                $peserta->nama_madrasah ?? $peserta->asal_sekolah ?? '-',
                $kelompokNames->isNotEmpty() ? $kelompokNames->implode(', ') : '-',
                $missingTaskCount,
            ];

            foreach ($materiCells as $cell) {
                $row[] = $cell;
            }

            $row[] = implode(' | ', $missingSummary);

            $rows->push($row);
        }

        return $rows->sortBy(function ($row) {
            return sprintf('%05d_%s', 99999 - ((int) $row[5]), strtolower((string) $row[1]));
        })->values();
    }
}
