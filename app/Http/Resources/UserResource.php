<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;

/**
 * @property-read User $resource
 */
class UserResource extends Resource
{
    const AUTHENTICATED = 'authenticated';

    #[Format(self::AUTHENTICATED)]
    public function authenticated(): array
    {
        return [
            'created_at' => $this->resource->created_at?->toIso8601ZuluString(),
            'email' => $this->resource->email,
            'first_name' => $this->resource->first_name,
            'id' => $this->resource->getRouteKey(),
            'last_name' => $this->resource->last_name,
            'permissions' => $this->resource->permissions()->pluck('value')->all(),
            'role' => $this->resource->role->value,
            'updated_at' => $this->resource->updated_at?->toIso8601ZuluString(),
        ];
    }
}
