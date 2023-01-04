<?php

use Spatie\OpenTelemetry\Facades\Measure;
use Spatie\OpenTelemetry\Support\Injectors\TextInjector;

it('injects current span context name to Laravel HTTP (outgoing) requests', function () {
    Http::fake();

    $parentSpan = Measure::start('parent');

    $parentSpanTraceContextID = "";
    TextInjector::Inject($parentSpanTraceContextID, $parentSpan);

    Http::withTrace()->post('http://example.com/first');

    Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($parentSpanTraceContextID) {
        return $request
            ->hasHeader('traceparent', $parentSpanTraceContextID);
    });

    $childSpan = Measure::start('second');
    $childSpanTraceContextID = "";
    TextInjector::Inject($childSpanTraceContextID, $childSpan);

    Http::withTrace()->post('http://example.com/second');

    Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($childSpanTraceContextID) {
        return $request
            ->hasHeader('traceparent', $childSpanTraceContextID);
    });

    Measure::stop('second');

    Http::withTrace()->post('http://example.com/third');

    Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($parentSpanTraceContextID) {
        return $request
            ->hasHeader('traceparent', $parentSpanTraceContextID);
    });

    Measure::stop('first');
});

it('wil not fall, if no spans present', function () {
    Http::fake();

    Http::withTrace()->post('http://example.com/first');

    Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
        return true;
    });
});
