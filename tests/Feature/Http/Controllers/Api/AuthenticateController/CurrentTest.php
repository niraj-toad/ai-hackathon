<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Api\AuthenticateController;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CurrentTest extends TestCase
{
    public function testCurrentWhenUnauthenticated(): void
    {
        // Arrange
        // Nothing to arrange.

        // Act
        $response = $this->performAction();

        // Assert
        $response->assertUnauthorized();
    }

    public function testCurrentWhenAuthenticated(): void
    {
        // Arrange
        $user = $this->actAs(User::factory());

        // Act
        $response = $this->performAction();

        // Assert
        $response->assertOk();
        $response->assertJson(UserResource::make($user)->toJsonAssertion());
    }

    private function performAction(): TestResponse
    {
        return $this->getJson(route('api.auth.current'));
    }
}
