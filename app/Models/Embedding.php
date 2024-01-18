<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Pgvector\Laravel\Vector;

/**
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property Vector $embedding
 * @property string $column_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Model $model
 */
class Embedding extends Model
{
    protected $fillable = [
        'column_name',
        'embedding',
    ];

    protected $casts = [
        'embedding' => Vector::class,
    ];

    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }
}
