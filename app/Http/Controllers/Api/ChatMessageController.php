<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\AI\CreateEmbedding;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChatMessage\StoreRequest;
use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\QuoteResource;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Embedding;
use App\Models\Quote;
use App\Services\Chatbot\ChatbotInterface;
use Illuminate\Http\JsonResponse;

class ChatMessageController extends Controller
{
    public function __construct(private readonly ChatbotInterface $chatbot)
    {
        //
    }

    public function create(StoreRequest $request, ChatSession $chatSession): JsonResponse
    {
        $message = new ChatMessage([
            'role' => 'user',
            'content' => $request->input('content'),
        ]);

        $message->chatSession()->associate($chatSession);
        $message->creator()->associate($request->user());

        $message->saveOrFail();

        $message->embeddings()->create([
            'column_name' => 'content',
            'embedding' => CreateEmbedding::resolve()->execute($message->content)->toArray(),
        ]);

        $prompt = $chatSession->messages()
            ->where('role', '=', 'system')
            ->firstOrFail();

        $messages = $chatSession->messages()
            ->where('role', '<>', 'system')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->push($prompt)
            ->reverse();

        $response = $this->chatbot->createChat($messages);

        $relatedQuote = $this->findRelatedQuote($message);
        $content = $response->choices[0]->message->content;
        if ($relatedQuote) {
            $content = $content
                . "\n\n\"$relatedQuote->quote\""
                . "\n-- $relatedQuote->author";
        }

        /** @var ChatMessage $responseMessage */
        $responseMessage = $chatSession->messages()->create([
            'role' => $response->choices[0]->message->role,
            'content' => $content,
        ]);

        $responseMessage->embeddings()->create([
            'column_name' => 'content',
            'embedding' => CreateEmbedding::resolve()->execute($responseMessage->content)->toArray(),
        ]);

        return ChatMessageResource::make($responseMessage)
            ->response()
            ->setStatusCode(201);
    }

    private function findRelatedQuote(ChatMessage $message): ?Quote
    {
        $messageEmbedding = $message->getEmbedding('content')->embedding;

        $closestQuoteEmbedding = Embedding::query()
            ->selectRaw('*, embedding <-> (?) AS distance', [$messageEmbedding])
            ->where('model_type', 'quote')
            ->where(function ($query) use ($message) {
                $query->where('column_name', 'quote')
                    ->orWhere('column_name', 'author');
            })
            ->whereRaw('embedding <-> (?) < ?', [$messageEmbedding, 0.5])
            ->inRandomOrder()
            ->first();

        /** @var Quote|null $quote */
        $quote = $closestQuoteEmbedding?->model;

        return $quote;
    }
}
