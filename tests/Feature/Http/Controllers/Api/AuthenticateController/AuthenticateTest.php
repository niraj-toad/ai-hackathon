<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Api\AuthenticateController;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{
    /** @dataProvider validationProvider */
    public function testAuthenticateValidation(array $payload, array $expectedErrors): void
    {
        // Arrange
        // Nothing to arrange.

        // Act
        $response = $this->performAction($payload);

        // Assert
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors($expectedErrors);
    }

    public static function validationProvider(): array
    {
        return [
            'email is required' => [
                'payload' => [],
                'expectedErrors' => [
                    'email' => 'The email field is required.',
                ],
            ],
            'email must be a string' => [
                'payload' => [
                    'email' => 321,
                ],
                'expectedErrors' => [
                    'email' => 'The email must be a string',
                ],
            ],
            'password is required' => [
                'payload' => [],
                'expectedErrors' => [
                    'password' => 'The password field is required.',
                ],
            ],
            'password must be a string' => [
                'payload' => [
                    'password' => 123,
                ],
                'expectedErrors' => [
                    'password' => 'The password must be a string.',
                ],
            ],
        ];
    }

    public function testAuthenticateWithInvalidCredentials(): void
    {
        // Arrange
        User::factory()
            ->email('test@example.com')
            ->createOne();

        // Act
        $response = $this->performAction([
            'email' => 'test@example.com',
            'password' => 'testPassword',
        ]);

        // Assert
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    public function testAuthenticateWithValidCredentials(): void
    {
        // Arrange
        $user = User::factory()
            ->email('test@example.com')
            ->password('password')
            ->createOne();

        $userResource = UserResource::make($user)->format(UserResource::AUTHENTICATED);

        // Act
        $response = $this->performAction([
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // Assert
        $this->assertAuthenticatedAs($user);
        $response->assertOk();
        $response->assertJson($userResource->toJsonAssertion());
    }

    private function performAction(array $payload): TestResponse
    {
        return $this->postJson('fortify/login', $payload);
    }
}
