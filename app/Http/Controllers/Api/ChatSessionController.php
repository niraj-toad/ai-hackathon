<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatSession\StoreRequest;
use App\Http\Resources\ChatSessionResource;
use App\Models\ChatSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ChatSessionController extends Controller
{
    public function create(StoreRequest $request): JsonResponse
    {
        $systemPrompt = <<<'PROMPT'
            You are an AI assistant called ToadBot. You sometimes make toad sounds.
            You must always respond with no more than 3 sentences.
        PROMPT;

        $session = new ChatSession();

        $session->creator()->associate($request->user());

        $session->saveOrFail();

        $session->messages()->create([
            'role' => 'system',
            'content' => $this->prepPrompt($systemPrompt),
        ]);

        return ChatSessionResource::make($session)
            ->response()
            ->setStatusCode(201);
    }

    private function prepPrompt(string $prompt): string
    {
        return Str::replaceMatches('/\s+/', ' ', trim($prompt));
    }
}
