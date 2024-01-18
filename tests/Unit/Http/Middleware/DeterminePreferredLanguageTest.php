<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\DeterminePreferredLanguage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DeterminePreferredLanguageTest extends TestCase
{
    #[DataProvider('expectedLocaleProvider')]
    public function testSetsLocaleToExpectedValue(
        ?string $expectedResult,
        ?string $acceptLanguage,
        array $supportedLocales,
    ): void {
        // Expectations
        if ($expectedResult) {
            App::expects('setLocale')
                ->with($expectedResult)
                ->once();
        } else {
            App::expects('setLocale')
                ->never();
        }

        // Arrange
        Config::set('app.locales', $supportedLocales);

        $mockRequest = new Request();
        if ($acceptLanguage) {
            $mockRequest->headers->set('Accept-Language', $acceptLanguage);
        }

        // Act
        make(DeterminePreferredLanguage::class)
            ->handle($mockRequest, fn(Request $request) => new Response());

        // Assert
        // No assertions, only expectations.
    }

    public static function expectedLocaleProvider(): array
    {
        return [
            'does nothing with no accept header' => [
                'expectedResult' => null,
                'acceptLanguage' => null,
                'supportedLocales' => ['en', 'es'],
            ],
            'sets preferred from accept header' => [
                'expectedResult' => 'es',
                'acceptLanguage' => 'es-MX',
                'supportedLocales' => ['en', 'es'],
            ],
            'uses supported language based on user priority' => [
                'expectedResult' => 'de',
                'acceptLanguage' => 'fr-CA, fr;q=0.9, en;q=0.7, de;q=0.8, *;q=0.5',
                'supportedLocales' => ['en', 'de'],
            ],
            'does not support non-supported locales' => [
                'expectedResult' => null,
                'acceptLanguage' => 'fr-CA',
                'supportedLocales' => ['en'],
            ],
        ];
    }
}
