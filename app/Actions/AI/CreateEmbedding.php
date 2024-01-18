<?php

namespace App\Actions\AI;

use App\Actions\Traits\ResolvesSelf;
use App\Services\Chatbot\ChatbotInterface;
use Pgvector\Vector;

class CreateEmbedding
{
    use ResolvesSelf;

    public function __construct(private readonly ChatbotInterface $chatbot)
    {
        //
    }

    public function execute(string $text): Vector
    {
        $response = $this->chatbot->generateEmbedding($text);

        return new Vector($response->embeddings[0]->embedding);
    }
}
