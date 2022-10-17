<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Spatie\OpenTelemetry\Tests\TestSupport\TestCase;

uses(TestCase::class)->in(__DIR__);

function sentRequestPayloads(): array
{
    return collect(Http::recorded())
        ->map(fn($requestResponse) => $requestResponse[0])
        ->map(fn(Request $request) => $request->data())
        ->toArray();
}
