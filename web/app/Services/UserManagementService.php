<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class UserManagementService
{
    /** @param array<string, mixed> $data */
    public function create(User $actor, array $data): User
    {
        Gate::forUser($actor)->authorize('create', User::class);

        return DB::transaction(fn (): User => User::query()->create($data));
    }

    /** @param array<string, mixed> $data */
    public function update(User $actor, User $target, array $data): User
    {
        Gate::forUser($actor)->authorize('update', $target);

        return DB::transaction(function () use ($target, $data): User {
            $administratorCount = $this->lockAdministrators();
            $target = User::query()->lockForUpdate()->findOrFail($target->getKey());
            $newRole = $this->roleFrom($data['role'] ?? $target->role);

            if ($target->hasRole(UserRole::Admin) && $newRole !== UserRole::Admin) {
                $this->ensureAnotherAdministrator($administratorCount, 'role');
            }

            if (($data['password'] ?? null) === null || $data['password'] === '') {
                unset($data['password']);
            }

            $target->update($data);

            return $target;
        });
    }

    public function delete(User $actor, User $target): void
    {
        Gate::forUser($actor)->authorize('delete', $target);

        DB::transaction(function () use ($target): void {
            $administratorCount = $this->lockAdministrators();
            $target = User::query()->lockForUpdate()->findOrFail($target->getKey());

            if ($target->hasRole(UserRole::Admin)) {
                $this->ensureAnotherAdministrator($administratorCount, 'delete');
            }

            $target->delete();
        });
    }

    private function lockAdministrators(): int
    {
        return User::query()
            ->where('role', UserRole::Admin->value)
            ->orderBy('id')
            ->lockForUpdate()
            ->get(['id'])
            ->count();
    }

    private function ensureAnotherAdministrator(int $administratorCount, string $field): void
    {
        if ($administratorCount > 1) {
            return;
        }

        throw ValidationException::withMessages([
            $field => 'Debe permanecer al menos un administrador activo.',
        ]);
    }

    private function roleFrom(UserRole|string $role): UserRole
    {
        return $role instanceof UserRole ? $role : UserRole::from($role);
    }
}
