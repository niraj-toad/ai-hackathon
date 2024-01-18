<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ChatSession;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;

/**
 * @property-read ChatSession $resource
 */
class ChatSessionResource extends Resource
{
    private const DEFAULT = 'default';

    #[Format(self::DEFAULT)]
    public function default(): array
    {
        return [
            'id' => $this->resource->getRouteKey(),
            'messages' => ChatMessageResource::collection(
                $this->resource->messages->where('role', '<>', 'system')
            ),
            'created_at' => $this->resource->created_at?->toIso8601ZuluString(),
            'updated_at' => $this->resource->updated_at?->toIso8601ZuluString(),
        ];
    }

}
