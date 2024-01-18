<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class DeterminePreferredLanguage
{
    public function handle(Request $request, Closure $next): Response
    {
        foreach ($request->getLanguages() as $language) {
            $locale = Str::before($language, '_');

            if (in_array($locale, (array) config('app.locales'))) {
                App::setLocale($locale);
                break;
            }
        }

        return $next($request);
    }
}
