<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Enums\Role;
use App\Models\User;
use Database\Factories\UserFactory;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\DeleteCookies;
use Tests\DuskTestCase;

class StaffMainTest extends DuskTestCase
{
    use DeleteCookies;

    #[DataProvider('roleProvider')]
    public function testStaffMainOnlyPermitsAccessWithPermission(
        UserFactory $factory,
        bool $shouldHaveAccess,
    ): void {
        $this->browse(function (Browser $browser) use ($factory, $shouldHaveAccess) {
            $user = $factory->createOne();

            $browser->loginAs($user);
            $browser->visit('/staff');

            $browser->waitForText($shouldHaveAccess ? 'Staff Section' : 'You do not have the necessary permissions.');
        });
    }

    public static function roleProvider(): array
    {
        return [
            Role::Admin->value => [
                'factory' => User::factory()->role(Role::Admin),
                'shouldHaveAccess' => true,
            ],
            Role::User->value => [
                'factory' => User::factory()->role(Role::User),
                'shouldHaveAccess' => false,
            ],
        ];
    }
}
