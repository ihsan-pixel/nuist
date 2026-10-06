<?php

namespace Tests\Unit;

use App\Services\StudentAcademicLevelInferenceService;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class StudentAcademicLevelInferenceServiceTest extends TestCase
{
    #[DataProvider('recognizedClasses')]
    public function test_it_recognizes_common_class_formats(string $kelas, string $school, string $level, int $grade): void
    {
        $result = app(StudentAcademicLevelInferenceService::class)->infer($kelas, $school);

        $this->assertSame($level, $result['jenjang_sekolah']);
        $this->assertSame($grade, $result['tingkat']);
        $this->assertSame('aktif', $result['status']);
    }

    public static function recognizedClasses(): array
    {
        return [
            ['1A', 'MI Maarif Contoh', 'MI_SD', 1],
            ['Kelas VI B', 'SD Negeri Contoh', 'MI_SD', 6],
            ['VII A', 'MTs Maarif Contoh', 'MTS_SMP', 7],
            ['9-B', 'SMP Negeri Contoh', 'MTS_SMP', 9],
            ['X IPA 1', 'MA Maarif Contoh', 'MA_SMA_SMK', 10],
            ['XII TKJ 2', 'SMK Maarif Contoh', 'MA_SMA_SMK', 12],
        ];
    }

    public function test_it_flags_unknown_class_without_guessing(): void
    {
        $result = app(StudentAcademicLevelInferenceService::class)->infer('IPA 1', 'MA Maarif Contoh');

        $this->assertNull($result['tingkat']);
        $this->assertSame('MA_SMA_SMK', $result['jenjang_sekolah']);
        $this->assertSame('perlu_verifikasi', $result['status']);
    }

    public function test_it_flags_a_grade_that_conflicts_with_the_school_level(): void
    {
        $result = app(StudentAcademicLevelInferenceService::class)->infer('X IPA 1', 'MTs Maarif Contoh');

        $this->assertSame(10, $result['tingkat']);
        $this->assertSame('MTS_SMP', $result['jenjang_sekolah']);
        $this->assertSame('perlu_verifikasi', $result['status']);
    }
}
