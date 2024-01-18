<?php

declare(strict_types=1);

/**
 * @template T of object
 *
 * @param class-string<T> $class
 * @return T
 */
function make(string $class, array $params = []): object
{
    return resolve($class, $params);
}

function configString(string $key): string
{
    $value = config($key);
    if (!is_string($value)) {
        throw new InvalidArgumentException("Config value at $key is not a string");
    }

    return $value;
}

function configInt(string $key): int
{
    $value = config($key);
    if (is_string($value)) {
        $value = filter_var($value, FILTER_VALIDATE_INT);
    }
    if (!is_int($value)) {
        throw new InvalidArgumentException("Config value at $key is not an integer");
    }

    return $value;
}
