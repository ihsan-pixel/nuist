<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminYayasan\PendataanGtkController;
use App\Models\GtkDocument;
use App\Models\Madrasah;
use App\Models\User;
use FPDF;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Tests\TestCase;

class PendataanGtkMultipleDocumentsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('madrasahs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('madrasah_id')->nullable();
            $table->string('role');
            $table->string('name');
            $table->string('nuist_id')->nullable();
            $table->string('avatar')->nullable();
            $table->string('ketugasan')->nullable();
            $table->timestamps();
        });
        Schema::create('gtk_pendataan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('sk_awal_path')->nullable();
            $table->timestamps();
        });
        Schema::create('gtk_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type');
            $table->string('disk');
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->timestamps();
        });
        Schema::create('simfoni', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
        Schema::create('mgmp_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('mgmp_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('mgmp_group_id')->nullable();
            $table->timestamps();
        });

        DB::table('madrasahs')->insert(['id' => 19, 'name' => 'Madrasah Uji']);
        DB::table('users')->insert([
            'id' => 7,
            'madrasah_id' => 19,
            'role' => 'tenaga_pendidik',
            'name' => 'Guru Uji',
            'nuist_id' => 'GTK-7',
        ]);
    }

    public function test_multiple_files_can_be_added_to_the_same_document_type_without_replacing_existing_file(): void
    {
        Storage::fake('local');
        $this->actingAs(new User(['role' => 'admin_yayasan']));

        $request = Request::create('/pendataan-gtk/19/documents/manage', 'POST', [], [], [
            'documents' => [
                7 => [
                    'sk_awal' => [
                        UploadedFile::fake()->create('sk-pertama-a.pdf', 50, 'application/pdf'),
                        UploadedFile::fake()->create('sk-pertama-b.pdf', 60, 'application/pdf'),
                    ],
                ],
            ],
        ]);

        (new PendataanGtkController)->storeManagedDocuments($request, Madrasah::findOrFail(19));

        $documents = DB::table('gtk_documents')->where('user_id', 7)->where('type', 'sk_awal')->get();
        $this->assertCount(2, $documents);
        $this->assertEqualsCanonicalizing(['sk-pertama-a.pdf', 'sk-pertama-b.pdf'], $documents->pluck('original_name')->all());
        foreach ($documents as $document) {
            Storage::disk('local')->assertExists($document->path);
        }
    }

    public function test_only_selected_pdf_pages_are_stored(): void
    {
        Storage::fake('local');
        $this->actingAs(new User(['role' => 'admin_yayasan']));

        $sourcePath = tempnam(sys_get_temp_dir(), 'gtk-pdf-');
        $source = new FPDF;
        foreach (['Halaman satu', 'Halaman dua', 'Halaman tiga'] as $text) {
            $source->AddPage();
            $source->SetFont('Arial', '', 16);
            $source->Cell(0, 10, $text);
        }
        $source->Output('F', $sourcePath);

        $request = Request::create('/pendataan-gtk/19/documents/manage', 'POST', [
            'selected_pages' => '2',
        ], [], [
            'documents' => [
                7 => ['sk_awal' => [new UploadedFile($sourcePath, 'sk-lengkap.pdf', 'application/pdf', null, true)]],
            ],
        ]);

        (new PendataanGtkController)->storeManagedDocuments($request, Madrasah::findOrFail(19));

        $document = DB::table('gtk_documents')->where('user_id', 7)->first();
        $storedPdf = new Fpdi;
        $this->assertSame(1, $storedPdf->setSourceFile(Storage::disk('local')->path($document->path)));
    }

    public function test_gtk_pdf_export_uses_the_official_form_template(): void
    {
        $this->actingAs(new User(['role' => 'admin_yayasan']));
        $this->app['events']->forget('composing: *');
        $user = User::findOrFail(7);
        $user->setRelation('madrasah', Madrasah::findOrFail(19));
        $user->setRelation('statusKepegawaian', null);
        $user->setRelation('gtkPendataan', null);
        $user->setRelation('simfoni', null);
        $user->setRelation('mgmpMemberships', new Collection);

        $response = (new PendataanGtkController)->exportPdf($user);

        $this->assertSame('application/pdf', $response->headers->get('content-type'));
        $this->assertStringStartsWith('%PDF-', $response->getContent());
        $this->assertStringContainsString('no-store', $response->headers->get('cache-control'));
        $this->assertStringContainsString('inline', $response->headers->get('content-disposition'));
        $this->assertStringContainsString('form-kelengkapan-dokumen-gtk-guru-uji.pdf', $response->headers->get('content-disposition'));
    }

    public function test_gtk_pdf_marks_uploaded_identity_and_employment_documents_as_complete(): void
    {
        $this->app['events']->forget('composing: *');
        $user = User::findOrFail(7);
        $user->setRelation('madrasah', Madrasah::findOrFail(19));
        $user->setRelation('statusKepegawaian', null);
        $user->setRelation('gtkPendataan', null);
        $user->setRelation('simfoni', null);
        $user->setRelation('mgmpMemberships', new Collection);
        $user->setRelation('gtkDocuments', new Collection([
            new GtkDocument(['type' => 'ktp']),
            new GtkDocument(['type' => 'sk_awal']),
            new GtkDocument(['type' => 'sk_akhir']),
            new GtkDocument(['type' => 'foto_resmi']),
            new GtkDocument(['type' => 'foto_bebas']),
        ]));

        $html = view('pdf.pendataan-gtk-template', [
            'user' => $user,
            'letterheadDataUri' => null,
        ])->render();
        $text = preg_replace('/\s+/', ' ', strip_tags($html));

        $this->assertStringContainsString('KTP asli telah di-scan✓ Sudah', $text);
        $this->assertStringContainsString('SK Pertama telah di-scan✓ Sudah', $text);
        $this->assertStringContainsString('SK Terakhir telah di-scan✓ Sudah', $text);
        $this->assertStringContainsString('Foto resmi✓ Sudah', $text);
        $this->assertStringContainsString('Foto bebas✓ Sudah', $text);
    }

    public function test_school_pdf_export_contains_one_pdf_per_teacher(): void
    {
        $this->actingAs(new User(['role' => 'admin_yayasan']));
        $this->app['events']->forget('composing: *');
        DB::table('users')->insert([
            'id' => 8,
            'madrasah_id' => 19,
            'role' => 'tenaga_pendidik',
            'name' => 'Guru Kedua',
            'nuist_id' => 'GTK-8',
        ]);

        $response = (new PendataanGtkController)->exportSchoolPdfs(Madrasah::findOrFail(19));
        $archivePath = $response->getFile()->getPathname();
        $zip = new \ZipArchive;

        try {
            $this->assertTrue($zip->open($archivePath));
            $this->assertSame(2, $zip->numFiles);
            $this->assertStringEndsWith('.pdf', $zip->getNameIndex(0));
            $this->assertStringStartsWith('%PDF-', $zip->getFromIndex(0));
        } finally {
            $zip->close();
            @unlink($archivePath);
        }
    }
}
