<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Auth\Pages\Login;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_the_login_page_is_displayed(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_a_user_can_sign_in_with_valid_credentials(): void
    {
        $user = User::factory()->create();

        Livewire::test(Login::class)
            ->fillForm([
                'email' => $user->email,
                'password' => 'password',
            ])
            ->call('authenticate')
            ->assertRedirect(Filament::getUrl());

        $this->assertAuthenticatedAs($user);
    }

    public function test_a_user_cannot_sign_in_with_a_wrong_password(): void
    {
        $user = User::factory()->create();

        Livewire::test(Login::class)
            ->fillForm([
                'email' => $user->email,
                'password' => 'wrong-password',
            ])
            ->call('authenticate')
            ->assertHasFormErrors(['email']);

        $this->assertGuest();
    }

    public function test_an_authenticated_user_sees_the_dashboard(): void
    {
        $this->actingAs(User::factory()->technician()->create())
            ->get('/admin')
            ->assertOk();
    }

    public function test_the_seeder_creates_users_for_every_role(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(count(DatabaseSeeder::USERS), User::count());

        foreach (UserRole::cases() as $role) {
            $this->assertTrue(User::where('role', $role)->exists(), "No user with role {$role->value}");
        }
    }
}
