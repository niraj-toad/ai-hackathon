<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasUlidRouteKey;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $ulid
 * @property int $created_by
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read User $creator
 * @property-read Collection<ChatMessage> $messages
 */
class ChatSession extends Model
{
    use HasFactory;
    use HasTimestamps;
    use HasUlidRouteKey;

    /**
     * @return BelongsTo<User, ChatSession>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * @return HasMany<ChatMessage>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }
}
