<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Prevent real requests from going out due to incomplete mocks. To allow stray requests
        // for a specific test call `Http::allowStrayRequests()` in the test.
        Http::preventStrayRequests();
    }

    public function actAs(User|UserFactory $user): User
    {
        $user = $user instanceof UserFactory ? $user->createOne() : $user;

        $this->actingAs($user);

        return $user;
    }
}
