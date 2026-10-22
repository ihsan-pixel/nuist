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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedInteger('member_count')->default(0);
            $table->timestamps();
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

    public function test_production_names_resolve_explicit_aliases(): void
    {
        DB::table('mgmp_groups')->delete();
        $cases = [
            'MGMP Bahasa Indonesia SMK' => ['Bahasa Indonesia', 'mgmp b. indonesia', 'MGMP  B INDONESIA MAARIF'],
            'MGBK (Musyawarah Guru Bimbingan dan Konseling)' => ['BK', 'MGBK', 'MGMP Bimbingan Konseling'],
            "META YOGYAKARTA (Ma'arif English Teacher Association)" => ['MGMP META', 'MGMP Bahasa Inggris'],
            'MGMP EKOBANK' => ['MGMP EKOBANK KWU', 'MGMP Ekobank dan KWU'],
            'MGMP Teknik Sepeda Motor' => ['TBSM', 'MGMP TSM', 'MGMP TBSM LPM'],
            'PENDIDIKAN PANCASILA' => ['MGMP PKN', 'MGMP PENDIDIKAN PANCASILA'],
            'IPA Terapan' => ['IPA Terapa'],
        ];
        $gtk = new GtkPendataan(['user_id' => 1]);
        foreach ($cases as $target => $aliases) {
            $id = DB::table('mgmp_groups')->insertGetId(['name' => $target]);
            foreach ($aliases as $alias) {
                $gtk->nama_mgmp = $alias;
                $this->assertSame([], app(GtkMgmpSyncService::class)->sync($gtk), $alias);
                $this->assertDatabaseHas('mgmp_members', ['mgmp_group_id' => $id]);
                $this->assertDatabaseCount('mgmp_members', 1);
            }
        }
    }

    public function test_multiple_groups_can_use_ampersand_and_sama(): void
    {
        $gtk = GtkPendataan::create(['user_id' => 1, 'nama_mgmp' => 'MGMP Matematika & Bahasa Indonesia']);
        $this->assertDatabaseCount('mgmp_members', 2);
        $gtk->update(['nama_mgmp' => 'Matematika sama MGMP Bahasa Indonesia']);
        $this->assertDatabaseCount('mgmp_members', 2);
    }

    public function test_missing_groups_and_regional_names_are_not_guessed(): void
    {
        DB::table('mgmp_groups')->delete();
        DB::table('mgmp_groups')->insert(['name' => 'MGMP Bahasa Indonesia SMK']);
        $gtk = new GtkPendataan(['user_id' => 1]);
        foreach (['MGMP Matematika', 'MGMP PAI', 'MGMP PJOK', 'MKKS', 'MGMP Bahasa Indonesia Gunungkidul'] as $name) {
            $gtk->nama_mgmp = $name;
            $this->assertSame([$name], app(GtkMgmpSyncService::class)->sync($gtk));
        }
        $this->assertDatabaseCount('mgmp_members', 0);
    }

    public function test_duplicate_normalized_group_names_are_ambiguous(): void
    {
        DB::table('mgmp_groups')->insert(['name' => 'MGMP Matematika']);
        $gtk = new GtkPendataan(['user_id' => 1, 'nama_mgmp' => 'Matematika']);
        $this->assertSame(['Matematika'], app(GtkMgmpSyncService::class)->sync($gtk));
        $this->assertDatabaseCount('mgmp_members', 0);
    }

    public function test_create_missing_groups_dry_run_then_sync_without_duplicates(): void
    {
        DB::table('mgmp_groups')->delete();
        DB::table('gtk_pendataan')->insert(['user_id' => 1, 'nama_mgmp' => 'PAI sama META']);
        $service = app(GtkMgmpSyncService::class);
        $this->assertSame(['MGMP PAI', "META YOGYAKARTA (Ma'arif English Teacher Association)"], $service->createMissingGroups(true));
        $this->assertDatabaseCount('mgmp_groups', 0);
        DB::table('gtk_pendataan')->where('user_id', 1)->update(['nama_mgmp' => 'MGMP LPM PAI & Sesogeo']);
        $this->artisan('mgmp:sync-gtk --create-missing-groups')->assertExitCode(0);
        $this->assertDatabaseHas('mgmp_groups', ['name' => 'MGMP PAI', 'user_id' => null]);
        $this->assertDatabaseHas('mgmp_groups', ['name' => 'MGMP Soseogeo', 'user_id' => null]);
        $this->assertDatabaseCount('mgmp_members', 2);
        $this->artisan('mgmp:sync-gtk --create-missing-groups')->assertExitCode(0);
        $this->assertDatabaseCount('mgmp_groups', 2);
        $this->assertDatabaseCount('mgmp_members', 2);
    }

    public function test_missing_groups_includes_organisations_and_unknown_names_but_not_placeholders(): void
    {
        DB::table('gtk_pendataan')->insert(['user_id' => 1, 'nama_mgmp' => 'MKKS, FKKS DIY, -, Tidak mengikuti, MGMP Tidak Dikenal']);
        $this->assertSame(['MKKS', 'FKKS DIY', 'MGMP Tidak Dikenal'], app(GtkMgmpSyncService::class)->createMissingGroups());
        $this->artisan('mgmp:sync-gtk --create-missing-groups')->assertExitCode(0);
        $this->assertDatabaseCount('mgmp_groups', 5);
        $this->assertDatabaseCount('mgmp_members', 3);
    }

    public function test_non_membership_entries_clear_old_memberships_without_creating_groups(): void
    {
        $gtk = GtkPendataan::create(['user_id' => 1, 'nama_mgmp' => 'Matematika']);
        $gtk->update(['nama_mgmp' => '-']);
        $this->assertDatabaseCount('mgmp_members', 0);
        $gtk->update(['nama_mgmp' => 'Matematika']);
        $gtk->update(['nama_mgmp' => 'Tidak mengikuti']);
        $this->assertDatabaseCount('mgmp_members', 0);
        $this->artisan('mgmp:sync-gtk --create-missing-groups')->assertExitCode(0);
        $this->assertDatabaseCount('mgmp_groups', 2);
    }
}
