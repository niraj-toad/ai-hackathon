<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

use App\Models\ChatMessage;
use Illuminate\Support\Collection;
use OpenAI\Contracts\ClientContract;
use OpenAI\Laravel\Facades\OpenAI as OpenAIFacade;
use OpenAI\Laravel\Facades\OpenAI as OpenAILaravelFacade;
use OpenAI\Responses\Chat\CreateResponse as CreateChatResponse;
use OpenAI\Responses\Embeddings\CreateResponse as EmbeddingsResponse;
use OpenAI\Responses\Moderations\CreateResponseResult as ModerationResponse;

class OpenAI implements ChatbotInterface
{
    /**
     * @param Collection<int, ChatMessage> $messages
     */
    public function createChat(Collection $messages): CreateChatResponse
    {
        return OpenAIFacade::chat()->create([
            'model' => 'gpt-3.5-turbo-16k',
            'messages' => $messages->map(fn(ChatMessage $message) => [
                'role' => $message->role,
                'content' => $message->content,
            ])->values()->toArray(),
        ]);
    }

    public function generateEmbedding(string $input): EmbeddingsResponse
    {
        /** @var ClientContract $client */
        $client = resolve('openai');

        return $client->embeddings()->create([
            'model' => 'text-embedding-ada-002',
            'input' => $input,
        ]);
    }

    public function moderate(string $input): ?ModerationResponse
    {
        $response = OpenAILaravelFacade::moderations()->create([
            'model' => 'text-moderation-latest',
            'input' => $input,
        ]);

        if (count($response->results) > 0) {
            return $response->results[0];
        }

        return null;
    }
}
