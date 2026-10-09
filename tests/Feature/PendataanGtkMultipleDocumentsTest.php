<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminYayasan\PendataanGtkController;
use App\Models\Madrasah;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use FPDF;
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
}
