<?php

namespace App\Models;

use App\Models\Traits\HasEmbeddings;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int $id
 * @property string $quote
 * @property string $author
 * @property Collection<Embedding> $embeddings
 */
class Quote extends Model
{
    use HasEmbeddings;

    protected $fillable = [
        'quote',
        'author',
    ];
}
