<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('embeddings', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->string('column_name');
            $table->vector('embedding');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('embeddings');
    }
};
