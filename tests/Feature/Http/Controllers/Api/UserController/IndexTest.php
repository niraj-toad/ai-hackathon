<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Api\UserController;

use App\Enums\Role;
use App\Models\User;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public function testPagination(): void
    {
        $actingUser = $this->actAs(User::factory()->role(Role::Admin));

        User::factory()->count(10)
            ->create()
            ->push($actingUser)
            ->sortBy('created_at')
            ->values();

        $url = route('api.users.index', [
            'page' => 2,
            'per_page' => 5,
        ]);
        $response = $this->getJson($url);
        $response->assertOk();
        $response->assertJsonCount(5, 'data');
        $response->assertJsonFragment([
            'current_page' => 2,
            'per_page' => 5,
            'total' => 11,
        ]);
    }

    public function testSorting(): void
    {
        $actingUser = $this->actAs(User::factory()->role(Role::Admin));

        $users = User::factory()->count(10)
            ->create()
            ->push($actingUser)
            ->sortBy('email')
            ->values();

        $url = route('api.users.index', [
            'sort' => 'email',
        ]);
        $response = $this->getJson($url);
        $response->assertOk();

        foreach ($users as $i => $user) {
            /** @var User $user */
            $response->assertJsonPath("data.$i.id", $user->ulid);
        }
    }

    public function testFiltering(): void
    {
        $actingUser = $this->actAs(User::factory()->role(Role::Admin));

        $users = User::factory()->count(10)
            ->create()
            ->push($actingUser)
            ->sortBy('created_at')
            ->values();

        $url = route('api.users.index', [
            'filter' => [
                'email' => $users[5]->email,
            ],
        ]);

        $response = $this->getJson($url);

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $users[5]->ulid);
    }
}
