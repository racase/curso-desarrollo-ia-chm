<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\UserManagementService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UserManagementServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_administrator_can_create_update_and_delete_a_user(): void
    {
        $admin = User::factory()->admin()->create();
        $service = app(UserManagementService::class);
        $user = $service->create($admin, [
            'name' => 'María Técnica',
            'email' => 'maria@example.com',
            'password' => 'secret-password',
            'role' => UserRole::Requester->value,
        ]);

        $this->assertTrue(Hash::check('secret-password', $user->password));
        $originalPassword = $user->password;

        $service->update($admin, $user, [
            'name' => 'María Actualizada',
            'email' => 'maria.updated@example.com',
            'role' => UserRole::Technician->value,
            'password' => '',
        ]);

        $user->refresh();
        $this->assertSame('María Actualizada', $user->name);
        $this->assertSame('maria.updated@example.com', $user->email);
        $this->assertSame(UserRole::Technician, $user->role);
        $this->assertSame($originalPassword, $user->password);

        $service->delete($admin, $user);

        $this->assertSoftDeleted($user);
    }

    public function test_the_service_enforces_policy_authorization(): void
    {
        $requester = User::factory()->create();

        $this->expectException(AuthorizationException::class);

        app(UserManagementService::class)->create($requester, [
            'name' => 'Usuario no autorizado',
            'email' => 'blocked@example.com',
            'password' => 'secret-password',
            'role' => UserRole::Requester->value,
        ]);
    }

    public function test_the_last_administrator_cannot_be_demoted(): void
    {
        $admin = User::factory()->admin()->create();

        try {
            app(UserManagementService::class)->update($admin, $admin, [
                'role' => UserRole::Technician->value,
            ]);
            $this->fail('The last administrator was demoted.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('role', $exception->errors());
        }

        $this->assertSame(UserRole::Admin, $admin->refresh()->role);
    }

    public function test_the_last_administrator_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();

        try {
            app(UserManagementService::class)->delete($admin, $admin);
            $this->fail('The last administrator was deleted.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('delete', $exception->errors());
        }

        $this->assertNotSoftDeleted($admin);
    }

    public function test_an_administrator_can_be_demoted_or_deleted_when_another_remains(): void
    {
        $actor = User::factory()->admin()->create();
        $adminToDemote = User::factory()->admin()->create();
        $service = app(UserManagementService::class);

        $service->update($actor, $adminToDemote, [
            'role' => UserRole::Technician->value,
        ]);

        $this->assertSame(UserRole::Technician, $adminToDemote->refresh()->role);

        $adminToDelete = User::factory()->admin()->create();
        $service->delete($actor, $adminToDelete);

        $this->assertSoftDeleted($adminToDelete);
    }
}
