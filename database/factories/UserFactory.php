<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    public $model = User::class;

    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'password' => 'password',
            'remember_token' => Str::random(10),
            'role' => Role::User,
        ];
    }

    public function email(string $email): static
    {
        return $this->set('email', $email);
    }

    public function name(string $first, string $last): static
    {
        return $this->set('first_name', $first)
            ->set('last_name', $last);
    }

    public function password(string $password): static
    {
        return $this->set('password', Hash::make($password));
    }

    public function role(Role $role): static
    {
        return $this->set('role', $role);
    }

    public function unverified(): static
    {
        return $this->set('email_verified_at', null);
    }
}
