<?php

namespace Tests\Unit;

use App\Models\Izin;
use App\Models\Presensi;
use App\Services\ExternalTeachingPermissionService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExternalTeachingPermissionServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge();
        DB::reconnect('sqlite');

        Schema::create('izins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal');
            $table->date('tanggal_selesai')->nullable();
            $table->string('type');
            $table->string('status');
            $table->text('approval_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal');
            $table->string('status')->nullable();
            $table->string('status_izin')->nullable();
            $table->text('keterangan')->nullable();
            $table->dateTime('waktu_masuk')->nullable();
            $table->dateTime('waktu_keluar')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('presensis');
        Schema::dropIfExists('izins');

        parent::tearDown();
    }

    public function test_approval_truncates_the_previous_schedule_and_clears_only_replaced_system_rows(): void
    {
        $previous = $this->createPermission('2026-10-01', '2026-12-31');
        $replacement = $this->createPermission('2026-10-15', '2026-11-30');

        $this->createExternalTeachingPresence('2026-10-10');
        $this->createExternalTeachingPresence('2026-10-20');
        $this->createExternalTeachingPresence('2026-10-20', '2026-10-20 07:00:00');
        $this->createExternalTeachingPresence('2027-01-15');

        ExternalTeachingPermissionService::activateApprovedRequest($replacement);

        $this->assertSame('2026-10-14', $previous->fresh()->tanggal_selesai->toDateString());
        $this->assertSame('approved', $previous->fresh()->status);
        $this->assertDatabaseHas('presensis', [
            'tanggal' => '2026-10-10 00:00:00',
            'keterangan' => ExternalTeachingPermissionService::KETERANGAN_TIDAK_PRESENSI,
        ]);
        $this->assertDatabaseMissing('presensis', [
            'tanggal' => '2026-10-20 00:00:00',
            'keterangan' => ExternalTeachingPermissionService::KETERANGAN_TIDAK_PRESENSI,
            'waktu_masuk' => null,
        ]);
        $this->assertDatabaseHas('presensis', [
            'tanggal' => '2026-10-20 00:00:00',
            'waktu_masuk' => '2026-10-20 07:00:00',
        ]);
        $this->assertDatabaseHas('presensis', [
            'tanggal' => '2027-01-15 00:00:00',
            'keterangan' => ExternalTeachingPermissionService::KETERANGAN_TIDAK_PRESENSI,
        ]);
    }

    public function test_approval_supersedes_an_older_approval_that_starts_on_the_same_date(): void
    {
        $previous = $this->createPermission('2026-10-15', '2026-12-31');
        $replacement = $this->createPermission('2026-10-15', '2026-11-30');

        ExternalTeachingPermissionService::activateApprovedRequest($replacement);

        $previous->refresh();

        $this->assertSame('superseded', $previous->status);
        $this->assertStringContainsString(
            "Digantikan oleh pengajuan izin #{$replacement->id}.",
            $previous->approval_notes,
        );
    }

    private function createPermission(string $start, string $end): Izin
    {
        return Izin::query()->create([
            'user_id' => 10,
            'tanggal' => $start,
            'tanggal_selesai' => $end,
            'type' => ExternalTeachingPermissionService::TYPE,
            'status' => 'approved',
        ]);
    }

    private function createExternalTeachingPresence(string $date, ?string $checkIn = null): Presensi
    {
        return Presensi::query()->create([
            'user_id' => 10,
            'tanggal' => $date,
            'status' => 'izin',
            'status_izin' => 'approved',
            'keterangan' => ExternalTeachingPermissionService::KETERANGAN_TIDAK_PRESENSI,
            'waktu_masuk' => $checkIn,
        ]);
    }
}
