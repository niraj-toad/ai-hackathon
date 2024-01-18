<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Enums\Role;
use App\Models\User;
use Database\Factories\UserFactory;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Browser\Components\UserMenu;
use Tests\DeleteCookies;
use Tests\DuskTestCase;

class LogoutTest extends DuskTestCase
{
    use DeleteCookies;

    #[DataProvider('roleProvider')]
    public function testLogsOutUserWhenRequested(UserFactory $factory, string $route): void
    {
        $this->browse(function (Browser $browser) use ($factory, $route) {
            $user = $factory->createOne();

            $browser->loginAs($user);
            $browser->visit($route)->waitForLocation($route);

            $userMenu = new UserMenu($browser);
            $userMenu->waitFor();
            $userMenu->open();
            $userMenu->clickLogout();

            $browser->waitForText('LOGIN');
            $browser->assertGuest();
        });
    }

    public static function roleProvider(): array
    {
        return [
            Role::Admin->value => [
                'factory' => User::factory()->role(Role::Admin),
                'route' => '/',
            ],
            Role::User->value.' at /' => [
                'factory' => User::factory()->role(Role::User),
                'route' => '/',
            ],
            Role::Admin->value.' at /staff' => [
                'factory' => User::factory()->role(Role::Admin),
                'route' => '/staff',
            ],
        ];
    }
}
