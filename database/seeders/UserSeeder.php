<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = [
            'email' => 'admin@example.com',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'password' => Hash::make('123456'),
            'role' => Role::Admin,
        ];

        $user = [
            'email' => 'user@example.com',
            'first_name' => 'User',
            'last_name' => 'User',
            'password' => Hash::make('123456'),
            'role' => Role::User,
        ];

        User::query()->upsert([$admin, $user], 'email');
    }
}
