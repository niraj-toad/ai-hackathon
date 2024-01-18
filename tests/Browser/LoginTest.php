<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Enums\Role;
use App\Models\User;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Browser\Components\LoginForm;
use Tests\Browser\Components\UserMenu;
use Tests\DeleteCookies;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DeleteCookies;

    #[DataProvider('loginRoleUrlProvider')]
    public function testLoginRedirectsSuccessfullyAndShowsAuthenticatedUserInMenu(
        Role $role,
        string $startUrl,
        string $expectedUrl,
        string $expectedText
    ): void {
        $this->browse(function (Browser $browser) use ($expectedText, $role, $startUrl, $expectedUrl) {
            $user = User::factory()
                ->password('another-mock-password')
                ->role($role)
                ->createOne();

            $browser->visit($startUrl);

            $loginForm = new LoginForm($browser);
            $loginForm->waitFor();
            $loginForm->fillEmail($user->email);
            $loginForm->fillPassword('another-mock-password');
            $loginForm->submit();

            $browser->waitForLocation($expectedUrl);
            $browser->waitForText($expectedText);

            $browser->assertSee($expectedText);
            $browser->assertAuthenticatedAs($user);

            (new UserMenu($browser))
                ->waitFor()
                ->open()
                ->seeAuthenticatedAs($user);
        });
    }

    public static function loginRoleUrlProvider(): array
    {
        return [
            Role::User->value.' starting at login' => [
                'role' => Role::User,
                'startUrl' => '/login',
                'expectedUrl' => '/',
                'expectedText' => 'Laravel Template',
            ],
        ];
    }
}
