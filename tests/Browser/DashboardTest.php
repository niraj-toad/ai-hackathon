<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Enums\Role;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\UserMenu;
use Tests\DeleteCookies;
use Tests\DuskTestCase;

class DashboardTest extends DuskTestCase
{
    use DeleteCookies;

    public function testDashboardLayoutForUser(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()
                ->password('another-mock-password')
                ->role(Role::User)
                ->createOne();

            $browser->loginAs($user);
            $browser->visit('/')
                ->waitForLocation('/');

            $userMenu = new UserMenu($browser);
            $userMenu->waitFor();

            $browser->assertSee('Home');

            $userMenu->open();
            $userMenu->seeLogout();
        });
    }

    public function testDashboardLayoutForAdmin(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()
                ->password('another-mock-password')
                ->role(Role::Admin)
                ->createOne();

            $browser->loginAs($user);
            $browser->visit('/staff')
                ->waitForLocation('/staff');

            $userMenu = new UserMenu($browser);
            $userMenu->waitFor();

            $browser->assertSee('Home');

            $userMenu->open();
            $userMenu->seeLogout();
        });
    }

    public function testCustomerDashboardLayoutForAdmin(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()
                ->password('another-mock-password')
                ->role(Role::Admin)
                ->createOne();

            $browser->loginAs($user);
            $browser->visit('/')
                ->waitForLocation('/');

            $userMenu = new UserMenu($browser);
            $userMenu->waitFor();

            $browser->assertSee('Home');

            $userMenu->open();
            $userMenu->clickToStaffPanel();

            $browser->waitForLocation('/staff');
        });
    }
}
