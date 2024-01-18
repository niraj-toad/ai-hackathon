<?php

declare(strict_types=1);

namespace Tests;

use Laravel\Dusk\Browser;

trait DeleteCookies
{
    public function setUpDeleteCookies(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->driver->manage()->deleteAllCookies();
        });
    }
}
