<?php

namespace App\Exports;

use App\Models\SkYayasanRequest;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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
            'Status Kepegawaian',
            'Tempat, Tanggal Lahir',
            'NUPTK',
            'NIPM',
            'Kartanu',
            'TMT',
            'Pendidikan Terakhir',
            'Tahun Lulus',
            'Program Studi',
            'No Pengajuan SK',
            'No Surat Pengajuan',
            'Tanggal Surat Pengajuan',
            'Jenis Pengajuan',
            'Kategori Kepegawaian',
            'Status Pengajuan',
            'Catatan Review',
            'Diajukan Pada',
            'Direview Pada',
            'Madrasah Pengajuan',
            'Template SK',
        ];
    }

    public function collection(): Collection
    {
        return $this->tenagaPendidiks->values()->map(function (User $user, int $index) {
            $latestRequest = $this->latestSkRequest($user);
            $latestImportRow = $this->latestSkImportRow($latestRequest);
            $namaDanGelar = $this->formatNamaDanGelar(
                $latestImportRow?->source_nama ?: $user->name,
                $latestImportRow?->source_gelar ?: $user->gelar
            );

            return [
                'no' => $index + 1,
                'scod' => $user->madrasah?->scod ?: '-',
                'nuist_id' => $user->nuist_id ?: '-',
                'nama_dan_gelar' => $namaDanGelar !== '' ? $namaDanGelar : '-',
                'asal_sekolah' => $user->madrasah?->name ?: '-',
                'status_kepegawaian' => $user->statusKepegawaian?->name ?: '-',
                'tempat_tanggal_lahir' => $this->formatTempatTanggalLahir($user),
                'nuptk' => $user->nuptk ?: '-',
                'nipm' => $user->nip ?: '-',
                'kartanu' => $user->kartanu ?: '-',
                'tmt' => $this->formatDate($user->tmt),
                'pendidikan_terakhir' => $user->pendidikan_terakhir ?: '-',
                'tahun_lulus' => $user->tahun_lulus ?: '-',
                'program_studi' => $user->program_studi ?: '-',
                'request_number' => $latestRequest?->request_number ?: '-',
                'submission_letter_number' => $latestRequest?->submission_letter_number ?: '-',
                'submission_letter_date' => $this->formatDate($latestRequest?->submission_letter_date),
                'request_type' => $latestRequest?->request_type ?: '-',
                'employment_category' => $latestRequest?->employment_category ?: '-',
                'current_status' => $latestRequest?->current_status ?: '-',
                'review_notes' => $latestRequest?->review_notes ?: '-',
                'submitted_at' => $this->formatDateTime($latestRequest?->submitted_at),
                'reviewed_at' => $this->formatDateTime($latestRequest?->reviewed_at),
                'request_madrasah' => $latestRequest?->madrasah?->name ?: '-',
                'template_name' => $latestRequest?->template?->name ?: '-',
            ];
        });
    }

    private function latestSkRequest(User $user): ?SkYayasanRequest
    {
        $requests = $user->skYayasanRequestsAsEmployee ?? collect();

        return $requests->first();
    }

    private function latestSkImportRow(?SkYayasanRequest $request): ?object
    {
        return $request?->importBatch?->rows
            ?->first(fn ($row) => (int) $row->matched_user_id === (int) $request->employee_id);
    }

    private function formatNamaDanGelar(?string $nama, ?string $gelar): string
    {
        $nama = trim((string) $nama);
        $gelar = trim((string) $gelar);

        if ($nama === '') {
            return $gelar === '' ? '-' : $this->formatNameWords($gelar);
        }

        $namaDanGelar = $nama;
        if ($gelar !== '' && ! $this->endsWithSameGelar($nama, $gelar)) {
            $namaDanGelar .= ', ' . $gelar;
        }

        return $this->formatNameWords($namaDanGelar);
    }

    private function endsWithSameGelar(string $nama, string $gelar): bool
    {
        $normalizedNama = $this->normalizeGelar($nama);
        $normalizedGelar = $this->normalizeGelar($gelar);

        return $normalizedGelar !== '' && Str::endsWith($normalizedNama, $normalizedGelar);
    }

    private function normalizeGelar(string $value): string
    {
        return Str::lower((string) preg_replace('/[^\pL\pN]/u', '', $value));
    }

    private function formatNameWords(string $value): string
    {
        $words = preg_split('/(\s+|,\s*)/u', trim($value), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        return collect($words)->map(function (string $part): string {
            if (preg_match('/^\s+$/u', $part) || str_starts_with($part, ',')) {
                return $part;
            }

            $segments = preg_split('/([.\'-])/u', Str::lower($part), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

            return collect($segments)->map(function (string $segment): string {
                if (in_array($segment, ['.', "'", '-'], true)) {
                    return $segment;
                }

                return mb_strtoupper(mb_substr($segment, 0, 1)) . mb_substr($segment, 1);
            })->implode('');
        })->implode('');
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

    private function formatDateTime(mixed $value): string
    {
        if (empty($value)) {
            return '-';
        }

        try {
            return Carbon::parse($value)->locale('id')->translatedFormat('j F Y H:i');
        } catch (\Throwable) {
            return (string) $value;
        }
    }
}
