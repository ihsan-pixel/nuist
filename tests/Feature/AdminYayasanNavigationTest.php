<?php

namespace Tests\Feature;

use App\Http\Middleware\RoleMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class AdminYayasanNavigationTest extends TestCase
{
    public function test_foundation_sidebar_contains_only_requested_sections(): void
    {
        $this->actingAs(new User(['role' => 'admin_yayasan']));
        // Isolate navigation rendering from the application settings database composer.
        $this->app['events']->forget('composing: *');
        $html = view('layouts.sidebar')->render();
        preg_match_all('/<span[^>]*>(.*?)<\/span>/s', $html, $matches);

        $this->assertSame([
            'Dashboard', 'Profile Madrasah/Sekolah', 'Kalender Akademik',
            'SK Yayasan', 'Progress Mengajar', 'Presensi Admin', 'Monitoring Riset MGMP',
        ], $matches[1]);
        $this->assertStringNotContainsString('Pendataan GTK', $html);
        $this->assertStringNotContainsString('Kegiatan BPPPMNU', $html);
    }

    public function test_requested_module_routes_accept_foundation_admin(): void
    {
        $this->actingAs(new User(['role' => 'admin_yayasan']));
        $names = [
            'dashboard', 'madrasah.profile', 'madrasah.profile.export', 'madrasah.detail',
            'academic-calendar-events.index', 'academic-calendar-events.store',
            'picket-schedule-periods.index', 'picket-schedule-periods.store',
            'sk-yayasan.dashboard', 'sk-yayasan.numbers.index', 'sk-yayasan.pengajuan.index',
            'sk-yayasan.template.index', 'sk-yayasan.generate.index',
            'admin.teaching_progress', 'admin.teaching_progress.teachers',
            'presensi_admin.index', 'presensi_admin.settings', 'presensi_admin.laporan_mingguan',
            'admin.mgmp_reset_uploads',
        ];

        foreach ($names as $name) {
            $route = app('router')->getRoutes()->getByName($name);
            $this->assertNotNull($route, $name);
            foreach ($route->gatherMiddleware() as $middleware) {
                if ($middleware instanceof \Closure) {
                    $response = $middleware(Request::create('/'.$route->uri()), fn () => response('allowed'));
                    $this->assertSame(200, $response->getStatusCode(), $name);
                    continue;
                }
                if (!str_starts_with($middleware, 'role:')) {
                    continue;
                }
                $response = app(RoleMiddleware::class)->handle(
                    Request::create('/'.$route->uri()), fn () => response('allowed'),
                    ...explode(',', substr($middleware, 5))
                );
                $this->assertSame(200, $response->getStatusCode(), $name);
            }
        }
    }

    public function test_unrelated_super_admin_modules_remain_restricted(): void
    {
        $this->actingAs(new User(['role' => 'admin_yayasan']));
        foreach (['madrasah.store', 'fake-location.index', 'admin.create_mgmp_user'] as $name) {
            $route = app('router')->getRoutes()->getByName($name);
            $roleMiddleware = collect($route->gatherMiddleware())->first(fn ($item) => str_starts_with($item, 'role:'));
            $this->assertNotNull($roleMiddleware);
            $response = app(RoleMiddleware::class)->handle(
                Request::create('/'.$route->uri()), fn () => response('allowed'),
                ...explode(',', substr($roleMiddleware, 5))
            );
            $this->assertSame(302, $response->getStatusCode(), $name);
        }
    }
}
