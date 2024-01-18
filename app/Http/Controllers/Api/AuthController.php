<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function current(Request $request): JsonResponse
    {
        return UserResource::make($request->user())
            ->format(UserResource::AUTHENTICATED)
            ->response();
    }
}
