<?php

namespace App\Filament\Resources\Assets\Pages;

use App\Filament\Resources\Assets\AssetResource;
use App\Models\User;
use App\Services\CatalogManagementService;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateAsset extends CreateRecord
{
    protected static string $resource = AssetResource::class;

    /** @param array<string, mixed> $data */
    protected function handleRecordCreation(array $data): Model
    {
        try {
            return app(CatalogManagementService::class)->createAsset($this->user(), $data);
        } catch (ValidationException $exception) {
            throw ValidationException::withMessages([
                'data.site_id' => $exception->errors()['site_id'][0],
            ]);
        }
    }

    private function user(): User
    {
        $user = Filament::auth()->user();

        abort_unless($user instanceof User, 403);

        return $user;
    }
}
