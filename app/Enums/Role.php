<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Collection;

/**
 * This enum has a counterpart in the frontend that is generated. If you update this,
 * you should run `php artisan build:ts-enum role`.
 */
enum Role: string
{
    case Admin = 'admin';
    case User = 'user';

    public function hasPermission(Permission $permission): bool
    {
        return $this->permissions()->contains($permission);
    }

    /** @return Collection<int, Permission> */
    public function permissions(): Collection
    {
        /** @var array<int, Permission> $permissions */
        $permissions = config("roles.{$this->value}.permissions");

        return collect($permissions);
    }
}
