<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_their_own_email_and_phone()
    {
        $user = User::factory()->create([
            'email' => 'old@example.com',
            'no_hp' => '08123456789',
        ]);

        $response = $this->actingAs($user)
            ->put(route('mobile.profile.update-account'), [
                'email' => 'new@example.com',
                'phone' => '08198765432',
            ]);

        $response->assertRedirect(route('mobile.pengaturan'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('new@example.com', $user->email);
        $this->assertEquals('08198765432', $user->no_hp);
    }

    public function test_user_cannot_update_to_existing_email()
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        $response = $this->actingAs($user1)
            ->put(route('mobile.profile.update-account'), [
                'email' => 'user2@example.com', // existing email
                'phone' => '08123456789',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');

        $user1->refresh();
        $this->assertEquals('user1@example.com', $user1->email); // should not change
    }

    public function test_user_cannot_update_to_existing_phone()
    {
        $user1 = User::factory()->create(['no_hp' => '08123456789']);
        $user2 = User::factory()->create(['no_hp' => '08198765432']);

        $response = $this->actingAs($user1)
            ->put(route('mobile.profile.update-account'), [
                'email' => 'new@example.com',
                'phone' => '08198765432', // existing phone
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('phone');

        $user1->refresh();
        $this->assertEquals('08123456789', $user1->no_hp); // should not change
    }

    public function test_user_can_keep_same_email_and_phone()
    {
        $user = User::factory()->create([
            'email' => 'same@example.com',
            'no_hp' => '08123456789',
        ]);

        $response = $this->actingAs($user)
            ->put(route('mobile.profile.update-account'), [
                'email' => 'same@example.com', // same email
                'phone' => '08123456789', // same phone
            ]);

        $response->assertRedirect(route('mobile.pengaturan'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('same@example.com', $user->email);
        $this->assertEquals('08123456789', $user->no_hp);
    }
}
