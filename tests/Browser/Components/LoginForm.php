<?php

declare(strict_types=1);

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;

readonly class LoginForm
{
    public function __construct(private Browser $browser)
    {
        //
    }

    public function waitFor(): void
    {
        $this->browser->waitFor('#login > form');
    }

    public function fillEmail(string $email): void
    {
        $this->browser->type('input[name="email"]', $email);
    }

    public function fillPassword(string $password): void
    {
        $this->browser->type('input[name="password"]', $password);
    }

    public function submit(): void
    {
        $this->browser->click('button[type="submit"]');
    }
}
