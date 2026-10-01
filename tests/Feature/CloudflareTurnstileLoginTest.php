<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CloudflareTurnstileLoginTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.turnstile.enabled', true);
        config()->set('services.turnstile.site_key', 'test-site-key');
        config()->set('services.turnstile.secret_key', 'test-secret-key');
        config()->set('services.turnstile.allowed_hostnames', ['localhost']);
    }

    public function test_login_page_displays_visible_turnstile_widget(): void
    {
        $template = file_get_contents(resource_path('views/auth/login.blade.php'));

        $this->assertIsString($template);
        $this->assertStringContainsString('https://challenges.cloudflare.com/turnstile/v0/api.js', $template);
        $this->assertStringContainsString('class="cf-turnstile"', $template);
        $this->assertStringContainsString('data-action="login"', $template);
    }

    public function test_mobile_login_page_contains_visible_turnstile_widget(): void
    {
        $template = file_get_contents(resource_path('views/mobile/login.blade.php'));

        $this->assertIsString($template);
        $this->assertStringContainsString('https://challenges.cloudflare.com/turnstile/v0/api.js', $template);
        $this->assertStringContainsString('class="cf-turnstile"', $template);
        $this->assertStringContainsString('data-action="login"', $template);
    }

    public function test_login_rejects_a_missing_turnstile_token(): void
    {
        $this->from('/login')->post('/login', [
            'email' => 'user@example.test',
            'password' => 'secret',
        ])->assertRedirect('/login')->assertSessionHasErrors('turnstile');

        Http::assertNothingSent();
    }

    public function test_mobile_login_rejects_a_missing_turnstile_token(): void
    {
        $this->from('/mobile/login')->post('/mobile/login', [
            'email' => 'user@example.test',
            'password' => 'secret',
        ])->assertRedirect('/mobile/login')->assertSessionHasErrors('turnstile');

        Http::assertNothingSent();
    }

    public function test_login_accepts_a_server_verified_turnstile_token(): void
    {
        Http::fake([
            'challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
                'success' => true,
                'hostname' => 'localhost',
                'action' => 'login',
                'error-codes' => [],
            ]),
        ]);

        $this->from('/login')->post('/login', [
            'cf-turnstile-response' => 'valid-turnstile-token',
        ])->assertRedirect('/login')->assertSessionHasErrors(['email', 'password']);

        Http::assertSent(fn ($request) => $request['secret'] === 'test-secret-key'
            && $request['response'] === 'valid-turnstile-token');
    }

    public function test_login_rejects_wrong_action_or_hostname(): void
    {
        Http::fake([
            'challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
                'success' => true,
                'hostname' => 'attacker.example',
                'action' => 'other-action',
                'error-codes' => [],
            ]),
        ]);

        $this->from('/login')->post('/login', [
            'email' => 'user@example.test',
            'password' => 'secret',
            'cf-turnstile-response' => 'wrong-context-token',
        ])->assertRedirect('/login')->assertSessionHasErrors('turnstile');
    }
}
