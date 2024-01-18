<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Http\Responses\LoginResponse;

class FortifyLoginResponse extends LoginResponse
{
    public function toResponse($request): JsonResponse
    {
        return UserResource::make($request->user())
            ->format(UserResource::AUTHENTICATED)
            ->toResponse($request);
    }
}
