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
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'no_hp' => '08123456789',
        ]);

        $response = $this->actingAs($user)
            ->put(route('mobile.profile.update-account'), [
                'name' => 'New Name',
                'email' => 'new@example.com',
                'phone' => '08198765432',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-01',
                'alamat' => 'Jl. Sudirman',
                'pendidikan_terakhir' => 'S1',
                'program_studi' => 'Teknik Informatika',
            ]);

        $response->assertRedirect(route('mobile.pengaturan'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertEquals('08198765432', $user->no_hp);
        $this->assertEquals('Jakarta', $user->tempat_lahir);
        $this->assertEquals('1990-01-01', $user->tanggal_lahir->format('Y-m-d'));
        $this->assertEquals('Jl. Sudirman', $user->alamat);
        $this->assertEquals('S1', $user->pendidikan_terakhir);
        $this->assertEquals('Teknik Informatika', $user->program_studi);
    }

    public function test_user_cannot_update_to_existing_email()
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        $response = $this->actingAs($user1)
            ->put(route('mobile.profile.update-account'), [
                'name' => 'User1',
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
                'name' => 'User1',
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
            'name' => 'Same Name',
            'email' => 'same@example.com',
            'no_hp' => '08123456789',
        ]);

        $response = $this->actingAs($user)
            ->put(route('mobile.profile.update-account'), [
                'name' => 'Same Name', // same name
                'email' => 'same@example.com', // same email
                'phone' => '08123456789', // same phone
            ]);

        $response->assertRedirect(route('mobile.pengaturan'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('Same Name', $user->name);
        $this->assertEquals('same@example.com', $user->email);
        $this->assertEquals('08123456789', $user->no_hp);
    }
}
