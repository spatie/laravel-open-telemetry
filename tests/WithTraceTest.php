<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Spatie\OpenTelemetry\Facades\Measure;

it('injects current span context name to HTTP client requests', function () {
    Http::fake();

    Measure::start('parent');

    Http::withTrace()->post('http://example.com/first');

    Http::assertSent(function (Request $request) {
        expect($request->hasHeader('traceparent'))->toBeTrue();

        return true;
    });
});

it('wil not fall, if no spans present', function () {
    Http::fake();

    Http::withTrace()->post('http://example.com/first');

    Http::assertSent(function (Request $request) {
        expect($request->hasHeader('traceparent'))->toBeFalse();

        return true;
    });
});
