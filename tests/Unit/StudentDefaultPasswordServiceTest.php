<?php

namespace Tests\Unit;

use App\Services\StudentDefaultPasswordService;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StudentDefaultPasswordServiceTest extends TestCase
{
    public function test_default_student_password_uses_nuist_and_creation_date(): void
    {
        Carbon::setTestNow('2026-08-14 09:30:00');

        $password = app(StudentDefaultPasswordService::class)->plainDefaultPassword();

        $this->assertSame('Nuist14082026', $password);

        Carbon::setTestNow();
    }
}
