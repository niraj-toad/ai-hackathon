<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @method static static create(array $attributes = [])
 * @method static static firstOrCreate(array $attributes, array $values = [])
 * @method static static firstOrNew(array $attributes, array $values = [])
 * @method static static firstWhere($column, $operator = null, $value = null, $boolean = 'and')
 * @method static static updateOrCreate(array $attributes, array $values = [])
 */
abstract class Model extends Eloquent
{
    //
}
