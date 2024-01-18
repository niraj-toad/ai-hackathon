<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

/**
 * @method string getRouteKey()
 */
trait HasUlidRouteKey
{
    use HasUlids;

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    public function uniqueIds(): array
    {
        return ['ulid'];
    }
}
