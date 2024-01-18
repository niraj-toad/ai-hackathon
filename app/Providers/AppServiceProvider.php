<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Resources\AnonymousResourceCollection;
use App\Http\Responses\FortifyLoginResponse;
use App\Models\ChatMessage;
use App\Models\Quote;
use App\Models\User;
use App\Services\Chatbot\ChatbotInterface;
use App\Services\Chatbot\OpenAI;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;
use Sourcetoad\EnhancedResources\AnonymousResourceCollection as EnhancedAnonymousResourceCollection;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Date::use(CarbonImmutable::class);
        Model::shouldBeStrict(!$this->app->isProduction());
        Relation::enforceMorphMap([
            'user' => User::class,
            'quote' => Quote::class,
            'chat_message' => ChatMessage::class,
        ]);

        $this->app->singleton(
            LoginResponse::class,
            FortifyLoginResponse::class,
        );

        $this->app->bind(
            EnhancedAnonymousResourceCollection::class,
            AnonymousResourceCollection::class,
        );

        $this->app->bind(
            ChatbotInterface::class,
            OpenAI::class,
        );
    }
}
