<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Testing\Fluent\AssertableJson;
use InvalidArgumentException;
use Sourcetoad\EnhancedResources\AnonymousResourceCollection as EnhancedAnonymousResourceCollection;

class AnonymousResourceCollection extends EnhancedAnonymousResourceCollection
{
    public function toJsonAssertion(bool $wrapped = true): Closure
    {
        return function (AssertableJson $json) use ($wrapped) {
            $content = $wrapped ? (string) $this->response()->getContent() : $this->toJson();
            $expectedData = json_decode($content, true);

            if (!is_array($expectedData)) {
                // This is impossible to trigger without writing a broken resource
                // @codeCoverageIgnoreStart
                throw new InvalidArgumentException('Invalid data returned from resource');
                // @codeCoverageIgnoreEnd
            }

            // If it's a LengthAwarePaginator ignore the links because we don't use them.
            if ($this->resource instanceof LengthAwarePaginator) {
                $json->where('data', $expectedData['data']);
                $json->has('links');
                $json->has('meta', function (AssertableJson $json) use ($expectedData) {
                    $json->where('current_page', $expectedData['meta']['current_page']);
                    $json->where('from', $expectedData['meta']['from']);
                    $json->where('last_page', $expectedData['meta']['last_page']);
                    $json->has('links');
                    $json->has('path');
                    $json->where('per_page', $expectedData['meta']['per_page']);
                    $json->where('to', $expectedData['meta']['to']);
                    $json->where('total', $expectedData['meta']['total']);
                });
            } else {
                $json->whereAll($expectedData);
            }
        };
    }
}
