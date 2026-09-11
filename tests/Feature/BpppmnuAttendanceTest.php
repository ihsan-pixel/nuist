<?php

namespace Tests\Feature;

use App\Models\BpppmnuEvent;
use App\Models\BpppmnuEventAttendance;
use App\Models\User;
use App\Services\BpppmnuAttendanceService;
use App\Services\BpppmnuReportService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BpppmnuAttendanceTest extends TestCase
{
    private User $member;

    private User $admin;

    private BpppmnuEvent $event;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'cache.default' => 'array', 'session.driver' => 'array']);
        DB::purge('sqlite');
        $this->withoutMiddleware(\App\Http\Middleware\UpdateLastSeen::class);
        $this->travelTo(\Carbon\Carbon::parse('2026-09-11 09:00:00', 'Asia/Jakarta'));
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->string('app_version');
        });
        DB::table('app_settings')->insert(['app_name' => 'NUIST', 'app_version' => 'test']);
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            foreach (['name', 'email', 'password', 'role', 'nuist_id', 'ketugasan', 'no_hp', 'alamat', 'avatar', 'jabatan'] as $field) {
                $table->string($field)->nullable();
            }
            $table->boolean('is_active')->default(true);
            $table->boolean('password_changed')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
        (require database_path('migrations/2026_09_11_000001_create_bpppmnu_event_tables.php'))->up();
        $this->member = $this->user('pengurus_bpppmnu');
        $this->admin = $this->user('admin_yayasan');
        $this->event = BpppmnuEvent::create([
            'name' => 'Rapat BPPPMNU', 'type' => 'Rapat', 'description' => 'Evaluasi program', 'organizer' => 'Yayasan',
            'person_in_charge' => 'Ketua', 'location_name' => 'Aula', 'start_at' => now(), 'end_at' => now()->addHours(2),
            'attendance_open_at' => now()->subHour(), 'attendance_close_at' => now()->addHours(3),
            'status' => 'published', 'created_by' => $this->admin->id,
        ]);
        $this->event->invitations()->create(['user_id' => $this->member->id]);
        $this->token = app(BpppmnuAttendanceService::class)->issue($this->event);
    }

    protected function tearDown(): void
    {
        $this->travelBack();
        parent::tearDown();
    }

    private function user(string $role): User
    {
        return User::create(['name' => 'User '.$role, 'email' => uniqid().'@example.test', 'role' => $role, 'password' => Hash::make('Password1!'), 'is_active' => true]);
    }

    private function scan(?string $token = null)
    {
        return $this->actingAs($this->member)->postJson('/mobile/bpppmnu/kegiatan/'.$this->event->id.'/scan', ['qr_token' => $token ?? $this->token]);
    }

    private function finish(): void
    {
        $this->travelTo(now()->addHours(4));
    }

    public function test_mobile_login_and_redirect(): void
    {
        $this->post('/mobile/login', ['email' => $this->member->email, 'password' => 'Password1!'])->assertRedirect('/mobile/bpppmnu/presensi');
        $this->assertAuthenticatedAs($this->member);
    }

    public function test_alias_domain_login_and_redirect(): void
    {
        $this->post('https://presensi.nuist.id/mobile/login', ['email' => $this->member->email, 'password' => 'Password1!'])->assertRedirect('/mobile/bpppmnu/presensi');
    }

    public function test_teacher_login_is_unchanged(): void
    {
        $teacher = $this->user('tenaga_pendidik');
        $this->post('/mobile/login', ['email' => $teacher->email, 'password' => 'Password1!'])->assertRedirect('/mobile/dashboard');
    }

    public function test_api_login_and_safe_payload(): void
    {
        $this->postJson('/api/mobile/login', ['identifier' => $this->member->email, 'password' => 'Password1!', 'login_as' => 'pengurus_bpppmnu'])
            ->assertOk()->assertJsonPath('mobile_route', '/mobile/bpppmnu/presensi')->assertJsonMissingPath('user.password')->assertJsonStructure(['token']);
    }

    public function test_roles_cannot_access_module_without_permission(): void
    {
        foreach (['tenaga_pendidik', 'pengurus', 'admin', 'super_admin', 'siswa', 'dps'] as $role) {
            $user = $this->user($role);
            $this->actingAs($user)->getJson('/mobile/bpppmnu/presensi')->assertForbidden();
            $this->getJson('/admin-yayasan/bpppmnu/kegiatan')->assertForbidden();
        }
        $this->actingAs($this->admin)->getJson('/mobile/bpppmnu/presensi')->assertForbidden();
    }

    public function test_new_role_cannot_access_old_pengurus_or_teacher_endpoints(): void
    {
        $this->actingAs($this->member)->getJson('/mobile/pengurus/dashboard')->assertForbidden();
        $this->getJson('/api/mobile/app/teacher/profile')->assertForbidden();
        $this->getJson('/api/mobile/app/pengurus/dashboard')->assertForbidden();
        $this->getJson('/admin-yayasan/bpppmnu/kegiatan')->assertForbidden();
    }

    public function test_only_invited_published_events_are_visible_and_idor_is_blocked(): void
    {
        $other = $this->event->replicate();
        $other->name = 'Rahasia';
        $other->save();
        $draft = $this->event->replicate();
        $draft->status = 'draft';
        $draft->save();
        $draft->invitations()->create(['user_id' => $this->member->id]);
        $this->actingAs($this->member)->getJson('/mobile/bpppmnu/presensi')->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.id', $this->event->id);
        $this->getJson('/mobile/bpppmnu/kegiatan/'.$other->id)->assertNotFound();
        $this->getJson('/mobile/bpppmnu/kegiatan/'.$draft->id)->assertNotFound();
    }

    public function test_uninvited_scan_is_rejected(): void
    {
        $this->member = $this->user('pengurus_bpppmnu');
        $this->scan()->assertUnprocessable()->assertJsonPath('message', 'Anda tidak terdaftar sebagai peserta kegiatan ini.');
    }

    public function test_valid_qr_uses_server_time_and_is_idempotent(): void
    {
        $this->scan()->assertOk()->assertJsonPath('duplicate', false);
        $this->scan()->assertOk()->assertJsonPath('duplicate', true)->assertJsonPath('message', 'Anda sudah melakukan presensi pada kegiatan ini.');
        $this->assertDatabaseCount('bpppmnu_event_attendances', 1);
        $this->assertSame(now()->format('Y-m-d H:i:s'), BpppmnuEventAttendance::first()->attended_at->format('Y-m-d H:i:s'));
    }

    public function test_invalid_qr_is_rejected(): void
    {
        $this->scan(str_repeat('a', 64))->assertUnprocessable()->assertJsonPath('message', BpppmnuAttendanceService::INVALID_QR);
    }

    public function test_revoked_qr_is_rejected(): void
    {
        $this->event->qrTokens()->update(['revoked_at' => now()]);
        $this->scan()->assertUnprocessable()->assertJsonPath('message', BpppmnuAttendanceService::INVALID_QR);
    }

    public function test_expired_qr_is_rejected(): void
    {
        $this->event->qrTokens()->update(['expires_at' => now()->subSecond()]);
        $this->scan()->assertUnprocessable()->assertJsonPath('message', BpppmnuAttendanceService::INVALID_QR);
    }

    public function test_before_open_is_rejected(): void
    {
        $this->travelTo(now()->subHours(2));
        $this->scan()->assertUnprocessable()->assertJsonPath('message', 'Presensi kegiatan belum dibuka.');
    }

    public function test_after_close_is_rejected(): void
    {
        $this->event->qrTokens()->update(['expires_at' => now()->addDays(2)]);
        $this->finish();
        $this->scan()->assertUnprocessable()->assertJsonPath('message', 'Waktu presensi kegiatan telah berakhir.');
    }

    public function test_cancelled_event_cannot_be_scanned(): void
    {
        $this->event->update(['status' => 'cancelled']);
        $this->scan()->assertUnprocessable()->assertJsonPath('message', 'Kegiatan telah dibatalkan.');
    }

    public function test_upcoming_and_open_events_are_not_absences(): void
    {
        $this->actingAs($this->member)->getJson('/mobile/bpppmnu/riwayat-presensi')->assertJsonCount(0, 'data');
        $this->travelTo(now()->addHours(2)->addMinute());
        $this->getJson('/mobile/bpppmnu/riwayat-presensi')->assertJsonCount(0, 'data');
        $this->assertSame('Belum Presensi', app(BpppmnuReportService::class)->recap($this->event)['rows'][0]->status);
    }

    public function test_finished_invitation_without_attendance_appears_as_absent(): void
    {
        $this->finish();
        $this->actingAs($this->member)->getJson('/mobile/bpppmnu/riwayat-presensi?status=tidak_hadir')->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.attended_at', null);
        $this->assertSame('Tidak Hadir', app(BpppmnuReportService::class)->recap($this->event)['rows'][0]->status);
    }

    public function test_finished_attended_event_and_history_filters(): void
    {
        $this->scan()->assertOk();
        $this->finish();
        $this->getJson('/mobile/bpppmnu/riwayat-presensi?status=hadir&month=9&year=2026')->assertJsonCount(1, 'data');
        foreach (['status=tidak_hadir', 'month=8', 'year=2025'] as $filter) {
            $this->getJson('/mobile/bpppmnu/riwayat-presensi?'.$filter)->assertJsonCount(0, 'data');
        }
        $this->getJson('/mobile/bpppmnu/riwayat-presensi?month=13')->assertUnprocessable();
        $this->assertSame('Hadir', app(BpppmnuReportService::class)->recap($this->event)['rows'][0]->status);
    }

    public function test_cancelled_events_are_not_absences(): void
    {
        $this->event->update(['status' => 'cancelled']);
        $this->finish();
        $this->actingAs($this->member)->getJson('/mobile/bpppmnu/riwayat-presensi')->assertJsonCount(0, 'data');
    }

    public function test_password_change_checks_old_password_and_confirmation(): void
    {
        $this->actingAs($this->member)->postJson('/mobile/bpppmnu/profil/password', ['current_password' => 'wrong', 'password' => 'NewPassword2!', 'password_confirmation' => 'NewPassword2!'])->assertUnprocessable();
        $this->postJson('/mobile/bpppmnu/profil/password', ['current_password' => 'Password1!', 'password' => 'NewPassword2!', 'password_confirmation' => 'NewPassword2!'])->assertOk();
        $this->assertTrue(Hash::check('NewPassword2!', $this->member->fresh()->password));
    }

    public function test_recap_is_based_on_invitations(): void
    {
        $second = $this->user('pengurus_bpppmnu');
        $this->event->invitations()->create(['user_id' => $second->id]);
        $this->scan();
        $recap = app(BpppmnuReportService::class)->recap($this->event);
        $this->assertSame(2, $recap['total']);
        $this->assertSame(1, $recap['present']);
        $this->assertSame(1, $recap['remaining']);
        $this->assertEquals(50, $recap['percentage']);
    }

    public function test_database_unique_constraints_enforce_integrity(): void
    {
        $this->scan();
        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->event->attendances()->create(['user_id' => $this->member->id, 'attended_at' => now(), 'method' => 'qr']);
    }

    public function test_invitation_unique_constraint(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->event->invitations()->create(['user_id' => $this->member->id]);
    }

    public function test_admin_cannot_edit_locked_agenda_or_cancel_recorded_attendance(): void
    {
        $this->actingAs($this->admin)->getJson('/admin-yayasan/bpppmnu/kegiatan/'.$this->event->id.'/edit')->assertForbidden();
        $this->scan();
        $this->actingAs($this->admin)->postJson('/admin-yayasan/bpppmnu/kegiatan/'.$this->event->id.'/cancel')->assertUnprocessable();
        $this->assertSame('published', $this->event->fresh()->status);
    }

    public function test_disabled_account_cannot_scan(): void
    {
        $this->member->update(['is_active' => false]);
        $this->scan()->assertForbidden();
    }

    public function test_api_bearer_access(): void
    {
        $token = $this->member->createToken('test')->plainTextToken;
        $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/mobile/app/bpppmnu/presensi')->assertOk();
    }

    public function test_private_attachment_requires_invitation(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('bpppmnu/invitations/test.pdf', 'test');
        $this->event->update(['attachment' => 'bpppmnu/invitations/test.pdf']);
        $this->actingAs($this->member)->get('/mobile/bpppmnu/kegiatan/'.$this->event->id.'/attachment')->assertDownload();
        $this->actingAs($this->user('pengurus_bpppmnu'))->get('/mobile/bpppmnu/kegiatan/'.$this->event->id.'/attachment')->assertNotFound();
    }

    public function test_issued_tokens_are_hashed_and_rotated(): void
    {
        $next = app(BpppmnuAttendanceService::class)->issue($this->event);
        $this->assertDatabaseHas('bpppmnu_event_qr_tokens', ['token_hash' => hash('sha256', $next)]);
        $this->assertDatabaseMissing('bpppmnu_event_qr_tokens', ['token_hash' => $next]);
        $this->scan()->assertUnprocessable();
        $this->scan($next)->assertOk();
    }

    public function test_all_mobile_pages_render_with_three_menu_items(): void
    {
        foreach (['presensi', 'riwayat-presensi', 'profil', 'kegiatan/'.$this->event->id] as $path) {
            $response = $this->actingAs($this->member)->get('/mobile/bpppmnu/'.$path)->assertOk()->assertSee('Riwayat Presensi')->assertSee('Pengurus BPPPMNU');
            $doc = new \DOMDocument;
            @$doc->loadHTML($response->getContent());
            $this->assertSame(3, (new \DOMXPath($doc))->query('//nav[@aria-label="Menu utama"]//a')->length);
        }
    }

    public function test_admin_pages_and_qr_render_and_export_downloads(): void
    {
        $this->actingAs($this->admin);
        foreach (['kegiatan', 'pengurus', 'kegiatan/create', 'kegiatan/'.$this->event->id] as $path) {
            $this->get('/admin-yayasan/bpppmnu/'.$path)->assertOk();
        }
        $this->post('/admin-yayasan/bpppmnu/kegiatan/'.$this->event->id.'/qr')->assertOk()->assertSee('<svg', false);
        $this->get('/admin-yayasan/bpppmnu/kegiatan/'.$this->event->id.'/export')->assertDownload();
    }

    private function agendaData(): array
    {
        return ['name' => 'Agenda Baru', 'type' => 'Rapat', 'description' => 'Deskripsi lengkap', 'organizer' => 'Yayasan', 'location_name' => 'Aula',
            'start_at' => now()->addDay()->format('Y-m-d H:i:s'), 'end_at' => now()->addDay()->addHours(2)->format('Y-m-d H:i:s'),
            'attendance_open_at' => now()->addDay()->subHour()->format('Y-m-d H:i:s'), 'attendance_close_at' => now()->addDay()->addHours(2)->format('Y-m-d H:i:s'),
            'invitees' => [$this->member->id]];
    }

    public function test_admin_creates_updates_publishes_and_cancels_draft(): void
    {
        $this->actingAs($this->admin)->post('/admin-yayasan/bpppmnu/kegiatan', $this->agendaData())->assertRedirect();
        $event = BpppmnuEvent::where('name', 'Agenda Baru')->firstOrFail();
        $this->assertSame('draft', $event->status);
        $this->assertEquals([$this->member->id], $event->invitations()->pluck('user_id')->all());
        $data = $this->agendaData();
        $data['name'] = 'Revisi';
        $this->put('/admin-yayasan/bpppmnu/kegiatan/'.$event->id, $data)->assertRedirect();
        $this->assertSame('Revisi', $event->fresh()->name);
        $this->post('/admin-yayasan/bpppmnu/kegiatan/'.$event->id.'/publish')->assertRedirect();
        $this->assertSame('published', $event->fresh()->status);
        $this->post('/admin-yayasan/bpppmnu/kegiatan/'.$event->id.'/cancel')->assertRedirect();
        $this->assertSame('cancelled', $event->fresh()->status);
    }

    public function test_admin_cannot_invite_other_roles_or_upload_unsafe_files(): void
    {
        $data = $this->agendaData();
        $data['invitees'] = [$this->admin->id];
        $this->actingAs($this->admin)->postJson('/admin-yayasan/bpppmnu/kegiatan', $data)->assertUnprocessable();
        $data = $this->agendaData();
        $data['attachment'] = UploadedFile::fake()->create('script.php', 1, 'application/x-php');
        $this->postJson('/admin-yayasan/bpppmnu/kegiatan', $data)->assertUnprocessable();
    }

    public function test_locked_agenda_rejects_update_and_preserves_invitations(): void
    {
        $this->actingAs($this->admin)->putJson('/admin-yayasan/bpppmnu/kegiatan/'.$this->event->id, $this->agendaData())->assertUnprocessable();
        $this->assertSame('Rapat BPPPMNU', $this->event->fresh()->name);
        $this->assertSame(1, $this->event->invitations()->count());
    }

    public function test_admin_provisions_member_and_cannot_change_other_role(): void
    {
        $data = ['name' => 'Pengurus Baru', 'email' => 'new@example.test', 'is_active' => 1, 'password' => 'NewPassword1!', 'password_confirmation' => 'NewPassword1!', 'role' => 'super_admin'];
        $this->actingAs($this->admin)->post('/admin-yayasan/bpppmnu/pengurus', $data)->assertRedirect();
        $member = User::where('email', 'new@example.test')->firstOrFail();
        $this->assertSame('pengurus_bpppmnu', $member->role);
        $this->assertTrue(Hash::check('NewPassword1!', $member->password));
        $this->putJson('/admin-yayasan/bpppmnu/pengurus/'.$this->admin->id, $data)->assertNotFound();
    }

    public function test_guests_are_redirected_to_mobile_login(): void
    {
        $this->get('/mobile/bpppmnu/presensi')->assertRedirect('/mobile/login');
        $this->getJson('/api/mobile/app/bpppmnu/presensi')->assertUnauthorized();
    }

    public function test_bearer_account_cannot_access_unscoped_legacy_dashboard(): void
    {
        $token = $this->member->createToken('test')->plainTextToken;
        $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/mobile/dashboard')->assertForbidden();
    }

    public function test_alias_domain_can_render_presensi_and_logout(): void
    {
        $this->actingAs($this->member)->get('https://presensi.nuist.id/mobile/bpppmnu/presensi')->assertOk();
        $this->post('https://presensi.nuist.id/mobile/bpppmnu/logout')->assertRedirect('/mobile/login');
        $this->assertGuest();
    }

    public function test_web_scan_keeps_csrf_protection(): void
    {
        $this->app['env'] = 'local';
        $this->scan()->assertStatus(419);
        $csrf = str_repeat('s', 40);
        $this->actingAs($this->member)->withSession(['_token' => $csrf])
            ->postJson('/mobile/bpppmnu/kegiatan/'.$this->event->id.'/scan', ['qr_token' => $this->token], ['X-CSRF-TOKEN' => $csrf])->assertOk();
    }

    public function test_export_preserves_ids_and_prevents_spreadsheet_formulas(): void
    {
        $rows = collect([(object) ['name' => '=1+1', 'nuist_id' => '000123', 'jabatan' => 'Ketua', 'status' => 'Hadir', 'attended_at' => '2026-09-11 09:00:00']]);
        $bytes = \Maatwebsite\Excel\Facades\Excel::raw(new \App\Exports\BpppmnuAttendanceExport($rows), \Maatwebsite\Excel\Excel::XLSX);
        $file = tempnam(sys_get_temp_dir(), 'bpp-export');
        try {
            file_put_contents($file, $bytes);
            $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file)->getActiveSheet();
            $this->assertSame('000123', $sheet->getCell('B2')->getValue());
            $this->assertSame('s', $sheet->getCell('A2')->getDataType());
        } finally {
            unlink($file);
        }
    }
}
