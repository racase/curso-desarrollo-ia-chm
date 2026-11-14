<?php

namespace App\Rules;

use App\Models\Asset;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Translation\PotentiallyTranslatedString;

class AvailableAsset implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isAvailable = Asset::query()
            ->whereKey($value)
            ->whereHas(
                'site',
                fn (Builder $query): Builder => $query->whereNull('sites.deleted_at'),
            )
            ->exists();

        if (! $isAvailable) {
            $fail('El activo seleccionado no está disponible.');
        }
    }
}
