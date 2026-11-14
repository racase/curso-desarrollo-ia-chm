<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_an_administrator_can_open_every_user_management_page(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create();
        $this->actingAs($admin);

        $this->get(UserResource::getUrl('index'))->assertOk();
        $this->get(UserResource::getUrl('create'))->assertOk();
        $this->get(UserResource::getUrl('edit', ['record' => $target]))->assertOk();
    }

    public function test_technicians_and_requesters_cannot_open_user_management_pages(): void
    {
        $target = User::factory()->create();
        $users = [
            User::factory()->technician()->create(),
            User::factory()->create(),
        ];

        foreach ($users as $user) {
            $this->actingAs($user);

            $this->get(UserResource::getUrl('index'))->assertForbidden();
            $this->get(UserResource::getUrl('create'))->assertForbidden();
            $this->get(UserResource::getUrl('edit', ['record' => $target]))->assertForbidden();
        }
    }

    public function test_an_administrator_sees_user_management_in_navigation(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Usuarios');
    }

    public function test_technicians_and_requesters_do_not_see_user_management_in_navigation(): void
    {
        foreach ([User::factory()->technician()->create(), User::factory()->create()] as $user) {
            $this->actingAs($user)
                ->get('/admin')
                ->assertOk()
                ->assertDontSee('Usuarios');
        }
    }

    public function test_the_list_shows_active_users_with_name_email_and_spanish_role(): void
    {
        $admin = User::factory()->admin()->create();
        $technician = User::factory()->technician()->create([
            'name' => 'Técnica Teresa',
            'email' => 'teresa@example.com',
        ]);
        $deletedUser = User::factory()->create([
            'name' => 'Usuario eliminado',
            'email' => 'deleted@example.com',
        ]);
        $deletedUser->delete();
        $this->actingAs($admin);

        $component = Livewire::test(ListUsers::class)
            ->assertCanSeeTableRecords([$admin, $technician])
            ->assertCanNotSeeTableRecords([$deletedUser])
            ->assertSee('Técnica Teresa')
            ->assertSee('teresa@example.com')
            ->assertSee('Técnico');

        $this->assertSame([], $component->instance()->getTable()->getToolbarActions());
    }

    public function test_an_administrator_can_create_a_user_with_a_valid_role(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Laura Técnica',
                'email' => 'laura@example.com',
                'password' => 'secure-password',
                'password_confirmation' => 'secure-password',
                'role' => UserRole::Technician->value,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $user = User::query()->where('email', 'laura@example.com')->firstOrFail();

        $this->assertSame('Laura Técnica', $user->name);
        $this->assertSame(UserRole::Technician, $user->role);
        $this->assertTrue(Hash::check('secure-password', $user->password));
    }

    public function test_the_create_form_requires_name_email_password_confirmation_and_role(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => null,
                'email' => null,
                'password' => null,
                'password_confirmation' => null,
                'role' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'password_confirmation' => 'required',
                'role' => 'required',
            ]);
    }

    public function test_the_create_form_rejects_a_duplicate_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Correo repetido',
                'email' => 'existing@example.com',
                'password' => 'secure-password',
                'password_confirmation' => 'secure-password',
                'role' => UserRole::Requester->value,
            ])
            ->call('create')
            ->assertHasFormErrors(['email' => 'unique']);
    }

    public function test_the_create_form_rejects_an_unknown_role(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Rol inválido',
                'email' => 'invalid-role@example.com',
                'password' => 'secure-password',
                'password_confirmation' => 'secure-password',
                'role' => 'owner',
            ])
            ->call('create')
            ->assertHasFormErrors(['role']);

        $this->assertDatabaseMissing(User::class, [
            'email' => 'invalid-role@example.com',
        ]);
    }

    public function test_an_administrator_can_edit_a_user_without_changing_the_password(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $originalPassword = $user->password;
        $this->actingAs($admin);

        Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
            ->fillForm([
                'name' => 'Usuario actualizado',
                'email' => 'updated@example.com',
                'role' => UserRole::Technician->value,
                'password' => '',
                'password_confirmation' => '',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $user->refresh();

        $this->assertSame('Usuario actualizado', $user->name);
        $this->assertSame('updated@example.com', $user->email);
        $this->assertSame(UserRole::Technician, $user->role);
        $this->assertSame($originalPassword, $user->password);
    }

    public function test_an_administrator_can_change_a_users_password(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $this->actingAs($admin);

        Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
            ->fillForm([
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue(Hash::check('new-secure-password', $user->refresh()->password));
    }

    public function test_the_edit_form_rejects_a_duplicate_email(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $this->actingAs($admin);

        Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
            ->fillForm(['email' => $otherUser->email])
            ->call('save')
            ->assertHasFormErrors(['email' => 'unique']);
    }

    public function test_the_edit_form_rejects_demoting_the_last_administrator(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        Livewire::test(EditUser::class, ['record' => $admin->getRouteKey()])
            ->fillForm(['role' => UserRole::Technician->value])
            ->call('save')
            ->assertHasFormErrors(['role']);

        $this->assertSame(UserRole::Admin, $admin->refresh()->role);
    }

    public function test_an_administrator_can_soft_delete_a_user_from_the_list(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $this->actingAs($admin);

        Livewire::test(ListUsers::class)
            ->callAction(TestAction::make(DeleteAction::class)->table($user))
            ->assertNotified('Usuario eliminado')
            ->assertCanNotSeeTableRecords([$user]);

        $this->assertSoftDeleted($user);
    }

    public function test_the_last_administrator_cannot_be_deleted_from_the_list(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        Livewire::test(ListUsers::class)
            ->callAction(TestAction::make(DeleteAction::class)->table($admin))
            ->assertNotified('No se puede eliminar el usuario')
            ->assertCanSeeTableRecords([$admin]);

        $this->assertNotSoftDeleted($admin);
    }
}
