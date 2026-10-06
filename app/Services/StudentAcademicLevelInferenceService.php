<?php

namespace App\Services;

class StudentAcademicLevelInferenceService
{
    /**
     * Infer the school level and numeric grade without mutating the source data.
     *
     * @return array{jenjang_sekolah:?string,tingkat:?int,status:string,catatan:?string}
     */
    public function infer(?string $kelas, ?string $namaMadrasah = null): array
    {
        $normalizedClass = $this->normalize($kelas);
        $schoolLevel = $this->inferSchoolLevel($namaMadrasah);
        $grade = $this->inferGrade($normalizedClass);
        $gradeLevel = $this->levelForGrade($grade);

        if ($grade === null) {
            return [
                'jenjang_sekolah' => $schoolLevel,
                'tingkat' => null,
                'status' => 'perlu_verifikasi',
                'catatan' => $normalizedClass === ''
                    ? 'Kelas siswa kosong; tingkat belum dapat ditentukan otomatis.'
                    : "Format kelas '{$kelas}' belum dapat dikenali otomatis.",
            ];
        }

        if ($schoolLevel !== null && $gradeLevel !== $schoolLevel) {
            return [
                'jenjang_sekolah' => $schoolLevel,
                'tingkat' => $grade,
                'status' => 'perlu_verifikasi',
                'catatan' => "Tingkat {$grade} tidak sesuai dengan jenjang {$schoolLevel} yang terbaca dari nama sekolah.",
            ];
        }

        return [
            'jenjang_sekolah' => $schoolLevel ?? $gradeLevel,
            'tingkat' => $grade,
            'status' => 'aktif',
            'catatan' => $schoolLevel === null
                ? 'Jenjang ditentukan dari tingkat pada data kelas.'
                : null,
        ];
    }

    private function inferGrade(string $kelas): ?int
    {
        if ($kelas === '') {
            return null;
        }

        $kelas = preg_replace('/^(KELAS|TINGKAT)\s+/u', '', $kelas) ?? $kelas;

        if (preg_match('/^(12|11|10|9|8|7|6|5|4|3|2|1)(?:\b|[^0-9])/u', $kelas, $match)) {
            return (int) $match[1];
        }

        $romanGrades = [
            'XII' => 12, 'XI' => 11, 'X' => 10,
            'IX' => 9, 'VIII' => 8, 'VII' => 7,
            'VI' => 6, 'V' => 5, 'IV' => 4,
            'III' => 3, 'II' => 2, 'I' => 1,
        ];

        foreach ($romanGrades as $roman => $grade) {
            if (preg_match('/^'.preg_quote($roman, '/').'(?:\b|[\s\-._\/])/u', $kelas)) {
                return $grade;
            }
        }

        return null;
    }

    private function inferSchoolLevel(?string $name): ?string
    {
        $name = $this->normalize($name);
        if ($name === '') {
            return null;
        }

        $patterns = [
            'MI_SD' => '/(^|\s)(MI|MIS|MIN|SD|SDI|SDIT|SDN)(\s|$)/u',
            'MTS_SMP' => '/(^|\s)(MTS|MTSS|MTSN|SMP|SMPI|SMPIT|SMPN)(\s|$)/u',
            'MA_SMA_SMK' => '/(^|\s)(MA|MAS|MAN|SMA|SMAI|SMAN|SMK|SMKN)(\s|$)/u',
        ];

        foreach ($patterns as $level => $pattern) {
            if (preg_match($pattern, $name)) {
                return $level;
            }
        }

        return null;
    }

    private function levelForGrade(?int $grade): ?string
    {
        return match (true) {
            $grade >= 1 && $grade <= 6 => 'MI_SD',
            $grade >= 7 && $grade <= 9 => 'MTS_SMP',
            $grade >= 10 && $grade <= 12 => 'MA_SMA_SMK',
            default => null,
        };
    }

    private function normalize(?string $value): string
    {
        return preg_replace('/\s+/u', ' ', mb_strtoupper(trim((string) $value))) ?? '';
    }
}
