<?php

namespace Tests\Feature;

use App\Models\GtkPendataan;
use App\Services\GtkMgmpSyncService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GtkMgmpSyncTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('madrasah_id')->nullable();
        });
        Schema::create('gtk_pendataan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nama_mgmp')->nullable();
            $table->timestamps();
        });
        Schema::create('mgmp_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('mgmp_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('mgmp_group_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('sekolah')->nullable();
            $table->unsignedBigInteger('madrasah_id')->nullable();
            $table->timestamps();
        });
        DB::table('users')->insert(['id' => 1, 'name' => 'Guru', 'email' => 'guru@example.test']);
        DB::table('mgmp_groups')->insert([
            ['id' => 1, 'name' => 'Matematika'], ['id' => 2, 'name' => 'Bahasa Indonesia'],
        ]);
    }

    public function test_saving_gtk_creates_moves_and_clears_membership(): void
    {
        $gtk = GtkPendataan::create(['user_id' => 1, 'nama_mgmp' => '  MATEMATIKA  ']);
        $this->assertDatabaseHas('mgmp_members', ['user_id' => 1, 'mgmp_group_id' => 1, 'name' => 'Guru']);
        $gtk->update(['nama_mgmp' => 'Bahasa   Indonesia']);
        $this->assertDatabaseMissing('mgmp_members', ['mgmp_group_id' => 1]);
        $this->assertDatabaseHas('mgmp_members', ['mgmp_group_id' => 2]);
        $gtk->update(['nama_mgmp' => '']);
        $this->assertDatabaseCount('mgmp_members', 0);
    }

    public function test_unknown_name_does_not_partially_replace_memberships(): void
    {
        $gtk = GtkPendataan::create(['user_id' => 1, 'nama_mgmp' => 'Matematika']);
        $gtk->update(['nama_mgmp' => 'Bahasa Indonesia, Tidak Dikenal']);
        $this->assertDatabaseHas('mgmp_members', ['mgmp_group_id' => 1]);
        $this->assertDatabaseCount('mgmp_members', 1);
        $this->assertSame(['Tidak Dikenal'], app(GtkMgmpSyncService::class)->sync($gtk));
    }

    public function test_backfill_is_idempotent_and_dry_run_does_not_write(): void
    {
        DB::table('gtk_pendataan')->insert(['user_id' => 1, 'nama_mgmp' => 'Matematika, Bahasa Indonesia']);
        $this->artisan('mgmp:sync-gtk --dry-run')->assertExitCode(0);
        $this->assertDatabaseCount('mgmp_members', 0);
        $this->artisan('mgmp:sync-gtk')->assertExitCode(0);
        $this->artisan('mgmp:sync-gtk')->assertExitCode(0);
        $this->assertDatabaseCount('mgmp_members', 2);
    }
}
