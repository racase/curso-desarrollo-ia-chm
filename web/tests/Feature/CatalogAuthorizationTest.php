<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class CatalogAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_administrator_can_manage_sites_and_assets(): void
    {
        $admin = User::factory()->admin()->create();
        $site = Site::factory()->create();
        $asset = Asset::factory()->for($site)->create();

        foreach ([[Site::class, $site], [Asset::class, $asset]] as [$model, $record]) {
            $gate = Gate::forUser($admin);

            $this->assertTrue($gate->allows('viewAny', $model));
            $this->assertTrue($gate->allows('view', $record));
            $this->assertTrue($gate->allows('create', $model));
            $this->assertTrue($gate->allows('update', $record));
            $this->assertTrue($gate->allows('delete', $record));
            $this->assertFalse($gate->allows('restore', $record));
            $this->assertFalse($gate->allows('forceDelete', $record));
        }
    }

    public function test_technicians_and_requesters_cannot_manage_sites_or_assets(): void
    {
        $site = Site::factory()->create();
        $asset = Asset::factory()->for($site)->create();

        foreach ([User::factory()->technician()->create(), User::factory()->create()] as $user) {
            foreach ([[Site::class, $site], [Asset::class, $asset]] as [$model, $record]) {
                $gate = Gate::forUser($user);

                $this->assertFalse($gate->allows('viewAny', $model));
                $this->assertFalse($gate->allows('view', $record));
                $this->assertFalse($gate->allows('create', $model));
                $this->assertFalse($gate->allows('update', $record));
                $this->assertFalse($gate->allows('delete', $record));
            }
        }
    }
}
