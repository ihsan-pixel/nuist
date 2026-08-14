<?php

namespace Tests\Unit;

use App\Models\Siswa;
use App\Services\StudentDefaultPasswordService;
use Tests\TestCase;

class StudentDefaultPasswordServiceTest extends TestCase
{
    public function test_default_student_password_uses_nuist_and_birth_date(): void
    {
        $siswa = new Siswa(['tanggal_lahir' => '2009-11-10']);

        $password = app(StudentDefaultPasswordService::class)->plainDefaultPassword($siswa);

        $this->assertSame('Nuist10112009', $password);
    }
}
