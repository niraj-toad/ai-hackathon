<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ChatMessage;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;

/**
 * @property-read ChatMessage $resource
 */
class ChatMessageResource extends Resource
{
    private const DEFAULT = 'default';

    #[Format(self::DEFAULT)]
    public function default(): array
    {
        return [
            'id' => $this->resource->getRouteKey(),
            'role' => $this->resource->role,
            'content' => $this->resource->content,
            'created_at' => $this->resource->created_at?->toIso8601ZuluString(),
            'updated_at' => $this->resource->updated_at?->toIso8601ZuluString(),
        ];
    }
}
