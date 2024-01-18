<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\TestJsonResponse;

class UserResourceTest extends TestCase
{
    public function testAuthenticatedFormat(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $resource = UserResource::make($user)->format(UserResource::AUTHENTICATED);

        // Act
        $response = new TestJsonResponse($resource->response());

        // Assert
        $response->assertWrappedJson(function (AssertableJson $json) use ($user) {
            $permissions = collect(config("roles.{$user->role->value}.permissions"))
                ->pluck('value')
                ->all();

            $json->where('created_at', $user->created_at?->toIso8601ZuluString())
                ->where('email', $user->email)
                ->where('first_name', $user->first_name)
                ->where('id', $user->ulid)
                ->where('last_name', $user->last_name)
                ->where('permissions', $permissions)
                ->where('role', $user->role->value)
                ->where('updated_at', $user->updated_at?->toIso8601ZuluString());
        });
    }
}
