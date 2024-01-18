<?php

declare(strict_types=1);

namespace Tests\Browser\Components;

use App\Enums\Role;
use App\Models\User;
use Laravel\Dusk\Browser;

class UserMenu
{
    public function __construct(private readonly Browser $browser)
    {
        //
    }

    public function waitFor(): static
    {
        $this->browser->waitFor('.user button');

        return $this;
    }

    public function open(): static
    {
        $this->browser->click('.user button');
        $this->browser->waitFor('.user nav.collapsed:not(.enter-active)'); // Wait for the menu animation

        return $this;
    }

    public function seeLogout(): static
    {
        $this->browser->assertSee('LOGOUT');

        return $this;
    }

    public function clickLogout(): static
    {
        $this->seeLogout();
        $this->browser->click('.authenticated-as button');

        return $this;
    }

    public function seeToStaffPanel(): static
    {
        $this->browser->assertSee('GO TO STAFF PANEL');

        return $this;
    }

    public function clickToStaffPanel(): static
    {
        $this->seeToStaffPanel();
        $this->browser->clickAtXPath("//a[contains(.,'Go to Staff Panel')]");

        return $this;
    }

    public function seeAuthenticatedAs(User $user): static
    {
        $this->browser->assertSeeIn(
            '.user nav .authenticated-as',
            $user->first_name,
        );

        if ($user->role !== Role::User) {
            $this->browser->assertSeeIn(
                '.user nav .authenticated-as',
                str($user->role->value)->headline()->value(),
            );
        }

        return $this;
    }
}
