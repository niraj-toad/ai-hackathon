<?php

namespace App\Actions\Traits;

trait ResolvesSelf
{
    public static function resolve(): static
    {
        return resolve(static::class);
    }
}
