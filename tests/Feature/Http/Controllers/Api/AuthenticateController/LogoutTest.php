<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Api\AuthenticateController;

use App\Models\User;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    public function testLogoutWhenUnauthenticated(): void
    {
        // Arrange
        // Nothing to arrange.

        // Act
        $response = $this->performAction();

        // Assert
        $this->assertGuest();
        $response->assertNoContent();
    }

    public function testLogoutWhenAuthenticated(): void
    {
        // Arrange
        $this->actAs(User::factory());

        // Act
        $response = $this->performAction();

        // Assert
        $this->assertGuest();
        $response->assertNoContent();
    }

    private function performAction(): TestResponse
    {
        return $this->postJson('fortify/logout');
    }
}
