<?php

namespace App\Models\Traits;

use App\Models\Embedding;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property Collection<Embedding> $embeddings
 * @method MorphMany morphMany(string $related, string $name, string $type = null, string $id = null, string $localKey = null)
 */
trait HasEmbeddings
{
    public function getEmbedding(string $columnName): ?Embedding
    {
        return $this->embeddings->firstWhere('column_name', $columnName);
    }

    public function embeddings(): MorphMany
    {
        return $this->morphMany(Embedding::class, 'model');
    }
}
