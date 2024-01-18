<?php

declare(strict_types=1);

namespace Tests;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;

class TestJsonResponse extends TestResponse
{
    /**
     * @param JsonResponse $response
     */
    public function __construct($response)
    {
        /** @noinspection PhpParamsInspection */
        parent::__construct($response);
    }

    public function assertWrappedJson(Closure $assert, string $wrapper = 'data'): static
    {
        return $this->assertJson(fn(AssertableJson $json) => $json->has($wrapper, $assert));
    }
}
