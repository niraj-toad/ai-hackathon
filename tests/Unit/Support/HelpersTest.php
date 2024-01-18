<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use Tests\TestCase;

class HelpersTest extends TestCase
{
    public function testConfigString(): void
    {
        $this->assertIsString(configString('app.name'));
    }

    public function testConfigInt(): void
    {
        $this->assertIsInt(configInt('auth.password_timeout'));
    }
}
