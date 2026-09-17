<?php

namespace Tests\Feature;

use App\Models\BiometricProfile;
use App\Models\FaceEnrollmentCapture;
use App\Models\FaceEnrollmentSession;
use App\Models\User;
use App\Services\KioskFaceEngineService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class RebuildFaceEmbeddingsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('madrasahs', fn (Blueprint $table) => $table->id());
        (require base_path('database/migrations/2026_08_31_000000_create_face_enrollment_sessions_tables.php'))->up();
        (require base_path('database/migrations/2026_08_30_000000_create_biometric_profiles_table.php'))->up();
        Schema::table('biometric_profiles', fn (Blueprint $table) => $table->string('pose')->nullable());
    }

    public function createApplication()
    {
        $app = parent::createApplication();
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
        $app['config']->set('biometric_v2.engine', 'onnxruntime');
        $app['config']->set('biometric_v2.dimension', 512);
        $app['config']->set('kiosk_face_v2.model', 'arcface');
        $app['config']->set('kiosk_face_v2.model_version', 'buffalo_l_w600k_r50');

        return $app;
    }

    private function seedCaptures(): User
    {
        $id = DB::table('users')->insertGetId(['name' => 'Guru Uji']);
        $user = User::findOrFail($id);
        $session = FaceEnrollmentSession::create([
            'session_uuid' => (string) Str::uuid(),
            'user_id' => $user->id,
            'operator_user_id' => $user->id,
            'status' => 'completed',
        ]);
        foreach (['front', 'front_smile', 'left', 'right', 'up', 'down'] as $index => $phase) {
            FaceEnrollmentCapture::create([
                'session_id' => $session->id,
                'phase_key' => $phase,
                'phase_label' => $phase,
                'capture_index' => $index,
                'captured_image' => 'data:image/jpeg;base64,dGVzdA==',
            ]);
        }
        BiometricProfile::create([
            'user_id' => $user->id,
            'enrollment_uuid' => (string) Str::uuid(),
            'engine' => 'opencv', 'model' => 'sface', 'model_version' => 'v1',
            'dimension' => 128, 'embedding' => array_fill(0, 128, 0.1),
            'source' => 'kiosk', 'status' => 'active',
        ]);

        return $user;
    }

    private function mockEngine(int $dimension = 512): void
    {
        $this->mock(KioskFaceEngineService::class, function ($mock) use ($dimension) {
            $mock->shouldReceive('enroll')->withArgs(function ($user, $payload, $context) {
                return ($context['rebuild'] ?? false) === true
                    && ! array_key_exists('expected_pose', $context);
            })->andReturn([
                'success' => true, 'provider' => 'insightface_arcface',
                'model' => 'arcface', 'model_version' => 'buffalo_l_w600k_r50',
                'face_embedding' => array_fill(0, $dimension, 1 / sqrt($dimension)),
                'quality_score' => 0.9, 'liveness_score' => 0.2,
            ]);
        });
    }

    public function test_dry_run_preserves_database(): void
    {
        $this->seedCaptures();
        $this->mockEngine();
        $this->artisan('face:rebuild-embeddings', ['--dry-run' => true])->assertExitCode(0);
        $this->assertDatabaseCount('biometric_profiles', 1);
    }

    public function test_rebuild_preserves_old_profile_and_is_idempotent(): void
    {
        $user = $this->seedCaptures();
        $this->mockEngine();
        $this->artisan('face:rebuild-embeddings', ['--user' => $user->id])->assertExitCode(0);
        $this->artisan('face:rebuild-embeddings', ['--user' => $user->id])->assertExitCode(0);
        $this->assertDatabaseCount('biometric_profiles', 7);
        $this->assertDatabaseHas('biometric_profiles', ['engine' => 'opencv', 'status' => 'active']);
        $this->assertSame(6, BiometricProfile::where('engine', 'onnxruntime')->where('dimension', 512)->count());
    }

    public function test_incompatible_engine_result_is_not_saved(): void
    {
        $this->seedCaptures();
        $this->mockEngine(128);
        $this->artisan('face:rebuild-embeddings')->assertExitCode(1);
        $this->assertDatabaseCount('biometric_profiles', 1);
    }

    public function test_existing_hosting_arcface_profile_is_untouched_without_engine_calls(): void
    {
        $user = $this->seedCaptures();
        $profile = BiometricProfile::create([
            'user_id' => $user->id, 'enrollment_uuid' => (string) Str::uuid(),
            'engine' => 'onnxruntime', 'model' => 'arcface',
            'model_version' => 'buffalo_l_w600k_r50', 'dimension' => 512,
            'embedding' => array_fill(0, 512, 1 / sqrt(512)),
            'source' => 'kiosk', 'status' => 'active',
        ]);
        $before = $profile->fresh()->getAttributes();
        $this->mock(KioskFaceEngineService::class, fn ($mock) => $mock->shouldNotReceive('enroll'));
        $this->artisan('face:rebuild-embeddings', ['--dry-run' => true])->assertExitCode(0);
        $this->artisan('face:rebuild-embeddings')->assertExitCode(0);
        $this->assertDatabaseCount('biometric_profiles', 2);
        $this->assertSame($before, $profile->fresh()->getAttributes());
    }
}
