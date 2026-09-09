<?php

namespace Tests\Feature;

use App\Exports\PendataanGtkExport;
use App\Http\Controllers\AdminYayasan\PendataanGtkController;
use App\Models\Madrasah;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class PendataanGtkSchoolAccessTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('madrasahs', function (Blueprint $table) {
            $table->id();
            foreach (['name', 'scod', 'kabupaten', 'alamat', 'logo'] as $column) {
                $table->string($column)->nullable();
            }
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('madrasah_id')->nullable();
            $table->string('role');
            $table->string('name');
            $table->string('ketugasan')->nullable();
        });
        foreach (['gtk_pendataan', 'mgmp_members'] as $name) {
            Schema::create($name, function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
            });
        }
        DB::table('madrasahs')->insert([
            ['id' => 1, 'name' => 'Sekolah Satu'],
            ['id' => 2, 'name' => 'Sekolah Dua'],
        ]);
        DB::table('users')->insert([
            ['id' => 11, 'madrasah_id' => 1, 'role' => 'tenaga_pendidik', 'name' => 'Guru Satu'],
            ['id' => 12, 'madrasah_id' => 2, 'role' => 'tenaga_pendidik', 'name' => 'Guru Dua'],
            ['id' => 13, 'madrasah_id' => null, 'role' => 'tenaga_pendidik', 'name' => 'Tanpa Sekolah'],
            ['id' => 14, 'madrasah_id' => 1, 'role' => 'admin', 'name' => 'Admin'],
        ]);
    }

    public function test_foundation_admin_exports_only_the_selected_school(): void
    {
        $this->actingAs(new User(['role' => 'admin_yayasan']));
        foreach ([1 => 'Guru Satu', 2 => 'Guru Dua'] as $schoolId => $name) {
            Excel::shouldReceive('download')->once()->withArgs(function (PendataanGtkExport $export, string $filename) use ($schoolId, $name) {
                return $export->collection()->pluck(3)->all() === [$name]
                    && str_contains($filename, 'sekolah-' . $schoolId . '-');
            })->andReturn(response('xlsx'));
            (new PendataanGtkController)->export(Madrasah::findOrFail($schoolId));
        }
    }

    public function test_school_admin_cannot_export_even_own_school(): void
    {
        $this->actingAs(new User(['role' => 'admin', 'madrasah_id' => 1]));
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Unauthorized access');
        (new PendataanGtkController)->export(Madrasah::findOrFail(1));
    }

    public function test_school_admin_cannot_export_all_data(): void
    {
        $this->actingAs(new User(['role' => 'admin', 'madrasah_id' => 1]));
        $this->expectException(HttpException::class);
        (new PendataanGtkController)->export();
    }

    public function test_school_admin_cannot_open_index(): void
    {
        $this->actingAs(new User(['role' => 'admin', 'madrasah_id' => 1]));
        $this->expectException(HttpException::class);
        (new PendataanGtkController)->index();
    }

    public function test_foundation_admin_can_export_all_assigned_teachers(): void
    {
        $this->actingAs(new User(['role' => 'admin_yayasan']));
        Excel::shouldReceive('download')->once()->withArgs(function (PendataanGtkExport $export, string $filename) {
            $this->assertSame(['Guru Satu', 'Guru Dua'], $export->collection()->pluck(3)->all());
            return true;
        })->andReturn(response('xlsx'));
        (new PendataanGtkController)->export();
    }

    public function test_foundation_index_lists_all_schools_and_teacher_counts(): void
    {
        $this->actingAs(new User(['role' => 'admin_yayasan']));
        $data = (new PendataanGtkController)->index()->getData();
        $this->assertEqualsCanonicalizing([1, 2], $data['madrasahs']->pluck('id')->all());
        $this->assertSame([1, 1], $data['madrasahs']->pluck('tenaga_pendidik_users_count')->all());
    }

    public function test_school_admin_does_not_gain_edit_access(): void
    {
        $this->actingAs(new User(['role' => 'admin', 'madrasah_id' => 1]));
        $this->expectException(HttpException::class);
        (new PendataanGtkController)->update(new \Illuminate\Http\Request, User::findOrFail(11));
    }
}
