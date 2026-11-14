<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class CatalogManagementService
{
    /** @param array<string, mixed> $data */
    public function createSite(User $actor, array $data): Site
    {
        Gate::forUser($actor)->authorize('create', Site::class);

        return DB::transaction(fn (): Site => Site::query()->create($data));
    }

    /** @param array<string, mixed> $data */
    public function updateSite(User $actor, Site $site, array $data): Site
    {
        Gate::forUser($actor)->authorize('update', $site);

        return DB::transaction(function () use ($site, $data): Site {
            $site = Site::query()->lockForUpdate()->findOrFail($site->getKey());
            $site->update($data);

            return $site;
        });
    }

    public function deleteSite(User $actor, Site $site): void
    {
        Gate::forUser($actor)->authorize('delete', $site);

        DB::transaction(function () use ($site): void {
            $site = Site::query()->lockForUpdate()->findOrFail($site->getKey());

            if ($site->assets()->exists()) {
                throw ValidationException::withMessages([
                    'delete' => 'Antes de eliminar la sede debe retirar sus activos activos.',
                ]);
            }

            $site->delete();
        });
    }

    /** @param array<string, mixed> $data */
    public function createAsset(User $actor, array $data): Asset
    {
        Gate::forUser($actor)->authorize('create', Asset::class);

        return DB::transaction(function () use ($data): Asset {
            $data['site_id'] = $this->activeSiteId($data['site_id'] ?? null);

            return Asset::query()->create($data);
        });
    }

    /** @param array<string, mixed> $data */
    public function updateAsset(User $actor, Asset $asset, array $data): Asset
    {
        Gate::forUser($actor)->authorize('update', $asset);

        return DB::transaction(function () use ($asset, $data): Asset {
            $asset = Asset::query()->lockForUpdate()->findOrFail($asset->getKey());
            $data['site_id'] = $this->activeSiteId($data['site_id'] ?? $asset->site_id);
            $asset->update($data);

            return $asset;
        });
    }

    public function deleteAsset(User $actor, Asset $asset): void
    {
        Gate::forUser($actor)->authorize('delete', $asset);

        DB::transaction(function () use ($asset): void {
            Asset::query()->lockForUpdate()->findOrFail($asset->getKey())->delete();
        });
    }

    private function activeSiteId(mixed $siteId): int
    {
        $site = Site::query()->lockForUpdate()->find($siteId);

        if (! $site instanceof Site) {
            throw ValidationException::withMessages([
                'site_id' => 'La sede seleccionada no está disponible.',
            ]);
        }

        return (int) $site->getKey();
    }
}
