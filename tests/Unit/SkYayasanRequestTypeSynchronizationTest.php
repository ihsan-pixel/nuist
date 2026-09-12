<?php

namespace Tests\Unit;

use App\Http\Controllers\SkYayasanController;
use App\Models\SkYayasanImportRow;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

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
}
