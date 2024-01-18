<?php

declare(strict_types=1);

namespace App\Data;

class Credentials
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
        //
    }
}
