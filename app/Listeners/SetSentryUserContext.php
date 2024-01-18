<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Login;
use function Sentry\configureScope;
use Sentry\State\Scope;
use Sentry\UserDataBag;

class SetSentryUserContext
{
    public function handle(Authenticated|Login $event): void
    {
        configureScope(function (Scope $scope) use ($event) {
            /** @var User $user */
            $user = $event->user;
            $scope->setUser(new UserDataBag(
                $user->getRouteKey(),
                ipAddress: request()->ip(),
            ));
            $scope->setTag('guard', $event->guard);
            $scope->setTag('role', $user->role->value);
        });
    }
}
