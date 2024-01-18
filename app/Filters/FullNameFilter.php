<?php

declare(strict_types=1);

namespace App\Filters;

use App\Models\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * @implements Filter<Model>
 */
class FullNameFilter implements Filter
{
    /**
     * @param string $value
     */
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        $query->where('first_name', 'like', "%{$value}%")
            ->orWhere('last_name', 'like', "%{$value}%")
            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$value}%"]);
    }
}
