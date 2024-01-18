<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatMessageController;
use App\Http\Controllers\Api\ChatSessionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/auth', [AuthController::class, 'current'])
    ->middleware('auth:sanctum')
    ->name('api.auth.current');

Route::post('/chat-session', [ChatSessionController::class, 'create'])
    ->name('api.chat-session.create');
Route::post('/chat-session/{chatSession}/chat-message', [ChatMessageController::class, 'create'])
    ->name('api.chat-message.create');

Route::get('/users', [UserController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('api.users.index');
