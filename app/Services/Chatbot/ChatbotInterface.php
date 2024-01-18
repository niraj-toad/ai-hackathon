<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

use App\Models\ChatMessage;
use Illuminate\Support\Collection;
use OpenAI\Responses\Chat\CreateResponse as CreateChatResponse;
use OpenAI\Responses\Embeddings\CreateResponse as EmbeddingsResponse;
use OpenAI\Responses\Moderations\CreateResponseResult;

interface ChatbotInterface
{
    /**
     * @param Collection<int, ChatMessage> $messages
     */
    public function createChat(Collection $messages): CreateChatResponse;
    public function generateEmbedding(string $input): EmbeddingsResponse;
    public function moderate(string $input): ?CreateResponseResult;
}
