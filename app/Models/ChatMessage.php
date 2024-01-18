<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasEmbeddings;
use App\Models\Traits\HasUlidRouteKey;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin HasEmbeddings
 *
 * @property int $id
 * @property string $ulid
 * @property int $chat_session_id
 * @property int|null $created_by
 * @property string $role
 * @property string $content
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read User|null $creator
 * @property-read ChatSession $chatSession
 */
class ChatMessage extends Model
{
    use HasFactory;
    use HasTimestamps;
    use HasUlidRouteKey;
    use HasEmbeddings;

    protected $fillable = [
        'role',
        'content',
    ];

    /**
     * @return BelongsTo<User, ChatMessage>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * @return BelongsTo<ChatSession, ChatMessage>
     */
    public function chatSession(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class);
    }
}
