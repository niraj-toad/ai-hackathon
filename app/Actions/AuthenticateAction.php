<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\Credentials;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticateAction
{
    public function execute(Credentials $credentials, bool $remember): ?User
    {
        $success = Auth::attempt([
            'email' => $credentials->email,
            'password' => $credentials->password,
        ], $remember);

        if ($success) {
            session()->regenerate();
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Auth::user();
    }
}
