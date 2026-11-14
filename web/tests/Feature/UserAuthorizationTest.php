<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class UserAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_administrator_can_manage_users(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create();

        $gate = Gate::forUser($admin);

        $this->assertTrue($gate->allows('viewAny', User::class));
        $this->assertTrue($gate->allows('view', $target));
        $this->assertTrue($gate->allows('create', User::class));
        $this->assertTrue($gate->allows('update', $target));
        $this->assertTrue($gate->allows('delete', $target));
    }

    public function test_technicians_and_requesters_cannot_manage_users(): void
    {
        $target = User::factory()->create();
        $users = [
            User::factory()->technician()->create(),
            User::factory()->create(),
        ];

        foreach ($users as $user) {
            $gate = Gate::forUser($user);

            $this->assertFalse($gate->allows('viewAny', User::class));
            $this->assertFalse($gate->allows('view', $target));
            $this->assertFalse($gate->allows('create', User::class));
            $this->assertFalse($gate->allows('update', $target));
            $this->assertFalse($gate->allows('delete', $target));
        }
    }
}
