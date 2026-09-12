<?php

namespace Tests\Unit;

use App\Http\Controllers\SkYayasanController;
use App\Models\SkYayasanImportRow;
use App\Models\SkYayasanImportBatch;
use App\Models\SkYayasanRequest;
use App\Models\SkYayasanTemplate;
use App\Models\User;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use Tests\TestCase;

class SkYayasanRequestTypeSynchronizationTest extends TestCase
{
    #[DataProvider('requestTypes')]
    public function test_request_type_follows_the_latest_import_keterangan(
        string $sourceKeterangan,
        array $skPayload,
        string $expected
    ): void {
        $row = new SkYayasanImportRow([
            'source_keterangan' => $sourceKeterangan,
            'sk_payload' => $skPayload,
        ]);

        $method = new ReflectionMethod(SkYayasanController::class, 'requestTypeFromImportRow');

        $this->assertSame(
            $expected,
            $method->invoke(new SkYayasanController(), $row)
        );
    }

    public static function requestTypes(): array
    {
        return [
            'pengangkatan' => ['Pengangkatan GTY', [], 'pengangkatan'],
            'perpanjangan' => ['Perpanjangan PTY', [], 'perpanjangan'],
            'combined' => ['Pengangkatan/Perpanjangan GTT', [], 'pengangkatan_perpanjangan'],
            'effective payload wins over source' => [
                'Pengangkatan/Perpanjangan GTT',
                ['keterangan' => 'Pengangkatan GTY'],
                'pengangkatan',
            ],
        ];
    }

    public function test_effective_row_payload_controls_the_displayed_type_and_template(): void
    {
        $employee = new User(['name' => 'Guru']);
        $employee->id = 10;
        $employee->setRelation('skYayasanEmployeeData', null);
        $employee->setRelation('statusKepegawaian', null);

        $row = new SkYayasanImportRow([
            'matched_user_id' => 10,
            'source_keterangan' => 'Perpanjangan GTY',
            'sk_payload' => ['keterangan' => 'Pengangkatan GTY'],
        ]);
        $batch = new SkYayasanImportBatch();
        $batch->setRelation('rows', new Collection([$row]));

        $request = new SkYayasanRequest([
            'request_type' => 'pengangkatan',
            'employment_category' => 'GTY',
        ]);
        $request->employee_id = 10;
        $request->setRelation('employee', $employee);
        $request->setRelation('importBatch', $batch);
        $request->setRelation('template', null);
        $request->setRelation('document', null);

        $extension = new SkYayasanTemplate(['name' => 'Perpanjangan GTY', 'is_active' => true]);
        $extension->id = 1;
        $appointment = new SkYayasanTemplate(['name' => 'Pengangkatan GTY', 'is_active' => true]);
        $appointment->id = 2;

        $controller = new SkYayasanController();
        $labelMethod = new ReflectionMethod($controller, 'formatSubmissionTypeLabel');
        $templateMethod = new ReflectionMethod($controller, 'resolveTemplateForSubmission');

        $this->assertSame('Pengangkatan GTY', $labelMethod->invoke($controller, $request));
        $this->assertSame(
            2,
            $templateMethod->invoke($controller, $request, new Collection([$extension, $appointment]))?->id
        );
    }
}
